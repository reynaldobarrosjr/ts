<?php
if ( function_exists('register_sidebar') )
    register_sidebar(array(
		'name' => 'Sidebar',
		'before_widget' => '<div id="%1$s" class="clearfix widget %2$s">',
		'after_widget' => '</div> <!-- end .widget_content -->
						</div> <!-- end .wrapper -->
					</div> <!-- end .widget -->',
		'before_title' => '<h3 class="title">',
		'after_title' => '</h3><div class="wrapper clearfix"><div class="widget_content">',
    ));
if ( function_exists('register_sidebar') )
    register_sidebar(array(
		'name' => 'Footer',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div> <!-- end .widget_content -->
						</div> <!-- end .widget -->',
		'before_title' => '<h3 class="title">',
		'after_title' => '</h3>
					<div class="widget_content">',
    )); ?>