let provinceData = [];
let to_district_id = null;
let to_ward_code = null;
let to_province_id = null;

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
        console.log("After changing location: ", { to_district_id, to_ward_code, to_province_id });

        if (to_district_id && to_ward_code && to_province_id !== "202") {
            console.log("Ward changes");

            calculateShippingFee(
                to_district_id,
                to_ward_code
            );
        }
    });

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

});

function innerCityShippingFee() { 
    $("#shipping-fee").text(
        new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
        }).format(30000)
    );
}

function initialLoadUserInfo(provinceData) {
    $.ajax({
        url: "/checkout/user-info",
        type: "GET",
        success: function (response) {
            if (response.success) {
                $("#name").val(response.fullname);
                $("#phone").val(response.phone);

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
                    findto_province_id(response.province),
                    response.district,
                    provinceData
                );
                populateWards(
                    findto_district_id(response.province, response.district),
                    response.ward,
                    provinceData
                );

                console.log("User info loaded:", response);
            }
        },
        error: function (xhr) {
            console.error("Failed to load user info:", xhr.responseJSON);
        },
    }).promise();
}
// function initializeCheckout() {
//     const userInfoPromise = initialLoadUserInfo(provinceData);
   
//     const shippingFeePromise = calculateShippingFee(district_id, ward_code);
//     Promise.all([userInfoPromise, shippingFeePromise])
//         .then(() => {
//             console.log(
//                 "User info and initial shipping fee loaded successfully."
//             );
//         })
//         .catch((error) => {
//             console.error("Error initializing checkout:", error);
//         });
// }

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
function findto_province_id(provinceName) {
    return Object.keys(provinceData).find(
        (id) => provinceData[id].ProvinceName.trim() === provinceName.trim()
    );
}

function findto_district_id(provinceName, districtName) {
    const to_province_id = findto_province_id(provinceName);
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
