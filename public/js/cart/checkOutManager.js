let provinceData = [];
let to_district_id = null;
let to_ward_code = null;
let to_province_id = null;

$.fn.formatPrice = function () {
    return this.each(function () {
        let $element = $(this);
        let price = $element.text().trim();

        // Remove any existing formatting
        price = price.replace(/[,.]/g, "");

        price = parseInt(price);
        if (isNaN(price)) return;

        const formattedPrice = price
            .toString()
            .replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        $element.text(formattedPrice);
    });
};

$(document).ready(function () {
    updateProvisionalPrice();

    $('#discounted-price').formatPrice();

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

    // Name, phone, address validation
    ["name", "phone", "address"].forEach(fieldId => {
        $(`#${fieldId}`).on("input", function() {
            $(".invalid-feedback").remove();
            validateCheckoutForm();
        });
    });
});

function submitOrder() {
    // Get address values
    const address = {
        province_city: $("#province option:selected").text(),
        district: $("#district option:selected").text(),
        commune_ward: $("#ward option:selected").text(),
        to_district_id: to_district_id,
        to_ward_code: to_ward_code,
        address: $("#address").val(),
    };

    const voucherName = $("#session-voucher-name").val() || null;
    const voucherValue = parseInt($("#session-voucher-discount").val()) || 0;
    console.log("Test voucher value: ", voucherValue);

    const shippingFee =
        parseInt($("#shipping-fee").text().replace(/[^\d]/g, "")) || 0;
    console.log("Test shipping fee: ", shippingFee);

    const provisionalPrice =
        parseInt($("#provisional-price").text().replace(/[^\d]/g, "")) || 0;
        
    console.log("Test provisional price: ", provisionalPrice);
    
    const realProvisionalPrice = provisionalPrice + voucherValue;
    const totalPrice = realProvisionalPrice + shippingFee;
    console.log("Test real provisional price: ", realProvisionalPrice);
    console.log("Test total price: ", totalPrice);


    const orderData = {
        voucher_name: voucherName,
        real_provisional_price: realProvisionalPrice,
        delivery_cost: shippingFee,
        total_price: totalPrice,
        address: address,
        payment_method:
            $('input[name="payment-method"]:checked').val() || "COD",
        additional_note: $("#additional-note").val(),
    };

    console.log('Order info: ', {
        total_price: orderData.total_price,
        delivery_cost: orderData.delivery_cost,
        provisional_price: orderData.real_provisional_price,
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
                window.location.href = `/checkout/success`;
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
    $("#shipping-fee").text(30000).formatPrice();
    updateTotal();
}

function initialLoadUserInfo(provinceData) {
    return $.ajax({
        url: "/checkout/user-info",
        method: "GET",
        success: function (response) {
            if (response.success) {
                const fields = {
                    'fullname': 'name',
                    'phone': 'phone',
                    'address': 'address'
                };

                for (const [responseKey, fieldId] of Object.entries(fields)) {
                    if (response[responseKey]) {
                        $(`#${fieldId}`)
                            .val(response[responseKey])
                            .removeClass('is-invalid')
                            .trigger('change')
                            .siblings(".invalid-feedback")
                            .remove();
                    }
                }

                // Set shipping variables
                to_district_id = response.district_id;
                to_ward_code = response.ward_code;
                to_province_id = response.province_id;

                // Calculate shipping fee
                if (to_province_id !== 202) {
                    calculateShippingFee(to_district_id, to_ward_code);
                } else {
                    innerCityShippingFee();
                }

                // Populate location dropdowns
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

                console.log("User info loaded successfully");
            } else {
                initializeEmptyForm(provinceData);
            }
        },
        error: function (xhr, status, error) {
            console.log("Error loading user info:", error);
            initializeEmptyForm(provinceData);
        },
    }).promise();
}

function initializeEmptyForm(provinceData, userInfo) {
    
    to_district_id = "";
    to_ward_code = "";
    to_province_id = "";

    // Initialize province dropdown
    const $provinceSelect = $("#province");
    $provinceSelect
        .empty()
        .append("<option selected>Lựa chọn Tỉnh/Thành Phố</option>");

    // Populate provinces
    Object.keys(provinceData).forEach((key) => {
        $provinceSelect.append(
            $("<option></option>")
                .attr("value", key)
                .text(provinceData[key].ProvinceName)
        );
    });

    // Reset district and ward dropdowns
    $("#district")
        .empty()
        .append("<option selected>Lựa chọn Quận/Huyện</option>");
    $("#ward").empty().append("<option selected>Lựa chọn Phường/Xã</option>");

    // Setup change handlers
    setupDropdownHandlers(provinceData);
    validateCheckoutForm();
}

function setupDropdownHandlers(provinceData) {
    $("#province")
        .off("change")
        .on("change", function () {
            const provinceId = $(this).val();
            updateDistrictDropdown(provinceId, provinceData);
            to_province_id = provinceId;
        });

    $("#district")
        .off("change")
        .on("change", function () {
            const districtId = $(this).val();
            updateWardDropdown(to_province_id, districtId, provinceData);
            to_district_id = districtId;
        });

    $("#ward")
        .off("change")
        .on("change", function () {
            const wardId = $(this).val();
            if (wardId) {
                to_ward_code = wardId;
                if (to_province_id === "202") {
                    innerCityShippingFee();
                } else {
                    calculateShippingFee(to_district_id, to_ward_code);
                }
            }
        });
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
            return true;
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
                $("#shipping-fee").text(response.shipping_fee).formatPrice();
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
function updateProvisionalPrice() {
    const voucherDiscount = $("#session-voucher-discount").val() || 0;
    const voucherName = $("#session-voucher-name").val() || null;
    console.log("Voucher discount in update price: ", voucherDiscount);
    console.log("Voucher name in update price: ", voucherName);

    const totalDiscountedPrice =
        $("#total-discounted-price").val() || 0;
    
    const provisionalPrice = totalDiscountedPrice - voucherDiscount;

    $("#provisional-price").text(provisionalPrice).formatPrice();

    return provisionalPrice;

}
function updateTotal() {
    const shippingFee =
        parseInt($("#shipping-fee").text().replace(/[^\d]/g, "")) || 0;
    const totalDiscountedPrice = updateProvisionalPrice();

    const finalTotal = totalDiscountedPrice + shippingFee;

    $(".total-amount").text(finalTotal).formatPrice();
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
        } 

        // Phone number validation
        if (fieldId === "phone") {
            const phoneRegex = /^\d{10}$/;
            if (!phoneRegex.test(value)) {
                $("#phone-warning").hide();
                formValid = false;
            } else {
                field.removeClass("is-invalid");
                $("#phone-warning").hide();
            }
        }

        // Name and address string validation
        else if (fieldId === "name") {
            if (typeof value !== 'string' || value.trim().length === 0 || /\d/.test(value)) {
                field.addClass("is-invalid");
                formValid = false;
            } else if (value.trim().length > 10) {
                field.removeClass("is-invalid"); 
                $("#address-warning").hide();
            }
        }
        else if (fieldId === "address") {
            if (typeof value !== 'string' || value.trim().length === 0) {
                field.addClass("is-invalid");
                formValid = false;
            } else {
                field.removeClass("is-invalid");
                $("#address-warning").hide();
            }
        }
        else {
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
