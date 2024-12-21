$(document).ready(function () {
    let provinceData = [];

    // Load provinces data
    $.getJSON("/data/provinces.json")
        .done(function (data) {
            console.log("Data loaded successfully");
            provinceData = data;
            populateProvinces();
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.error(
                "Failed to load provinces data:",
                textStatus,
                errorThrown
            );
        });

    // Populate provinces dropdown
    function populateProvinces() {
        const $provinceSelect = $("#province");
        $provinceSelect.empty();
        $provinceSelect.append(
            "<option selected>Lựa chọn Tỉnh/Thành Phố</option>"
        );
		
        Object.keys(provinceData).forEach((key) => {
            $provinceSelect.append(
                $("<option></option>")
                    .attr("value", key)
                    .text(provinceData[key].ProvinceName)
            );
        });
    }

    // Handle province change
    $("#province").change(function () {
        const provinceId = $(this).val();
        const $districtSelect = $("#district");
        const $wardSelect = $("#ward");

        // Reset district and ward dropdowns
        $districtSelect.empty();
        $wardSelect.empty();

        $districtSelect.append("<option selected>Lựa chọn Quận/Huyện</option>");
        $wardSelect.append("<option selected>Lựa chọn Phường/Xã</option>");

        resetShippingCalculation();

        if (provinceId && provinceData[provinceId]) {
            const districts = provinceData[provinceId].Districts;
            Object.keys(districts).forEach((districtId) => {
                $districtSelect.append(
                    $("<option></option>")
                        .attr("value", districtId) // Store ID as value
                        .text(districts[districtId].DistrictName) // Display name as text
                );
            });
        }
    });

    // Handle district change
    $("#district").change(function () {
        const provinceId = $("#province").val();
        const districtId = $(this).val();
        const $wardSelect = $("#ward");

        // Reset ward dropdown
        $wardSelect.empty();
        $wardSelect.append("<option selected>Lựa chọn Phường/Xã</option>");

        resetShippingCalculation();

        if (
            provinceId &&
            districtId &&
            provinceData[provinceId].Districts[districtId]
        ) {
            const wards = provinceData[provinceId].Districts[districtId].Wards;
            Object.keys(wards).forEach((wardId) => {
                $wardSelect.append(
                    $("<option></option>")
                        .attr("value", wardId) // Store ID as value
                        .text(wards[wardId].WardName) // Display name as text
                );
            });
        }
    });

    // Hanlde ward change
    $("#ward").change(function () {
        const districtId = $("#district").val();
        const wardCode = $(this).val();

        if (districtId && wardCode) {
            calculateShippingFee({
                to_district_id: districtId,
                to_ward_code: wardCode
            });
        }
    })
});

function resetShippingCalculation() { 
    $("#shipping-fee").text("---");
}

function calculateShippingFee(to_district_id, to_ward_code) { 
    $.ajax({
        method: "POST",
        url: "/calculate-shipping",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: JSON.stringify({
            to_district_id: to_district_id,
            to_ward_code: to_ward_code
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