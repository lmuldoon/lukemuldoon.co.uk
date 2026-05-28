<?php
/**
 * Template Part: Announcement bar
 * Controlled via ACF Options page (Site Settings → announcement_bar group).
 * Toggle the 'enabled' true/false field to show/hide without a deploy.
 */

$bar     = get_field('promotion', get_option('page_on_front')) ?: [];
$enabled = !empty($bar['enabled']);
if ( !$enabled ) return;

$message    = $bar['promo_message'] ?? '';
$btn        = $bar['promo_link']    ?? [];
$btn_url    = $btn['url']           ?? '';
$btn_target = !empty($btn['target']) ? ' target="' . esc_attr($btn['target']) . '" rel="noopener noreferrer"' : '';
?>
<a href="<?php echo esc_url($btn_url); ?>" class="announcement-bar"<?php echo $btn_target; ?> aria-label="Special offer">
    <div class="container container--wide announcement-bar__inner">
        <?php if ($message) : ?>
            <p class="announcement-bar__message"><?php echo esc_html($message); ?> &#8594;</p>
        <?php endif; ?>
    </div>
</a>
