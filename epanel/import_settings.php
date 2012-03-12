<?php 
add_action( 'admin_enqueue_scripts', 'import_epanel_javascript' );
function import_epanel_javascript( $hook_suffix ) {
	if ( 'admin.php' == $hook_suffix && isset( $_GET['import'] ) && isset( $_GET['step'] ) && 'wordpress' == $_GET['import'] && '1' == $_GET['step'] )
		add_action( 'admin_head', 'admin_headhook' );
}

function admin_headhook(){ ?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			$("p.submit").before("<p><input type='checkbox' id='importepanel' name='importepanel' value='1' style='margin-right: 5px;'><label for='importepanel'>Replace ePanel settings with sample data values</label></p>");
		});
	</script>
<?php }

add_action('import_end','importend');
function importend(){
	global $wpdb, $shortname;
	
	#make custom fields image paths point to sampledata/sample_images folder
	$sample_images_postmeta = $wpdb->get_results("SELECT meta_id, meta_value FROM $wpdb->postmeta WHERE meta_value REGEXP 'http://et_sample_images.com'");
	if ( $sample_images_postmeta ) {
		foreach ( $sample_images_postmeta as $postmeta ){
			$template_dir = get_template_directory_uri();
			if ( is_multisite() ){
				switch_to_blog(1);
				$main_siteurl = site_url();
				restore_current_blog();
				
				$template_dir = $main_siteurl . '/wp-content/themes/' . get_template();
			}
			preg_match( '/http:\/\/et_sample_images.com\/([^.]+).jpg/', $postmeta->meta_value, $matches );
			$image_path = $matches[1];
			
			$local_image = preg_replace( '/http:\/\/et_sample_images.com\/([^.]+).jpg/', $template_dir . '/sampledata/sample_images/$1.jpg', $postmeta->meta_value );
			
			$local_image = preg_replace( '/s:55:/', 's:' . strlen( $template_dir . '/sampledata/sample_images/' . $image_path . '.jpg' ) . ':', $local_image );
			
			$wpdb->update( $wpdb->postmeta, array( 'meta_value' => $local_image ), array( 'meta_id' => $postmeta->meta_id ), array( '%s' ) );
		}
	}

	if ( !isset($_POST['importepanel']) )
		return;
	
	$importOptions = 'YTo4ODp7czowOiIiO047czoxMjoiMTNmbG9vcl9sb2dvIjtzOjA6IiI7czoxNToiMTNmbG9vcl9mYXZpY29uIjtzOjA6IiI7czoyMDoiMTNmbG9vcl9jb2xvcl9zY2hlbWUiO3M6NDoiQmx1ZSI7czoxODoiMTNmbG9vcl9ibG9nX3N0eWxlIjtOO3M6MTg6IjEzZmxvb3JfZ3JhYl9pbWFnZSI7TjtzOjIwOiIxM2Zsb29yX2NhdG51bV9wb3N0cyI7czoxOiI2IjtzOjI0OiIxM2Zsb29yX2FyY2hpdmVudW1fcG9zdHMiO3M6MToiNSI7czoyMzoiMTNmbG9vcl9zZWFyY2hudW1fcG9zdHMiO3M6MToiNSI7czoyMDoiMTNmbG9vcl90YWdudW1fcG9zdHMiO3M6MToiNSI7czoxOToiMTNmbG9vcl9kYXRlX2Zvcm1hdCI7czo2OiJNIGosIFkiO3M6MTk6IjEzZmxvb3JfdXNlX2V4Y2VycHQiO047czoyMjoiMTNmbG9vcl9ob21lcGFnZV9wb3N0cyI7czoxOiI3IjtzOjIyOiIxM2Zsb29yX2V4bGNhdHNfcmVjZW50IjtOO3M6MTY6IjEzZmxvb3JfZmVhdHVyZWQiO3M6Mjoib24iO3M6MTc6IjEzZmxvb3JfdXNlX3BhZ2VzIjtOO3M6MjQ6IjEzZmxvb3JfY3VzdG9tX2FuaW1hdGlvbiI7czoyOiJvbiI7czoxNjoiMTNmbG9vcl9mZWF0X2NhdCI7czo4OiJGZWF0dXJlZCI7czoyMDoiMTNmbG9vcl9mZWF0dXJlZF9udW0iO3M6MToiMyI7czoxODoiMTNmbG9vcl9mZWF0X3BhZ2VzIjtOO3M6MTk6IjEzZmxvb3Jfc2xpZGVyX2F1dG8iO047czoxOToiMTNmbG9vcl9wYXVzZV9ob3ZlciI7TjtzOjI0OiIxM2Zsb29yX3NsaWRlcl9hdXRvc3BlZWQiO3M6NDoiNTAwMCI7czoxNzoiMTNmbG9vcl9tZW51cGFnZXMiO047czoyNDoiMTNmbG9vcl9lbmFibGVfZHJvcGRvd25zIjtzOjI6Im9uIjtzOjE3OiIxM2Zsb29yX2hvbWVfbGluayI7czoyOiJvbiI7czoxODoiMTNmbG9vcl9zb3J0X3BhZ2VzIjtzOjEwOiJwb3N0X3RpdGxlIjtzOjE4OiIxM2Zsb29yX29yZGVyX3BhZ2UiO3M6MzoiYXNjIjtzOjI1OiIxM2Zsb29yX3RpZXJzX3Nob3duX3BhZ2VzIjtzOjE6IjMiO3M6MTY6IjEzZmxvb3JfbWVudWNhdHMiO047czozNToiMTNmbG9vcl9lbmFibGVfZHJvcGRvd25zX2NhdGVnb3JpZXMiO3M6Mjoib24iO3M6MjQ6IjEzZmxvb3JfY2F0ZWdvcmllc19lbXB0eSI7czoyOiJvbiI7czozMDoiMTNmbG9vcl90aWVyc19zaG93bl9jYXRlZ29yaWVzIjtzOjE6IjMiO3M6MTY6IjEzZmxvb3Jfc29ydF9jYXQiO3M6NDoibmFtZSI7czoxNzoiMTNmbG9vcl9vcmRlcl9jYXQiO3M6MzoiYXNjIjtzOjIzOiIxM2Zsb29yX2Rpc2FibGVfdG9wdGllciI7TjtzOjE3OiIxM2Zsb29yX3Bvc3RpbmZvMiI7YTo0OntpOjA7czo2OiJhdXRob3IiO2k6MTtzOjQ6ImRhdGUiO2k6MjtzOjEwOiJjYXRlZ29yaWVzIjtpOjM7czo4OiJjb21tZW50cyI7fXM6MTg6IjEzZmxvb3JfdGh1bWJuYWlscyI7czoyOiJvbiI7czoyNToiMTNmbG9vcl9zaG93X3Bvc3Rjb21tZW50cyI7czoyOiJvbiI7czoyMzoiMTNmbG9vcl9wYWdlX3RodW1ibmFpbHMiO047czoyNjoiMTNmbG9vcl9zaG93X3BhZ2VzY29tbWVudHMiO047czoxNzoiMTNmbG9vcl9wb3N0aW5mbzEiO2E6NDp7aTowO3M6NjoiYXV0aG9yIjtpOjE7czo0OiJkYXRlIjtpOjI7czoxMDoiY2F0ZWdvcmllcyI7aTozO3M6ODoiY29tbWVudHMiO31zOjI0OiIxM2Zsb29yX3RodW1ibmFpbHNfaW5kZXgiO3M6Mjoib24iO3M6MjE6IjEzZmxvb3JfY3VzdG9tX2NvbG9ycyI7TjtzOjE3OiIxM2Zsb29yX2NoaWxkX2NzcyI7TjtzOjIwOiIxM2Zsb29yX2NoaWxkX2Nzc3VybCI7czowOiIiO3M6MjI6IjEzZmxvb3JfY29sb3JfbWFpbmZvbnQiO3M6MDoiIjtzOjIyOiIxM2Zsb29yX2NvbG9yX21haW5saW5rIjtzOjA6IiI7czoyMjoiMTNmbG9vcl9jb2xvcl9wYWdlbGluayI7czowOiIiO3M6Mjk6IjEzZmxvb3JfY29sb3JfcGFnZWxpbmtfYWN0aXZlIjtzOjA6IiI7czoyMjoiMTNmbG9vcl9jb2xvcl9oZWFkaW5ncyI7czowOiIiO3M6Mjc6IjEzZmxvb3JfY29sb3Jfc2lkZWJhcl9saW5rcyI7czowOiIiO3M6MjM6IjEzZmxvb3JfZm9vdGVyX2hlYWRpbmdzIjtzOjA6IiI7czoyNToiMTNmbG9vcl9jb2xvcl9mb290ZXJsaW5rcyI7czowOiIiO3M6MjI6IjEzZmxvb3Jfc2VvX2hvbWVfdGl0bGUiO047czoyODoiMTNmbG9vcl9zZW9faG9tZV9kZXNjcmlwdGlvbiI7TjtzOjI1OiIxM2Zsb29yX3Nlb19ob21lX2tleXdvcmRzIjtOO3M6MjY6IjEzZmxvb3Jfc2VvX2hvbWVfY2Fub25pY2FsIjtOO3M6MjY6IjEzZmxvb3Jfc2VvX2hvbWVfdGl0bGV0ZXh0IjtzOjA6IiI7czozMjoiMTNmbG9vcl9zZW9faG9tZV9kZXNjcmlwdGlvbnRleHQiO3M6MDoiIjtzOjI5OiIxM2Zsb29yX3Nlb19ob21lX2tleXdvcmRzdGV4dCI7czowOiIiO3M6MjE6IjEzZmxvb3Jfc2VvX2hvbWVfdHlwZSI7czoyNzoiQmxvZ05hbWUgfCBCbG9nIGRlc2NyaXB0aW9uIjtzOjI1OiIxM2Zsb29yX3Nlb19ob21lX3NlcGFyYXRlIjtzOjM6IiB8ICI7czoyNDoiMTNmbG9vcl9zZW9fc2luZ2xlX3RpdGxlIjtOO3M6MzA6IjEzZmxvb3Jfc2VvX3NpbmdsZV9kZXNjcmlwdGlvbiI7TjtzOjI3OiIxM2Zsb29yX3Nlb19zaW5nbGVfa2V5d29yZHMiO047czoyODoiMTNmbG9vcl9zZW9fc2luZ2xlX2Nhbm9uaWNhbCI7TjtzOjMwOiIxM2Zsb29yX3Nlb19zaW5nbGVfZmllbGRfdGl0bGUiO3M6OToic2VvX3RpdGxlIjtzOjM2OiIxM2Zsb29yX3Nlb19zaW5nbGVfZmllbGRfZGVzY3JpcHRpb24iO3M6MTU6InNlb19kZXNjcmlwdGlvbiI7czozMzoiMTNmbG9vcl9zZW9fc2luZ2xlX2ZpZWxkX2tleXdvcmRzIjtzOjEyOiJzZW9fa2V5d29yZHMiO3M6MjM6IjEzZmxvb3Jfc2VvX3NpbmdsZV90eXBlIjtzOjIxOiJQb3N0IHRpdGxlIHwgQmxvZ05hbWUiO3M6Mjc6IjEzZmxvb3Jfc2VvX3NpbmdsZV9zZXBhcmF0ZSI7czozOiIgfCAiO3M6Mjc6IjEzZmxvb3Jfc2VvX2luZGV4X2Nhbm9uaWNhbCI7TjtzOjI5OiIxM2Zsb29yX3Nlb19pbmRleF9kZXNjcmlwdGlvbiI7TjtzOjIyOiIxM2Zsb29yX3Nlb19pbmRleF90eXBlIjtzOjI0OiJDYXRlZ29yeSBuYW1lIHwgQmxvZ05hbWUiO3M6MjY6IjEzZmxvb3Jfc2VvX2luZGV4X3NlcGFyYXRlIjtzOjM6IiB8ICI7czozMToiMTNmbG9vcl9pbnRlZ3JhdGVfaGVhZGVyX2VuYWJsZSI7czoyOiJvbiI7czoyOToiMTNmbG9vcl9pbnRlZ3JhdGVfYm9keV9lbmFibGUiO3M6Mjoib24iO3M6MzQ6IjEzZmxvb3JfaW50ZWdyYXRlX3NpbmdsZXRvcF9lbmFibGUiO3M6Mjoib24iO3M6Mzc6IjEzZmxvb3JfaW50ZWdyYXRlX3NpbmdsZWJvdHRvbV9lbmFibGUiO3M6Mjoib24iO3M6MjQ6IjEzZmxvb3JfaW50ZWdyYXRpb25faGVhZCI7czowOiIiO3M6MjQ6IjEzZmxvb3JfaW50ZWdyYXRpb25fYm9keSI7czowOiIiO3M6MzA6IjEzZmxvb3JfaW50ZWdyYXRpb25fc2luZ2xlX3RvcCI7czowOiIiO3M6MzM6IjEzZmxvb3JfaW50ZWdyYXRpb25fc2luZ2xlX2JvdHRvbSI7czowOiIiO3M6MTg6IjEzZmxvb3JfNDY4X2VuYWJsZSI7TjtzOjE3OiIxM2Zsb29yXzQ2OF9pbWFnZSI7czowOiIiO3M6MTU6IjEzZmxvb3JfNDY4X3VybCI7czowOiIiO3M6MTk6IjEzZmxvb3JfNDY4X2Fkc2Vuc2UiO3M6MDoiIjt9';
	
	/*global $options;
	
	foreach ($options as $value) {
		if( isset( $value['id'] ) ) { 
			update_option( $value['id'], $value['std'] );
		}
	}*/
	
	$importedOptions = unserialize(base64_decode($importOptions));
	
	foreach ($importedOptions as $key=>$value) {
		if ($value != '') update_option( $key, $value );
	}
	update_option( $shortname . '_use_pages', 'false' );
} ?>