$(document).ready(function () {
    const $quantityWrappers = $('.quantity-wrapper');

    $quantityWrappers.each(function () {
        const $minusBtn = $(this).find('.minus');
        const $plusBtn = $(this).find(".plus");
        const $input = $(this).find(".quantity-input");
        const maxStock = parseInt($input.attr('max'));

        function updateButtonStates(value) {
            $minusBtn.prop('disabled', value <= 1);
            $plusBtn.prop('disabled', value >= maxStock);
        }

        updateButtonStates(parseInt($input.val()));

        $input.on("change", function () {
            let value = parseInt($input.val());
            if (value > maxStock) {
                value = maxStock;
                $input.val(value);
            } else if (value < 1) {
                value = 1;
                $input.val(value);
            }
            updateButtonStates(value);
            calculatePrice($input);
            updateCartCount();
            calculateCartTotal();
        });

        $minusBtn.on('click', function() {
            let value = parseInt($input.val());
            if (value > 1) {
                value--;
                $input.val(value);
                updateButtonStates(value);
                calculatePrice($input);
                updateCartCount();
                calculateCartTotal();

            } else if (value === 1) {
                if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                    removeCartItem($input);
                }
            }
        });

        $plusBtn.on('click', function() { 
            let value = parseInt($input.val());
            if (value < maxStock) {
                value++;
                $input.val(value);  
                updateButtonStates(value);
                calculatePrice($input);
                updateCartCount();
                calculateCartTotal();
            }
        })

    })
})

function updateCartCount() {
    let totalQuantity = 0;
    $('.quantity-input').each(function () {
        totalQuantity += parseInt($(this).val());
    });

    $('.items-count').text(totalQuantity);
    $('.items-count-mh').text(totalQuantity + ' mặt hàng');
    $('#cart-count').text(totalQuantity);
}

// function removeCartItem($input) { 
//     const $cartItem = $input.closest('.each-cart-item');
//     const cartItemId = $cartItem.data('cart-id');

//     $.ajax({
//         url: 'cart/remove-item' + cartItemId,
//         method: 'DELETE',
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function (response) {
//             // Remove cart form DOM
//             $cartItem.remove();

//             // Update cart total
//             if (response.cartCount) {
//                 $()
//             }
//         }

//     })

//     $cartItem.remove();
// }                                     

function calculatePrice($input) {
    try {
        const $item = $input.closest(".cart-control");
        const $price = $item.find(".price.total-uprice");

        const unitPrice = parseInt($price.data("unit-price"));
        const discountPercent = parseInt(
            $price.data("discount-percent")
        );
        const quantity = parseInt($input.val());

        // Validate inputs
        if (isNaN(quantity)) {
            console.error("Invalid inputs:", {
                quantity
            });
            return;
        }
        
        const subTotal = unitPrice * quantity;
        const subDiscountedTotal = subTotal * (1 - discountPercent / 100);

        $price.html(
            `${formatPrice(
                subDiscountedTotal
            )} <span class="currency-label">VND</span>`
        );
    } catch (error) {
        console.error("Price calculation error:", error);
    }
    // updateCartCount($item.data('cart-id'));
}

function calculateCartTotal() {
    let total = 0;

    $('.each-cart-item').each(function () {
        const quantity = parseInt($(this).find('.quantity-input').val());
        const price = parseFloat($(this).find(".price").data("price"));
        const discount = parseFloat($(this).find(".price").data("discount"));

        const discountedPrice = price * (1 - discount / 100);
        const subTotal = discountedPrice * quantity;

        total += subTotal;
    });

    $('#first-total-price').text(formatPrice(total) + ' VND');
}
// function updateCartCount(cartId) {
//     // Log to verify cartId
// 	console.log("Updating cart:", cartId);
	
//     $.ajax({
//         // configure Object Properties
//         url: "/cart/update-cart-count", // handled by CartController@updateItemsCount
//         method: "POST",
//         data: {
//             cart_id: cartId,
//             _token: $('meta[name="csrf-token"]').attr("content"),
//         },
//         dataType: "json",

//         success: function (response) {
//             if (response.success) {
//                 // Update cart count without page reload
//                 $("#cart-count").text(response.data.cartItemsCount);
//             }
//         },
//         error: function (error) {
//             console.log(error);
//         },
//     });
// }

// function updateCartItemQuantity($input, newQuantity) {
//     const $cartItem = $input.closest('.each-cart-item');
//     const cartId = $cartItem.data('.cart-id');
//     const productId = $cartItem.data('product-id');

//     $.ajax({
//         url: "/cart/update-quantity",
//         method: "POST",
//         headers: {
//             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//         },
//         data: {
//             cart_id: cartId,
//             product_id: productId,
//             quantity: newQuantity,
//         },
//         success: function (response) {
//             if (response.success) {
//                 $input.val(newQuantity);
//                 calculatePrice($input);
//                 $('.cart-count').text(response.cartCount);

//             }
//         },
//         error: function() {
//             alert('Error updating quantity');
//             // Revert to original value
//             $input.val(originalValue);
//         }
//     });
// }
function formatPrice(price) {
	return new Intl.NumberFormat('VND').format(price);
}
