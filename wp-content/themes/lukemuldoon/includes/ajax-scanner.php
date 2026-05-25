<?php

/**
 * Lighthouse Scanner — AJAX handler, lead capture, and admin view.
 *
 * Requires PSI_API_KEY to be defined in wp-config.php:
 *   define( 'PSI_API_KEY', 'your-key-here' );
 */

// ── DB table setup ────────────────────────────────────────────────────────────

add_action( 'after_switch_theme', 'lm_scanner_setup_table' );

function lm_scanner_setup_table() {
	global $wpdb;

	$table   = $wpdb->prefix . 'lm_scanner_leads';
	$charset = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS {$table} (
		id             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		url            VARCHAR(500)     NOT NULL,
		scanned_at     DATETIME         NOT NULL,
		performance    TINYINT UNSIGNED,
		accessibility  TINYINT UNSIGNED,
		best_practices TINYINT UNSIGNED,
		seo            TINYINT UNSIGNED,
		ip_hash        VARCHAR(64),
		follow_up      TINYINT NOT NULL DEFAULT 0,
		PRIMARY KEY  (id),
		KEY scanned_at (scanned_at)
	) {$charset};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
	update_option( 'lm_scanner_db_version', '1.0' );
}

// Create table on first load if theme was activated before this file existed.
if ( get_option( 'lm_scanner_db_version' ) !== '1.0' ) {
	add_action( 'init', 'lm_scanner_setup_table' );
}

// ── AJAX handler ─────────────────────────────────────────────────────────────

add_action( 'wp_ajax_nopriv_lm_scan', 'lm_ajax_scan' );
add_action( 'wp_ajax_lm_scan',        'lm_ajax_scan' );

function lm_ajax_scan() {

	// 1. Nonce verification.
	check_ajax_referer( 'lm_scan_nonce', 'nonce' );

	// 2. Honeypot — silently return success so bots think they succeeded.
	if ( ! empty( $_POST['website'] ) ) {
		wp_send_json_success( [] );
	}

	// 3. Rate limiting — max 10 scans per IP per hour.
	$ip_key   = 'lm_ratelimit_' . md5( sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) ) );
	$requests = (int) get_transient( $ip_key );
	if ( $requests >= 10 ) {
		wp_send_json_error( [ 'message' => 'Too many scans. Please try again in an hour.' ] );
	}
	set_transient( $ip_key, $requests + 1, HOUR_IN_SECONDS );

	// 4. Validate and sanitise the URL.
	$raw = isset( $_POST['url'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['url'] ) ) ) : '';

	if ( $raw === '' ) {
		wp_send_json_error( [ 'message' => 'Please enter a website URL.' ] );
	}

	// Prepend scheme if missing.
	if ( ! preg_match( '#^https?://#i', $raw ) ) {
		$raw = 'https://' . $raw;
	}

	if ( strlen( $raw ) > 500 ) {
		wp_send_json_error( [ 'message' => 'URL is too long.' ] );
	}

	$url = esc_url_raw( $raw );

	if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
		wp_send_json_error( [ 'message' => 'Please enter a valid website URL.' ] );
	}

	$parsed = wp_parse_url( $url );

	if ( ! in_array( $parsed['scheme'] ?? '', [ 'http', 'https' ], true ) ) {
		wp_send_json_error( [ 'message' => 'Only http and https URLs are supported.' ] );
	}

	// Block private / loopback / reserved hosts (SSRF mitigation).
	$host = strtolower( $parsed['host'] ?? '' );

	$blocked_tlds = [ '.local', '.localhost', '.internal', '.test', '.example', '.invalid' ];
	foreach ( $blocked_tlds as $tld ) {
		if ( str_ends_with( $host, $tld ) ) {
			wp_send_json_error( [ 'message' => 'That URL cannot be scanned.' ] );
		}
	}

	if ( preg_match( '/^(127\.|10\.|192\.168\.|172\.(1[6-9]|2[0-9]|3[01])\.|localhost$)/i', $host )
		|| in_array( $host, [ '::1', '0.0.0.0' ], true )
	) {
		wp_send_json_error( [ 'message' => 'That URL cannot be scanned.' ] );
	}

	// 5. Check transient cache.
	$cache_key = 'lm_scan_' . md5( $url );
	$cached    = get_transient( $cache_key );
	if ( $cached !== false ) {
		wp_send_json_success( $cached );
	}

	// 6. Ensure API key is configured.
	if ( ! defined( 'PSI_API_KEY' ) || '' === PSI_API_KEY ) {
		wp_send_json_error( [ 'message' => 'Scanner is not configured yet. Please contact the site owner.' ] );
	}

	// 7. Call PageSpeed Insights API.
	$api_url = sprintf(
		'https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=%s&key=%s&strategy=desktop&category=performance&category=accessibility&category=best-practices&category=seo',
		rawurlencode( $url ),
		rawurlencode( PSI_API_KEY )
	);

	$response = wp_remote_get( $api_url, [
		'timeout'   => 45,
		'sslverify' => true,
	] );

	if ( is_wp_error( $response ) ) {
		wp_send_json_error( [ 'message' => 'Could not reach the PageSpeed API. Please try again.' ] );
	}

	$code = wp_remote_retrieve_response_code( $response );
	if ( (int) $code !== 200 ) {
		wp_send_json_error( [ 'message' => 'PageSpeed API returned an error. Please try again shortly.' ] );
	}

	$body = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( empty( $body['lighthouseResult']['categories'] ) ) {
		wp_send_json_error( [ 'message' => 'No Lighthouse data returned. The URL may be unreachable.' ] );
	}

	$cats   = $body['lighthouseResult']['categories'];
	$scores = [
		'performance'    => (int) round( ( $cats['performance']['score']    ?? 0 ) * 100 ),
		'accessibility'  => (int) round( ( $cats['accessibility']['score']  ?? 0 ) * 100 ),
		'best-practices' => (int) round( ( $cats['best-practices']['score'] ?? 0 ) * 100 ),
		'seo'            => (int) round( ( $cats['seo']['score']            ?? 0 ) * 100 ),
	];

	// 8. Cache for 1 hour.
	set_transient( $cache_key, $scores, HOUR_IN_SECONDS );

	// 9. Store lead (GDPR: hashed IP only, no personal data).
	global $wpdb;
	$wpdb->insert(
		$wpdb->prefix . 'lm_scanner_leads',
		[
			'url'           => $url,
			'scanned_at'    => current_time( 'mysql' ),
			'performance'   => $scores['performance'],
			'accessibility' => $scores['accessibility'],
			'best_practices' => $scores['best-practices'],
			'seo'           => $scores['seo'],
			'ip_hash'       => hash( 'sha256', sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) ) ),
			'follow_up'     => 0,
		],
		[ '%s', '%s', '%d', '%d', '%d', '%d', '%s', '%d' ]
	);

	wp_send_json_success( $scores );
}

// ── Admin view ────────────────────────────────────────────────────────────────

add_action( 'admin_menu', 'lm_scanner_admin_menu' );

function lm_scanner_admin_menu() {
	add_management_page(
		'Scanner Leads',
		'Scanner Leads',
		'manage_options',
		'lm-scanner-leads',
		'lm_scanner_admin_page'
	);
}

function lm_scanner_score_badge( $score ) {
	if ( $score === null ) {
		return '<span style="color:#aaa">—</span>';
	}
	$score = (int) $score;
	if ( $score >= 90 )      { $color = '#6FAE00'; }
	elseif ( $score >= 50 )  { $color = '#C28A00'; }
	else                     { $color = '#E54B2A'; }

	return '<strong style="color:' . esc_attr( $color ) . '">' . esc_html( $score ) . '</strong>';
}

function lm_scanner_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Insufficient permissions.' );
	}

	global $wpdb;
	$table = $wpdb->prefix . 'lm_scanner_leads';

	// Handle follow-up toggle.
	if ( isset( $_POST['toggle_followup'] ) && check_admin_referer( 'lm_toggle_followup' ) ) {
		$id      = (int) $_POST['lead_id'];
		$new_val = (int) $_POST['follow_up_value'];
		$wpdb->update( $table, [ 'follow_up' => $new_val ], [ 'id' => $id ], [ '%d' ], [ '%d' ] );
		wp_redirect( add_query_arg( 'page', 'lm-scanner-leads', admin_url( 'tools.php' ) ) );
		exit;
	}

	$leads = $wpdb->get_results( "SELECT * FROM {$table} ORDER BY scanned_at DESC LIMIT 200" );

	echo '<div class="wrap">';
	echo '<h1>Scanner Leads</h1>';
	echo '<p style="color:#666">Every URL scanned via the hero form. Use this to identify prospects who haven\'t been in touch.</p>';

	if ( empty( $leads ) ) {
		echo '<p>No scans yet.</p>';
		echo '</div>';
		return;
	}

	echo '<table class="widefat striped" style="margin-top:1rem">';
	echo '<thead><tr>';
	echo '<th>URL</th><th>Scanned</th><th>Perf</th><th>A11y</th><th>Best Practices</th><th>SEO</th><th>Avg</th><th>Actioned</th>';
	echo '</tr></thead><tbody>';

	foreach ( $leads as $lead ) {
		$avg = (int) round( ( $lead->performance + $lead->accessibility + $lead->best_practices + $lead->seo ) / 4 );

		echo '<tr>';
		echo '<td><a href="' . esc_url( $lead->url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $lead->url ) . '</a></td>';
		echo '<td>' . esc_html( $lead->scanned_at ) . '</td>';
		echo '<td>' . lm_scanner_score_badge( $lead->performance ) . '</td>';
		echo '<td>' . lm_scanner_score_badge( $lead->accessibility ) . '</td>';
		echo '<td>' . lm_scanner_score_badge( $lead->best_practices ) . '</td>';
		echo '<td>' . lm_scanner_score_badge( $lead->seo ) . '</td>';
		echo '<td>' . lm_scanner_score_badge( $avg ) . '</td>';
		echo '<td>';
		$new_val = $lead->follow_up ? 0 : 1;
		echo '<form method="post" style="margin:0">';
		wp_nonce_field( 'lm_toggle_followup' );
		echo '<input type="hidden" name="toggle_followup" value="1">';
		echo '<input type="hidden" name="lead_id" value="' . (int) $lead->id . '">';
		echo '<input type="hidden" name="follow_up_value" value="' . $new_val . '">';
		echo '<input type="checkbox" ' . checked( 1, (int) $lead->follow_up, false ) . ' onchange="this.form.submit()" title="Mark as actioned">';
		echo '</form>';
		echo '</td>';
		echo '</tr>';
	}

	echo '</tbody></table>';
	echo '</div>';
}
