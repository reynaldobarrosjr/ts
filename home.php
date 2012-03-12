<?php if(get_option('13floor_featured') == 'on') { ?>
	<?php get_header(); ?>

	<?php get_template_part('includes/featured'); ?>

	<?php get_footer(); ?>
<?php } else { ?>
	<?php get_template_part('index'); ?>
<?php }; ?>