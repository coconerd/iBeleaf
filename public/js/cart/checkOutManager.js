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

        if (provinceId && provinceData[provinceId]) {
            const districts = provinceData[provinceId].Districts;
            Object.keys(districts).forEach((districtId) => {
                $districtSelect.append(
                    `<option value="${districtId}">${districts[districtId].DistrictName}</option>`
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

        if (
            provinceId &&
            districtId &&
            provinceData[provinceId].Districts[districtId]
        ) {
            const wards = provinceData[provinceId].Districts[districtId].Wards;
            Object.keys(wards).forEach((wardId) => {
                $wardSelect.append(
                    `<option value="${wardId}">${wards[wardId].WardName}</option>`
                );
            });
        }
    });
});