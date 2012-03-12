<?php	
	$ids = array();
	$arr = array();
	$i=1;
	
	$width = 335;
	$height = 220;
	$maxSlides = 4;
		
	$featured_cat = esc_attr(get_option('13floor_feat_cat'));
	$featured_num = esc_attr(get_option('13floor_featured_num'));
	if ($featured_num > $maxSlides) $featured_num = $maxSlides;
	
	if (get_option('13floor_use_pages') == 'false') query_posts("showposts=$featured_num&cat=".get_catId($featured_cat));
	else { 
		global $pages_number;
		
		if (get_option('13floor_feat_pages') <> '') $featured_num = count(get_option('13floor_feat_pages'));
		else $featured_num = $pages_number;
		
		if ($featured_num > $maxSlides) $featured_num = $maxSlides;
		
		query_posts(array
						('post_type' => 'page',
						'orderby' => 'menu_order',
						'order' => 'ASC',
						'post__in' => (array) get_option('13floor_feat_pages'),
						'showposts' => $featured_num
					));
	};
	
	while (have_posts()) : the_post();
			
		$arr[$i]["title"] = truncate_title(25,false);
		$arr[$i]["small_title_default"] = truncate_title(10,false);	
		$arr[$i]["fulltitle"] = truncate_title(250,false);
		
		$arr[$i]["excerpt"] = truncate_post(430,false);
		$arr[$i]["small_excerpt_default"] = truncate_post(45,false);
		$arr[$i]["button_text"] = get_post_meta($post->ID, 'Button', true);
		
		$arr[$i]["small_title"] = get_post_meta($post->ID, 'Title', true);
		$arr[$i]["small_excerpt"] = get_post_meta($post->ID, 'Excerpt', true);
		
		$arr[$i]["permalink"] = get_permalink();
		
		$arr[$i]["thumbnail"] = get_thumbnail($width,$height,'featured_image',$arr[$i]["fulltitle"],$arr[$i]["fulltitle"]);
		
		$arr[$i]["thumb"] = $arr[$i]["thumbnail"]["thumb"];		
		$arr[$i]["use_timthumb"] = $arr[$i]["thumbnail"]["use_timthumb"];

		$i++;
		$ids[]= $post->ID;
		
	endwhile; wp_reset_query();	?>
	
	
	<div id="featured-area">

		<div id="feat-content" class="clearfix<?php if(get_option('13floor_custom_animation') == 'on') echo(' custom_animation'); ?>">
			
			<?php for ($i = 1; $i <= $featured_num; $i++) { ?>
			
				<div class="slide">
					<div class="description">
						<h2 class="title"><a href="<?php echo($arr[$i]["permalink"]); ?>" title="<?php printf(esc_attr__('Permanent Link to %s', '13floor'), $arr[$i]["fulltitle"]) ?>"><?php echo esc_html($arr[$i]["title"]); ?></a></h2>
						<p><?php echo($arr[$i]["excerpt"]); ?></p>
						
						<a href="<?php echo esc_url($arr[$i]["permalink"]); ?>" class="readmore"><span><?php if($arr[$i]["button_text"] <> '') echo esc_html($arr[$i]["button_text"]); else echo(esc_html__('Sign Up Today', '13floor')); ?></span></a>
					</div>
					
					<a href="<?php echo($arr[$i]["permalink"]); ?>" title="<?php printf(esc_attr__('Permanent Link to %s', '13floor'), $arr[$i]["fulltitle"]) ?>">
						<?php print_thumbnail($arr[$i]["thumb"], $arr[$i]["use_timthumb"], $arr[$i]["fulltitle"], $width, $height, 'featured_image'); ?>
					</a>
				</div> <!-- end .slide -->
							
			<?php }; ?>
			
		</div> <!-- end #feat-content -->
		
		<div id="control-bg"></div>
		
		
		<div id="controls" class="clearfix">
			<a href="" id="prevlink"><?php esc_html_e('Prev','13floor'); ?></a>
			<a href="" id="nextlink"><?php esc_html_e('Next','13floor'); ?></a>
			
			<?php for ($i = 1; $i <= $featured_num; $i++) { ?>
				<a class="control_tab<?php if($i == 1) echo(" active"); if($i == $maxSlides) echo(' last'); ?>" href="#">
					<span class="heading"><?php if($arr[$i]["small_title"] <> '') echo($arr[$i]["small_title"]); else echo($arr[$i]["small_title_default"]); ?></span>
					<span class="excerpt"><?php if($arr[$i]["small_excerpt"] <> '') echo($arr[$i]["small_excerpt"]); else echo($arr[$i]["small_excerpt_default"]); ?></span>
				</a>
			<?php }; ?>
		
		</div> <!-- end #controls -->
				
	</div> <!-- end #featured-area -->