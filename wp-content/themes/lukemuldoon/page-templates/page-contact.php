<?php
/**
 * Template Name: Contact
 */

get_header();

$hero = get_field('hero') ?: [];
?>

<main id="main-content">

    <section class="hero hero__page section bg-chalk" aria-labelledby="contact-heading">
        <div class="container">
            <div class="flow js-reveal">
                <?php if (!empty($hero['kicker'])) : ?>
                    <p class="kicker"><?php echo esc_html($hero['kicker']); ?></p>
                <?php endif; ?>
                <?php if (!empty($hero['title'])) : ?>
                    <h1 id="contact-heading" class="h2 hero__heading"><?php echo esc_html($hero['title']); ?></h1>
                <?php endif; ?>
                <?php if (!empty($hero['text'])) : ?>
                    <div class="flow hero__body"><?php echo wp_kses_post($hero['text']); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="section bg-paper" aria-label="Contact form">
        <div class="container">
            <div class="two-col contact-layout">

                <!-- FORM -->
                <div class="js-reveal">
                    <form
                        id="contact-form"
                        class="contact-form"
                        novalidate
                        data-time="<?php echo esc_attr(time()); ?>"
                    >
                        <div class="contact-form__field">
                            <label class="label" for="cf-name">Your name</label>
                            <input class="contact-form__input" type="text" id="cf-name" name="cf_name" autocomplete="name" required>
                            <span class="contact-form__error" aria-live="polite"></span>
                        </div>

                        <div class="contact-form__field">
                            <label class="label" for="cf-email">Email address</label>
                            <input class="contact-form__input" type="email" id="cf-email" name="cf_email" autocomplete="email" required>
                            <span class="contact-form__error" aria-live="polite"></span>
                        </div>

                        <div class="contact-form__field">
                            <label class="label" for="cf-budget">Budget range <span class="label__tag">(optional)</span></label>
                            <div class="contact-form__select-wrap">
                                <select class="contact-form__select" id="cf-budget" name="cf_budget">
                                    <option value="">Select a range</option>
                                    <option value="under-1000">Under £1,000</option>
                                    <option value="1000-2500">£1,000 – £2,500</option>
                                    <option value="2500-5000">£2,500 – £5,000</option>
                                    <option value="5000-plus">£5,000+</option>
                                    <option value="not-sure">Not sure yet</option>
                                </select>
                            </div>
                        </div>

                        <div class="contact-form__field">
                            <label class="label" for="cf-message">Tell me about your project</label>
                            <textarea class="contact-form__input contact-form__textarea" id="cf-message" name="cf_message" rows="6" required></textarea>
                            <span class="contact-form__error" aria-live="polite"></span>
                        </div>

                        <!-- Honeypot -->
                        <input type="text" name="website" tabindex="-1" aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0" autocomplete="off">

                        <!-- hCaptcha -->
                        <div class="contact-form__captcha h-captcha" data-sitekey="<?php echo esc_attr(defined('HCAPTCHA_SITE_KEY') ? HCAPTCHA_SITE_KEY : ''); ?>"></div>

                        <div class="contact-form__actions">
                            <button type="submit" class="button button--primary contact-form__submit">
                                Send message &#8594;
                            </button>
                        </div>

                        <div class="contact-form__feedback" role="alert" hidden></div>
                    </form>

                    <div class="contact-form__success" hidden>
                        <span class="contact-form__success-icon" aria-hidden="true">&#10003;</span>
                        <h2 class="contact-form__success-title">Message sent.</h2>
                        <p>Thanks — I'll be in touch within one business day.</p>
                    </div>
                </div>

                <!-- SIDEBAR -->
                <aside class="contact-info js-reveal">
                    <div class="contact-info__item">
                        <p class="contact-info__label">Response time</p>
                        <p class="contact-info__value">Within one business day</p>
                    </div>
                    <div class="contact-info__item">
                        <p class="contact-info__label">Or email directly</p>
                        <a href="mailto:luke@lukemuldoon.co.uk" class="contact-info__email">luke@lukemuldoon.co.uk</a>
                    </div>
                    <div class="contact-info__item">
                        <p class="contact-info__label">What to include</p>
                        <p class="contact-info__value">What you need, your timeline, and any budget in mind. No lengthy briefs needed — a few sentences is enough to get started.</p>
                    </div>
                </aside>

            </div>
        </div>
    </section>

    <?php get_template_part('template-parts/cta'); ?>

</main>

<?php get_footer(); ?>
