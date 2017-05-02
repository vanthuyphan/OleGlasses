<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	$output = $after_output = '';
	$atts   = vc_map_get_attributes( $this->getShortcode(), $atts );
	extract( $atts );

	wp_enqueue_script( 'wpb_composer_front_js' );

	$el_class = $this->getExtraClass( $el_class );

	$css_classes        = array(
		//'vc_row',
		'row-wrapper',
		'section',
		$el_class,
		vc_shortcode_custom_css_class( $css ),
		$hippo_theme_css_class
	);
	$wrapper_attributes = array();
	// build attributes for wrapper
	if ( ! empty( $el_id ) ) {
		$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
	}

	// use default video if user checked video, but didn't chose url
	if ( ! empty( $video_bg ) && empty( $video_bg_url ) ) {
		$video_bg_url = 'https://www.youtube.com/watch?v=lMJXxhRFO1k';
	}

	$has_video_bg = ( ! empty( $video_bg ) && ! empty( $video_bg_url ) && vc_extract_youtube_id( $video_bg_url ) );

	if ( $has_video_bg ) {
		$parallax       = $video_bg_parallax;
		$parallax_image = $video_bg_url;
		$css_classes[]  = ' vc_video-bg-container';
		wp_enqueue_script( 'vc_youtube_iframe_api_js' );
	}

	if ( ! empty( $parallax ) ) {
		wp_enqueue_script( 'vc_jquery_skrollr_js' );
		$wrapper_attributes[] = 'data-vc-parallax="1.5"'; // parallax speed
		$css_classes[]        = 'vc_general vc_parallax vc_parallax-' . $parallax;
		if ( strpos( $parallax, 'fade' ) !== FALSE ) {
			$css_classes[]        = 'js-vc_parallax-o-fade';
			$wrapper_attributes[] = 'data-vc-parallax-o-fade="on"';
		} elseif ( strpos( $parallax, 'fixed' ) !== FALSE ) {
			$css_classes[] = 'js-vc_parallax-o-fixed';
		}
	}

	if ( ! empty ( $parallax_image ) ) {
		if ( $has_video_bg ) {
			$parallax_image_src = $parallax_image;
		} else {
			$parallax_image_id  = preg_replace( '/[^\d]/', '', $parallax_image );
			$parallax_image_src = wp_get_attachment_image_src( $parallax_image_id, 'full' );
			if ( ! empty( $parallax_image_src[ 0 ] ) ) {
				$parallax_image_src = $parallax_image_src[ 0 ];
			}
		}
		$wrapper_attributes[] = 'data-vc-parallax-image="' . esc_attr( $parallax_image_src ) . '"';
	}
	if ( ! $parallax && $has_video_bg ) {
		$wrapper_attributes[] = 'data-vc-video-bg="' . esc_attr( $video_bg_url ) . '"';
	}
	$css_class            = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $css_classes ) ), $this->settings[ 'base' ], $atts ) );
	$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';

	$output .= '<section ' . implode( ' ', $wrapper_attributes ) . '>';
	$output .= '<div class="' . $row_width . '"><div class="row">';
	$output .= wpb_js_remove_wpautop( $content );
	$output .= '</div></div>';
	$output .= $after_output;
	$output .= '</section>';
	$output .= $this->endBlockComment( $this->getShortcode() );

	echo $output;