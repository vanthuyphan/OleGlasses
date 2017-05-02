<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

$changed_objects = array();

if ( !function_exists( 'yit_get_prop' ) ) {
    /**
     *
     */
    function yit_get_prop( $object, $key, $single = true, $context = 'view' ) {

        $prop_map   = yit_return_new_attribute_map();
        $is_wc_data = $object instanceof WC_Data;

        if ( $is_wc_data ) {
            $key = ( array_key_exists( $key, $prop_map ) ) ? $prop_map[ $key ] : $key;

            if ( ( $getter = "get{$key}" ) && method_exists( $object, $getter ) ) {
                return $object->$getter( $context );
            } elseif ( ( $getter = "get_{$key}" ) && method_exists( $object, $getter ) ) {
                return $object->$getter( $context );
            } else {
                return $object->get_meta( $key, $single );
            }
        } else {
            $key = ( in_array( $key, $prop_map ) ) ? array_search( $key, $prop_map ) : $key;

            if ( isset( $object->$key ) ) {
                return $object->$key;
            } elseif ( yit_wc_check_post_columns( $key ) ) {
                return $object->post->$key;
            } else {
                $getter = 'get_user_meta';
                !$object instanceof WC_Customer && $getter = 'get_post_meta';

                return $getter( $object->id, $key, true );
            }
        }
    }
}

if ( !function_exists( 'yit_set_prop' ) ) {
    /**
     *
     */
    function yit_set_prop( $object, $arg1, $arg2 = false ) {

        if ( !is_array( $arg1 ) ) {
            $arg1 = array(
                $arg1 => $arg2
            );
        }

        $prop_map   = yit_return_new_attribute_map();
        $is_wc_data = $object instanceof WC_Data;

        foreach ( $arg1 as $key => $value ) {
            if ( $is_wc_data ) {
                $key = ( array_key_exists( $key, $prop_map ) ) ? $prop_map[ $key ] : $key;

                if ( ( $setter = "set{$key}" ) && method_exists( $object, $setter ) ) {
                    $object->$setter( $value );
                } elseif ( ( $setter = "set_{$key}" ) && method_exists( $object, $setter ) ) {
                    $object->$setter( $value );
                } else {
                    $object->update_meta_data( $key, $value );
                }
            } else {
                $key = ( in_array( $key, $prop_map ) ) ? array_search( $key, $prop_map ) : $key;
                ( strpos( $key, '_' ) === 0 ) && $key = substr( $key, 1 );

                if ( yit_wc_check_post_columns( $key ) ) {
                    $object->post->$key = $value;
                } else {
                    $object->$key = $value;
                }
            }
        }
    }
}

if ( !function_exists( 'yit_save_prop' ) ) {
    /**
     *
     */
    function yit_save_prop( $object, $arg1, $arg2 = false, $force_meta = false ) {
        if ( !is_array( $arg1 ) ) {
            $arg1 = array(
                $arg1 => $arg2
            );
        }

        $is_wc_data = $object instanceof WC_Data;

        foreach ( $arg1 as $key => $value ) {
            yit_set_prop( $object, $key, $value );

            if ( $is_wc_data ) {
                yit_store_changes( $object, $key, $value );
            } else {
                if ( yit_wc_check_post_columns( $key ) ) {
                    yit_store_changes( $object->post, $key, $value );
                } else {
                    update_post_meta( $object->id, $key, $value );
                }
            }
        }
    }
}

if ( !function_exists( 'yit_delete_prop' ) ) {
    /**
     *
     */
    function yit_delete_prop( $object, $key, $value = '' ) {
        $prop_map   = yit_return_new_attribute_map();
        $is_wc_data = $object instanceof WC_Data;

        if ( $is_wc_data ) {
            $key = ( array_key_exists( $key, $prop_map ) ) ? $prop_map[ $key ] : $key;

            if ( ( $setter = "set{$key}" ) && ( $getter = "get{$key}" ) && method_exists( $object, $setter ) && ( !$value || $object->$getter == $value ) ) {
                $object->$setter( '' );
            } elseif ( ( $setter = "set_{$key}" ) && ( $getter = "get_{$key}" ) && method_exists( $object, $setter ) && ( !$value || $object->$getter == $value ) ) {
                $object->$setter( '' );
            } elseif ( ( !$value || $object->get_meta( $key ) == $value ) ) {
                $object->delete_meta_data( $key, $value );
            }

            yit_store_changes( $object );
        } else {
            if ( yit_wc_check_post_columns( $key ) && ( !$value || $object->post->$key == $value ) ) {
                yit_store_changes( $object->post, $key, '' );
            } else {
                delete_post_meta( $object->id, $key, $value );
            }
        }
    }
}

if ( !function_exists( 'yit_return_new_attribute_map' ) ) {
    function yit_return_new_attribute_map() {
        return array(
            'post_parent'            => 'parent_id',
            'post_title'             => 'name',
            'post_status'            => 'status',
            'post_content'           => 'description',
            /* Orders */
            'paid_date'              => 'date_paid',
            '_paid_date'             => '_date_paid',
            'completed_date'         => 'date_completed',
            '_completed_date'        => '_date_completed',
            '_order_date'            => '_date_created',
            'order_date'             => 'date_created',
            /* Products */
            'visibility'             => 'catalog_visibility',
            '_visibility'            => '_catalog_visibility',
            'sale_price_dates_from'  => 'date_on_sale_from',
            '_sale_price_dates_from' => '_date_on_sale_from',
            'sale_price_dates_to'    => 'date_on_sale_to',
            '_sale_price_dates_to'   => '_date_on_sale_to',
        );
    }
}

if ( !function_exists( 'yit_store_changes' ) ) {
    function yit_store_changes( $object, $key = false, $value = false ) {
        global $changed_objects;

        $is_wc_data = $object instanceof WC_Data;

        if ( $is_wc_data ) {
            /**
             * @var $object \WC_Data
             */
            $object_reference = $object->get_id();

            $cache_key = is_a( $object, 'WC_Product' ) ? 'product' : 'order';
            $key       = $cache_key . '-' . $object_reference;
            $group     = $cache_key . 's';

            $changed_objects[ $object_reference ] = array(
                'key'   => $key,
                'group' => $group
            );

            wp_cache_set( $key, $object, $group );
        } else {
            $changed_objects[ $object->ID ][ $key ] = $value;
        }
    }
}

if ( !function_exists( 'yit_send_changes_to_db' ) ) {
    function yit_send_changes_to_db() {
        global $changed_objects;

        if ( !empty( $changed_objects ) ) {
            foreach ( $changed_objects as $id => $data ) {
                if ( version_compare( WC()->version, '2.7.0', '>=' ) ) {
                    /**
                     * @var $object \WC_Data
                     */
                    $object = wp_cache_get( $data[ 'key' ], $data[ 'group' ] );

                    $object->save();
                } else {
                    $data[ 'ID' ] = $id;
                    wp_update_post( $data );
                }
            }
        }
    }
}

if ( !function_exists( 'yit_get_orders' ) ) {
    /**
     *
     */
    function yit_get_orders( $args ) {
        if ( version_compare( WC()->version, '2.6', '<' ) ) {
            $args[ 'fields' ] = 'objects';
            $posts            = get_posts( $args );

            return array_map( 'wc_get_order', $posts );
        } else {
            return wc_get_orders( $args );
        }
    }
}

if ( !function_exists( 'yit_get_products' ) ) {
    /**
     *
     */
    function yit_get_products( $args ) {
        if ( version_compare( WC()->version, '2.6', '<' ) ) {
            $args[ 'fields' ] = 'objects';
            $posts            = get_posts( $args );

            return array_map( 'wc_get_product', $posts );
        } else {
            return wc_get_products( $args );
        }
    }
}

if ( !function_exists( 'yit_update_product_stock' ) ) {
    /**
     *
     */
    function yit_update_product_stock( $product, $stock_quantity = 1, $operation = 'set' ) {
        if ( function_exists( 'wc_update_product_stock' ) ) {
            $stock = wc_update_product_stock( $product, $stock_quantity, $operation );
        } else {
            switch ( $operation ) {
                case 'increase':
                    $stock = $product->increase_stock( $stock_quantity );
                    break;
                case 'decrease':
                    $stock = $product->reduce_stock( $stock_quantity );
                    break;
                case 'set':
                default:
                    $stock = $product->set_stock( $stock_quantity );
                    break;
            }
        }

        return $stock;
    }
}

if ( !function_exists( 'yit_wc_deprecated_filters' ) ) {
    /**
     *
     */
    function yit_wc_deprecated_filters() {
        return apply_filters( 'yit_wc_deprecated_filters', array(
            'woocommerce_email_order_schema_markup'      => 'woocommerce_structured_data_order',
            'woocommerce_product_width'                  => 'woocommerce_product_get_width',
            'woocommerce_product_height'                 => 'woocommerce_product_get_height',
            'woocommerce_product_length'                 => 'woocommerce_product_get_length',
            'woocommerce_product_weight'                 => 'woocommerce_product_get_weight',
            'woocommerce_get_sku'                        => 'woocommerce_product_get_sku',
            'woocommerce_get_price'                      => 'woocommerce_product_get_price',
            'woocommerce_get_regular_price'              => 'woocommerce_product_get_regular_price',
            'woocommerce_get_sale_price'                 => 'woocommerce_product_get_sale_price',
            'woocommerce_product_tax_class'              => 'woocommerce_product_get_tax_class',
            'woocommerce_get_stock_quantity'             => 'woocommerce_product_get_stock_quantity',
            'woocommerce_get_product_attributes'         => 'woocommerce_product_get_attributes',
            'woocommerce_product_gallery_attachment_ids' => 'woocommerce_product_get_gallery_image_ids',
            'woocommerce_product_review_count'           => 'woocommerce_product_get_review_count',
            'woocommerce_product_files'                  => 'woocommerce_product_get_downloads',
            'woocommerce_get_currency'                   => 'woocommerce_order_get_currency',
            'woocommerce_order_amount_discount_total'    => 'woocommerce_order_get_discount_total',
            'woocommerce_order_amount_discount_tax'      => 'woocommerce_order_get_discount_tax',
            'woocommerce_order_amount_shipping_total'    => 'woocommerce_order_get_shipping_total',
            'woocommerce_order_amount_shipping_tax'      => 'woocommerce_order_get_shipping_tax',
            'woocommerce_order_amount_cart_tax'          => 'woocommerce_order_get_cart_tax',
            'woocommerce_order_amount_total'             => 'woocommerce_order_get_total',
            'woocommerce_order_amount_total_tax'         => 'woocommerce_order_get_total_tax',
            'woocommerce_order_amount_total_discount'    => 'woocommerce_order_get_total_discount',
            'woocommerce_order_amount_subtotal'          => 'woocommerce_order_get_subtotal',
            'woocommerce_order_tax_totals'               => 'woocommerce_order_get_tax_totals',
            'woocommerce_refund_amount'                  => 'woocommerce_get_order_refund_get_amount',
            'woocommerce_refund_reason'                  => 'woocommerce_get_order_refund_get_reason',
            'default_checkout_country'                   => 'default_checkout_billing_country',
            'default_checkout_state'                     => 'default_checkout_billing_state',
            'default_checkout_postcode'                  => 'default_checkout_billing_postcode',
            'woocommerce_add_order_item_meta'            => 'woocommerce_new_order_item',

        ) );
    }
}

if ( !function_exists( 'yit_fix_wc_deprecated_filters' ) ) {
    /**
     *
     */
    function yit_fix_wc_deprecated_filters() {

        if ( !version_compare( WC()->version, '2.7.0', '<' ) ) {
            return;
        }

        $deprecated_filters = yit_wc_deprecated_filters();
        foreach ( $deprecated_filters as $old => $new ) {
            add_filter( $old, 'yit_wc_deprecated_filter_mapping', 10, 100 );
        }
    }
}

if ( !function_exists( 'yit_wc_deprecated_filter_mapping' ) ) {
    /**
     *
     */
    function yit_wc_deprecated_filter_mapping() {
        $deprecated_filters = yit_wc_deprecated_filters();

        $filter = current_filter();
        $args   = func_get_args();
        $data   = $args[ 0 ];


        if ( isset( $deprecated_filters[ $filter ] ) ) {
            if ( has_filter( $deprecated_filters[ $filter ] ) ) {
                $data = apply_filters_ref_array( $deprecated_filters[ $filter ], $args );
            }
        }

        return $data;
    }
}

if ( !function_exists( 'yit_wc_check_post_columns' ) ) {
    /**
     *
     */
    function yit_wc_check_post_columns( $key ) {
        $columns = array(
            'post_author',
            'post_date',
            'post_date_gmt',
            'post_content',
            'post_title',
            'post_excerpt',
            'post_status',
            'comment_status',
            'ping_status',
            'post_password',
            'post_name',
            'to_ping',
            'pinged',
            'post_modified',
            'post_modified_gmt',
            'post_content_filtered',
            'post_parent',
            'guid',
            'menu_order',
            'post_type',
            'post_mime_type',
            'comment_count',
        );

        return in_array( $key, $columns );
    }
}


/*  Shortcuts for common functions   */

if ( !function_exists( 'yit_get_order_id' ) ) {
    /**
     * Retrieve the order id
     *
     * @param WC_Order $order
     *
     * @return mixed
     */
    function yit_get_order_id( $order ) {
        return yit_get_prop( $order, 'id' );
    }
}

if ( !function_exists( 'yit_get_product_id' ) ) {
    /**
     * Retrieve the product id
     *
     * @param WC_Product $product
     *
     * @return mixed
     */
    function yit_get_product_id( $product ) {
        return yit_get_prop( $product, 'id' );
    }
}

if ( !function_exists( 'yit_get_base_product_id' ) ) {
    /**
     * New way to retrieve the $product->id as it was before WC 2.7.
     *
     * Retrieve the parent product id for WC_Product_Variation instances
     * or the product id in the other cases.
     *
     * @param WC_Product $product
     *
     * @return mixed
     */
    function yit_get_base_product_id( $product ) {

        return $product instanceof WC_Data && $product->is_type( 'variation' ) ?
            yit_get_prop( $product, 'parent_id' ) :
            yit_get_prop( $product, 'id' );
    }
}

if ( !function_exists( 'yit_get_display_price' ) ) {
    /**
     * @param WC_Product $product
     * @param string     $price
     * @param int        $qty
     */
    function yit_get_display_price( $product, $price = '', $qty = 1 ) {

        if ( version_compare( WC()->version, '2.7.0', '>=' ) ) {

            $price = wc_get_price_to_display( $product, array( 'qty' => $qty, 'price' => $price ) );
        } else {

            $price = $product->get_display_price( $price, $qty );
        }

        return $price;
    }
}

if ( !function_exists( 'yit_get_price_excluding_tax' ) ) {
    /**
     * @param WC_Product $product
     * @param int        $qty
     * @param string     $price
     *
     * @return float|string
     */
    function yit_get_price_excluding_tax( $product, $qty = 1, $price = '' ) {

        if ( version_compare( WC()->version, '2.7.0', '>=' ) ) {

            $price = wc_get_price_excluding_tax( $product, array( 'qty' => $qty, 'price' => $price ) );
        } else {

            $price = $product->get_price_excluding_tax( $qty, $price );
        }

        return $price;
    }
}

if ( !function_exists( 'yit_get_price_including_tax' ) ) {
    /**
     * @param WC_Product $product
     * @param int        $qty
     * @param string     $price
     *
     * @return float|string
     */
    function yit_get_price_including_tax( $product, $qty = 1, $price = '' ) {

        if ( version_compare( WC()->version, '2.7.0', '>=' ) ) {

            $price = wc_get_price_including_tax( $product, array( 'qty' => $qty, 'price' => $price ) );
        } else {

            $price = $product->get_price_including_tax( $qty, $price );
        }

        return $price;
    }
}


yit_fix_wc_deprecated_filters();
add_action( 'shutdown', 'yit_send_changes_to_db' );