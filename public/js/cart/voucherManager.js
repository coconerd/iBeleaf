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
    const voucherType = errorResponse.voucher_type;
    const additionalPrice = errorResponse.min_price - errorResponse.cart_total;
    let errorMessage = "";

    if (
        errorCode === "MIN_PRICE" &&
        (voucherType === "cash" || voucherType === "percentage")) {
        errorMessage = `Mua thêm ${formatPrice(additionalPrice)} VND để sử dụng Voucher bạn nhé!`;
    } else if (
        errorCode === "MIN_PRICE" &&
        (voucherType === "free_shipping")) {
        errorMessage = `Mua thêm ${formatPrice(additionalPrice)} VND để  được miễn phí giao hàng!`;
    } else {
        errorMessage = errorResponse.message
    }
    
    $("#voucher-error").text(errorMessage);
    $voucherBox.hide();
    $voucherError.show();
}

function validateVoucherName(name) {
    const cartTotal = parseInt(
        $("#first-total-price")
            .text()
            .replace(/[^0-9.-]+/g, "")
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
                console.log('Order count: ', response.order_count)
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
                    formatPrice(cartTotal - voucherDiscount) + " VND"
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
        $description.css("font-size", "0.85em");
    } else {
        $description.css("font-size", "1em");
    }

    if (type === "percentage") {
        $("#voucher-discount")
            .text("(Giảm " + formatPrice(value) + " VND)")
            .show();
    } else {
        $("#voucher-discount").hide();
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat("VND").format(price);
}
