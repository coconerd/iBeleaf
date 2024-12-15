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
    });
});

function hanldeQuantityUpdate($input) {
    const value = parseInt($input.val());
    updateCartCount();
    calculatePrice($input);
    calculateCartTotal();
    updateDiscountAmount($input);
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
}

function updateDiscountAmount($input) {
    const $item = $input.closest(".card-body");
    const $discount = $item.find("#total-discount-amount");
    const quantity = parseInt($input.val());
    const unitPrice = parseInt(
        $item.find(".price.total-uprice").data("unit-price")
    );
    const discountPercent = parseInt(
        $item.find(".price.total-uprice").data("discount-percent")
    );

    if ($discount.length) {
        const discountAmount = unitPrice * quantity * (discountPercent / 100);
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

    $("#first-total-price").text(formatPrice(total) + " VND");
}

function formatPrice(price) {
    return new Intl.NumberFormat("VND").format(price);
}
