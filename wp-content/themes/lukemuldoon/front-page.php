<?php

/**
 * The front page template file
 */

get_header();

?>

<?php while (have_posts()) : ?>
    <?php the_post(); ?>

    <?php get_template_part('template-parts/hero-home'); ?>

    <div class="_anchor" id="main-content"></div>

    <article id="post-<?php the_ID(); ?>" <?php post_class('page-home-content'); ?>>

        <!-- ===================== STATS BAR ===================== -->
        <?php
        $stats_bar = get_field('stats_bar') ?: [];
        $stats = [
            $stats_bar['stat_one']   ?? [],
            $stats_bar['stat_two']   ?? [],
            $stats_bar['stat_three'] ?? [],
        ];
        ?>
        <section id="stats-bar" class="section bg-paper" aria-label="Performance statistics">
            <div class="container">
                <ul class="stat-list js-reveal" role="list">
                    <?php foreach ($stats as $stat) :
                        $value  = $stat['stat_value']  ?? '';
                        $suffix = $stat['stat_suffix']  ?? '';
                        $label  = $stat['stat_label']   ?? '';
                        if ($value === '') continue;
                    ?>
                        <li class="stat-item">
                            <strong class="stat-item__value">
                                <span class="stat-item__main"><?php echo esc_html($value); ?></span><?php if ($suffix) : ?><span class="stat-item__suffix"><?php echo esc_html($suffix); ?></span><?php endif; ?>
                            </strong>
                            <?php if ($label) : ?><p class="stat-item__desc"><?php echo esc_html($label); ?></p><?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>

        <!-- ===================== THE PROBLEM ===================== -->
        <?php
        $the_problem = get_field('the_problem') ?: [];
        $tp_kicker   = $the_problem['kicker'] ?? '';
        $tp_title    = $the_problem['title']  ?? '';
        $tp_text     = $the_problem['text']   ?? '';
        ?>
        <section id="the-problem" class="section bg-chalk pb-0" aria-labelledby="problem-heading">
            <div class="container">
                <div class="two-col two-col--wide-left">
                    <div class="two-col__left flow js-reveal">
                        <?php if ($tp_kicker) : ?><p class="kicker"><?php echo esc_html($tp_kicker); ?></p><?php endif; ?>
                        <?php if ($tp_title) : ?><h2 id="problem-heading"><?php echo esc_html($tp_title); ?></h2><?php endif; ?>
                    </div>
                    <div class="two-col__right flow js-reveal">
                        <?php if ($tp_text) : echo wp_kses_post($tp_text); endif; ?>
                        <figure class="bounce-chart" aria-label="Bounce rate increases as page load time increases">
                            <p class="bounce-chart__header">Bounce rate by load time &middot; Google&nbsp;/&nbsp;SOASTA</p>
                            <ul class="bounce-chart__list" role="list">
                                <li class="bounce-chart__row bounce-chart__row--good" style="--bar: 34">
                                    <span class="bounce-chart__label">1s</span>
                                    <div class="bounce-chart__track" aria-hidden="true"><div class="bounce-chart__fill"></div></div>
                                    <span class="bounce-chart__value">+32%</span>
                                </li>
                                <li class="bounce-chart__row bounce-chart__row--caution" style="--bar: 48">
                                    <span class="bounce-chart__label">3s</span>
                                    <div class="bounce-chart__track" aria-hidden="true"><div class="bounce-chart__fill"></div></div>
                                    <span class="bounce-chart__value">+64%</span>
                                </li>
                                <li class="bounce-chart__row bounce-chart__row--warning" style="--bar: 75">
                                    <span class="bounce-chart__label">5s</span>
                                    <div class="bounce-chart__track" aria-hidden="true"><div class="bounce-chart__fill"></div></div>
                                    <span class="bounce-chart__value">+154%</span>
                                </li>
                                <li class="bounce-chart__row bounce-chart__row--warning" style="--bar: 100">
                                    <span class="bounce-chart__label">10s</span>
                                    <div class="bounce-chart__track" aria-hidden="true"><div class="bounce-chart__fill"></div></div>
                                    <span class="bounce-chart__value">+277%</span>
                                </li>
                            </ul>
                            <figcaption class="bounce-chart__caption">Probability a visitor leaves before the page loads.</figcaption>
                        </figure>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== WHAT I BUILD ===================== -->
        <?php
        $wib = get_field('what_i_build') ?: [];

        $btn_classes = [
            'primary' => 'button button--primary',
            'outline' => 'button button--outline',
            'regular' => 'button',
        ];

        $panels = [
            ['data' => $wib['panel_one'] ?? [], 'id' => 'service-wp-heading',     'card_class' => 'card card--service card--dark'],
            ['data' => $wib['panel_two'] ?? [], 'id' => 'service-static-heading', 'card_class' => 'card card--service'],
        ];
        ?>
        <section id="what-i-build" class="section bg-chalk" aria-labelledby="services-heading">
            <div class="container">
                <div class="section-header flow js-reveal">
                    <?php if (!empty($wib['kicker'])) : ?><p class="kicker"><?php echo esc_html($wib['kicker']); ?></p><?php endif; ?>
                    <?php if (!empty($wib['title']))  : ?><h2 id="services-heading"><?php echo esc_html($wib['title']); ?></h2><?php endif; ?>
                    <?php if (!empty($wib['text']))   : ?><div class="section-header__lead"><?php echo wp_kses_post($wib['text']); ?></div><?php endif; ?>
                </div>
                <div class="card-grid js-reveal">
                    <?php foreach ($panels as $panel) :
                        $p          = $panel['data'];
                        $btn        = $p['button']      ?? [];
                        $btn_type   = $p['button_type'] ?? '';
                        $btn_class  = $btn_classes[$btn_type] ?? 'button';
                        $btn_url    = $btn['url']    ?? '';
                        $btn_label  = $btn['title']  ?? '';
                        $btn_target = !empty($btn['target']) ? ' target="' . esc_attr($btn['target']) . '" rel="noopener noreferrer"' : '';
                    ?>
                        <article class="<?php echo esc_attr($panel['card_class']); ?>" aria-labelledby="<?php echo esc_attr($panel['id']); ?>">
                            <div class="card__body flow">
                                <?php if (!empty($p['kicker'])) : ?><p class="kicker"><?php echo esc_html($p['kicker']); ?></p><?php endif; ?>
                                <?php if (!empty($p['title']))  : ?><h3 id="<?php echo esc_attr($panel['id']); ?>" class="card__title"><?php echo esc_html($p['title']); ?></h3><?php endif; ?>
                                <?php if (!empty($p['text']))   : ?><div class="card__content flow"><?php echo wp_kses_post($p['text']); ?></div><?php endif; ?>
                                <?php if ($btn_url && $btn_label) : ?>
                                    <div>
                                        <a href="<?php echo esc_url($btn_url); ?>" class="<?php echo esc_attr($btn_class); ?>"<?php echo $btn_target; ?>><?php echo esc_html(rtrim($btn_label, " \t→")); ?> &#8594;</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- ===================== WHY ME ===================== -->
        <?php
        $why_me = get_field('why_me') ?: [];
        $why_me_items = [
            $why_me['item_one']   ?? [],
            $why_me['item_two']   ?? [],
            $why_me['item_three'] ?? [],
            $why_me['item_four']  ?? [],
        ];
        ?>
        <section id="why-me" class="section bg-paper" aria-labelledby="why-me-heading">
            <div class="container">
                <div class="section-header flow js-reveal">
                    <?php if (!empty($why_me['kicker'])) : ?><p class="kicker"><?php echo esc_html($why_me['kicker']); ?></p><?php endif; ?>
                    <?php if (!empty($why_me['title']))  : ?><h2 id="why-me-heading"><?php echo esc_html($why_me['title']); ?></h2><?php endif; ?>
                </div>
                <ul class="feature-grid js-reveal" role="list">
                    <?php foreach ($why_me_items as $i => $item) :
                        if (empty($item['title']) && empty($item['text'])) continue;
                    ?>
                        <li class="feature-item flow">
                            <span class="feature-item__num" aria-hidden="true"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
                            <?php if (!empty($item['title'])) : ?><h3 class="feature-item__title"><?php echo esc_html($item['title']); ?></h3><?php endif; ?>
                            <?php if (!empty($item['text']))  : ?><p class="feature-item__desc"><?php echo esc_html($item['text']); ?></p><?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>

        <!-- ===================== THE PROOF ===================== -->
        <?php
        $proof = get_field('proof') ?: [];
        $proof_btn      = $proof['button']      ?? [];
        $proof_btn_type = $proof['button_type'] ?? '';
        $proof_btn_classes = [
            'primary' => 'button button--primary',
            'outline' => 'button button--outline',
            'regular' => 'button',
        ];
        $proof_btn_class = $proof_btn_classes[$proof_btn_type] ?? 'button';

        $score_class = function(int $score): string {
            if ($score >= 90) return 'good';
            if ($score >= 50) return 'caution';
            return 'warning';
        };

        $proof_sites = [
            ['data' => $proof['site_one'] ?? [], 'bg' => 'bg-paper'],
            ['data' => $proof['site_two'] ?? [], 'bg' => 'score-block--contrast bg-white'],
        ];
        ?>
        <section id="the-proof" class="section bg-chalk" aria-labelledby="proof-heading">
            <div class="container">
                <div class="two-col">
                    <div class="two-col__left flow js-reveal">
                        <?php if (!empty($proof['kicker'])) : ?><p class="kicker"><?php echo esc_html($proof['kicker']); ?></p><?php endif; ?>
                        <?php if (!empty($proof['title']))  : ?><h2 id="proof-heading"><?php echo esc_html($proof['title']); ?></h2><?php endif; ?>
                        <?php if (!empty($proof['text']))   : ?><?php echo wp_kses_post($proof['text']); ?><?php endif; ?>
                        <?php if (!empty($proof_btn['url']) && !empty($proof_btn['title'])) :
                            $proof_btn_target = !empty($proof_btn['target']) ? ' target="' . esc_attr($proof_btn['target']) . '" rel="noopener noreferrer"' : '';
                        ?>
                            <a href="<?php echo esc_url($proof_btn['url']); ?>" class="<?php echo esc_attr($proof_btn_class); ?>"<?php echo $proof_btn_target; ?>><?php echo esc_html(rtrim($proof_btn['title'], " \t→")); ?> &#8594;</a>
                        <?php endif; ?>
                    </div>
                    <div class="two-col__right js-reveal">
                        <?php foreach ($proof_sites as $site) :
                            $s               = $site['data'];
                            $s_performance   = isset($s['performance'])    ? (int) $s['performance']    : null;
                            $s_accessibility = isset($s['accessibility'])  ? (int) $s['accessibility']  : null;
                            $s_best          = isset($s['best_practices']) ? (int) $s['best_practices'] : null;
                            $s_seo           = isset($s['seo'])            ? (int) $s['seo']            : null;
                            $badge_class     = ($s['label_type'] ?? '') === 'good' ? 'badge--live' : 'badge--template';
                        ?>
                            <div class="score-block <?php echo esc_attr($site['bg']); ?>">
                                <header class="score-block__header">
                                    <div class="flex flex-col flow">
                                        <?php if (!empty($s['kicker'])) : ?><p class="kicker"><?php echo esc_html($s['kicker']); ?></p><?php endif; ?>
                                        <?php if (!empty($s['site']))   : ?><span class="score-block__site"><?php echo esc_html($s['site']); ?></span><?php endif; ?>
                                    </div>
                                    <?php if (!empty($s['label'])) : ?><span class="badge <?php echo esc_attr($badge_class); ?>"><?php echo esc_html($s['label']); ?></span><?php endif; ?>
                                </header>
                                <dl class="score-list">
                                    <?php foreach ([
                                        ['Performance',    $s_performance],
                                        ['Accessibility',  $s_accessibility],
                                        ['Best Practices', $s_best],
                                        ['SEO',            $s_seo],
                                    ] as [$metric_label, $score]) :
                                        if ($score === null) continue;
                                    ?>
                                        <div class="score-list__item">
                                            <dt class="score-list__label"><?php echo esc_html($metric_label); ?></dt>
                                            <dd class="score-list__value score-list__value--<?php echo esc_attr($score_class($score)); ?>" style="--score:<?php echo $score; ?>"><?php echo $score; ?></dd>
                                        </div>
                                    <?php endforeach; ?>
                                </dl>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== RECENT WORK ===================== -->
        <?php
        $sites = get_field('sites') ?: [];
        $site_items = [
            $sites['site_one']   ?? [],
            $sites['site_two']   ?? [],
            $sites['site_three'] ?? [],
        ];
        ?>
        <section id="recent-work" class="section bg-paper" aria-labelledby="work-heading">
            <div class="container">
                <?php
                $sites_btn       = $sites['button']      ?? [];
                $sites_btn_type  = $sites['button_type'] ?? '';
                $sites_btn_class = $btn_classes[$sites_btn_type] ?? 'button';
                $sites_btn_url   = $sites_btn['url']   ?? '';
                $sites_btn_label = $sites_btn['title'] ?? '';
                $sites_btn_target = !empty($sites_btn['target']) ? ' target="' . esc_attr($sites_btn['target']) . '" rel="noopener noreferrer"' : '';
                ?>
                <div class="section-header section-header--row flow js-reveal">
                    <div class="flow">
                        <?php if (!empty($sites['kicker'])) : ?><p class="kicker"><?php echo esc_html($sites['kicker']); ?></p><?php endif; ?>
                        <?php if (!empty($sites['title']))  : ?><h2 id="work-heading"><?php echo esc_html($sites['title']); ?></h2><?php endif; ?>
                    </div>
                    <?php if ($sites_btn_url && $sites_btn_label) : ?>
                        <a href="<?php echo esc_url($sites_btn_url); ?>" class="<?php echo esc_attr($sites_btn_class); ?>"<?php echo $sites_btn_target; ?>><?php echo esc_html(rtrim($sites_btn_label, " \t→")); ?> &#8594;</a>
                    <?php endif; ?>
                </div>
                <div class="image-content-list">
                    <?php foreach ($site_items as $i => $site) :
                        if (empty($site['title'])) continue;
                        $site_id   = 'work-site-' . ($i + 1);
                        $site_link = is_array($site['link'] ?? '') ? ($site['link']['url'] ?? '') : ($site['link'] ?? '');
                    ?>
                        <div class="image-content-item js-reveal" aria-labelledby="<?php echo esc_attr($site_id); ?>">
                            <?php if (!empty($site['image'])) : ?>
                                <figure class="image-content-item__media card__media ratio ratio--16-9">
                                    <?php echo wp_get_attachment_image($site['image'], 'large', false, ['loading' => 'lazy', 'class' => 'ratio__content']); ?>
                                </figure>
                            <?php endif; ?>
                            <div class="image-content-item__content flow">
                                <!-- <span class="image-content-item__index" aria-hidden="true"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span> -->
                                <?php if (!empty($site['kicker'])) : ?><p class="kicker"><?php echo esc_html($site['kicker']); ?></p><?php endif; ?>
                                <?php if (!empty($site['title'])) : ?>
                                    <div class="card__meta flow">
                                        <h3 id="<?php echo esc_attr($site_id); ?>" class="card__title"><?php echo esc_html($site['title']); ?></h3>
                                        <?php if ($site_link) : ?>
                                            <a href="<?php echo esc_url($site_link); ?>" class="card__external-link" target="_blank" rel="noopener noreferrer" aria-label="Visit site">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <line x1="3" y1="17" x2="17" y2="3"/>
                                                    <polyline points="7 3 17 3 17 13"/>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($site['text'])) : ?><div class="card__desc flow"><?php echo wp_kses_post($site['text']); ?></div><?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- ===================== FOUNDING OFFER ===================== -->
        <?php get_template_part('template-parts/promo-section'); ?>

        <!-- ===================== NEXT STEP ===================== -->
        <?php get_template_part('template-parts/cta'); ?>

    </article><!-- #post -->

<?php endwhile; ?>

<?php get_footer(); ?>
