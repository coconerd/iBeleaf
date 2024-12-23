let provinceData = [];
let to_district_id = null;
let to_ward_code = null;
let to_province_id = null;

function applyVoucher(voucherId, description, discount) {
    const $voucherBox = $("#valid-voucher-box");
    const $voucherDetails = $voucherBox.find(".voucher-details");

    $voucherDetails.attr("data-voucher-id", voucherId);
    $("#voucher-id").val(voucherId);
    $("#voucher-description").text(description);
    $("#voucher-discount").text(discount);

    $voucherBox.show();
}

$(document).ready(function () {
    // Load provinces data from JSON file
    $.getJSON("/data/provinces.json")
        .done(function (data) {
            console.log("Provinces data loaded successfully");
            provinceData = data;

            // Populate provinces dropdown with user's initial data
            initialLoadUserInfo(provinceData);
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.error(
                "Failed to load provinces data:",
                textStatus,
                errorThrown
            );
        });

    // Add validation on input/change
    $(".form-control, .form-select").on("input change", function () {
        validateCheckoutForm();
    });

    // Initial validation
    validateCheckoutForm();

    // Dropdown change handlers
    $("#province").change(function () {
        // const to_province_id = $(this).val();
        to_province_id = $(this).val();
        populateDistricts(to_province_id, null, provinceData);
        $("#ward")
            .empty()
            .append("<option selected>Lựa chọn Phường/Xã</option>");
        resetShippingCalculation();

        if (to_province_id === "202") {
            innerCityShippingFee();
        }

        if (to_province_id === "202") {
            $(".alert-icon-container").hide();
        } else {
            $(".alert-icon-container").show();
            showAlertMessage();
        }
    });

    $("#district").change(function () {
        // const to_province_id = $("#province").val();
        to_district_id = $(this).val();
        populateWards(to_district_id, null, provinceData);
        if (to_province_id === "202") {
            innerCityShippingFee();
        } else {
            resetShippingCalculation();
        }
    });

    $("#ward").change(function () {
        to_ward_code = $(this).val();
        // const to_province_id = $("#province").val();
        console.log("After changing location: ", {
            to_district_id,
            to_ward_code,
            to_province_id,
        });

        if (to_district_id && to_ward_code && to_province_id !== "202") {
            console.log("Ward changes");

            calculateShippingFee(to_district_id, to_ward_code);
        }
    });

    // Set default address
    $("#pay-btn").on("click", function () {
        const addressData = {
            province: $("#province option:selected").text(),
            district: $("#district option:selected").text(),
            ward: $("#ward option:selected").text(),
            address: $("#address").val(),
        };

        // Validate fields
        if (
            !addressData.province ||
            !addressData.district ||
            !addressData.ward ||
            !addressData.address
        ) {
            console.log("Vui lòng điền đầy đủ thông tin địa chỉ");
            return;
        }

        // Check if default address toggle is checked
        if ($("#defaultAddress").is(":checked")) {
            $.ajax({
                url: "/checkout/update-default-address",
                method: "POST",
                data: addressData,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.status === "success") {
                        console.log("Default address updated");
                    }
                },
                error: function (xhr) {
                    console.error("Failed to update default address:", xhr);
                },
            });
        }
    });

    // Order submit
    $("#pay-btn").on("click", function () {
        if (validateCheckoutForm()) {
            submitOrder();
        }
    });
});

function getAppliedVoucherId() {
    return (
        $("#valid-voucher-box .voucher-details").attr("data-voucher-id") || null
    );
}

function submitOrder() {
    // Get address values
    const address = {
        province_city: $("#province option:selected").text(),
        district: $("#district option:selected").text(),
        commune_ward: $("#ward option:selected").text(),
        address: $("#address").val(),
    };

    const orderData = {
        voucher_id: getAppliedVoucherId(),

        // Not included voucher/coupon, just discount amount only
        provisional_price: parseFloat(
            $("#provisional-price")
                .text()
                .replace(/[^0-9.-]+/g, "")
        ),
        delivery_cost: parseFloat(
            $("#shipping-fee")
                .text()
                .replace(/[^0-9.-]+/g, "")
        ),
        total_price: $(".total-amount")
            .text()
            .replace(/[.,₫\s]/g, ""),
        address: address,
        payment_method:
            $('input[name="payment-method"]:checked').val() || "COD",
        additional_note: $("#additional-note").val(),
    };

    console.log('Order info: ', {
        total_price: orderData.total_price,
        delivery_cost: orderData.delivery_cost,
        provisional_price: orderData.provisional_price,
        additional_note: orderData.additional_note,
    });
    
    $.ajax({
        url: '/checkout/submit-order',
        method: "POST",
        data: orderData,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.success) {
                // Redirect to order success page
                // window.location.href = `/order-success/${response.order_id}`;
            } else {
                toastr.error(response.message || 'Failed to create order');
            }
        }
    });
    
}
function showAlertMessage() {
    // Toggle popup on click
    $(".alert-icon-container").on("click", function (e) {
        e.stopPropagation();
        $(this).toggleClass("active");
    });

    // Close when clicking outside
    $(document).on("click", function (e) {
        if (!$(e.target).closest(".alert-icon-container").length) {
            $(".alert-icon-container").removeClass("active");
        }
    });
}
function innerCityShippingFee() { 
    $("#shipping-fee").text(
        new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
        }).format(30000)
    );
    updateTotal();
}

function initialLoadUserInfo(provinceData) {
    $.ajax({
        url: "/checkout/user-info",
        method: "GET",
        success: function (response) {
            if (response.success) {
                $("#name").val(response.fullname);
                $("#phone").val(response.phone);
                $("#address").val(response.address);

                to_district_id = response.district_id;
                to_ward_code = response.ward_code;
                to_province_id = response.province_id;
                console.log("Updated globals:", { district_id: to_district_id, ward_code: to_ward_code, province_id: to_province_id });
                
                if(to_province_id !== 202) {
                    calculateShippingFee(to_district_id, to_ward_code);
                } else {
                    innerCityShippingFee();
                }

                // Populate dropdowns with user's initial location data
                populateProvinces(response.province, provinceData);

                populateDistricts(
                    find_to_province_id(response.province),
                    response.district,
                    provinceData
                );
                populateWards(
                    find_to_district_id(response.province, response.district),
                    response.ward,
                    provinceData
                );
                validateCheckoutForm();
                console.log("User info loaded:", response);
            }
        },
        error: function (xhr) {
            console.error("Failed to load user info:", xhr.responseJSON);
        },
    }).promise();
}

function populateProvinces(selectedProvince, data) {
    const $provinceSelect = $("#province");
    $provinceSelect.empty();
    $provinceSelect.append(
        "<option selected>Lựa chọn Tỉnh/Thành Phố</option>"
    );

    Object.keys(data).forEach((to_province_id) => {
        $provinceSelect.append(
            `<option value="${to_province_id}" ${
                data[to_province_id].ProvinceName.trim() ===
                selectedProvince?.trim()
                    ? "selected"
                    : ""
            }>${data[to_province_id].ProvinceName}</option>`
        );
    });
}

function populateDistricts(to_province_id, selectedDistrict, data) {
    const $districtSelect = $("#district");
    $districtSelect.empty();
    $districtSelect.append("<option selected>Lựa chọn Quận/Huyện</option>");

    if (to_province_id && data[to_province_id]?.Districts) {
        Object.keys(data[to_province_id].Districts).forEach((to_district_id) => {
            $districtSelect.append(
                `<option value="${to_district_id}" ${
                    data[to_province_id].Districts[
                        to_district_id
                    ].DistrictName.trim() === selectedDistrict?.trim()
                        ? "selected"
                        : ""
                }>${
                    data[to_province_id].Districts[to_district_id].DistrictName
                }</option>`
            );
        });
    }
}

function populateWards(to_district_id, selectedWard, data) {
    const $wardSelect = $("#ward");
    $wardSelect.empty();
    $wardSelect.append("<option selected>Lựa chọn Phường/Xã</option>");

    Object.values(data).some((province) => {
        if (province.Districts && province.Districts[to_district_id]) {
            const wards = province.Districts[to_district_id].Wards || {};
            Object.keys(wards).forEach((to_ward_code) => {
                $wardSelect.append(
                    `<option value="${to_ward_code}" ${
                        wards[to_ward_code].WardName.trim() ===
                        selectedWard?.trim()
                            ? "selected"
                            : ""
                    }>${wards[to_ward_code].WardName}</option>`
                );
            });
            return true; // Exit loop once found
        }
        return false;
    });
}

// Helpers to find IDs by name
function find_to_province_id(provinceName) {
    return Object.keys(provinceData).find(
        (id) => provinceData[id].ProvinceName.trim() === provinceName.trim()
    );
}

function find_to_district_id(provinceName, districtName) {
    const to_province_id = find_to_province_id(provinceName);
    if (!to_province_id || !provinceData[to_province_id].Districts) return null;

    return Object.keys(provinceData[to_province_id].Districts).find(
        (id) =>
            provinceData[to_province_id].Districts[id].DistrictName.trim() ===
            districtName.trim()
    );
}

function resetShippingCalculation() {
    $("#shipping-fee").text("---");
}

function calculateShippingFee(district_id, ward_code) {
    $.ajax({
        method: "POST",
        url: "/checkout/calculate-shipping",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: JSON.stringify({
            to_district_id: district_id,
            to_ward_code: ward_code
        }),
        contentType: "application/json",
        success: function (response) {
            if (response.success) {
                $("#shipping-fee").text(
                    new Intl.NumberFormat("vi-VN", {
                        style: "currency",
                        currency: "VND",
                    }).format(response.shipping_fee)
                );
                updateTotal();
            } else {
                console.error(
                    "Failed to calculate shipping fee:",
                    response.message
                );
                resetShippingCalculation();
            }
        },
        error: function (xhr, status, error) {
            console.error("Shipping calculation error:", error);
            resetShippingCalculation();
        },
    });
}

function updateTotal() {
    // Get shipping fee from span element and convert to number
    const shippingFeeText = $("#shipping-fee").text().replace(/[^\d]/g, "");
    const shippingFee = parseInt(shippingFeeText) || 0;

    // Get total discounted price from PHP
    const totalDiscountedPrice = parseFloat($("#total-discounted-price").val());

    // Calculate final total
    const finalTotal = totalDiscountedPrice + shippingFee;

    // Update total display
    $(".total-amount").text(
        new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
        }).format(finalTotal)
    );
}


function validateCheckoutForm() {
    let formValid = true;
    let locationValid = true;

    // Required fields validation
    const requiredFields = [
        "name",
        "phone",
        "province",
        "district",
        "ward",
        "address",
    ];

    requiredFields.forEach((fieldId) => {
        const field = $(`#${fieldId}`);
        const value = field.val();

        if (!value || value.includes("Lựa chọn")) {
            field.addClass("is-invalid");
            formValid = false;
            $("#address-warning").show();
        } else {
            field.removeClass("is-invalid");
            $("#address-warning").hide();
        }
    });

    // Location restriction check
    const hasCheckElement = $("#wrong").length > 0;
    if (hasCheckElement && $("#province").val() !== "202") {
        locationValid = false;
    }

    // Enable button only when both conditions are met
    $("#pay-btn").prop("disabled", !(formValid && locationValid));
    console.log("Form validation:", { formValid, locationValid });

    return formValid;
}
