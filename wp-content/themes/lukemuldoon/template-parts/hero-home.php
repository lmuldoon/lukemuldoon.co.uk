<?php

/**
 * Home page hero section.
 */

$hero         = get_field( 'hero' ) ?: [];
$hero_kicker  = $hero['kicker']           ?? 'FAST WEBSITES · CUSTOM WORDPRESS · STATIC SITES';
$hero_title   = $hero['title']            ?? "Don't lose customers to **slow pages**.";
$hero_lead    = $hero['text']             ?? '';
$show_scanner = $hero['show_site_scanner'] ?? false;

// Parse **word** → <span class="accent-underline">word</span>
$hero_title = preg_replace(
    '/\*\*(.*?)\*\*/',
    '<span class="accent-underline">$1</span>',
    esc_html($hero_title)
);
?>

<section class="hero section bg-chalk" id="hero" aria-labelledby="hero-heading">

    <div class="hero__score-ring" aria-hidden="true">
        <svg class="hero__score-ring-svg" viewBox="0 0 480 480" xmlns="http://www.w3.org/2000/svg">
            <circle class="hero__ring-circle" cx="240" cy="240" r="200" />
            <text class="hero__ring-text" x="240" y="240">100</text>
        </svg>
    </div>

    <div class="container js-reveal">

        <p class="kicker"><?php echo esc_html($hero_kicker); ?></p>

        <h1 id="hero-heading" class="hero__heading">
            <?php echo $hero_title; ?>
        </h1>

        <?php if ($hero_lead) : ?>
            <p class="hero__lead"><?php echo esc_html($hero_lead); ?></p>
        <?php endif; ?>

        <?php if ($show_scanner) : ?>
            <form class="scanner" aria-label="Scan a website's PageSpeed score" novalidate>
                <label for="scanner-url" class="sr-only">Website URL to scan</label>
                <span class="scanner__prefix" aria-hidden="true">https://</span>
                <input type="text" id="scanner-url" name="url" class="scanner__input" placeholder="your-site.com" autocomplete="url" inputmode="url" spellcheck="false">
                <!-- Honeypot: hidden from real users, filled only by bots -->
                <input type="text" name="website" tabindex="-1" aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0" autocomplete="off">
                <button type="submit" class="button button--primary scanner__btn">Scan it &#8594;</button>
            </form>

            <p class="hero__trust">
                <span class="hero__trust-icon" aria-hidden="true">&#10003;</span>
                Live PageSpeed Insights · scan any URL, including a competitor's
            </p>

        <?php endif; ?>

    </div>
</section>

<?php if ($show_scanner) : ?>
    <div class="scanner-modal" id="scanner-modal" role="dialog" aria-modal="true" aria-labelledby="scanner-modal-title" hidden>
        <div class="scanner-modal__backdrop"></div>
        <div class="scanner-modal__panel">

            <!-- Loading state -->
            <div class="scanner-modal__loading">
                <span class="scanner-modal__pulse" aria-hidden="true"></span>
                <p class="scanner-modal__status" id="scanner-modal-title">
                    Scanning <strong class="scanner-modal__url"></strong>&hellip;
                </p>
                <p class="scanner-modal__hint">Takes ~15 seconds &mdash; Lighthouse is auditing your site.</p>
            </div>

            <!-- Results state -->
            <div class="scanner-modal__results" hidden>
                <header class="scanner-modal__header">
                    <span class="scanner-modal__site-name"></span>
                    <button class="scanner-modal__close" aria-label="Close results">&#10005;</button>
                </header>
                <p class="scanner-modal__methodology">Scores use Google&rsquo;s throttled desktop simulation &mdash; real-world performance is typically higher.</p>

                <dl class="score-list score-list--large">
                    <div class="score-list__item">
                        <dt class="score-list__label">Performance</dt>
                        <dd class="score-list__value" data-category="performance" style="--score:0">&ndash;</dd>
                    </div>
                    <div class="score-list__item">
                        <dt class="score-list__label">Accessibility</dt>
                        <dd class="score-list__value" data-category="accessibility" style="--score:0">&ndash;</dd>
                    </div>
                    <div class="score-list__item">
                        <dt class="score-list__label">Best Practices</dt>
                        <dd class="score-list__value" data-category="best-practices" style="--score:0">&ndash;</dd>
                    </div>
                    <div class="score-list__item">
                        <dt class="score-list__label">SEO</dt>
                        <dd class="score-list__value" data-category="seo" style="--score:0">&ndash;</dd>
                    </div>
                </dl>

                <p class="scanner-modal__cta-note"></p>
                <a href="#next-step" class="button button--primary scanner-modal__cta">
                    Want scores like these? Let&rsquo;s talk &#8594;
                </a>
            </div>

        </div>
    </div>
<?php endif; ?>