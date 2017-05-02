<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );


	if ( function_exists( 'vc_map' ) ) :


		$products = array();

		$products[ __( "Select a Products", 'hippo-plugin' ) ] = '';

		$query = new WP_Query( array(
			                       'post_type'      => 'product',
			                       'posts_per_page' => - 1,
			                       'post_status'    => 'publish'
		                       ) );

		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) :
				$query->the_post();
				$products[ get_the_title() ] = get_the_id();
			endwhile;
		endif;

		wp_reset_postdata();

		//---------------------------------------------------------------------
		// Hippo VC Home Carousel
		//---------------------------------------------------------------------

		$animation = apply_filters( 'hippo-plugin-vc-animation-array', array(
			__( "Select animation", "hippo-plugin" ) => '',
			__( "fadeIn", "hippo-plugin" )           => 'fadeIn',
			__( "fadeInDown", "hippo-plugin" )       => 'fadeInDown',
			__( "fadeInDownBig", "hippo-plugin" )    => 'fadeInDownBig',
			__( "fadeInLeft", "hippo-plugin" )       => 'fadeInLeft',
			__( "fadeInLeftBig", "hippo-plugin" )    => 'fadeInLeftBig',
			__( "fadeInRight", "hippo-plugin" )      => 'fadeInRight',
			__( "fadeInRightBig", "hippo-plugin" )   => 'fadeInRightBig',
			__( "fadeInUp", "hippo-plugin" )         => 'fadeInUp',
			__( "fadeInUpBig", "hippo-plugin" )      => 'fadeInUpBig'
		) );

		$animation_delay = apply_filters( 'hippo-plugin-vc-animation-delay-array', array(
			__( "Select animation delay", "hippo-plugin" ) => '',
			__( "Delay 300ms", "hippo-plugin" )            => 'animation-delay-3',
			__( "Delay 600ms", "hippo-plugin" )            => 'animation-delay-6',
			__( "Delay 900ms", "hippo-plugin" )            => 'animation-delay-9',
			__( "Delay 1200ms", "hippo-plugin" )           => 'animation-delay-12',
			__( "Delay 1500ms", "hippo-plugin" )           => 'animation-delay-15',
			__( "Delay 1800ms", "hippo-plugin" )           => 'animation-delay-18',
			__( "Delay 2100ms", "hippo-plugin" )           => 'animation-delay-21',
			__( "Delay 2400ms", "hippo-plugin" )           => 'animation-delay-24',
			__( "Delay 2700ms", "hippo-plugin" )           => 'animation-delay-27',
			__( "Delay 3000ms", "hippo-plugin" )           => 'animation-delay-30',
		) );

		$hippo_home_carousels_array = apply_filters( 'hippo-plugin-vc-home_carousels-map', array(
			"name"                    => __( "Home Carousel", 'hippo-plugin' ),
			"base"                    => "home_carousels",
			'controls'                => "full",
			"icon"                    => "fa fa-picture-o",
			"show_settings_on_create" => FALSE,
			"description"             => __( 'Display Carousel', 'hippo-plugin' ),
			"as_parent"               => array( 'only' => 'home_carousel' ),
			// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
			"content_element"         => TRUE,
			// "admin_enqueue_css" => get_template_directory_uri() . '/visual-composer/mappings/css/client-carousel.css',
			'category'                => sprintf( esc_html__( '%s Theme Elements', 'hippo-plugin' ), HIPPO_PLUGIN_THEME_NAME ),
			'default_content'         => '
				[home_carousel images=""/]
				',
			"params"                  => apply_filters( 'hippo-plugin-vc-home_carousels-params', array(

				// add params same as with any other content element
				array(
					"type"        => "textfield",
					"heading"     => __( "Extra class name", 'hippo-plugin' ),
					"param_name"  => "el_class",
					"description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'hippo-plugin' )
				)
			) ),
			"js_view"                 => 'VcColumnView',
		) );

		vc_map( $hippo_home_carousels_array );

		//---------------------------------------------------------------------
		// Hippo VC Carousel Item
		//---------------------------------------------------------------------

		$hippo_home_carousel_array = apply_filters( 'hippo-plugin-vc-home_carousel-map', array(
			"name"                    => __( "Carousel item", 'hippo-plugin' ),
			"base"                    => "home_carousel",
			"content_element"         => TRUE,
			"show_settings_on_create" => TRUE,
			"as_child"                => array( 'only' => 'home_carousels' ),
			//'is_container'            => TRUE,
			// Use only|except attributes to limit parent (separate multiple values with comma)
			"params"                  => apply_filters( 'hippo-plugin-vc-home_carousel-params', array(

				array(
					"type"        => "dropdown",
					"heading"     => __( "Active this item?", 'hippo-plugin' ),
					"param_name"  => "active_item",
					"value"       => array(
						__( "Select an option", "hippo-plugin" ) => '',
						__( "Yes", "hippo-plugin" )              => 'active',
						__( "No", "hippo-plugin" )               => ''

					),
					"admin_label" => TRUE,
					"description" => __( "You must active only one item", 'hippo-plugin' )
				),
				array(
					'type'        => 'attach_image',
					'heading'     => __( 'Carousel images', 'hippo-plugin' ),
					'param_name'  => 'images',
					'description' => __( 'Choose images from media library', 'hippo-plugin' )
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Title", 'hippo-plugin' ),
					"param_name"  => "title",
					"holder"      => "h3",
					"description" => __( "Enter Slider title here", 'hippo-plugin' )
				),
				array(
					"type"        => "dropdown",
					"heading"     => __( "Title Animation", 'hippo-plugin' ),
					"param_name"  => "title_animation",
					"value"       => $animation,
					"admin_label" => TRUE,
					"description" => __( "Select title animation", 'hippo-plugin' )
				),
				array(
					"type"        => "dropdown",
					"heading"     => __( "Title Animation Delay", 'hippo-plugin' ),
					"param_name"  => "title_animation_delay",
					"value"       => $animation_delay,
					"admin_label" => TRUE,
					"description" => __( "Select title animation delay", 'hippo-plugin' )
				),
				array(
					"type"        => "textarea",
					"heading"     => __( "Details", 'hippo-plugin' ),
					"param_name"  => "details",
					//"holder"      => "h3",
					"description" => __( "Enter description here", 'hippo-plugin' )
				),
				array(
					"type"        => "dropdown",
					"heading"     => __( "Details Animation", 'hippo-plugin' ),
					"param_name"  => "details_animation",
					"value"       => $animation,
					"admin_label" => FALSE,
					"description" => __( "Select details animation", 'hippo-plugin' )
				),
				array(
					"type"        => "dropdown",
					"heading"     => __( "Details Animation Delay", 'hippo-plugin' ),
					"param_name"  => "details_animation_delay",
					"value"       => $animation_delay,
					"admin_label" => FALSE,
					"description" => __( "Select details animation delay", 'hippo-plugin' )
				),
				array(
					"type"        => "dropdown",
					"heading"     => __( "Show Link", 'hippo-plugin' ),
					"param_name"  => "link_visibility",
					"value"       => array(
						__( "Select option", "hippo-plugin" ) => '',
						__( "Show", "hippo-plugin" )          => 'show',
						__( "Hide", "hippo-plugin" )          => 'hide'
					),
					"description" => __( "Choose link to show or hide", 'hippo-plugin' )
				),
				array(
					"type"        => "vc_link",
					"heading"     => __( "Button Link", 'hippo-plugin' ),
					"param_name"  => "link",
					"description" => __( "Enter or choose link", 'hippo-plugin' ),
					"dependency"  => array(
						'element' => "link_visibility",
						'value'   => array( 'show' )
					),
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Link text", 'hippo-plugin' ),
					"param_name"  => "link_text",
					"description" => __( "Enter link text", 'hippo-plugin' ),
					"value"       => __( "Shop Now", 'hippo-plugin' ),
					"admin_label" => TRUE,
					"dependency"  => array(
						'element' => "link_visibility",
						'value'   => array( 'show' )
					),
				),
				array(
					"type"        => "dropdown",
					"heading"     => __( "Animation", 'hippo-plugin' ),
					"param_name"  => "link_animation",
					"value"       => $animation,
					"admin_label" => TRUE,
					"description" => __( "Select link text animation", 'hippo-plugin' ),
					"dependency"  => array(
						'element' => "link_visibility",
						'value'   => array( 'show' )
					),
				),
				array(
					"type"        => "dropdown",
					"heading"     => __( "Animation Delay", 'hippo-plugin' ),
					"param_name"  => "link_animation_delay",
					"value"       => $animation_delay,
					"admin_label" => TRUE,
					"description" => __( "Select link text animation delay", 'hippo-plugin' ),
					"dependency"  => array(
						'element' => "link_visibility",
						'value'   => array( 'show' )
					),
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Extra class name", 'hippo-plugin' ),
					"param_name"  => "el_class",
					"description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'hippo-plugin' )
				)
			) )
		) );

		vc_map( $hippo_home_carousel_array );


		if ( class_exists( 'WPBakeryShortCodesContainer' ) and ! class_exists( 'WPBakeryShortCode_Home_Carousels' ) ) :
			class WPBakeryShortCode_Home_Carousels extends WPBakeryShortCodesContainer {

			}
		endif; //  class_exists( 'WPBakeryShortCodesContainer' ) and ! class_exists( 'WPBakeryShortCode_Home_Carousels' )


		if ( class_exists( 'WPBakeryShortCode' ) and ! class_exists( 'WPBakeryShortCode_Home_Carousel' ) ) :
			class WPBakeryShortCode_Home_Carousel extends WPBakeryShortCode {

			}
		endif; // class_exists( 'WPBakeryShortCode' ) and ! class_exists( 'WPBakeryShortCode_Home_Carousel' )

	endif;