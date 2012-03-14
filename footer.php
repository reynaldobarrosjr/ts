		<?php if(!is_home() || get_option('13floor_featured') == 'false') { ?>
				</div> <!-- end #content -->	
			</div> <!-- end #contentwrap -->
			
			<div id="content-bottom"></div>
			
			<div id="footer-top"></div>
			<div id="footer" class="clearfix">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer') ) : ?> 
				<?php endif; ?>
			</div> <!-- end #footer -->
		<?php }; ?>
				
		<div id="copyright" class="clearfix">
			<p><a href="http://www.tecscap.com.br">2012 Tecscap</a> | <?php esc_html_e('Desenvolvido por ','13floor'); ?> <a href="http://www.trevobr.com">Trevo</a></p>
		</div> <!-- end #copyright -->
			
	</div> <!-- end #wrap -->
	
	<?php get_template_part('includes/scripts'); ?>
	<?php wp_footer(); ?>	
</div>
</div>
</body>
</html>