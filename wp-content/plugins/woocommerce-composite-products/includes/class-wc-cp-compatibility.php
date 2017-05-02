<?php
/**
 * WC_CP_Compatibility class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Composite Products
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 3rd-party Extensions Compatibility.
 *
 * @class    WC_CP_Compatibility
 * @version  3.8.0
 */
class WC_CP_Compatibility {

	/**
	 * Array of min required plugin versions.
	 * @var array
	 */
	private $required = array();

	/**
	 * The single instance of the class.
	 * @var WC_CP_Compatibility
	 *
	 * @since 3.7.0
	 */
	protected static $_instance = null;

	/**
	 * Main WC_CP_Compatibility instance.
	 *
	 * Ensures only one instance of WC_CP_Compatibility is loaded or can be loaded.
	 *
	 * @static
	 * @return WC_CP_Compatibility
	 * @since  3.7.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 3.7.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'woocommerce-composite-products' ), '3.7.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 3.7.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'woocommerce-composite-products' ), '3.7.0' );
	}

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->required = array(
			'pb'     => '5.2.0',
			'cc'     => '1.1.0',
			'cq'     => '1.1.0',
			'addons' => '2.7.16'
		);

		if ( is_admin() ) {
			// Check plugin min versions.
			add_action( 'admin_init', array( $this, 'check_required_versions' ) );
		}

		// Initialize.
		add_action( 'plugins_loaded', array( $this, 'init' ), 100 );

		// Prevent initialization of deprecated mini-extensions.
		$this->prevent_init();
	}

	/**
	 * Prevent deprecated mini-extensions from initializing.
	 *
	 * @since 3.7.0
	 */
	private function prevent_init() {

		// Conditional Components mini-extension was merged into CP v3.7+.
		if ( class_exists( 'WC_CP_Scenario_Action_Conditional_Components' ) ) {
			remove_action( 'plugins_loaded', array( 'WC_CP_Scenario_Action_Conditional_Components', 'load' ), 10 );
		}
	}

	/**
	 * Init compatibility classes.
	 */
	public function init() {

		// Addons support.
		if ( class_exists( 'WC_Product_Addons' ) ) {
			require_once( 'compatibility/class-wc-addons-compatibility.php' );
		}

		// NYP support.
		if ( function_exists( 'WC_Name_Your_Price' ) ) {
			require_once( 'compatibility/class-wc-nyp-compatibility.php' );
		}

		// Points and Rewards support.
		if ( class_exists( 'WC_Points_Rewards_Product' ) ) {
			require_once( 'compatibility/class-wc-pnr-compatibility.php' );
		}

		// Pre-orders support.
		if ( class_exists( 'WC_Pre_Orders' ) ) {
			require_once( 'compatibility/class-wc-po-compatibility.php' );
		}

		// Product Bundles support.
		if ( class_exists( 'WC_Bundles' ) ) {
			require_once( 'compatibility/class-wc-pb-compatibility.php' );
		}

		// One Page Checkout support.
		if ( function_exists( 'is_wcopc_checkout' ) ) {
			require_once( 'compatibility/class-wc-opc-compatibility.php' );
		}

		// Cost of Goods support.
		if ( class_exists( 'WC_COG' ) ) {
			require_once( 'compatibility/class-wc-cog-compatibility.php' );
		}

		// Shipwire integration.
		if ( class_exists( 'WC_Shipwire' ) ) {
			require_once( 'compatibility/class-wc-shipwire-compatibility.php' );
		}

		// Shipstation integration.
		require_once( 'compatibility/class-wc-shipstation-compatibility.php' );

		// QuickView support.
		if ( class_exists( 'WC_Quick_View' ) ) {
			require_once( 'compatibility/class-wc-qv-compatibility.php' );
		}

		// WC Quantity Increment support.
		if ( class_exists( 'WooCommerce_Quantity_Increment' ) ) {
			require_once( 'compatibility/class-wc-qi-compatibility.php' );
		}

		// PIP support.
		if ( class_exists( 'WC_PIP' ) ) {
			require_once( 'compatibility/class-wc-pip-compatibility.php' );
		}

		// Subscriptions fixes.
		if ( class_exists( 'WC_Subscriptions' ) ) {
			require_once( 'compatibility/class-wc-subscriptions-compatibility.php' );
		}
	}

	/**
	 * Checks minimum required versions of compatible/integrated extensions.
	 */
	public function check_required_versions() {

		global $woocommerce_bundles;

		// PB version check.
		if ( ! empty( $woocommerce_bundles ) && version_compare( $woocommerce_bundles->version, $this->required[ 'pb' ] ) < 0 ) {
			$notice = sprintf( __( '<strong>WooCommerce Composite Products</strong> is not compatible with the version of <strong>WooCommerce Product Bundles</strong> found on your system. Please update <strong>WooCommerce Product Bundles</strong> to version <strong>%s</strong> or higher.', 'woocommerce-composite-products' ), $this->required[ 'pb' ] );
			WC_CP_Admin_Notices::add_notice( $notice, 'warning' );
		}

		// CC existence check.
		if ( class_exists( 'WC_CP_Scenario_Action_Conditional_Components' ) ) {
			$notice = sprintf( __( 'The <strong>WooCommerce Composite Products - Conditional Components</strong> mini-extension is now part of <strong>WooCommerce Composite Products</strong>. Please deactivate and remove the <strong>WooCommerce Composite Products - Conditional Components</strong> plugin.', 'woocommerce-composite-products' ) );
			WC_CP_Admin_Notices::add_notice( $notice, 'warning' );
		}

		// CQ version check.
		if ( class_exists( 'WC_CP_Scenario_Action_Override_Qty' ) && version_compare( WC_CP_Scenario_Action_Override_Qty::$version, $this->required[ 'cq' ] ) < 0 ) {
			$notice = sprintf( __( '<strong>WooCommerce Composite Products</strong> is not compatible with the version of <strong>WooCommerce Composite Products - Conditional Quantities</strong> found on your system. Please update <strong>WooCommerce Composite Products - Conditional Quantities</strong> to version <strong>%s</strong> or higher.', 'woocommerce-composite-products' ), $this->required[ 'cq' ] );
			WC_CP_Admin_Notices::add_notice( $notice, 'warning' );
		}

		// Addons version check.
		if ( class_exists( 'WC_Product_Addons' ) ) {
			$reflector   = new ReflectionClass( 'WC_Product_Addons' );
			$file        = $reflector->getFileName();
			$addons_data = get_plugin_data( $file, false, false );
			$version     = $addons_data[ 'Version' ];
			if ( version_compare( $version, $this->required[ 'addons' ] ) < 0 ) {
				$notice = sprintf( __( '<strong>WooCommerce Composite Products</strong> is not compatible with the version of <strong>WooCommerce Product Addons</strong> found on your system. Please update <strong>WooCommerce Product Addons</strong> to version <strong>%s</strong> or higher.', 'woocommerce-composite-products' ), $this->required[ 'addons' ] );
				WC_CP_Admin_Notices::add_notice( $notice, 'warning' );
			}
		}
	}

	/**
	 * Tells if a product is a Name Your Price product, provided that the extension is installed.
	 *
	 * @param  mixed  $product
	 * @return boolean
	 */
	public function is_nyp( $product ) {

		if ( ! class_exists( 'WC_Name_Your_Price_Helpers' ) ) {
			return false;
		}

		if ( WC_Name_Your_Price_Helpers::is_nyp( $product ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks PHP version.
	 *
	 * @param  string  $version
	 * @return boolean
	 */
	public static function php_version_gte( $version ) {
		return function_exists( 'phpversion' ) && version_compare( phpversion(), $version, '>=' );
	}
}
