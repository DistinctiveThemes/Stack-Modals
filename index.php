<?php

/*
Plugin Name: Stack Modals
Plugin URI: http://www.tommusrhodus.com/
Description: A simple to use modal manager for the Stack WordPress Theme
Author: Daniel Jones (TommusRhodus Support)
Version: 1.0.0
Author URI: http://www.tommusrhodus.com/
*/


// REGISTER METABOXES 
add_action( 'cmb2_admin_init', 'sc_stack_modals_register_metasboxes' );

function sc_stack_modals_register_metasboxes() {

	if(isset($_GET['post'])) {
		$post_id = $_GET['post'];
		if(isset($post_id)) {
			$shortcode = '[sc-stack-modal modal_id="'.$post_id.'"]';
			$desc = 'or you can create a link with the following href <strong>#trigger-modal-'.$post_id.'</strong>';
		} else {
			$shortcode = '';
			$desc = '';
		}		
	} else {
		$shortcode = '';
		$desc = '';
	}	

	$sc_metas = new_cmb2_box( array(
		'id'            => 'stack_modal_metabox',
		'title'         => esc_html__( 'Modal Settings', 'stack-modals' ),
		'object_types'  => array( 'stack_modal' ),
	) );

	$sc_metas->add_field( array(
		'name'       => esc_html__( 'Modal Shortcode', 'stack-modals' ),
		'desc'		=> $desc,
		'id'         => 'modal_shortcode',
		'type'       => 'text_medium',
		'default'    => $shortcode,
		'save_field' => false, // Disables the saving of this field.
		'attributes' => array(
			'disabled' => 'disabled',
			'readonly' => 'readonly',
		),
	) );

	$sc_metas->add_field( array(
		'name'             => esc_html__( 'Modal Type', 'stack-modals' ),
		'desc' 			   => esc_html__( 'Is this modal to be used on all pages? (like a login form, contact form etc) or just in specific areas?', 'stack-modals' ),
		'id'               => 'modal_type',
		'type'             => 'select',
		'show_option_none' => false,
		'options'          => array(
			'shortcode_only' => 'Show Only Where I Place The Shortcode',
			'all_pages' => 'Show On All Pages (Ideal for login modals etc)',
		),
	) );

	$sc_metas->add_field( array(
		'name'             => esc_html__( 'Modal Layout', 'stack-modals' ),
		'desc' 			   => esc_html__( 'Choose your modal layout', 'stack-modals' ),
		'id'               => 'layout',
		'type'             => 'select',
		'show_option_none' => false,
		'options'          => array(
			'basic' => 'Basic Modal',
			'image-left' => 'Image Left & Content Right',
			'image-right' => 'Image Right & Content Left',
			'narrow' => 'Image Top',
			'image-background' => 'Image Background'
		),
	) );

	$sc_metas->add_field( array(
		'name'             => esc_html__( 'Show Trigger Button', 'stack-modals' ),
		'desc' 			   => esc_html__( 'Enable or disable the trigger button - which is handy if you want to create a login modal or trigger the modal via a custom link', 'stack-modals' ),
		'id'               => 'show_trigger',
		'type'             => 'select',
		'show_option_none' => false,
		'options'          => array(
			'yes' => 'Yes',
			'no'   => 'No',
		),
	) );

	$sc_metas->add_field( array(
		'name'             => esc_html__( 'Button Display Type', 'stack-modals' ),
		'desc' 			   => esc_html__( 'If your choosing so show the trigger button, what style would you like?', 'stack-modals' ),
		'id'               => 'btn_class',
		'type'             => 'select',
		'show_option_none' => false,
		'options'          => array(
			'btn--notneeded' => 'Outline Button',
			'btn--primary' => 'Standard Button',
			'type--uppercase' => 'Outline Button Uppercase',
			'btn--primary type--uppercase' => 'Standard Button Uppercase'
		),
	) );

	$sc_metas->add_field( array(
		'name'       => esc_html__( 'Button Text', 'stack-modals' ),
		'desc' 			   => esc_html__( 'What button label should the trigger have?', 'stack-modals' ),
		'id'         => 'button_text',
		'type'       => 'text',
	) );

	$sc_metas->add_field( array(
		'name' => esc_html__( 'Block Image', 'stack-modals' ),
		'desc' 			   => esc_html__( 'Upload an optional image', 'stack-modals' ),
		'id'   => 'image',
		'type' => 'file',
	) );

	$sc_metas->add_field( array(
		'name'       => esc_html__( 'Autoshow Counter', 'stack-modals' ),
		'desc' 			   => esc_html__( 'If you want this modal to show after a certain period, enter is here (eg 4000 = 4 seconds)', 'stack-modals' ),
		'id'         => 'autoshow',
		'type'       => 'text',
	) );

	$sc_metas->add_field( array(
		'name'       => esc_html__( 'Cookie Name', 'stack-modals' ),
		'desc' 			   => esc_html__( 'Would you like to set a cookie or change the cookie name? Do it here.', 'stack-modals' ),
		'id'         => 'cookie',
		'type'       => 'text',
	) );

	$sc_metas->add_field( array(
		'name'       => esc_html__( 'Exit Intent Class', 'stack-modals' ),
		'desc' 			   => esc_html__( 'If you want to show this modal when a user leaves a certain div on a page, enter the selector here.', 'stack-modals' ),
		'id'         => 'exit',
		'type'       => 'text',
	) );

}

// Register Custom Post Type
function sc_stack_modals_cpt() {

	$labels = array(
		'name'                  => _x( 'Stack Modals', 'Post Type General Name', 'stack-modals' ),
		'singular_name'         => _x( 'Modal', 'Post Type Singular Name', 'stack-modals' ),
		'menu_name'             => __( 'Stack Modals', 'stack-modals' ),
		'name_admin_bar'        => __( 'Stack Modals', 'stack-modals' ),
		'archives'              => __( 'Item Archives', 'stack-modals' ),
		'attributes'            => __( 'Stack Modal Attributes', 'stack-modals' ),
		'parent_item_colon'     => __( 'Parent Item:', 'stack-modals' ),
		'all_items'             => __( 'All Stack Modals', 'stack-modals' ),
		'add_new_item'          => __( 'Add New Stack Modal', 'stack-modals' ),
		'add_new'               => __( 'Add New Stack Modal', 'stack-modals' ),
		'new_item'              => __( 'New Stack Modal', 'stack-modals' ),
		'edit_item'             => __( 'Edit Stack Modal', 'stack-modals' ),
		'update_item'           => __( 'Update Stack Modal', 'stack-modals' ),
		'view_item'             => __( 'View Stack Modal', 'stack-modals' ),
		'view_items'            => __( 'View Stack Modals', 'stack-modals' ),
		'search_items'          => __( 'Search Stack Modals', 'stack-modals' ),
		'not_found'             => __( 'Not found', 'stack-modals' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'stack-modals' ),
		'featured_image'        => __( 'Featured Image', 'stack-modals' ),
		'set_featured_image'    => __( 'Set featured image', 'stack-modals' ),
		'remove_featured_image' => __( 'Remove featured image', 'stack-modals' ),
		'use_featured_image'    => __( 'Use as featured image', 'stack-modals' ),
		'insert_into_item'      => __( 'Insert into item', 'stack-modals' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'stack-modals' ),
		'items_list'            => __( 'Items list', 'stack-modals' ),
		'items_list_navigation' => __( 'Items list navigation', 'stack-modals' ),
		'filter_items_list'     => __( 'Filter items list', 'stack-modals' ),
	);
	$args = array(
		'label'                 => __( 'Modal', 'stack-modals' ),
		'description'           => __( 'Modals, for Stack', 'stack-modals' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', ),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,		
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'stack_modal', $args );

}
add_action( 'init', 'sc_stack_modals_cpt', 0 );

// POP THE MODALS INTO THE FOOTER
function sc_stack_modals_add_modals_to_footer() { 

	global $post;
	$current_single_id = $post->ID;

	$args = array(
		'post_type' => array( 'stack_modal' ),
		'posts_per_page'  => -1,
	);

	$sc_modal_query = new WP_Query( $args );

	if ( $sc_modal_query->have_posts() ) {
		
		while ( $sc_modal_query->have_posts() ) {
			$sc_modal_query->the_post();
			$post_id = get_the_ID();	

			$modal_type = get_post_meta( $post_id, 'modal_type', true );	
			$layout = get_post_meta( $post_id, 'layout', true );	
			$show_trigger = get_post_meta( $post_id, 'show_trigger', true );	
			$btn_class = get_post_meta( $post_id, 'btn_class', true );	
			$button_text = get_post_meta( $post_id, 'button_text', true );	
			$image = get_post_meta( $post_id, 'image', true );	
			if(!empty($image)) {
				$image = '<img src="'.$image .'">';
			} else {
				$image = false;
			}
			$autoshow = get_post_meta( $post_id, 'autoshow', true );	
			$cookie = get_post_meta( $post_id, 'cookie', true );	
			$exit = get_post_meta( $post_id, 'exit', true );	

			$autoshow = ( $autoshow ) ? 'data-autoshow="'. (int) $autoshow .'"' : false;
			$cookie = ( $cookie ) ? 'data-cookie="'. $cookie .'"' : false;
			$exit = ( $exit ) ? 'data-show-on-exit="'. $exit .'"' : false;

			$modal_post = get_post($post_id);
			ob_start();
			the_content();
			$content = ob_get_clean();

			if($modal_type == 'all_pages') {
			
				include('inc/modal-content.php');

			}

		}

	}

	wp_reset_postdata();

	?>
	<script type="text/javascript">
		jQuery('[href^="#trigger-modal-"]').on('click', function(e) {
			e.preventDefault();
			var theID = jQuery(this).attr('href').replace('#trigger-modal-', '');
			console.log(theID);			
            targetModal = jQuery('.all-page-modals').find('[data-modal-id="'+theID+'"]');
            
            mr.util.activateIdleSrc(targetModal, 'iframe');
            mr.modals.autoplayVideo(targetModal);
            mr.modals.showModal(targetModal);
            return false;
		});
	</script>
	<?php

}

add_filter('ebor_before_footer', 'sc_stack_modals_add_modals_to_footer' );

// Add Shortcode
function sc_stack_modal_shortcode( $atts ) {
	ob_start();
	// Attributes
	$atts = shortcode_atts(
		array(
			'modal_id' => '',
		),
		$atts,
		'sc-stack-modal'
	);

	$layout = get_post_meta( $atts['modal_id'], 'layout', true );	
	$show_trigger = get_post_meta( $atts['modal_id'], 'show_trigger', true );	
	$btn_class = get_post_meta( $atts['modal_id'], 'btn_class', true );	
	$button_text = get_post_meta( $atts['modal_id'], 'button_text', true );	
	$image = get_post_meta( $atts['modal_id'], 'image', true );	
	if(!empty($image)) {
		$image = '<img src="'.$image .'">';
	} else {
		$image = false;
	}
	$autoshow = get_post_meta( $atts['modal_id'], 'autoshow', true );	
	$cookie = get_post_meta( $atts['modal_id'], 'cookie', true );	
	$exit = get_post_meta( $atts['modal_id'], 'exit', true );	

	$modal_post = get_post($atts['modal_id']);
	$content = $modal_post->post_content;
	
	include('inc/modal-content.php');

	wp_reset_postdata();
	return ob_get_clean();

}
add_shortcode( 'sc-stack-modal', 'sc_stack_modal_shortcode' );