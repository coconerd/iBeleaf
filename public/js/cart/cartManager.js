// Add jQuery plugin method
$.fn.formatPrice = function () {
    return this.each(function () {
        let $element = $(this);
        let price = $element.text().trim();

        // Remove any existing formatting
        price = price.replace(/[,.]/g, "");

        // Convert to number and validate
        price = parseInt(price);
        if (isNaN(price)) return;

        // Format with thousand separators
        const formattedPrice = price
            .toString()
            .replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        // Update element text
        $element.text(formattedPrice);
        $(".currency-label").html(" ₫");
    });
};

$(document).ready(function () {
    const $quantityWrappers = $(".quantity-wrapper");

    $(".price").formatPrice();

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
                showMinQuantityAlert($input);
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
        // Add blur handler to set minimum value = 1 when user leaves field empty
        $(".quantity-input").on("blur", function () {
            if (!this.value) {
                this.value = 1;
                $(this).trigger("change");
            }
        });

        $(".remove-item-btn").on("click", function () {
            const cartItem = $(this).closest(".each-cart-item");
            const cartId = cartItem.data("cart-id");
            const productId = cartItem.data("product-id");
            const productName = $(this).data("product-name");

            Swal.fire(deleteAlertConfig(productName)).then((result) => {
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
                                    clearVoucher(); // Clear any applied voucher
                                    updateDiscountAmount();
                                    calculateCartTotal();
                                });

                                Swal.fire(successAlertConfig()).then(() => {
                                    window.location.reload();
                                });

                                // If cart is empty after removal, reload the page
                                if ($(".each-cart-item").length === 1) {
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                }
                            }
                            // hanldeQuantityUpdate($(this));
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

    //Checkout Button (Thanh toan) handling
    $("#checkout-btn").on("click", function () {
        const cartItems = [];
        $(".each-cart-item").each(function () {
            if (isInStock($(this))) {
                cartItems.push({
                    product_id: $(this).data("product-id"),
                    quantity: $(this).find(".quantity-input").val(),
                });
            }
        });

        let voucherName = null;
        // Get voucher name if voucher box is visible
        if ($("#valid-voucher-box").is(":visible")) {
            voucherName = $("#voucher-input").val();
        }
        console.log("Voucher name: ", voucherName);

        const voucherValue = $("#final-price").text()
            ? parseInt($("#first-total-price").text().replace(/[^\d]/g, "")) -
              parseInt($("#final-price").text().replace(/[^\d]/g, ""))
            : 0;
        console.log("Voucher value: ", voucherValue);

        // Proceed with checkout AJAX
        $.ajax({
            url: `/cart/items-update`,
            type: "POST",
            data: {
                items: cartItems,
                voucher_name: voucherName || null,
                voucher_discount: voucherValue || 0,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    updateCartCount(response.items_counts);
                    window.location.href = "/cart/checkout";
                }
            },
            error: function (error) {
                console.error("Error:", error);
                Swal.fire({
                    title: "Error",
                    text: error.responseJSON?.message || "An error occurred",
                    icon: "error",
                });
            },
        });
    });
});

function deleteAlertConfig(productName) {
    return {
        title: '<h3 style="color: #1E362D; font-size: 24px;" > Xác nhận xóa!</h3> ',
        html: `
            <div style="color: #666; font-size: 19px; margin: 15px 0;">
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
            confirmButton: "custom-confirm-button btn-equal-size",
            cancelButton: "custom-cancel-button btn-equal-size",
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
    };
}

function successAlertConfig() {
    return {
        title: `
            <div style="text-align: center;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-left: -20px;">
                    <i class="fas fa-check-circle" style="color: #28a745; font-size: 48px;"></i>
                    <h4 style="color: #1E362D; margin: 0;">Đã xóa thành công!</h4>
                </div>
            </div>`,
        html: '<div style="color: #666; font-size: 20px;">Sản phẩm đã được xóa khỏi giỏ hàng</div>',
        timer: 1700,
        showConfirmButton: false,
        customClass: {
            popup: "custom-swal-popup",
        },
    };
}
function isInStock($item) {
    return !$item.hasClass("out-of-stock");
}

function hanldeQuantityUpdate($input) {
    const $cartItem = $input.closest(".each-cart-item");
    if (!isInStock($cartItem)) return;

    updateCartCount();
    const $quantityInput = $input.is("input")
        ? $input
        : $input.siblings(".quantity-input");
    calculateSubPrice($quantityInput);
    calculateCartTotal();
    updateDiscountAmount();
}
function applyVoucher(voucherId, description, discount) {
    const $voucherBox = $("#valid-voucher-box");
    const $voucherDetails = $voucherBox.find(".voucher-details");

    $voucherDetails.attr("data-voucher-id", voucherId);
    $("#voucher-id").val(voucherId);
    $("#voucher-description").text(description);
    $("#voucher-discount").text(discount);

    $voucherBox.show();
}

function getAppliedVoucherId() {
    return (
        $("#valid-voucher-box .voucher-details").attr("data-voucher-id") || null
    );
}
function showMinQuantityAlert($input) {
    if (!$input || !($input instanceof jQuery)) {
        throw new TypeError("Invalid input parameter");
    }

    const cartItem = $input.closest(".each-cart-item");
    const productName = cartItem.find(".product-name").text();

    Swal.fire({
        title: '<h3 style="color: #1E362D; font-size: 24px;" > Xác nhận xóa!</h3> ',
        html: `
            <div style="color: #666; font-size: 19px; margin: 15px 0;">
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
            confirmButton: "custom-confirm-button btn-equal-size",
            cancelButton: "custom-cancel-button btn-equal-size",
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
                dataType: "json",
            })
                .then((response) => {
                    console.log("Server response:", response);
                    if (response.success) {
                        cartItem.remove();
                        updateCartCount();
                        clearVoucher();
                        calculateCartTotal();

                        // Success notification
                        Swal.fire({
                            title: `
                                        <div style="text-align: center;">
                                            <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-left: -20px;">
                                                <i class="fas fa-check-circle" style="color: #28a745; font-size: 48px;"></i>
                                                <h4 style="color: #1E362D; margin: 0;">Đã xóa thành công!</h4>
                                            </div>
                                        </div>`,
                            html: '<div style="color: #666; font-size: 20px;">Sản phẩm đã được xóa khỏi giỏ hàng</div>',
                            timer: 1700,
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
                })

                .catch((error) => {
                    console.error(
                        "Server Error Details:",
                        error.responseJSON || error
                    );
                });
        }
        return Promise.reject("User cancelled!");
    });
}

function clearVoucher() {
    $("#voucher-input").val(""); // Clear input
    $("#valid-voucher-box").hide(); // Hide voucher box
    $("#voucher-error").hide(); // Hide any error messages
    $("#final-price").text($("#first-total-price").text()); // Reset final price to original price
}

/* Validate quantity input*/
function validateQuantityInput(input) {
    if (input.value === "") return;
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

    $(
        ".all-cart-items .each-cart-item:not(.out-of-stock) .quantity-input"
    ).each(function () {
        totalQuantity += parseInt($(this).val() || 0);
    });
    console.log("Total In-Stock Quantity:", totalQuantity);

    $(".items-count").text(totalQuantity);
    $(".items-count-mh").text(totalQuantity + " mặt hàng");
    $("#cart-count").text(totalQuantity);
}

function calculateSubPrice($input) {
    try {
        const $item = $input.closest(".cart-control");
        if (!isInStock($item)) {
            return; // Skip processing for out-of-stock items
        }
        const $price = $item.find(".price.total-uprice");
        const productId = $item.data("product-id");

        // Clean price values
        const unitPrice =
            parseInt(cleanPriceString($price.data("unit-price"))) || 0;
        const discountPercent =
            parseInt(cleanPriceString($price.data("discount-percent"))) || 0;
        const quantity = parseInt(cleanPriceString($input.val())) || 0;

        if (quantity <= 0) {
            console.error("Invalid quantity:", quantity);
            return;
        }

        const subTotal = unitPrice * quantity;
        const subDiscountedTotal = subTotal * (1 - discountPercent / 100);
        const discountAmount = subTotal - subDiscountedTotal;

        $price.text(subDiscountedTotal).formatPrice();
    } catch (error) {
        console.error("Price calculation error:", error);
    }
}

function updateDiscountAmount() {
    try {
        const $summary = $(".card-body");
        const $discount = $summary.find("#total-discount-amount");
        let totalDiscount = 0;

        // Calculate discount for each cart item
        $(".cart-control").each(function () {
            const $item = $(this);
            if (!isInStock($item)) {
                return;
            }

            const $input = $item.find(".quantity-input");
            const $price = $item.find(".total-uprice");

            if (!$input.length || !$price.length) {
                console.warn(
                    "Missing required elements for discount calculation"
                );
                return;
            }

            const quantity = Math.max(
                0,
                parseInt(cleanPriceString($input.val())) || 0
            );
            const unitPrice = Math.max(
                0,
                parseInt(cleanPriceString($price.data("unit-price"))) || 0
            );
            const discountPercent = Math.min(
                100,
                Math.max(
                    0,
                    parseInt(
                        cleanPriceString($price.data("discount-percent"))
                    ) || 0
                )
            );

            if (quantity && unitPrice) {
                const itemDiscount =
                    (unitPrice * quantity * discountPercent) / 100;
                totalDiscount += itemDiscount;
            }
        });

        // Update discount display if element exists
        if ($discount.length && !isNaN(totalDiscount)) {
            $discount.text(totalDiscount).formatPrice();
        }
    } catch (error) {
        console.error("Error updating discount amount:", error);
        return 0;
    }
}

function calculateCartTotal() {
    let total = 0;

    $(".each-cart-item").each(function () {
        if (isInStock($(this))) {
            const quantity =
                parseInt(
                    cleanPriceString($(this).find(".quantity-input").val())
                ) || 0;
            const price =
                parseFloat(
                    cleanPriceString($(this).find(".price").data("price"))
                ) || 0;
            const discount =
                parseFloat(
                    cleanPriceString($(this).find(".price").data("discount"))
                ) || 0;

            if (quantity && price) {
                const discountedPrice = price * (1 - discount / 100);
                const subTotal = discountedPrice * quantity;
                total += isNaN(subTotal) ? 0 : subTotal;
            }
        }
    });

    $("#first-total-price").text(total).formatPrice();
    $("#final-price").text(total).formatPrice();
}

function cleanPriceString(priceStr) {
    return String(priceStr || "").replace(/[^\d]/g, "");
}
