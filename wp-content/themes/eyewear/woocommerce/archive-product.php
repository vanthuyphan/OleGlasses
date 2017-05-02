<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	global $wp_query;

	get_header( 'shop' ); ?>

	<section id="content" class="site-content shop-section shop-section-archive">
		<div class="filter-section">
			<div class="container">
				<div class="row">


				</div>
			</div>
			<!-- .container -->
		</div>
		<!-- .filter-section -->
		<div class="container">
            <div class="shop-sidebar-wrap">
                <div class="shop-sidebar">
                    <div class="row">
                        <?php dynamic_sidebar( 'woosidebar' ); ?>
                    </div>
                </div>
            </div>
			<?php
				/**
				 * woocommerce_archive_description hook
				 *
				 * @hooked woocommerce_taxonomy_archive_description - 10
				 * @hooked woocommerce_product_archive_description - 10
				 */

				do_action( 'woocommerce_archive_description' );

				if ( have_posts() ) :


					/**
					 * woocommerce_before_shop_loop hook
					 *
					 * @hooked woocommerce_result_count - 20
					 * @hooked woocommerce_catalog_ordering - 30
					 */
					//do_action( 'woocommerce_before_shop_loop' );

					woocommerce_product_loop_start(); ?>

					<?php woocommerce_product_subcategories(); ?>

					<div class="row">

						<?php
							while ( have_posts() ) : the_post();

								wc_get_template_part( 'content', 'product' );

							endwhile; // end of the loop.
						?>
					</div>

					<?php woocommerce_product_loop_end(); ?>

					<?php
					/**
					 * woocommerce_after_shop_loop hook
					 *
					 * @hooked woocommerce_pagination - 10
					 */
					do_action( 'woocommerce_after_shop_loop' );
					?>

				<?php elseif ( ! woocommerce_product_subcategories( array(
					                                                    'before' => woocommerce_product_loop_start( FALSE ),
					                                                    'after'  => woocommerce_product_loop_end( FALSE )
				                                                    ) )
				) : ?>

					<?php wc_get_template( 'loop/no-products-found.php' ); ?>

				<?php endif; ?>

		</div>
		<!-- .container -->
	</section>
<?php get_footer( 'shop' );