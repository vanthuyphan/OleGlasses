=== YITH Composite Products for WooCommerce ===

Contributors: yithemes
Tags: woocommerce, composite, product
Requires at least: 4.4
Tested up to: 4.7.2
Stable tag: 1.0.10
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Changelog ==

= 1.0.10 =

New: Support to WooCommerce 2.7(BETA 3)
New: Filter to change the product description for the selected product, the filter is called "yith_wcp_product_description"

= 1.0.9.1 =

* Fix: selection doesn' t works anymore after 1.0.9 release with PHP versions less than 5.6

= 1.0.9 =

* New: Added option "Remove composite product quantity from order table" that allow the administrator to remove the quantity of the composite product in order list.
* New: The class printed in the cart table are now printed in the mini-cart too.
* Fix: The add to cart button was shown even if the composite product was "out of stock".

= 1.0.8 =

* New: Option "Sort product by" in the component settings, now the admin can choose what is the order products for each component.
* New: filters "yith_wcp_calculate_subtotals" and "yith_wcp_composite_add_child_items".
* Fix: Quantity default value when minimum quantity is set in the component options.

= 1.0.7 =

* New: Dutch language (Credit to Paul PaHeDomotica).
* Fix: "Product Base Price" and "Component Options Total" wrong in the single product page price table.
* Fix: Refreshed variation price transient when applying discount to the components.

= 1.0.6 =

* New: Added option "Counted separately" in the component settings, that allow the customer to set the quantity of a selected product indipendent from the "add to cart quantity".
* New: Now for each selected component a subtotal amount is shown.
* Fix: The "From - To" price was not shown the discount set in the component settings.
* Fix: Stock visibility, now you can hide "out of stock" products inside the components.

= 1.0.5 =

* New: WooCommerce 2.6.10 support(resolved variation alert not found message).
* Fix: The invetory tab was always disabled for Composite Products
* Fix: The discount applied with YITH WooCommerce Dynamic Pricing Premium now is not applied to the products inside the components.


= 1.0.4 =

* New: WordPress 4.7 support.
* New: Composite Products is now integrated with YITH WooCommerce Product Add-Ons Premium(with versions grather than 1.2.0.7).
* New: Added filter to change the product base price text, the name is "ywcp_product_base_price_text".
* New: Added filter to not use the quick view button on the selected component box(when the quick view plugin is installed), the name is "ywcp_use_quick_view".

= 1.0.3 =

* New: Option "Ajax variations threshold" that allow the user to set the max number variations count
* Fix: Issue when WooCommerce load the variation via ajax
* Fix: "Base Price" and "Total Price" was not translated correctly

= 1.0.2 =

* Fixed: javascript loop with force selection set in the dependecies

= 1.0.1 =

* New: YITH WooCommerce Request a quote compatibility
* New: YITH WooCommerce Colors and Label Variation compatibility
* New: Italian languages
* Fix: component was automatically unselected when dependencies are set
* Fix: tags list not saved in the back end
* Fix: hided total box when the price is zero
* Fix: hided input quantity when minimum and maximum value is set to 1 in the component
* Fix: prevent notice with WooCommerce 2.5

= 1.0.0 =

Initial Release
