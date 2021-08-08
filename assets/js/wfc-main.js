(function($) {
    "use strict";
    jQuery(document).ready(function($) {
        // Ajax delete product in the cart
        $(document).on('click', '.mini-cart-wrapper .mini-cart-item .remove_button', function(e) {
            e.preventDefault();
            var product_id = $(this).attr("data-product_id"),
                cart_item_key = $(this).attr("data-cart_item_key"),
                product_container = $(this).parents('.mini_cart_item');

            // Add loader
            product_container.block({
                message: null,
                overlayCSS: {
                    cursor: 'none'
                }
            });

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: wc_add_to_cart_params.ajax_url,
                data: {
                    action: "WFC_cart_product_remove",
                    product_id: product_id,
                    cart_item_key: cart_item_key
                },
                success: function(response) {
                    if (!response || response.error)
                        return;

                    var fragments = response.fragments;

                    // Replace fragments
                    if (fragments) {
                        $.each(fragments, function(key, value) {
                            $(key).replaceWith(value);
                        });
                    }
                }
            });
        });
        $(document).on('click', '.wfc-cart-link', function(e) {
            e.preventDefault();
            if ($('.wfc-minicart-wrapper').hasClass('show')) {
                $('.wfc-minicart-wrapper').removeClass('show');
                $('.wfc-minicart-wrapper .wfc-cart-link').fadeIn();
                $('.wfc-minicart-wrapper .wfc-mini-cart-wrapper').fadeOut();
            } else {
                $('.wfc-minicart-wrapper').addClass('show');
                $('.wfc-minicart-wrapper .wfc-cart-link').fadeOut();
                $('.wfc-minicart-wrapper .wfc-mini-cart-wrapper').fadeIn();
            }
        });
        $(document).on('click', '.busket-head .close-busget', function(e) {
            e.preventDefault();
            $('.wfc-minicart-wrapper').removeClass('show');
            $('.wfc-minicart-wrapper .wfc-cart-link').fadeIn();
            $('.wfc-minicart-wrapper .wfc-mini-cart-wrapper').fadeOut();
        });
    });
}(jQuery));