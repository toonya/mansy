
<?php
/*

add section_page on admin menu

*/

$custom_section = array(
	array("type"=>"page_section","name"=>"section"),
	array("type"=>"list_section","name"=>"list_section")
);
new ty_section_page($custom_section);	

/** 
 * The main Class
 */
 
 
class ty_section_page      //这里为什么要做这个类？ 这个类的意思是以后用来做一个用户自己添加section的页面，可以定制一些信息。
{
    public 	function ty_init() {
    	wp_enqueue_script('ty_section-page', get_stylesheet_directory_uri().'/section-page/section-page.js');
    	wp_enqueue_style('ty_section-page', get_stylesheet_directory_uri().'/section-page/section-page.css');
    }
    public 	function ty_front_init() {
    	wp_enqueue_script('ty_section', get_stylesheet_directory_uri().'/section-page/section-page-front.js');
    	wp_enqueue_style('ty-section', get_stylesheet_directory_uri().'/section-page/section-page-front.css');
    }
     
    public function __construct($custom_section)
    {	
    	add_action('admin_enqueue_scripts', array($this,'ty_init'));   //回调函数用函数组把指针和方程传过去 也可以直接用function(){code herr...}
    	if($custom_section=="") $custom_section=array(array("type"=>"page_section"));
    	foreach($custom_section as $section){
	    	switch($section['type']){
		    	case "page_section":
			    	new ty_section_page_buld($section['name']);
			    	new ty_section_meta($section['name']);
			    	add_action('wp_enqueue_scripts', array($this,'ty_front_init'));//模版页面中包含 wp_head() 才能调用出来
		    	break;
		    	case "list_section":
			    	new ty_section_page_buld($section['name']);
			    	new ty_section_meta($section['name']);	    		
		    	break;
	    	}
    	}
    }
}
?>







<?php

/*


Buld custom post type


*/

class ty_section_page_buld      
{
	protected $args = array(
		    'public' => true,
		    'publicly_queryable' => true,
		    'show_ui' => true, 
		    'show_in_menu' => true, 
		    'query_var' => true,
		    'capability_type' => 'post',
		    'has_archive' => true, 
		    'hierarchical' => false,
		    'menu_position' => null,
		    'supports' => array( 'title' )
		  );	
	protected $page_name;	  	  
    public function __construct($page_name)
    {	
        if($page_name) {
        	
        	$this->page_name = $page_name;
        
	    	$this->args['label'] = $this->page_name;

	    	$this->args['rewrite'] = array( 'slug' => $this->page_name, 'with_front' => false );
	    	
        }
        else{
	        $this->page_name = "section";
        }
        add_action( 'init', array($this, 'section_page_init') );
    }
    /**
     * Adds the custom post type
     */
     
    public 	function section_page_init() {
		register_post_type( $this->page_name, $this->args);
    } 


}

?>



<?php
/*


Add costom meta box


*/

class ty_section_meta
{
	protected $prefix_sec = 'sec_';  
	protected $section_meta_fields;
	protected $page_name = 'section';  

    public function __construct($page_name)
    {	
	    $this->section_meta_fields = array( 
/*
	    	array(
	    		'label' => 'Section type',
	    		'desc' => 'Choose the type of section',
	    		'id' => $this->prefix_sec.'radio',
	    		'type'  => 'section_type',
				'options' => array (
					'one' => array (
						'label' => 'page',
						'value'	=> 'page'
					),
					'two' => array(
						'label' => 'list',
						'value' => 'list'
					)
	    		)
	    	),
*/
	    	array(
		        'label'=> 'Sections',  
		        'desc'  => 'Add sections for this post.',  
		        'id'    => $this->prefix_sec.'text',  
		        'type'  => 'section',
		        'content' => $this->prefix_sec.'textarea'
	        )   
	    );
	    if($page_name) $this->page_name = $page_name;
		add_action('add_meta_boxes', array($this,'add_section_meta_box'));
		add_action('admin_head',array($this,'add_section_scripts'));
		add_action('save_post', array($this,'save_section_meta'));    	 
    }
    
        // Add the Meta Box  
	public function add_section_meta_box() {  
	    add_meta_box(  
	        'section_content_meta_box', // $id  
	        'Section Content', // $title   
	        array($this,'show_section_content_meta_box'), // $callback  
	        $this->page_name, // $page  
	        'normal', // $context  
	        'high'); // $priority 
	    add_meta_box(  
	        'section_meta_box', // $id  
	        'Section Title', // $title   
	        array($this,'show_section_meta_box'), // $callback  
	        $this->page_name, // $page  
	        'side', // $context  
	        'core'); // $priority 
          
	}
	
	//javsscript
	
	public function add_section_scripts() {
		
		$output = '<script type="text/javascript">
					jQuery(function() {';
		
		//some special code with php args here.
		
		$output .= '});
			</script>';
			
		echo $output;
	}
	
	// add meta box
	public function show_section_content_meta_box() {  	    
	    wp_editor( /* $content */'', 'section-page-content', array('wpautop'=>false,'media_buttons'=>false) );
	}
	public	function show_section_meta_box() {
		global $post;
		$section_meta_fields = $this->section_meta_fields;
		echo '<input type="hidden" name="section_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';  
	    // Begin the field table and loop  
	    echo '<table class="form-table">';  
	    foreach ($section_meta_fields as $field) {  
	        // get value of this field if it exists for this post  
	        $meta = get_post_meta($post->ID, $field['id'], true);
	        if($field['type']=='section') $meta_content = get_post_meta($post->ID, $field['content'], true);  
	        // begin a table row with  
	        echo '<tr>';  
	                switch($field['type']) {
	                	case 'section_type':
						    echo '</select><br /><span class="description">'.$field['desc'].'</span><br />';  
						    foreach ( $field['options'] as $option ) {  
						        echo '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' /> 
				                <label for="'.$option['value'].'">   '.$option['label'].'</label><br />';  
						    }  
    	                	break;  
						case 'section':  
						    echo '</select><br /><span class="description">'.$field['desc'].'</span>';  
						    echo '<ul id="'.$field['id'].'-repeatable" class="section_repeatable">';  
						    $i = 0;  
						    if ($meta) {  
						        foreach($meta as $row) {
							        $meta_content_val = '';
									$placeholder = "Please enter";
									if($meta_content[$i]!=''){
										$meta_content_val = $meta_content[$i];
									}
						            echo '<li><a class="button section-content-edit"><</a> 
						            			<span class="section-title"><span class="title-name">'.$row.'</span>
						                        <a class="repeatable-remove button" href="#">-</a></span>
						            			<span class="section-title-edit">
						                        <input type="text" name="'.$field['id'].'['.$i.']" id="'.$field['id'].'" value="'.$row.'" size="20" />
						                        <a class="section-title-save button">OK</a>
						                        </span>
						                        
						                        <textarea style="display:none;" placeholder="'.$placeholder.'" name="'.$field['content'].'['.$i.']" id="'.$field['content'].'" row="3" />'.$meta_content_val.'</textarea> </li>';  
						            $i++;  
						        }
						       
						    } else {  
						        echo '<li class="active"><a class="button section-content-edit"><</a> 
						            			<span class="section-title" style="display: none; "><span class="title-name"></span>
						                        <a class="repeatable-remove button" href="#">-</a></span>
						            			<span class="section-title-edit" style="display: inline; ">
						                        <input type="text" name="'.$field['id'].'['.$i.']" id="'.$field['id'].'" value="" size="20">
						                        <a class="section-title-save button">OK</a>
						                        </span>
						                        
						                        <textarea style="display:none;" placeholder="Please enter" name="'.$field['content'].'['.$i.']" id="'.$field['content'].'"></textarea> </li>';  
						    }  
						    echo '</ul> 
						    	<a class="repeatable-add button" href="#">Add a section</a> 
						    	<a class="repeatable-save" href="#">save</a>';  
						break;                				 					
	                } //end switch  
	        echo '</tr>';  
	    } // end foreach  
	    echo '</table>'; // end table  

	} 

	public function save_section_meta($post_id) {  
		$section_meta_fields = $this->section_meta_fields;
	      
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
	        $old_content =  get_post_meta($post_id, $field['content'], true);
   	        $new_content = $_POST[$field['content']]; 
	        if ($new && $new != $old) {  
	            update_post_meta($post_id, $field['id'], $new);  
	        } elseif ('' == $new && $old) {  
	            delete_post_meta($post_id, $field['id'], $old);  
	        }
	        if ($new_content && $new_content != $old_content) {  
	            update_post_meta($post_id, $field['content'], $new_content);  
	        } elseif ('' == $new_content && $old_content) {  
	            delete_post_meta($post_id, $field['content'], $old_content);  
	        }    
	    } // end foreach  
	    // save taxonomies  
		$post = get_post($post_id);  
		$category = $_POST['category'];  
		wp_set_object_terms( $post_id, $category, 'category' );  
	}  
}

?>



