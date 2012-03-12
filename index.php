<?php if (is_home()) $args=array(
					   'showposts'=> (int) get_option('13floor_homepage_posts'),
					   'paged'=>$paged,
					   'category__not_in' => (array) get_option('13floor_exlcats_recent'),
					); ?>
<?php get_header(); ?>
	
	<?php 
		if (is_home()) query_posts($args);
	?>
	
	<div id="content-top"></div>
		
	<div id="contentwrap">
		<div id="content" class="clearfix">
		
			<div id="content-area">
				
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<?php $thumb = ''; 	  

						  $width = 136;
						  $height = 136;
						  $classtext = 'thumbnail alignleft';
						  $titletext = get_the_title();
						
					   	  $thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
						  $thumb = $thumbnail["thumb"]; ?>
						  
					<?php global $post;
						  $page_result = is_search() && ($post->post_type == 'page') ? true : false; ?>
					
					<div class="entry clearfix<?php if ($page_result) echo(' page_result'); ?>">
					
						<h2 class="title"><a href="<?php the_permalink() ?>" title="<?php printf(esc_attr__('Permanent Link to %s', '13floor'), $titletext) ?>"><?php the_title(); ?></a></h2>
						
						<?php if ((get_option('13floor_postinfo1') <> '') && !($page_result)) get_template_part('includes/postinfo'); ?>
						
						<?php if($thumb <> '' && get_option('13floor_thumbnails_index') == 'on') { ?>						
							<a href="<?php the_permalink() ?>" title="<?php printf(esc_attr__('Permanent Link to %s', '13floor'), $titletext) ?>">
								<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
							</a>
						<?php }; ?>	
						
						<?php if (get_option('13floor_blog_style') == 'on') the_content(""); else { ?>
							<p><?php truncate_post(365); ?></p>
						<?php }; ?>
												
						<a class="readmore" href="<?php the_permalink() ?>" title="<?php printf(esc_attr__('Permanent Link to %s', '13floor'), $titletext) ?>"><span><?php esc_html_e('Read More','13floor'); ?></span></a>
						
					</div> <!-- end .entry -->

				<?php endwhile; ?>

					<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
						else { ?>
							<?php get_template_part('includes/navigation'); ?>
					<?php } ?>

				<?php else : ?>
					<?php get_template_part('includes/no-results'); ?>
				<?php endif; wp_reset_query(); ?>		
				
			</div> <!-- end #content-area -->
			
			<?php get_sidebar(); ?>
			
			<?php get_footer(); ?>