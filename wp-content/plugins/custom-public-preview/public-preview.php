<?php
/*
Plugin Name: Custom Public Preview
Description: Adds a public preview button for drafts and pending posts with selectable post types.
Version: 1.0
Author: Manolis Giouvanakis
*/

if (!defined('PUBLIC_PREVIEW_SECRET')) {
    define('PUBLIC_PREVIEW_SECRET', 'FWbQeLrvvuUiuAChiDBlXByrNcrKPjxB');
}

defined('ABSPATH') || exit;

register_activation_hook(__FILE__, function () {
    add_option('public_preview_post_types', ['post', 'page']);
    flush_rewrite_rules();
});
register_deactivation_hook(__FILE__, 'flush_rewrite_rules');

add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('dashicons');
});

// === Admin Settings Page ===
add_action('admin_menu', function () {
    add_options_page('Public Preview Settings', 'Public Preview', 'manage_options', 'public-preview-settings', 'public_preview_settings_page');
});

add_action('admin_init', function () {
    register_setting('public_preview_settings_group', 'public_preview_transient_timeout');
    register_setting('public_preview_settings_group', 'public_preview_post_types');
});

add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
    $settings_link = '<a href="' . esc_url(admin_url('options-general.php?page=public-preview-settings')) . '">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
});

function public_preview_settings_page()
{
    $post_types = get_post_types(['public' => true], 'objects');
    $selected_post_types = (array)get_option('public_preview_post_types', []);
    $timeout_option = get_option('public_preview_transient_timeout', 'week'); // default 'week'

    $timeout_options = [
        'day' => '1 Day',
        'week' => '1 Week',
        'month' => '1 Month',
    ];
?>
    <div class="wrap">
        <h1>Public Preview Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('public_preview_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Enable Public Preview For:</th>
                    <td>
                        <?php foreach ($post_types as $pt) : ?>
                            <label>
                                <input type="checkbox" name="public_preview_post_types[]" value="<?php echo esc_attr($pt->name); ?>" <?php checked(in_array($pt->name, $selected_post_types)); ?>>
                                <?php echo esc_html($pt->labels->singular_name); ?>
                            </label><br>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Preview Token Expiration:</th>
                    <td>
                        <select name="public_preview_transient_timeout">
                            <?php foreach ($timeout_options as $value => $label) : ?>
                                <option value="<?php echo esc_attr($value); ?>" <?php selected($timeout_option, $value); ?>><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description">Select how long the public preview token should be valid.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

function public_preview_get_transient_timeout()
{
    $timeout = get_option('public_preview_transient_timeout', 'week');

    switch ($timeout) {
        case 'day':
            return DAY_IN_SECONDS;
        case 'month':
            return MONTH_IN_SECONDS;
        case 'week':
        default:
            return WEEK_IN_SECONDS;
    }
}

function ppv_get_allowed_post_types()
{
    return (array)get_option('public_preview_post_types', []);
}

add_action('post_submitbox_misc_actions', 'add_public_preview_button');
add_filter('page_row_actions', 'add_public_preview_link_to_list', 10, 2);
add_filter('post_row_actions', 'add_public_preview_link_to_list', 10, 2);

function add_public_preview_button()
{
    global $post;
    $post_types = ppv_get_allowed_post_types();
    if (
        !$post ||
        !in_array($post->post_type, $post_types) ||
        !in_array($post->post_status, ['draft', 'pending'])
    ) {
        return;
    }

    $secret_key = defined('PUBLIC_PREVIEW_SECRET') ? PUBLIC_PREVIEW_SECRET : 'FWbQeLrvvuUiuAChiDBlXByrNcrKPjxB';
    $token = hash_hmac('sha256', $post->ID, $secret_key);
    set_transient("public_preview_token_{$post->ID}", $token, public_preview_get_transient_timeout());

    $post_slug = $post->post_name ?: sanitize_title($post->post_title);
    $preview_url = home_url("/public-preview/{$post->ID}/{$post_slug}");

?>
    <div class="misc-pub-section misc-pub-public-preview" style="display: flex; align-items: center; gap: 6px;">
        <a href="<?php echo esc_url($preview_url); ?>" target="_blank" class="button button-secondary">
            Public Preview
        </a>
        <button type="button" class="button" onclick="copyPreviewURL('<?php echo esc_url($preview_url); ?>', this);" title="Copy Preview URL" style="padding: 6px 10px; height: 32px; line-height: 1;">
            <span class="dashicons dashicons-admin-links" style="font-size: 17px;"></span>
        </button>

        <script>
            function copyPreviewURL(url, button) {
                const input = document.createElement('input');
                input.setAttribute('value', url);
                document.body.appendChild(input);
                input.select();
                try {
                    document.execCommand('copy');
                    button.innerHTML = '<span class="dashicons dashicons-yes"></span>';
                } catch (err) {
                    console.error('Copy failed', err);
                    button.innerHTML = '<span class="dashicons dashicons-no-alt"></span>';
                }
                document.body.removeChild(input);
                setTimeout(() => {
                    button.innerHTML = '<span class="dashicons dashicons-admin-links"></span>';
                }, 1500);
            }
        </script>
    </div>
<?php
}


function add_public_preview_link_to_list($actions, $post)
{
    $post_types = ppv_get_allowed_post_types();
    if (!in_array($post->post_type, $post_types) || !in_array($post->post_status, ['draft', 'pending'])) {
        return $actions;
    }

    $secret_key = defined('PUBLIC_PREVIEW_SECRET') ? PUBLIC_PREVIEW_SECRET : 'FWbQeLrvvuUiuAChiDBlXByrNcrKPjxB';
    $token = hash_hmac('sha256', $post->ID, $secret_key);
    set_transient("public_preview_token_{$post->ID}", $token, public_preview_get_transient_timeout());

    $post_slug = $post->post_name ?: sanitize_title($post->post_title);
    $preview_url = home_url("/public-preview/{$post->ID}/{$post_slug}");

    $actions['public_preview'] = '<a href="' . esc_url($preview_url) . '" target="_blank">Public Preview</a>';
    return $actions;
}

add_action('parse_request', function ($wp) {

    if (empty($wp->query_vars['public_preview'])) {
        return;
    }

    $post_id = intval($wp->query_vars['post_id']);
    if (!$post_id) return;

    $post = get_post($post_id);
    if (!$post) return;

    // Validate post type & status
    if (!in_array($post->post_type, ppv_get_allowed_post_types(), true)) {
        return;
    }
    if (!in_array($post->post_status, ['draft', 'pending', 'future'], true)) {
        return;
    }

    // Validate token
    $secret = PUBLIC_PREVIEW_SECRET;
    $stored = get_transient("public_preview_token_{$post_id}");
    $expected = hash_hmac('sha256', $post_id, $secret);

    if (!$stored || !hash_equals($expected, $stored)) {
        return; // DO NOT wp_die (causes 404 for visitors)
    }

    // -------------------------------------------------
    // ✔ Set the correct minimal query vars
    // -------------------------------------------------
    if ($post->post_type === 'page') {
        // Use page query vars
        $wp->query_vars['page_id']  = $post_id;
        $wp->query_vars['pagename'] = $post->post_name;
        unset($wp->query_vars['p']); // prevent WP from thinking it's a single
    } else {
        // Use post-like query vars
        $wp->query_vars['p']    = $post_id;
        $wp->query_vars['name'] = $post->post_name;
    }

    $wp->query_vars['preview'] = true;
});


add_filter('template_include', function ($template) {
    if (get_query_var('public_preview')) {
        return $template;
    }
    return $template;
});

add_action('pre_get_posts', function ($q) {

    if (is_admin() || !$q->is_main_query()) {
        return;
    }

    if (!$q->get('preview')) {
        return;
    }

    $post_id = intval($q->get('page_id') ?: $q->get('p'));
    if (!$post_id) return;

    $secret = PUBLIC_PREVIEW_SECRET;
    $stored = get_transient("public_preview_token_{$post_id}");
    $expected = hash_hmac('sha256', $post_id, $secret);

    if (!hash_equals($expected, $stored)) {
        return; // let WP output 404 normally
    }

    // ✔ Allow WordPress to load draft/pending/future
    $q->set('post_status', ['draft', 'pending', 'future', 'publish']);
});


add_action('init', function () {
    add_rewrite_rule('^public-preview/([0-9]+)/([^/]+)/?$', 'index.php?public_preview=1&post_id=$matches[1]', 'top');
});

add_filter('query_vars', function ($vars) {
    $vars[] = 'public_preview';
    $vars[] = 'post_id';
    return $vars;
});
