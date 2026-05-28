<?php

add_action('wp_ajax_nopriv_lm_contact', 'lm_ajax_contact');
add_action('wp_ajax_lm_contact',        'lm_ajax_contact');

function lm_ajax_contact() {

    // 1. Honeypot
    if (!empty($_POST['website'])) {
        wp_send_json_success();
    }

    // 2. Time check — reject if submitted in under 3 seconds
    $loaded_at = (int) ($_POST['form_time'] ?? 0);
    if ($loaded_at && (time() - $loaded_at) < 3) {
        wp_send_json_error(['message' => 'Please try again.']);
    }

    // 3. Rate limit — 3 per IP per hour
    $ip_key   = 'lm_contact_' . md5(sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'] ?? '')));
    $requests = (int) get_transient($ip_key);
    if ($requests >= 3) {
        wp_send_json_error(['message' => 'Too many submissions. Please try again in an hour.']);
    }
    set_transient($ip_key, $requests + 1, HOUR_IN_SECONDS);

    // 4. hCaptcha verification
    $hcaptcha_response = sanitize_text_field(wp_unslash($_POST['h-captcha-response'] ?? ''));
    if (defined('HCAPTCHA_SECRET_KEY') && HCAPTCHA_SECRET_KEY) {
        $verify = wp_remote_post('https://hcaptcha.com/siteverify', [
            'body' => [
                'secret'   => HCAPTCHA_SECRET_KEY,
                'response' => $hcaptcha_response,
            ],
        ]);
        if (is_wp_error($verify)) {
            wp_send_json_error(['message' => 'Verification failed. Please try again.']);
        }
        $result = json_decode(wp_remote_retrieve_body($verify), true);
        if (empty($result['success'])) {
            wp_send_json_error(['message' => 'Please complete the captcha.']);
        }
    }

    // 5. Validate & sanitise fields
    $name    = sanitize_text_field(wp_unslash($_POST['cf_name']    ?? ''));
    $email   = sanitize_email(wp_unslash($_POST['cf_email']        ?? ''));
    $budget  = sanitize_text_field(wp_unslash($_POST['cf_budget']  ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['cf_message'] ?? ''));

    if (!$name)             wp_send_json_error(['field' => 'cf_name',    'message' => 'Please enter your name.']);
    if (!is_email($email))  wp_send_json_error(['field' => 'cf_email',   'message' => 'Please enter a valid email address.']);
    if (strlen($message) < 10) wp_send_json_error(['field' => 'cf_message', 'message' => 'Please tell me a bit about your project.']);

    // 6. Send email
    $budget_labels = [
        'under-1000' => 'Under £1,000',
        '1000-2500'  => '£1,000 – £2,500',
        '2500-5000'  => '£2,500 – £5,000',
        '5000-plus'  => '£5,000+',
        'not-sure'   => 'Not sure yet',
    ];
    $budget_label = $budget_labels[$budget] ?? 'Not provided';

    $body = "Name: {$name}\nEmail: {$email}\nBudget: {$budget_label}\n\nMessage:\n{$message}";

    $sent = wp_mail(
        get_option('admin_email'),
        "New enquiry from {$name} — lukemuldoon.co.uk",
        $body,
        ["Reply-To: {$name} <{$email}>"]
    );

    if (!$sent) {
        wp_send_json_error(['message' => 'Something went wrong. Please email me directly at luke@lukemuldoon.co.uk']);
    }

    wp_send_json_success();
}
