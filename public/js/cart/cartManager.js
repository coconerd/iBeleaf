$(document).ready(function () {
    const $quantityWrappers = $(".quantity-wrapper");

    function updateButtonStates($minusBtn, $plusBtn, value, maxStock) {
        $minusBtn.prop("disabled", value < 1);
        $plusBtn.prop("disabled", value >= maxStock);
    }

    // Quantity changes handling
    $quantityWrappers.each(function () {
        const $minusBtn = $(this).find(".minus");
        const $plusBtn = $(this).find(".plus");
        const $input = $(this).find(".quantity-input");
        const maxStock = parseInt($input.attr("max"));

        updateButtonStates(
            $minusBtn,
            $plusBtn,
            parseInt($input.val()),
            maxStock
        );

        $input.on("change", function () {
            let value = parseInt($input.val());
            if (value > maxStock) {
                value = maxStock;
                $input.val(value);
            } else if (value < 1) {
                value = 1;
                $input.val(value);
            }
            hanldeQuantityUpdate($(this));
            updateButtonStates(
                $minusBtn,
                $plusBtn,
                parseInt($input.val()),
                maxStock
            );
        });

        $minusBtn.on("click", async function () {
            let value = parseInt($input.val());

            if (value === 1) {
                await showMinQuantityAlert($input);
                return;
            }

            value--;
            $input.val(value);
            hanldeQuantityUpdate($(this));
            updateButtonStates(
                $minusBtn,
                $plusBtn,
                parseInt($input.val()),
                maxStock
            );
        });

        $plusBtn.on("click", function () {
            let value = parseInt($input.val());
            if (value < maxStock) {
                value++;
                $input.val(value);
                hanldeQuantityUpdate($(this));
            }
        });

        $(".quantity-input").on("keydown", preventInvalidChars);
        $(".quantity-input").on("input", function () {
            validateQuantityInput(this);
        });

        $('.remove-item-btn').on('click', function() {
            const cartItem = $(this).closest('.each-cart-item');
            const cartId = cartItem.data('cart-id');
            const productId = cartItem.data('product-id');
            const productName = $(this).data('product-name');

            Swal.fire({
                title: '<h4 style="color: #1E362D; font-size: 24px;">Xác nhận xóa</h4>',
                html: `
            <div style="color: #666; font-size: 16px; margin: 15px 0;">
                Bạn có chắc chắn muốn xóa <span style="color: #1E362D; font-weight: 600;">${productName}</span> khỏi giỏ hàng?
            </div>
        `,
                icon: null,
                showCancelButton: true,
                confirmButtonColor: "#c78b5e",
                cancelButtonColor: "#6c757d",
                confirmButtonText: '<i class="fas fa-trash-alt"></i> Xóa',
                cancelButtonText: "Hủy",
                customClass: {
                    popup: "custom-swal-popup",
                    confirmButton: "custom-confirm-button",
                    cancelButton: "custom-cancel-button",
                },
                buttonsStyling: true,
                reverseButtons: true,
                padding: "2em",
                background: "#fff",
                borderRadius: "15px",
                showClass: {
                    popup: "animate__animated animate__fadeInDown",
                },
                hideClass: {
                    popup: "animate__animated animate__fadeOutUp",
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/cart/${cartId}/${productId}`,
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        success: function (response) {
                            if (response.success) {
                                // Remove the item from DOM
                                cartItem.fadeOut(300, function () {
                                    $(this).remove();
                                    updateCartCount();
                                    calculateCartTotal();
                                });

                                // Success notification
                                Swal.fire({
                                    title: '<h4 style="color: #1E362D;">Đã xóa thành công!</h4>',
                                    html: '<div style="color: #666;">Sản phẩm đã được xóa khỏi giỏ hàng</div>',
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: "custom-swal-popup",
                                    },
                                });

                                // If cart is empty after removal, reload the page
                                if ($(".each-cart-item").length === 1) {
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                }
                            }
                        },
                        error: function (xhr) {
                            Swal.fire({
                                title: '<h4 style="color: #dc3545;">Lỗi!</h4>',
                                html: '<div style="color: #666;">Có lỗi xảy ra khi xóa sản phẩm</div>',
                                icon: "error",
                                customClass: {
                                    popup: "custom-swal-popup",
                                },
                            });
                            console.error("Error:", xhr.responseJSON);
                        },
                    });
                }
            });
        });
    });
});


function hanldeQuantityUpdate($input) {
    updateCartCount();
    const $quantityInput = $input.is("input")
        ? $input
        : $input.siblings(".quantity-input");
    calculatePrice($quantityInput);
    calculateCartTotal();
    if ($input.data('discount-percentage') > 0) {
        updateDiscountAmount($quantityInput);
    }
}

function showMinQuantityAlert($input) {
    if (!$input || !($input instanceof jQuery)) {
        throw new TypeError("Invalid input parameter");
    }

    return Swal.fire({
        iconHtml:
            '<i class="fa-solid fa-circle-exclamation" style="color: #1e4733;"></i>',
        title: "<h4>Xóa sản phẩm từ giỏ hàng</h4>",
        html: `
            <div style="color: #6c757d; font-size: 17px;">
                Bạn có muốn xóa sản phẩm khỏi giỏ hàng không?
            </div>
        `,
        customClass: {
            popup: "custom-alert-popup",
            title: "custom-alert-title",
            confirmButton: "custom-confirm-btn",
            cancelButton: "custom-cancel-btn",
        },
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: "Xác nhận",
        cancelButtonText: "Hủy",
        focusConfirm: false, // Removes focus from the confirm button
        allowOutsideClick: false,
    }).then((result) => {
        if (result.isConfirmed) {
            const cartItem = $input.closest(".each-cart-item");
            const cartId = cartItem.data("cart-id");
            const productId = cartItem.data("product-id");

            if (!cartId || !productId) {
                throw new Error("Missing cart or product ID");
            }
            console.log("Debug - Attempting delete:", { cartId, productId });

            return $.ajax({
                url: `/cart/${cartId}/${productId}`,
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                dataType: "json"
            }).then((response) => {
                console.log("Server response:", response);
                if (response.success) {
                    cartItem.remove();
                    updateCartCount();
                    calculateCartTotal();
                }
            }).catch((error) => {
                console.error(
                    "Server Error Details:",
                    error.responseJSON || error
                );
            });
        }
        return Promise.reject("User cancelled!");
    });
}

/* Validate quantity input*/
function validateQuantityInput(input) {
    // Remove non-numeric characters
    let value = input.value.replace(/[^0-9]/g, "");
    let numValue = parseInt(value) || 1;
    const maxStock = parseInt(input.dataset.maxStock);

    if (numValue < 1) numValue = 1;
    if (numValue > maxStock) numValue = maxStock;

    input.value = numValue;
}
function preventInvalidChars(e) {
    // Prevent 'e', '+', '-' characters
    if (["e", "E", "+", "-"].includes(e.key)) {
        e.preventDefault();
    }
}

function updateCartCount() {
    let totalQuantity = 0;
    $(".quantity-input").each(function () {
        totalQuantity += parseInt($(this).val());
    });

    $(".items-count").text(totalQuantity);
    $(".items-count-mh").text(totalQuantity + " mặt hàng");
    $("#cart-count").text(totalQuantity);
}

function calculatePrice($input) {
    try {
        const $item = $input.closest(".cart-control");
        const $price = $item.find(".price.total-uprice");

        const unitPrice = parseInt($price.data("unit-price"));
        const discountPercent = parseInt($price.data("discount-percent"));
        const quantity = parseInt($input.val());

        if (isNaN(quantity)) {
            console.error("Invalid inputs:", {
                quantity,
            });
            return;
        }

        const subTotal = unitPrice * quantity;
        const subDiscountedTotal = subTotal * (1 - discountPercent / 100);

        $price.text(formatPrice(subDiscountedTotal));
    } catch (error) {
        console.error("Price calculation error:", error);
    }
}

function updateDiscountAmount($input) {
    const $item = $input.closest("body").find(".card-body");
    const $discount = $item.find("#total-discount-amount");
    const quantity = parseInt($input.val());
    console.log("Quantity:", quantity); 

    const unitPrice = parseInt(
        $input.closest(".cart-control").find(".total-uprice").data("unit-price")
    );
    console.log("Unit price:", unitPrice);

    const discountPercent = parseInt(
        $input
            .closest(".cart-control")
            .find(".total-uprice")
            .data("discount-percent")
    );
    console.log("Discount percentage:", discountPercent);

    if ($discount.length) {
        const discountAmount = unitPrice * quantity * (discountPercent / 100);
        console.log("Total discount amount: ", discountAmount);
        $discount.text(formatPrice(discountAmount) + " VND");
    }
}

function calculateCartTotal() {
    let total = 0;

    $(".each-cart-item").each(function () {
        const quantity = parseInt($(this).find(".quantity-input").val());
        const price = parseFloat($(this).find(".price").data("price"));
        const discount = parseFloat($(this).find(".price").data("discount"));

        const discountedPrice = price * (1 - discount / 100);
        const subTotal = discountedPrice * quantity;

        total += subTotal;
    });
    // console.log('Final total Price: ', total);
    $("#first-total-price").text(formatPrice(total) + " VND");
    $("#final-price").text(formatPrice(total) + " VND");
}

function formatPrice(price) {
    return new Intl.NumberFormat("VND").format(price);
}

