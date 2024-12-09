// Frontend logic (JS): hanldes UI updates
function updateQuantity(btn, change) {
	const $input = $(btn).siblings('.quantity-input');
	const cartId = $('#cart-count').data('cart-id');
	const newValue = parseInt($input.val()) + change;

	if (newValue >= 1 && newValue <= 99) { 
		$input.val(newValue);
		calculateItemTotal($input);
		updateCartCount(cartId);
	}
}

function calculateItemTotal($input) {
	const $item = $input.closet('.cart-item');
	const price = parseFloat($item.data('price'));
	const quantity = parseInt($input.val());
	const total = price * quantity;
	
	$item.find('.item-total').text(formatPrice(total));
}

function updateCartCount(cartId) {
    // Log to verify cartId
	console.log("Updating cart:", cartId);
	
    $.ajax({
        // configure Object Properties
        url: "/cart/update-cart-count", // handled by CartController@updateItemsCount
        method: "POST",
        data: {
            cart_id: cartId,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "json",

        success: function (response) {
            if (response.success) {
                // Update cart count without page reload
                $("#cart-count").text(response.data.cartItemsCount);
            }
        },
        error: function (error) {
            console.log(error);
        },
    });
}
function formatPrice(price) {
	return new Intl.NumberFormat('VND').format(number);
}
