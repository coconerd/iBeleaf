$(document).ready(function () {
	$("#voucher-apply").on("click", function (e) {
		e.preventDefault();
		const voucherCode = $("#voucher-input").val().trim().toUpperCase();
		if (voucherCode) {
			validateVoucherCode(voucherCode);
		}
	});
});

function validateVoucherCode(code) {
    $.ajax({
        url: "/voucher/validate",
        method: "POST",
        data: {
            code: code
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        success: function (response) {
            if (response.valid) {
                const cartTotal = parseInt(
                    $("#first-total-price")
                        .text()
                        .replace(/[^0-9.-]+/g, "")
                );
                const voucherDiscount = calculateDiscount(
                    cartTotal,
                    response.type,
                    response.value
                );

                updateVoucherBoxDisplay(response.description, voucherDiscount);
                showVoucherBox(true);
                $("#final-price").text(
                    formatPrice(cartTotal - voucherDiscount) + " VND"
                );
            } else {
                $("#voucher-error").text(response.message);
                showVoucherBox(false);
            }
        },
        error: function (e) {
            console.error("Voucher validation error:", e);
            showVoucherBox(false);
        },
    });
}

function calculateDiscount(total, type, value) {
    return type === "percentage" ? (total * value) / 100 : value;
}

function updateVoucherBoxDisplay(description, value) {
    $("#voucher-description").text(description);
    $("#voucher-discount").text("Giảm " + formatPrice(value) + " VND");
}

function showVoucherBox(isValid) {
    const $voucherBox = $("#voucher-box");
    const $voucherError = $("#voucher-error");

    if (isValid) {
        $voucherBox.slideDown(300); // Animate down in 300ms
        $voucherError.slideUp(300);
    } else {
        $voucherBox.slideUp(300); // Animate up in 300ms
        $voucherError.text("Mã giảm giá không hợp lệ!").slideDown(300);
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat("VND").format(price);
}