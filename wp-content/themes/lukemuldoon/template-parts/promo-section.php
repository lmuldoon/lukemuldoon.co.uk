<?php
/**
 * Template Part: Founding offer / promotional section
 * Uses the 'founding_offer' ACF group field on the front page.
 * Toggle the 'enabled' true/false field to show/hide without a deploy.
 */

$promo   = get_field('promotion') ?: [];
$enabled = !empty($promo['enabled']);
if ( !$enabled ) return;
$btn_classes = [
    'primary' => 'button button--primary',
    'outline' => 'button button--outline',
    'regular' => 'button',
];
$kicker    = $promo['kicker']       ?? '';
$title     = $promo['title']        ?? '';
$text      = $promo['text']         ?? '';
$terms     = $promo['terms']        ?? '';
$btn        = $promo['button']      ?? [];
$btn_type   = $promo['button_type'] ?? '';
$btn_class  = $btn_classes[$btn_type] ?? 'button';
$btn_url    = $btn['url']    ?? '';
$btn_label  = $btn['title']  ?? '';
$btn_target = !empty($btn['target']) ? ' target="' . esc_attr($btn['target']) . '" rel="noopener noreferrer"' : '';

$title = preg_replace(
    '/\*\*(.*?)\*\*/',
    '<span class="accent-underline has-inline-svg">$1'
    . '<svg class="accent-underline__svg" viewBox="0 0 300 14" preserveAspectRatio="none" aria-hidden="true">'
    . '<path d="M3,9 C50,11 90,4 150,7 C200,10 250,4 297,7" stroke="#9FE000" stroke-width="4" fill="none" stroke-linecap="round"/>'
    . '</svg></span>',
    esc_html($title)
);

if ($btn_url && !str_starts_with($btn_url, 'mailto:') && filter_var($btn_url, FILTER_VALIDATE_EMAIL)) {
    $btn_url = 'mailto:' . $btn_url . '?subject=' . rawurlencode('Luke Muldoon - Website Enquiry');
}

if ( !$title ) return;
?>

<section id="promotion-offer" class="section bg-chalk promo-section" aria-labelledby="promo-heading">
    <div class="container">
        <div class="promo-section__inner flow js-reveal">
            <?php if ($kicker) : ?>
                <p class="kicker"><?php echo esc_html($kicker); ?></p>
            <?php endif; ?>
            <h2 id="promo-heading"><?php echo ($title); ?></h2>
            <?php if ($text) : ?>
                <div class="promo-section__body flow"><?php echo wp_kses_post($text); ?></div>
            <?php endif; ?>
            <?php if ($terms) : ?>
                <div class="promo-section__terms smaller"><?php echo wp_kses_post($terms); ?></div>
            <?php endif; ?>
            <?php if ($btn_url && $btn_label) : ?>
                <div class="cta-block__actions">
                    <a href="<?php echo esc_url($btn_url); ?>" class="<?php echo esc_attr($btn_class); ?>"<?php echo $btn_target; ?>><?php echo esc_html(rtrim($btn_label, " \t→")); ?> &#8594;</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
