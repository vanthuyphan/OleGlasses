jQuery( function($) {

	var enhanced_select_format_string = {
		'language' : {
			errorLoading: function() {
				return wc_composite_admin_params.i18n_ajax_error;
			},
			inputTooLong: function( args ) {
				var overChars = args.input.length - args.maximum;

				if ( 1 === overChars ) {
					return wc_composite_admin_params.i18n_input_too_long_1;
				}

				return wc_composite_admin_params.i18n_input_too_long_n.replace( '%qty%', overChars );
			},
			inputTooShort: function( args ) {
				var remainingChars = args.minimum - args.input.length;

				if ( 1 === remainingChars ) {
					return wc_composite_admin_params.i18n_input_too_short_1;
				}

				return wc_composite_admin_params.i18n_input_too_short_n.replace( '%qty%', remainingChars );
			},
			loadingMore: function() {
				return wc_composite_admin_params.i18n_load_more;
			},
			maximumSelected: function( args ) {
				if ( args.maximum === 1 ) {
					return wc_composite_admin_params.i18n_selection_too_long_1;
				}

				return wc_composite_admin_params.i18n_selection_too_long_n.replace( '%qty%', args.maximum );
			},
			noResults: function() {
				return wc_composite_admin_params.i18n_no_matches;
			},
			searching: function() {
				return wc_composite_admin_params.i18n_searching;
			}
		}
	};

	var enhanced_select_format_string_legacy = {

		formatMatches: function( matches ) {
			if ( 1 === matches ) {
				return wc_composite_admin_params.i18n_matches_1;
			}

			return wc_composite_admin_params.i18n_matches_n.replace( '%qty%', matches );
		},
		formatNoMatches: function() {
			return wc_composite_admin_params.i18n_no_matches;
		},
		formatAjaxError: function( jqXHR, textStatus, errorThrown ) {
			return wc_composite_admin_params.i18n_ajax_error;
		},
		formatInputTooShort: function( input, min ) {
			var number = min - input.length;

			if ( 1 === number ) {
				return wc_composite_admin_params.i18n_input_too_short_1;
			}

			return wc_composite_admin_params.i18n_input_too_short_n.replace( '%qty%', number );
		},
		formatInputTooLong: function( input, max ) {
			var number = input.length - max;

			if ( 1 === number ) {
				return wc_composite_admin_params.i18n_input_too_long_1;
			}

			return wc_composite_admin_params.i18n_input_too_long_n.replace( '%qty%', number );
		},
		formatSelectionTooBig: function( limit ) {
			if ( 1 === limit ) {
				return wc_composite_admin_params.i18n_selection_too_long_1;
			}

			return wc_composite_admin_params.i18n_selection_too_long_n.replace( '%qty%', limit );
		},
		formatLoadMore: function( pageNumber ) {
			return wc_composite_admin_params.i18n_load_more;
		},
		formatSearching: function() {
			return wc_composite_admin_params.i18n_searching;
		}
	};

	// Regular select2 fields init.
	$.fn.wc_cp_select2 = function() {
		$( document.body ).trigger( 'wc-enhanced-select-init' );
	};

	// Custom select2 fields init.
	$.fn.wc_cp_select2_component_options = function() {

		if ( 'yes' === wc_composite_admin_params.is_wc_version_gte_2_7 ) {

			$( ':input.wc-component-options-search' ).filter( ':not(.enhanced)' ).each( function() {

				var action       = $( this ).data( 'action' ),
					$select      = $( this );

				var select2_args = {
					allowClear:         $( this ).data( 'allow_clear' ) ? true : false,
					placeholder:        $( this ).data( 'placeholder' ),
					minimumInputLength: $( this ).data( 'minimum_input_length' ) ? $( this ).data( 'minimum_input_length' ) : '3',
					escapeMarkup: function( m ) {
						return m;
					},
					ajax: {
						url:         wc_enhanced_select_params.ajax_url,
						dataType:    'json',
						quietMillis: 250,
						data: function( params ) {
							return {
								term:      params.term,
								action:    action,
								security:  wc_enhanced_select_params.search_products_nonce,
								exclude:   $( this ).data( 'exclude' ),
								include:   $( this ).data( 'include' ),
								limit:     $( this ).data( 'limit' )
							};
						},
						processResults: function( data ) {
							var terms = [];

							if ( $select.data( 'component_optional' ) === 'yes' ) {
								if ( $select.find( 'option[value="-1"]' ).length === 0 ) {
									terms.push( { id: '-1', text: wc_composite_admin_params.i18n_none } );
								}
							}

							if ( $select.find( 'option[value="0"]' ).length === 0 ) {
								terms.push( { id: '0', text: wc_composite_admin_params.i18n_all } );
							}

							if ( data ) {
								$.each( data, function( id, text ) {
									terms.push( { id: id, text: text } );
								} );
							}

							return { results: terms };
						},

						cache: true
					}
				};

				select2_args = $.extend( select2_args, enhanced_select_format_string );

				$( this ).select2( select2_args ).addClass( 'enhanced' );

			} );

		} else {

			$( this ).find( ':input.wc-component-options-search' ).filter( ':not(.enhanced)' ).each( function() {

				var action       = $( this ).data( 'action' ),
					$select      = $( this );

				var select2_args = {
					allowClear:  $( this ).data( 'allow_clear' ) ? true : false,
					placeholder: $( this ).data( 'placeholder' ),
					minimumInputLength: $( this ).data( 'minimum_input_length' ) ? $( this ).data( 'minimum_input_length' ) : '3',
					escapeMarkup: function( m ) {
						return m;
					},
					ajax: {
				        url:         wc_enhanced_select_params.ajax_url,
				        dataType:    'json',
				        quietMillis: 250,
				        data: function( term, page ) {
				            return {
								term:      term,
								action:    action,
								security:  woocommerce_admin_meta_boxes.search_products_nonce,
								exclude:   $( this ).data( 'exclude' ),
								include:   $( this ).data( 'include' ),
								limit:     $( this ).data( 'limit' )
				            };
				        },
				        results: function( data, page ) {

				        	var terms = [];

							if ( $select.data( 'component_optional' ) === 'yes' ) {
								if ( $select.find( 'option[value="-1"]' ).length === 0 ) {
									terms.push( { id: '-1', text: wc_composite_admin_params.i18n_none } );
								}
							}

							if ( $select.find( 'option[value="0"]' ).length === 0 ) {
								terms.push( { id: '0', text: wc_composite_admin_params.i18n_all } );
							}

					        if ( data ) {
								$.each( data, function( id, text ) {
									terms.push( { id: id, text: text } );
								} );
							}

				            return { results: terms };
				        },

				        cache: true
				    }
				};

				if ( $( this ).data( 'multiple' ) === true ) {
					select2_args.multiple = true;
					select2_args.initSelection = function( element, callback ) {
						var data     = $.parseJSON( element.attr( 'data-selected' ) );
						var selected = [];

						$( element.val().split( ',' ) ).each( function( i, val ) {
							selected.push( { id: val, text: data[ val ] } );
						} );
						return callback( selected );
					};
					select2_args.formatSelection = function( data ) {
						return '<div class="selected-option" data-id="' + data.id + '">' + data.text + '</div>';
					};
				} else {
					select2_args.multiple = false;
					select2_args.initSelection = function( element, callback ) {
						var data = {id: element.val(), text: element.attr( 'data-selected' )};
						return callback( data );
					};
				}

				select2_args = $.extend( select2_args, enhanced_select_format_string_legacy );

				$( this ).select2( select2_args ).addClass( 'enhanced' );
			} );
		}
	};

	var wc_cp_block_params = {
		message:    null,
		overlayCSS: {
			background: '#fff',
			opacity:    0.6
		}
	};

	// Composite type move stock msg up.
	$( '.composite_stock_msg' ).appendTo( '._manage_stock_field .description' );

	// Hide the default "Sold Individually" field.
	$( '#_sold_individually' ).closest( '.form-field' ).addClass( 'hide_if_composite' );

	// Hide the "Grouping" field.
	$( '#linked_product_data .grouping.show_if_simple, #linked_product_data .form-field.show_if_grouped' ).addClass( 'hide_if_composite' );

	// Simple type options are valid for bundles.
	$( '.show_if_simple:not(.hide_if_composite)' ).addClass( 'show_if_composite' );

	if ( typeof woocommerce_admin_meta_boxes === 'undefined' ) {
		woocommerce_admin_meta_boxes = woocommerce_writepanel_params;
	}

	// Composite type specific options.
	$( 'body' ).on( 'woocommerce-product-type-change', function( event, select_val, select ) {

		if ( select_val === 'composite' ) {

			$( '.show_if_external' ).hide();
			$( '.show_if_composite' ).show();

			$( 'input#_manage_stock' ).change();

			if ( wc_composite_admin_params.is_wc_version_gte_2_7 === 'no' ) {
				$( '#_regular_price' ).val( $( '#_wc_cp_base_regular_price' ).val() ).change();
				$( '#_sale_price' ).val( $( '#_wc_cp_base_sale_price' ).val() ).change();
			}
		}

	} );

	$( 'select#product-type' ).change();

	// Downloadable support.
	$( 'input#_downloadable' ).change( function() {
		$( 'select#product-type' ).change();
	} );

	// Layout selection.
	$( '#bto_product_data .bundle_group .bto_layouts' ).on( 'click', '.bto_layout_label', function() {

		$( this ).closest( '.bto_layouts' ).find( '.selected' ).removeClass( 'selected' );
		$( this ).addClass( 'selected' );

	} );


	/*------------------------------------*/
	/*  Components                        */
	/*------------------------------------*/

	// Subsubsub navigation.

	$( '#bto_product_data .config_group' )

		.on( 'click', '.subsubsub a', function() {

			$( this ).closest( '.subsubsub' ).find( 'a' ).removeClass( 'current' );
			$( this ).addClass( 'current' );

			$( this ).closest( '.bto_group_data' ).find( '.tab_group' ).addClass( 'tab_group_hidden' );

			var tab = $( this ).data( 'tab' );

			$( this ).closest( '.bto_group_data' ).find( '.tab_group_' + tab ).removeClass( 'tab_group_hidden' );

			return false;

		} )

		// Component Remove.

		.on( 'click', 'a.remove_row', function( e ) {

			var $parent = $( this ).parent().parent();

			$parent.find('*').off();
			$parent.remove();
			group_row_indexes();

			e.preventDefault();

		} )

		// Component Keyup.

		.on( 'keyup', 'input.group_title', function() {
			$( this ).closest( '.bto_group' ).find( 'h3 .group_name' ).text( $( this ).val() );
		} )

		// Component Expand.

		.on( 'click', '.expand_all', function( e ) {
			var $metaboxes = $( this ).closest( '.wc-metaboxes-wrapper' ).find( '.wc-metabox' );
			$metaboxes.removeClass( 'closed' ).addClass( 'open' );
			$metaboxes.find( '.bto_group_data' ).show();
			e.preventDefault();
		} )

		// Component Close.

		.on( 'click', '.close_all', function( e ) {
			var $metaboxes = $( this ).closest( '.wc-metaboxes-wrapper' ).find( '.wc-metabox' );
			$metaboxes.removeClass( 'open' ).addClass( 'closed' );
			$metaboxes.find( '.bto_group_data' ).hide();
			e.preventDefault();
		} )

		// Query type.

		.on( 'change', 'select.bto_query_type', function() {

			var query_type = $( this ).val();

			$( this ).closest( '.bto_group' ).find( '.bto_query_type_selector' ).hide();
			$( this ).closest( '.bto_group' ).find( '.bto_query_type_' + query_type ).show();

		} )

		// Priced individually.

		.on( 'change', '.group_priced_individually input', function() {
			if ( $( this ).is( ':checked' ) ) {
				$( this ).closest( '.bto_group' ).find( '.group_discount' ).show();
			} else {
				$( this ).closest( '.bto_group' ).find( '.group_discount' ).hide();
			}
		} )

		// Filters.

		.on( 'change', '.group_show_filters input', function() {

			if ( $( this ).is( ':checked' ) ) {
				$( this ).closest( '.bto_group' ).find( '.group_filters' ).show();
			} else {
				$( this ).closest( '.bto_group' ).find( '.group_filters' ).hide();
			}

		} );

	$( '#bto_product_data .config_group select.bto_query_type' ).change();
	$( '#bto_product_data .config_group .group_priced_individually input' ).change();
	$( '#bto_product_data .config_group .group_show_filters input' ).change();

	// Ajax select2.

	$( '#bto_product_data .config_group, #bto_scenario_data .bto_scenarios' ).wc_cp_select2_component_options();

	// Component Add.

	var bto_groups_metabox_count = $( '.bto_groups .bto_group' ).length;

	$( '#bto_product_data' ).on( 'click', 'button.add_bto_group', function() {

		$( '#bto_product_data' ).block( wc_cp_block_params );

		bto_groups_metabox_count++;

		var data = {
			action: 	'woocommerce_add_composite_component',
			post_id: 	woocommerce_admin_meta_boxes.post_id,
			id: 		bto_groups_metabox_count,
			security: 	wc_composite_admin_params.add_component_nonce
		};

		$.post( woocommerce_admin_meta_boxes.ajax_url, data, function ( response ) {

			$( '#bto_config_group_inner .bto_groups' ).append( response );

			var $added = $( '#bto_config_group_inner .bto_groups .bto_group' ).last();

			// Regular select2 init.
			$added.wc_cp_select2();

			$added.find( 'select.bto_query_type' ).change();

			$added.find( '.group_show_filters input' ).change();
			$added.find( '.group_priced_individually input' ).change();

			$added.find( '.woocommerce-help-tip' ).tipTip( {
				'attribute': 'data-tip',
				'fadeIn':    50,
				'fadeOut':   50,
				'delay':     200
			} );

			$( '#bto_product_data' ).unblock();
			$added.trigger( 'woocommerce_bto_component_added' );

		} );

		return false;

	} );

	// Set component image.

	var component_image_frame_data = {
		image_frame: false,
		$button:     false
	};

	$( '#bto_product_data' ).on( 'click', '.upload_component_image_button', function( e ) {

		component_image_frame_data.$button = $( this );

		e.preventDefault();

		// If the media frame already exists, reopen it.
		if ( component_image_frame_data.image_frame ) {
			component_image_frame_data.image_frame.open();
			return;
		}

		// Create the media frame.
		component_image_frame_data.image_frame = wp.media( {

			// Set the title of the modal.
			title: wc_composite_admin_params.i18n_choose_component_image,
			button: {
				text: wc_composite_admin_params.i18n_set_component_image
			},
			states: [
				new wp.media.controller.Library( {
					title: wc_composite_admin_params.i18n_choose_component_image,
					filterable: 'all'
				} )
			]
		} );

		// When an image is selected, run a callback.
		component_image_frame_data.image_frame.on( 'select', function () {

			var attachment = component_image_frame_data.image_frame.state().get( 'selection' ).first().toJSON(),
				url        = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

			component_image_frame_data.$button.addClass( 'has_image' );
			component_image_frame_data.$button.closest( '.component_image' ).find( '.remove_component_image_button' ).addClass( 'has_image' );
			component_image_frame_data.$button.find( 'input' ).val( attachment.id ).change();
			component_image_frame_data.$button.find( 'img' ).eq( 0 ).attr( 'src', url );
		} );

		// Finally, open the modal.
		component_image_frame_data.image_frame.open();
	} );

	// Unset component image.

	$( '#bto_product_data' ).on( 'click', '.remove_component_image_button', function( e ) {

		var $button         = $( this ),
			$option_wrapper = $button.closest( '.component_image' ),
			$upload_button  = $option_wrapper.find( '.upload_component_image_button' );

		e.preventDefault();

		$upload_button.removeClass( 'has_image' );
		$button.removeClass( 'has_image' );
		$option_wrapper.find( 'input' ).val( '' ).change();
		$upload_button.find( 'img' ).eq( 0 ).attr( 'src', wc_composite_admin_params.wc_placeholder_img_src );
	} );

	/*------------------------------------*/
	/* Scenarios                          */
	/*------------------------------------*/

	$( '#bto_scenario_data #bto_scenarios_inner' )

	// Scenario Remove.

		.on( 'click', 'a.remove_row', function( e ) {

			var $parent = $( this ).parent().parent();

			$parent.find('*').off();
			$parent.remove();
			scenario_row_indexes();

			e.preventDefault();

		} )

		// Scenario Keyup.

		.on( 'keyup', 'input.scenario_title', function() {
			$( this ).closest( '.bto_scenario' ).find( 'h3 .scenario_name' ).text( $( this ).val() );
		} )

		// Scenario Expand.

		.on( 'click', '.expand_all', function( e ) {
			var $metaboxes = $( this ).closest( '.wc-metaboxes-wrapper' ).find( '.wc-metabox' );
			$metaboxes.removeClass( 'closed' ).addClass( 'open' );
			$metaboxes.find( '.bto_scenario_data' ).show();
			e.preventDefault();
		} )

		// Scenario Close.

		.on( 'click', '.close_all', function( e ) {
			var $metaboxes = $( this ).closest( '.wc-metaboxes-wrapper' ).find( '.wc-metabox' );
			$metaboxes.removeClass( 'open' ).addClass( 'closed' );
			$metaboxes.find( '.bto_scenario_data' ).hide();
			e.preventDefault();
		} );

		// Exclude option modifier.

		$( '#bto_scenario_data #bto_scenarios_inner' ).on( 'change', 'select.bto_scenario_exclude', function() {

			if ( $( this ).val() === 'masked' ) {
				$( this ).closest( '.bto_scenario_selector' ).find( '.bto_scenario_selector_inner' ).slideUp( 200 );
			} else {
				$( this ).closest( '.bto_scenario_selector' ).find( '.bto_scenario_selector_inner' ).slideDown( 200 );
			}

		} );

	// Scenario Add.

	var bto_scenarios_metabox_count = $( '.bto_scenarios .bto_scenario' ).length;

	$( '#bto_scenario_data' ).on( 'click', 'button.add_bto_scenario', function () {

		$( '#bto_scenario_data' ).block( wc_cp_block_params );

		bto_scenarios_metabox_count++;

		var data = {
			action: 	'woocommerce_add_composite_scenario',
			post_id: 	woocommerce_admin_meta_boxes.post_id,
			id: 		bto_scenarios_metabox_count,
			security: 	wc_composite_admin_params.add_scenario_nonce
		};

		$.post( woocommerce_admin_meta_boxes.ajax_url, data, function ( response ) {

			$( '#bto_scenarios_inner .bto_scenarios' ).append( response );

			var $added = $( '.bto_scenario_' + bto_scenarios_metabox_count );

			// select2 init.
			$added.wc_cp_select2();
			$added.wc_cp_select2_component_options();

			$added.find( '.tips, .woocommerce-help-tip' ).tipTip( {
				'attribute': 'data-tip',
				'fadeIn':    50,
				'fadeOut':   50,
				'delay':     200
			} );

			$( '#bto_scenario_data' ).unblock();
			$added.trigger( 'woocommerce_bto_scenario_added' );

		} );

		return false;
	} );

	// "Hide Components" scenario action.
	$( '#bto_scenario_data #bto_scenarios_inner' ).on( 'change', '.toggle_conditional_components input', function() {

		if ( $( this ).is( ':checked' ) ) {
			$( this ).closest( '.scenario_action_conditional_components_group' ).find( '.action_components' ).slideDown( 200 );
		} else {
			$( this ).closest( '.scenario_action_conditional_components_group' ).find( '.action_components' ).slideUp( 200 );
		}

	} );

	// Init metaboxes.

	init_bto_composition_metaboxes();
	init_bto_scenario_metaboxes();

	function group_row_indexes() {
		$( '.bto_groups .bto_group' ).each( function( index, el ){
			$( '.group_position', el ).val( parseInt( $(el).index( '.bto_groups .bto_group' ) ) );
		} );
	}

	function init_bto_composition_metaboxes() {

		// Initial order.
		var bto_groups = $( '.bto_groups' ).find( '.bto_group' ).get();

		bto_groups.sort( function(a, b) {
		   var compA = parseInt( $(a).attr( 'rel' ) );
		   var compB = parseInt( $(b).attr( 'rel' ) );
		   return ( compA < compB ) ? -1 : ( compA > compB ) ? 1 : 0;
		} );

		$(bto_groups).each( function( idx, itm ) {
			$( '.bto_groups' ).append(itm);
		} );

		// Component ordering
		$( '.bto_groups' ).sortable( {
			items:'.bto_group',
			cursor:'move',
			axis:'y',
			handle: 'h3',
			scrollSensitivity:40,
			forcePlaceholderSize: true,
			helper: 'clone',
			opacity: 0.65,
			placeholder: 'wc-metabox-sortable-placeholder',
			start:function( event, ui ){
				ui.item.css( 'background-color','#f6f6f6' );
			},
			stop:function( event, ui ){
				ui.item.removeAttr( 'style' );
				group_row_indexes();
			}
		} );
	}

	function scenario_row_indexes() {
		$( '.bto_scenarios .bto_scenario' ).each( function( index, el ) {
			$( '.scenario_position', el ).val( parseInt( $(el).index( '.bto_scenarios .bto_scenario' ) ) );
		} );
	}

	function init_bto_scenario_metaboxes() {

		// Initial order.
		var bto_scenarios = $( '.bto_scenarios' ).find( '.bto_scenario' ).get();

		bto_scenarios.sort( function( a, b ) {
		   var compA = parseInt( $(a).attr('rel') );
		   var compB = parseInt( $(b).attr('rel') );
		   return ( compA < compB ) ? -1 : ( compA > compB ) ? 1 : 0;
		} );

		$(bto_scenarios).each( function( idx, itm ) {
			$( '.bto_scenarios' ).append( itm );
		} );

		// Scenario ordering.
		$( '.bto_scenarios' ).sortable( {
			items:'.bto_scenario',
			cursor:'move',
			axis:'y',
			handle: 'h3',
			scrollSensitivity:40,
			forcePlaceholderSize: true,
			helper: 'clone',
			opacity: 0.65,
			placeholder: 'wc-metabox-sortable-placeholder',
			start:function( event, ui ){
				ui.item.css( 'background-color','#f6f6f6' );
			},
			stop:function( event, ui ){
				ui.item.removeAttr( 'style' );
				scenario_row_indexes();
			}
		} );

	}

	// Save data and update configuration options via ajax.

	$( '.save_composition' ).on( 'click', function() {

		$( '#bto_product_data, #bto_scenario_data' ).block( wc_cp_block_params );

		$( '.bto_groups .bto_group' ).find('*').off();

		var data = {
			post_id: 		woocommerce_admin_meta_boxes.post_id,
			data:			$( '#bto_product_data, #bto_scenario_data' ).find( 'input, select, textarea' ).serialize(),
			action: 		'woocommerce_bto_composite_save',
			security: 		wc_composite_admin_params.save_composite_nonce
		};

		$.post( woocommerce_admin_meta_boxes.ajax_url, data, function( post_response ) {

			var this_page = window.location.toString();

			this_page = this_page.replace( 'post-new.php?', 'post.php?post=' + woocommerce_admin_meta_boxes.post_id + '&action=edit&' );

			$.get( this_page, function( response ) {

				var open_components            = [],
					open_scenarios             = [],
					$components_group          = $( '#bto_product_data #bto_config_group_inner' ),
					$scenarios_group           = $( '#bto_scenario_data #bto_scenarios_inner' ),
					$components_group_response = $( response ).find( '#bto_config_group_inner' ),
					$scenarios_group_response  = $( response ).find( '#bto_scenarios_inner' );

				// Make a list of open Components by title.
				$components_group.find( '.bto_group' ).each( function() {

					var $el = $( this );

					if ( $el.hasClass( 'open' ) ) {
						var title = $el.find( 'h3 .group_name' ).text();
						if ( title ) {
							open_components.push( title );
						}
					}
				} );

				// Apply open/close state to Components in response.
				$components_group_response.find( '.bto_group' ).each( function() {

					var $el   = $( this ),
						title = $el.find( 'h3 .group_name' ).text();

					if ( $.inArray( title, open_components ) !== -1 ) {
						$el.addClass( 'open' ).removeClass( 'closed' );
						$el.find( '.wc-metabox-content' ).show();
					} else {
						$el.find( '.wc-metabox-content' ).hide();
					}
				} );

				// Make a list of open Scenarios by title.
				$scenarios_group.find( '.bto_scenario' ).each( function() {

					var $el = $( this );

					if ( $el.hasClass( 'open' ) ) {
						var title = $el.find( 'h3 .scenario_name' ).text();
						if ( title ) {
							open_scenarios.push( title );
						}
					}
				} );

				// Apply open/close state to Scenarios in response.
				$scenarios_group_response.find( '.bto_scenario' ).each( function() {

					var $el   = $( this ),
						title = $el.find( 'h3 .scenario_name' ).text();

					if ( $.inArray( title, open_scenarios ) !== -1 ) {
						$el.addClass( 'open' ).removeClass( 'closed' );
						$el.find( '.wc-metabox-content' ).show();
					} else {
						$el.find( '.wc-metabox-content' ).hide();
					}
				} );

				$components_group.html( $components_group_response.html() );
				$scenarios_group.html( $scenarios_group_response.html() );

				init_bto_composition_metaboxes();
				init_bto_scenario_metaboxes();

				$( '#bto_product_data .woocommerce-help-tip, #bto_scenario_data .woocommerce-help-tip, #bto_scenario_data .tips' ).tipTip( {
					'attribute': 'data-tip',
					'fadeIn':    50,
					'fadeOut':   50,
					'delay':     200
				} );

				$components_group.find( 'select.bto_query_type' ).change();
				$components_group.find( '.group_priced_individually input' ).change();
				$components_group.find( '.group_show_filters input' ).change();

				// Regular select2 init.
				$components_group.wc_cp_select2();
				$scenarios_group.wc_cp_select2();

				// Custom select2 init.
				$components_group.wc_cp_select2_component_options();
				$scenarios_group.wc_cp_select2_component_options();

				if ( post_response.length > 0 ) {
					$.each( post_response, function( index, part ) {
						window.alert( part );
					} );
				}

				$( '#bto_product_data, #bto_scenario_data' ).unblock();
			} );

		}, 'json' );

	} );

} );
