$.fn.formatPrice = function () {
    return this.each(function () {
        let $element = $(this);
        let price = $element.text().trim();

        price = price.replace(/[,.]/g, "");

        price = parseInt(price);
        if (isNaN(price)) return;

        // Format with thousand separators
        const formattedPrice = price
            .toString()
            .replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        // Update element text
        $element.text(formattedPrice);
    });
};

$(document).ready(function () {
    $("#voucher-apply").on("click", function (e) {
        e.preventDefault();
        const voucherName = $("#voucher-input").val().trim().toUpperCase();
        if (voucherName) {
            validateVoucherName(voucherName);
        }
    });

    $("#voucher-input").keypress(function (e) {
        if (e.which == 13) {
            // Enter key
            e.preventDefault();
            $("#voucher-apply").click();
        }
    });
});

function handleVoucherError(errorResponse) {
    const $voucherBox = $("#valid-voucher-box");
    const $voucherError = $("#voucher-error");
    const errorCode = errorResponse.ecode;
    let errorMessage = "";

    if (errorCode === "MIN_PRICE") {
        const voucherType = errorResponse.voucher_type;
        const cartTotal = parseInt(
            errorResponse.cart_total.toString().replace(/[,.]/g, "")
        );
        const additionalPrice = errorResponse.min_price - cartTotal;

        if (voucherType === "cash" || voucherType === "percentage") {
            errorMessage = `Mua thêm ${$("<span>")
                .text(additionalPrice)
                .formatPrice()
                .text()} ₫ để sử dụng Voucher bạn nhé!`;
        } else if (voucherType === "free_shipping") {
            errorMessage = `Mua thêm ${$("<span>")
                .text(additionalPrice)
                .formatPrice()
                .text()} ₫ để được miễn phí giao hàng!`;
        }
    } else {
        errorMessage = errorResponse.message;
    }

    $("#voucher-error").text(errorMessage);
    $voucherBox.hide();
    $voucherError.show();
}

function validateVoucherName(name) {
    const cartTotal = parseInt(
        $("#first-total-price")
            .text()
            .trim()
            .replace(/[,.]/g, "")
    );
    console.log("Sending cartTotal:", cartTotal);

    $.ajax({
        url: "/voucher/validate",
        method: "POST",
        data: {
            voucher_name: name,
            cart_total: cartTotal,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.valid) {
                console.log("Voucher validation response:", response);
                console.log("Order count: ", response.order_count);
                const voucherDiscount = calculateDiscount(
                    cartTotal,
                    response.voucher_type,
                    response.voucher_value
                );

                updateVoucherBoxDisplay(
                    response.voucher_description,
                    voucherDiscount,
                    response.voucher_type
                );
                $("#valid-voucher-box").slideDown(80);
                $("#voucher-error").slideUp(80);

                $("#final-price").text(
                    formatPrice(cartTotal - voucherDiscount)
                );
            } else {
                handleVoucherError(response);
                return;
            }
        },
        error: function (xhr) {
            console.error("Voucher validation error:", xhr);
            if (xhr.responseJSON) {
                handleVoucherError(xhr.responseJSON);
            } else {
                handleVoucherError({ message: "INVALID" });
            }
        },
    });
}

function calculateDiscount(total, type, value) {
    return type === "percentage" ? (total * value) / 100 : value;
}

function updateVoucherBoxDisplay(description, value, type) {
    const $description = $("#voucher-description");
    $description.text(description);

    // Adjust font size based on text length
    if (description.length > 40) {
        $description.css("font-size", "0.73em");
    } else {
        $description.css("font-size", "1em");
    }

    if (type === "percentage") {
        $("#voucher-discount")
            .text(
                "(Giảm " + $("<span>").text(value).formatPrice().text() + " ₫)"
            )
            .show();
    } else {
        $("#voucher-discount").hide();
    }
}