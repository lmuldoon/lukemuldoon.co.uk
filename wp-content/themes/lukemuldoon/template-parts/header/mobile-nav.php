<?php
/**
 * Mobile navigation drawer.
 */

try {
    $menu = new WP_Menu_Query(['location' => 'header-menu']);
} catch (Exception $e) {
    return;
}

if (!$menu->have_items()) return;
?>

<div class="menu-body-shade js-mobile-nav-shade" aria-hidden="true"></div>

<nav class="mobile-nav js-mobile-nav" id="mobile-nav" aria-label="Mobile navigation">

    <div class="mobile-nav__header">
        <button class="mobile-nav__close js-close-menu" type="button" aria-label="Close menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                <line x1="2" y1="2" x2="16" y2="16"/>
                <line x1="16" y1="2" x2="2" y2="16"/>
            </svg>
            Close
        </button>
    </div>

    <ul class="mobile-nav__list">
        <?php while ($menu->have_items()) :
            $item = $menu->the_item();
            $classes = [];
            if ($item->is_current() || $item->has_current_child()) {
                $classes[] = 'is-current';
            }
        ?>
        <li class="<?php echo esc_attr(implode(' ', $classes)); ?>">
            <a class="mobile-nav__item"
               href="<?php echo esc_url($item->url); ?>"
               <?php echo $item->target ? 'target="' . esc_attr($item->target) . '" rel="noopener noreferrer"' : ''; ?>
               <?php if ($item->is_current()) : ?>aria-current="page"<?php endif; ?>>
                <?php echo esc_html($item->title); ?>
            </a>
        </li>
        <?php endwhile; ?>
    </ul>

</nav>
