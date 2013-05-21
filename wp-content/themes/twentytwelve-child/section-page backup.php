
<?php

	
	
	function section_page_init() {
	  $labels = array(
	    'name' => 'Sections',
	    'singular_name' => 'Section',
	    'add_new' => 'Add New',
	    'add_new_item' => 'Add New Section',
	    'edit_item' => 'Edit Section',
	    'new_item' => 'New Section',
	    'all_items' => 'All Sections',
	    'view_item' => 'View Section',
	    'search_items' => 'Search Sections',
	    'not_found' =>  'No books found',
	    'not_found_in_trash' => 'No sections found in Trash', 
	    'parent_item_colon' => '',
	    'menu_name' => 'Sections'
	  );
	
	  $args = array(
	    'labels' => $labels,
	    'description' => 'A short descriptive summary of what the post type is.',
	    'public' => true,
	    'publicly_queryable' => true,
	    'show_ui' => true, 
	    'show_in_menu' => true, 
	    'query_var' => true,
	    'rewrite' => array( 'slug' => 'section', 'with_front' => false ),
	    'capability_type' => 'post',
	    'has_archive' => true, 
	    'hierarchical' => false,
	    'menu_position' => null,
	    'supports' => array( 'title' )
	  ); 
	
	  register_post_type( 'section', $args );
	}
	add_action( 'init', 'section_page_init' );
	
	//add filter to ensure the text Book, or book, is displayed when user updates a book 
	
	?>
	
	
	
	<?php
	
	
	// Add the Meta Box  
	function add_section_meta_box() {  
	    add_meta_box(  
	        'section_meta_box', // $id  
	        'Section Meta Box', // $title   
	        'show_section_meta_box', // $callback  
	        'section', // $page  
	        'side', // $context  
	        'core'); // $priority 
	    add_meta_box(  
	        'section_content_meta_box', // $id  
	        'Section Content Meta Box', // $title   
	        'show_section_content_meta_box', // $callback  
	        'section', // $page  
	        'normal', // $context  
	        'high'); // $priority           
	}  
	add_action('add_meta_boxes', 'add_section_meta_box');  
	
	$prefix_sec = 'sec_';  
	$section_meta_fields = array(  
	    array(  
	        'label'=> 'Sections',  
	        'desc'  => 'Add sections for this post.',  
	        'id'    => $prefix_sec.'text',  
	        'type'  => 'section',
	        'content' => $prefix_sec.'textarea'  
	    )        
	);  
	
	
	add_action('admin_head','add_section_scripts');
	function add_section_scripts() {
		
		$output = '<script type="text/javascript">
					jQuery(function() {';
		
		//alert(jQuery("#section-page-content").val())
		
		$output .= '});
			</script>';
			
		echo $output;
	}
	
	
	//section content
	function show_section_content_meta_box() {  
	global $section_meta_fields, $post;  
	// Use nonce for verification  
	echo '<input type="hidden" name="section_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';  
	    
	    
	    wp_editor( /* $content */'', 'section-page-content', array('wpautop'=>false,'media_buttons'=>false) );
	
	    // Begin the field table and loop  
	    echo '<table class="form-table">';  
	    echo '</table>'; // end table  
	}  
	//section list
	function show_section_meta_box() {  
	global $section_meta_fields, $post;  
	// Use nonce for verification  
	echo '<input type="hidden" name="section_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';  
	
	    // Begin the field table and loop  
	    echo '<table class="form-table">';  
	    foreach ($section_meta_fields as $field) {  
	        // get value of this field if it exists for this post  
	        $meta = get_post_meta($post->ID, $field['id'], true);
	        if($field['type']=='section') $meta_content = get_post_meta($post->ID, $field['content'], true);  
	        // begin a table row with  
	        echo '<tr><label for="'.$field['id'].'">'.$field['label'].'</label></tr><tr>';  
	                switch($field['type']) {  
	                    // case items will go here 
	                    // text  
						case 'section':  
						    echo '<a class="repeatable-add button" href="#">+</a> 
						            <ul id="'.$field['id'].'-repeatable" class="section_repeatable">';  
						    $i = 0;  
						    if ($meta) {  
						        foreach($meta as $row) {
	/*
							        foreach($meta_content as $row) { 
							        	if($j==$i) {
							        		$meta_content = $row;
							        		}
							            $j++;  
							        }    
	*/								
									$meta_content_val = '';
									$placeholder = "Please enter";
									if($meta_content[$i]!=''){
										$meta_content_val = $meta_content[$i];
									}
						            echo '<li><span class="sort hndle">|||</span> 
						            			<span class="section-title">'.$row.'</span>
						                        <input type="text" name="'.$field['id'].'['.$i.']" id="'.$field['id'].'" value="'.$row.'" size="20" />
						                        <textarea placeholder="'.$placeholder.'" name="'.$field['content'].'['.$i.']" id="'.$field['content'].'" row="3" />'.$meta_content_val.'</textarea> 
						                        <a class="repeatable-remove button" href="#">-</a></li>';  
						            $i++;  
						        }
						       
						    } else {  
						        echo '<li><span class="sort hndle">|||</span> 
						                    <input type="text" name="'.$field['id'].'['.$i.']" id="'.$field['id'].'" value="" size="30" /> 
						                    <a class="repeatable-remove button" href="#">-</a></li>';  
						    }  
						    echo '</ul> 
						        <span class="description">'.$field['desc'].'</span>';  
						break;                				 					
	                } //end switch  
	        echo '</tr>';  
	    } // end foreach  
	    echo '</table>'; // end table  
	} 
	
	
	
	
	function save_section_meta($post_id) {  
	    global $section_meta_fields;  
	      
	    // verify nonce  
	    if (!wp_verify_nonce($_POST['section_meta_box_nonce'], basename(__FILE__)))   
	        return $post_id;  
	    // check autosave  
	    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)  
	        return $post_id;  
	    // check permissions  
	    if ('section' == $_POST['post_type']) {  
	        if (!current_user_can('edit_page', $post_id))  
	            return $post_id;  
	        } elseif (!current_user_can('edit_post', $post_id)) {  
	            return $post_id;  
	    }  
	      
	    // loop through fields and save the data  
	    foreach ($section_meta_fields as $field) {
	    	if($field['type'] == 'tax_select') continue;   
	        $old = get_post_meta($post_id, $field['id'], true);  
	        $new = $_POST[$field['id']];  
	        if ($new && $new != $old) {  
	            update_post_meta($post_id, $field['id'], $new);  
	        } elseif ('' == $new && $old) {  
	            delete_post_meta($post_id, $field['id'], $old);  
	        }  
	    } // end foreach  
	    // save taxonomies  
		$post = get_post($post_id);  
		$category = $_POST['category'];  
		wp_set_object_terms( $post_id, $category, 'category' );  
	}  
	add_action('save_post', 'save_section_meta');   

?>

