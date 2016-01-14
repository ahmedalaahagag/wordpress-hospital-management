<?php
	function appointgen_programmatically_create_page($title,$slug,$page_content,$post_type='page') {
		global $wpdb;
		$page_id = -1;
		$slug_original = $slug;
		$current_user = wp_get_current_user();
		$author_id = $current_user->ID;
		$page_data_by_slug = '';
		$isGenerate = true;
		$count = 0;
		while($isGenerate){
			$count++;
			$sql = $wpdb->prepare( "SELECT ID FROM ".$wpdb->prefix."posts WHERE post_name = %s AND post_type= %s AND post_status = 'publish'", $slug, $post_type );
			$page = $wpdb->get_var($sql);
			if ( $page ){ 
				$page_data_by_slug = get_post($page, ARRAY_N ); 
			}	
			else{ 
				$page_data_by_slug = null; 
			}
			if($page_data_by_slug == NULL){
				$page_id = wp_insert_post(
				array(
						'comment_status'	=>	'closed',
						'ping_status'		=>	'closed',
						'post_author'		=>	$author_id,
						'post_name'		=>	$slug,
						'post_title'		=>	$title,
						'post_status'		=>	'publish',
						'post_type'		=>	$post_type,
						'post_content' => $page_content,
						'post_parent'=>0
					)
				);
				update_option($slug_original, $title);
				$isGenerate = false;
			}
			else{
				$title = $title.$count;
				$slug = $slug.$count;
				$page_id = -2;
			}
		}
		return $page_id;
	}