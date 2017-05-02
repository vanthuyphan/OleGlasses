<?php
/**
 * Dropdown option style
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

echo '<select name="'.$option_name.'" class="ywcp_component_otpions_select">';

echo '<option value="-1" >' . __( 'None' , 'yith-composite-products-for-woocommerce' ) . '</option>';

if ( $products_loop->have_posts() ) {

	while ( $products_loop->have_posts() ) : $products_loop->the_post();
		global $product;
		if( isset( $product ) ) {

			if ( ! $product->is_purchasable() ) {
				return;
			}

            $product_id = yit_get_base_product_id( $product );

			YITH_WCP_Frontend::markProductAsCompositeProcessed( $product, $component_product_id, $wcp_key );

			$price_html = $product->get_price_html();
			$price_html = ( !empty( $price_html ) ) ?  ' - '.$product->get_price_html() : '' ;

            $availability_text = YITH_WCP_Frontend::getAvailabilityText( $product );

            $title =  $product->get_title().$price_html.( $availability_text ? '('.$availability_text.')' : '' );

			echo '<option value="' . $product_id . '" class="ywcp_product_'. $product_id .'">' . $title . '</option>';

		}

	endwhile;
}

echo '</select>';

?>



