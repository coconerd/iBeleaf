let salesChart = null;

$(document).ready(function () {
    const ctx = $("#salesChart")[0].getContext("2d");
    const orderStatusColors = {
        pending: "#8B4513",
        delivering: "#C19A6B",
        delivered: "#A0522D",
        cancelled: "#5E2D1A",
    };

    const chart = new Chart(ctx, {
        type: "line",
        data: {
            labels: [],
            datasets: [
                {
                    label: "Chờ xác nhận",
                    borderColor: orderStatusColors.pending,
                    data: [],
                    tension: 0.4,
                    fill: false,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: orderStatusColors.pending,
                },
                {
                    label: "Đang giao hàng",
                    borderColor: orderStatusColors.delivering,
                    data: [],
                    tension: 0.4,
                    fill: false,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: orderStatusColors.delivering,
                },
                {
                    label: "Đã giao hàng",
                    borderColor: orderStatusColors.delivered,
                    data: [],
                    tension: 0.4,
                    fill: false,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: orderStatusColors.delivered,
                },
                {
                    label: "Đã hủy",
                    borderColor: orderStatusColors.cancelled,
                    data: [],
                    tension: 0.4,
                    fill: false,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: orderStatusColors.cancelled,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "top",
                    padding: 25,
                    labels: {
                        padding: 20,
                        boxWidth: 40,
                        font: {
                            size: 13,
                        },
                    },
                },
            },
            interaction: {
                mode: "nearest",
                intersect: false,
                axis: "x",
            },
            datasets: {
                line: {
                    hoverBorderWidth: 4,
                },
            },
            hover: {
                mode: "nearest",
                intersect: false,
            },
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
            plugins: {
                tooltip: {
                    mode: "index",
                    intersect: false,
                },
                legend: {
                    onHover: (e) => {
                        e.chart.data.datasets.forEach((dataset) => {
                            dataset.borderWidth = 2;
                        });
                    },
                },
            },
            datasetHoverStyle: {
                dataset: {
                    borderWidth: 4,
                },
            },
        },
    });

    // Add AJAX call to fetch data
    function fetchOrderData() {
        $.ajax({
            url: "dashboard/sales-data",
            method: "GET",
            success: function (response) {
                chart.data.labels = response.labels;
                chart.data.datasets[0].data = response.pending;
                chart.data.datasets[1].data = response.delivering;
                chart.data.datasets[2].data = response.delivered;
                chart.data.datasets[3].data = response.cancelled;
                chart.update();
            },
            error: function (xhr) {
                console.error("Error:", xhr);
            },
        });
    }

    fetchOrderData(); // Initial load
    showStatsCards();
});

function updateStatCard(metric, options) {
    $.ajax({
        url: `dashboard/analyze/${metric}`,
        method: "GET",
        success: function (response) {
            const { valueId, indicatorId, growthId, prefix, suffix } = options;

            // Update value with prefix/suffix
            const formattedValue = [
                prefix,
                options.formatter
                    ? options.formatter(response.todayTotal)
                    : response.todayTotal,
                suffix,
            ]
                .filter(Boolean)
                .join(" ");

            $(`#${valueId}`).text(formattedValue);
            
            const growthClass =
            response.trend === "increase"
            ? "growth-positive"
            : "growth-negative";
            
            // Update indicator
            const iconHtml =
            response.trend === "increase"
                ? '<i class="fa-solid fa-arrow-up"></i>'
                : '<i class="fa-solid fa-arrow-down"></i>';
            
            $(`#${indicatorId}`).empty().html(iconHtml).addClass(growthClass);
            
            // Update growth
            $(`#${growthId}`)
            .text(`${Math.abs(response.growth)}%`)
                .removeClass("growth-positive growth-negative")
                .addClass(growthClass);
            },
    });
}
    
function showStatsCards() {
    // Update Revenue
    updateStatCard("sales", {
        valueId: "today-sales",
        indicatorId: "sales-indicator",
        growthId: "sales-growth",
        formatter: formatPrice,
    });

    // Update Orders
    updateStatCard("orders", {
        valueId: "today-orders",
        indicatorId: "orders-indicator",
        growthId: "orders-growth",
        prefix: "Số lượng: ",
    });

    // Update Customers
    updateStatCard("customers", {
        valueId: "total-customers",
        indicatorId: "customers-indicator",
        growthId: "customers-growth",
        suffix: "người",
    });
}

function formatPrice(price) {
    return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
    }).format(price);
}