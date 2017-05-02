<?php
/**
 * Product Thumbnails option style
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 *
 * @author 		YITHEMES
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

echo '<div class="ywcp_component_otpions_thumbnails_container">';

if ( $products_loop->have_posts() ) {

	echo '<ul class="products">';
	
	while ( $products_loop->have_posts() ) : $products_loop->the_post();
		global $product, $post;
		if( isset( $product ) ) {

			if ( ! $product->is_purchasable() || ! function_exists( 'wc_get_product_attachment_props' ) ) {
				return;
			}

			$product_id = yit_get_base_product_id( $product );

			YITH_WCP_Frontend::markProductAsCompositeProcessed( $product, $component_product_id, $wcp_key );

			echo '<li data-product-id="'. $product_id .'" class="ywcp_product_'. $product_id .'">';

			echo '<div class="ywcp_image_container">';

			$props = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
			$image            = get_the_post_thumbnail( $post->ID, 'shop_thumbnail', array(
				'title'	 => $props['title'],
				'alt'    => $props['alt'],
			) );

			echo apply_filters(
				'woocommerce_single_product_image_html',
				$image,
				$post->ID
			);

			echo '</div>';

			echo '<div class="ywcp_product_info">';

			echo '<div class="ywcp_product_title">'.$product->get_title().'</div>';

			echo '<div class="ywcp_product_price">'.$product->get_price_html().'</div>';

			echo YITH_WCP_Frontend::getAvailabilityHtml( $product );

			echo '</div>';

			echo '</li>';

		}

	endwhile;

	echo '</ul>';

	echo YITH_WCP()->frontend->getNavigationLinks( $products_loop );
	
}

echo '</div>';

?>



