<?php

/**
 * Flexible content partial: Masterplan
 */

$spacing = get_sub_field('spacing');
$spacing_style = lm26_get_spacing_css_style($spacing);
$container_size = get_sub_field('container_size');
$num_rows = count(get_sub_field('downloads'));
$title = get_sub_field('title');
?>

<?php if (have_rows('downloads')) : ?>

    <div class="" data-acf-layout="<?= get_row_layout(); ?>" <?= $spacing_style; ?> <?= lm26_format_id_attr(get_sub_field('custom_id')); ?>>

        <div class="container animated container--<?= esc_attr($container_size); ?>">

            <?php if ($title) : ?>
                <h2 class="uppercase mb-10"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>

            <?php if ($num_rows == 1) : ?>
                
                <?php while (have_rows('downloads')) : the_row(); ?>
                    <?php
                    $file_data = get_sub_field('file');
                    if ($file_data && is_array($file_data) && !empty($file_data['url'])) :
                        $file_url = $file_data['url'];
                        $file_name = basename($file_url);
                    endif;
                    ?>
                    <?php if ($file_url) : ?>
                        <div>
                            <a href="<?php echo esc_url($file_url); ?>" download="<?php echo esc_attr($file_name); ?>" target="_blank" class="button button--rect button--red mb-10"><?php echo esc_html(get_sub_field('text')); ?></a>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>

            <?php else : ?>
                
                    <?php while (have_rows('downloads')) :  the_row(); ?>
                        <?php
                        $file_data = get_sub_field('file');
                        if ($file_data && is_array($file_data) && !empty($file_data['url'])) :
                            $file_url = $file_data['url'];
                            $file_name = basename($file_url);
                        endif;
                        ?>
                        <div class="flex justify-between items-center gap-x-10 gap-y-4 border-solid border-t-0 border-l-0 border-r-0 border-red py-4">
                            <p><?php echo esc_html(get_sub_field('text')); ?></p>
                            <?php if ($file_url) : ?>
                                <a href="<?php echo esc_url($file_url); ?>" download="<?php echo esc_attr($file_name); ?>" target="_blank" class="button hidden sm:inline-flex">
                                    Download
                                </a>
                                <a class="block sm:hidden" href="<?php echo esc_url($file_url); ?>" download="<?php echo esc_attr($file_name); ?>" target="_blank">
                                    <svg  xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                                        <g fill="none">
                                            <path d="M24 0v24H0V0h24ZM12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427c-.002-.01-.009-.017-.017-.018Zm.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093c.012.004.023 0 .029-.008l.004-.014l-.034-.614c-.003-.012-.01-.02-.02-.022Zm-.715.002a.023.023 0 0 0-.027.006l-.006.014l-.034.614c0 .012.007.02.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01l-.184-.092Z" />
                                            <path fill="#ED1C24" d="M20 15a1 1 0 0 1 1 1v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4a1 1 0 1 1 2 0v4h14v-4a1 1 0 0 1 1-1ZM12 2a1 1 0 0 1 1 1v10.243l2.536-2.536a1 1 0 1 1 1.414 1.414l-4.066 4.066a1.25 1.25 0 0 1-1.768 0L7.05 12.121a1 1 0 1 1 1.414-1.414L11 13.243V3a1 1 0 0 1 1-1Z" />
                                        </g>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>