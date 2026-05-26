<?php
/**
 * Template Part: CTA section
 * Uses the 'cta' ACF group field on the current page.
 */

$cta        = get_field('cta') ?: [];
$cta_kicker = $cta['kicker'] ?? '';
$cta_title  = $cta['title']  ?? '';
$cta_text   = $cta['text']   ?? '';
$cta_button = $cta['button'] ?? [];

if (empty($cta_kicker) && empty($cta_title)) return;

// Build section ID from kicker: lowercase, spaces → hyphens
$cta_id = strtolower(trim(preg_replace('/\s+/', '-', $cta_kicker)));

// Parse **word** → <span class="accent-underline">word</span>
$cta_title_html = preg_replace(
    '/\*\*(.*?)\*\*/',
    '<span class="accent-underline has-inline-svg">$1'
    . '<svg class="accent-underline__svg" viewBox="0 0 300 14" preserveAspectRatio="none" aria-hidden="true">'
    . '<path d="M3,9 C50,11 90,4 150,7 C200,10 250,4 297,7" stroke="#9FE000" stroke-width="4" fill="none" stroke-linecap="round"/>'
    . '</svg></span>',
    esc_html($cta_title)
);

$btn_url    = $cta_button['url']    ?? '';
$btn_label  = $cta_button['title'] ?? '';
$btn_target = !empty($cta_button['target']) ? ' target="' . esc_attr($cta_button['target']) . '" rel="noopener noreferrer"' : '';

// If the URL looks like an email address but is missing the mailto: scheme, add it.
if ($btn_url && !str_starts_with($btn_url, 'mailto:') && filter_var($btn_url, FILTER_VALIDATE_EMAIL)) {
    $btn_url = 'mailto:' . $btn_url . '?subject=' . rawurlencode('Luke Muldoon - Website Enquiry');
}
?>

<section id="<?php echo esc_attr($cta_id); ?>" class="section bg-coal" aria-label="<?php echo esc_attr($cta_kicker); ?>">
    <div class="container">
        <div class="cta-block flow js-reveal">
            <?php if ($cta_kicker) : ?>
                <p class="kicker"><?php echo esc_html($cta_kicker); ?></p>
            <?php endif; ?>
            <?php if ($cta_title_html) : ?>
                <h2 class="cta-block__heading"><?php echo $cta_title_html; ?></h2>
            <?php endif; ?>
            <?php if ($cta_text) : ?>
                <div class="cta-block__lead"><?php echo wp_kses_post($cta_text); ?></div>
            <?php endif; ?>
            <?php if ($btn_url) : ?>
                <div class="cta-block__actions">
                    <a href="<?php echo esc_url($btn_url); ?>" class="button button--primary"<?php echo $btn_target; ?>><?php echo esc_html(rtrim($btn_label, " \t→")); ?> &#8594;</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
