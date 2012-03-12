<?php global $shortname; ?>

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.cycle.all.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.easing.1.3.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/superfish.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.backgroundPosition.js"></script>
	
	<script type="text/javascript">
	//<![CDATA[
		jQuery.noConflict();
				
		var $featured_area = jQuery('#featured-area'),
			$featured_content = jQuery('#feat-content');

		et_top_menu();
		et_search_bar()
		et_footer_improvements('#footer .widget');
		
		if ($featured_content.length) {
			var widthButtonsBg,
				controlTabString = 'a.control_tab',
				$controlTab = jQuery(controlTabString),
				$slider_control = jQuery('div#controls'),
				$slider_control_tab = jQuery('div#controls '+controlTabString),
				$slider_arrows = jQuery('#featured-area a#prevlink, #featured-area a#nextlink');
				
			<!-- buttons background -->	
			if ($controlTab.length === 2) widthButtonsBg = 434;
			if ($controlTab.length === 1) widthButtonsBg = 220;	
			if (widthButtonsBg) jQuery('#control-bg').css('width',widthButtonsBg+'px');
				
			function isIE6() { return ((window.XMLHttpRequest == undefined) && (ActiveXObject != undefined)) }			
			
			if (($featured_content.hasClass("custom_animation")) && (!isIE6()))
				et_custom_featured();
			else 
				et_cycle_integration(); 
		};
		
		
		<!---- Top Menu Improvements ---->
		function et_top_menu(){
			jQuery('ul.superfish').superfish({ 
				delay:       300,                            // one second delay on mouseout 
				animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
				speed:       'fast',                          // faster animation speed 
				autoArrows:  true,                           // disable generation of arrow mark-up 
				dropShadows: false                            // disable drop shadows 
			});
					
			jQuery('ul.nav > li > a').hover(function(){
				jQuery(this).stop().animate({paddingTop: "14px"},300,function(){
					jQuery(this).addClass("top_arrow");
				})
			},function(){
				jQuery(this).stop().removeClass("top_arrow").animate({paddingTop: "25px"},300);
			});
			
			<?php if (get_option($shortname.'_disable_toptier') == 'on') echo('jQuery("ul.nav > li > ul").prev("a").attr("href","#");'); ?>
			
			var $current_page_cat = jQuery('ul.nav > li.current_page_item, ul.nav > li.current-cat');
			
			if ($current_page_cat.length) {
				//shows the current page,category arrow
				var currentLeft = $current_page_cat.position().left,
					currentWidth = $current_page_cat.width();
				
				if ($featured_area.length) currentLeft = currentLeft + 25;
				
				var currentSpan = '<span class="current_arrow" style="left: ' + currentLeft + 'px; width: '+ currentWidth +'px;"></span>';
				
				if ($featured_area.length) $featured_area.append(currentSpan)
				else jQuery("#content-top").append(currentSpan);
			};
		};	
		
		
		<!---- Top Menu Improvements ---->
		function et_footer_improvements($selector){
			var $footer_widget = jQuery($selector);
		
			if (!($footer_widget.length == 0)) {
				$footer_widget.each(function (index, domEle) {
					if ((index+1)%3 == 0) jQuery(domEle).addClass("last").after("<div class='clear'></div>");
				});
			};
		};
		
		
		<!---- Search Bar Improvements ---->
		function et_search_bar(){
			var $searchform = jQuery('#header div#search-form'),
				$searchinput = $searchform.find("input#searchinput"),
				searchvalue = $searchinput.val();
				
			$searchinput.focus(function(){
				if (jQuery(this).val() === searchvalue) jQuery(this).val("");
			}).blur(function(){
				if (jQuery(this).val() === "") jQuery(this).val(searchvalue);
			});
		};
		
		
		<!---- Featured Slider Cycle Integration ---->
		function et_cycle_integration(){
			$featured_content.cycle({
				timeout: 0,
				speed: 300,
				cleartypeNoBg: true
			});
										
			var ordernum;
			var pause_scroll = false;
			
			<?php if (get_option($shortname.'_pause_hover') == 'on') { ?>
				$featured_area.mouseover(function(){
					pause_scroll = true;
				}).mouseout(function(){
					pause_scroll = false;
				});
			<?php }; ?>

			function gonext(this_element){	
				$slider_control.children(controlTabString+".active").removeClass('active');
				var activeLeft = this_element.position().left+8;
				$slider_control.animate({backgroundPosition: activeLeft+'px 7px'},500,function(){
					this_element.addClass('active');
				});
								
				ordernum = this_element.prevAll(controlTabString).length+1;
				$featured_content.cycle(ordernum - 1);
			} 
			
			$slider_control_tab.click(function() {
				clearInterval(interval);
				gonext(jQuery(this));
				return false;
			});
			
			$slider_arrows.click(function() {
				clearInterval(interval);
								
				if (jQuery(this).attr("id") === 'nextlink') {
					auto_number = $slider_control.children(controlTabString+".active").prevAll(controlTabString).length+1;
					if (auto_number === $slider_control_tab.length) auto_number = 0;
				} else {
					auto_number = $slider_control.children(controlTabString+".active").prevAll(controlTabString).length-1; 
					if (auto_number === -1) auto_number = $slider_control_tab.length-1;
				};
				
				gonext($slider_control_tab.eq(auto_number));
				return false;
			});
			
			var auto_number;
			var interval;
			
			$slider_control_tab.bind('autonext', function autonext(){
				if (!pause_scroll) gonext(jQuery(this)); 
				return false;
			});
			
			<?php if (get_option($shortname.'_slider_auto') == 'on') { ?>	
				interval = setInterval(function() {
					auto_number = $slider_control.children(controlTabString+".active").prevAll(controlTabString).length+1;
					if (auto_number === $slider_control_tab.length) auto_number = 0;
					$slider_control_tab.eq(auto_number).trigger('autonext');
				}, <?php echo esc_js(get_option($shortname.'_slider_autospeed')); ?>);
			<?php }; ?>
						
		};
		
		
		<!---- Custom Featured Slider Animation ---->
		function et_custom_featured(){
			var $slide = $featured_area.find('div.slide');
			var isAnimation = false;
			
			var ordernum;
			var pause_scroll = false;
		
			<?php if (get_option($shortname.'_pause_hover') == 'on') { ?>
				$featured_area.mouseover(function(){
					pause_scroll = true;
					clearTimeout(interval);
				}).mouseout(function(){
					pause_scroll = false;
					<?php if (get_option($shortname.'_slider_auto') == 'on') { ?>	
						interval = setTimeout( et_custom_slider_autonext, <?php echo esc_js(get_option($shortname.'_slider_autospeed')); ?> );
					<?php }; ?>
				});
			<?php }; ?>
			
			$slide.css('display','none').filter(':first').css({'display':'block'});
			
			$slider_control_tab.click(function() {
				clearTimeout(interval);
				if (!isAnimation) gonext(jQuery(this));
				return false;
			});
			
			function gonext(next_element){
				isAnimation = true;
								
				var $current_tab = $slider_control.children(controlTabString+".active"),
					ordernumCurrent = $current_tab.prevAll(controlTabString).length+1,
					ordernumNext = next_element.prevAll(controlTabString).length+1,
					$currentElement = $slide.eq(ordernumCurrent-1),
					$nextElement = $slide.eq(ordernumNext-1),
					sliderSpeed = 300;
					
				var readmoreButton = "a.readmore",
					featuredImage = "img.featured_image",
					descriptionDiv = "div.description",
					doAnimation;
					
				doAnimation = true;
				if (ordernumCurrent === ordernumNext) { doAnimation = false; isAnimation = false; };
				
				if (doAnimation) {
					move_arrow();
					
					$currentElement.find(readmoreButton).animate({'opacity':'hide'},sliderSpeed,function(){
						$currentElement.find(featuredImage).animate({'opacity':'hide'},sliderSpeed, function(){
							$currentElement.find(descriptionDiv).animate({'opacity':'hide'},sliderSpeed,function(){
								$currentElement.css('display','none');
								
								$nextElement.children().css('visibility','hidden').end().css('display','block').children(descriptionDiv).children().css({'display':'block','visibility':'hidden'}).end().css('display','block');
								
								$nextElement.find(readmoreButton).css({'top': '-190px','display': 'block','visibility':'visible','opacity':'0'}).animate({top:0,opacity:1},1200,'easeOutBounce',function(){
									$nextElement.find(featuredImage).parent('a').css({'visibility': 'visible','opacity':'0'}).animate({opacity:1},sliderSpeed, function(){
										$nextElement.find(descriptionDiv).css({'visibility': 'visible','opacity':'1'}).children().filter(':not('+readmoreButton+')').css({'visibility': 'visible','opacity':'0'}).animate({opacity:1},sliderSpeed);
										
										$currentElement.find(featuredImage).css({'opacity':'1','display':'block'});
										
										isAnimation = false;
										
										<?php if (get_option($shortname.'_slider_auto') == 'on') { ?>	
											interval = setTimeout( et_custom_slider_autonext, <?php echo esc_js(get_option($shortname.'_slider_autospeed')); ?> );
										<?php }; ?>
									});
								});
							});
						});
					});
				};
				
				function move_arrow(){
					$current_tab.removeClass('active');
				
					var activeLeft = next_element.position().left+8;
					$slider_control.animate({backgroundPosition: activeLeft+'px 7px'},500,function(){
						next_element.addClass('active');
					});
				};
			}; 
			
			$slider_arrows.click(function() {
				clearTimeout(interval);
								
				if (jQuery(this).attr("id") === 'nextlink') {
					auto_number = $slider_control.children(controlTabString+".active").prevAll(controlTabString).length+1;
					if (auto_number === $slider_control_tab.length) auto_number = 0;
				} else {
					auto_number = $slider_control.children(controlTabString+".active").prevAll(controlTabString).length-1; 
					if (auto_number === -1) auto_number = $slider_control_tab.length-1;
				};
				
				if (!isAnimation) gonext($slider_control_tab.eq(auto_number));
				return false;
			});
			
			var auto_number;
			var interval;
			
			$slider_control_tab.bind('autonext', function autonext(){
				if (!pause_scroll) gonext(jQuery(this)); 
				return false;
			});
			
			<?php if (get_option($shortname.'_slider_auto') == 'on') { ?>	
				interval = setTimeout( et_custom_slider_autonext, <?php echo esc_js(get_option($shortname.'_slider_autospeed')); ?> );
			<?php }; ?>
			
			function et_custom_slider_autonext(){
				auto_number = $slider_control.children(controlTabString+".active").prevAll(controlTabString).length+1;
				if (auto_number === $slider_control_tab.length) auto_number = 0;
				$slider_control_tab.eq(auto_number).trigger('autonext');
			}
			
		};
		
	//]]>	
	</script>