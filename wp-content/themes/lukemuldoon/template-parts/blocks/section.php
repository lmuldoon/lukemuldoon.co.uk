<?php

/**
 * Flexible content partial: Start Section with Background Colour
 */

$spacing = get_sub_field('spacing');
$spacing_style = lm26_get_spacing_css_style($spacing);

$spacing_style = str_replace('style=', '', $spacing_style);
$spacing_style = str_replace('"', '', $spacing_style);

global $_section_open;

$colour = get_sub_field('background_colour');

$next_block = lm26_acf_get_adjacent_layout(1);

?>

<?php if ( $_section_open ): ?>
	</div> <!-- /.section -->
	<?php $_section_open = false; ?>
<?php endif; ?>

<?php
	if ( 'transparent' === $colour ) {
		return;
	}
?>

<div 
	class="section"
	data-acf-layout="<?= get_row_layout(); ?>"
	data-acf-layout-next="<?= $next_block ? $next_block['acf_fc_layout'] : ''; ?>"
	<?= lm26_format_id_attr(get_sub_field('custom_id')); ?>
	<?php if ( 'transparent' !== $colour ): ?>
		<?php
			$colour_props = lm26_get_hex_props($colour);

			$text_color = '';
			if ( $colour_props['lightness'] < LIGHTNESS_THRESHOLD ) {
				$text_color = 'white';
			}
		?>
		style="background-color:<?php echo $colour; ?>; <?php echo $text_color ? 'color:'.$text_color.';' : ''; ?> --section-color:<?php echo $colour; ?>; <?= $spacing_style; ?>"
	<?php else: ?>
		style="<?= $spacing_style; ?>"
	<?php endif; ?>
>
	<?php $_section_open = true; ?>
