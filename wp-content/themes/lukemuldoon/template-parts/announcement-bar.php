<?php
/**
 * Template Part: Announcement bar
 * Controlled via ACF Options page (Site Settings → announcement_bar group).
 * Toggle the 'enabled' true/false field to show/hide without a deploy.
 */

$bar     = get_field('promotion', get_option('page_on_front')) ?: [];
$enabled = !empty($bar['enabled']);
if ( !$enabled ) return;

$message      = $bar['promo_message']      ?? '';
//$promo_link = is_array($bar['promo_link'] ?? '') ? ($bar['promo_link']['url'] ?? '') : ($bar['promo_link'] ?? '');
$btn        = $bar['promo_link']      ?? [];
$btn_url    = $btn['url']    ?? '';
$btn_label  = $btn['title']  ?? '';
$btn_target = !empty($btn['target']) ? ' target="' . esc_attr($btn['target']) . '" rel="noopener noreferrer"' : '';
?>
<div class="announcement-bar" role="note" aria-label="Special offer">
    <div class="container container--wide announcement-bar__inner">
        <?php if ($message) : ?>
            <p class="announcement-bar__message"><?php echo esc_html($message); ?></p>
        <?php endif; ?>

        <?php if ($btn_url && $btn_label) : ?>
                <a href="<?php echo esc_url($btn_url); ?>" class="announcement-bar__btn"<?php echo $btn_target; ?>><?php echo esc_html(rtrim($btn_label, " \t→")); ?> &#8594;</a>
        <?php endif; ?>
    </div>
</div>