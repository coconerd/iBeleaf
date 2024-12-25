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
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "top",
                    padding: 15,
                    labels: {
                        padding: 12,
                        boxWidth: 30,
                        font: {
                            size: 15,
                        },
                    },
                },
                tooltip: {
                    mode: "index",
                    intersect: false,
                    padding: 20,
                    displayColors: true,
                    titleFont: {
                        size: 14,
                    },
                    bodyFont: {
                        size: 15,
                    },
                    callbacks: {
                        label: function (context) {
                            const label = context.dataset.label || "";
                            return "  " + label + ": " + context.parsed.y;
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
    loadTopSellingProducts();
    // Call the initialization
    initLocationChart();
});

function updateStatCard(metric, options) {
    $.ajax({
        url: `dashboard/analyze/${metric}`,
        method: "GET",
        success: function (response) {
            const { valueId, indicatorId, growthId, prefix, suffix } = options;

            // Update value with prefix/suffix only if there's data
            let formattedValue = '';
            if (response.todayTotal === null || response.todayTotal === 0) {
                formattedValue = "...";
            } else {
                formattedValue = [
                    prefix,
                    options.formatter
                        ? options.formatter(response.todayTotal)
                        : response.todayTotal,
                    suffix,
                ]
                    .filter(Boolean)
                    .join(" ");
            }

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
            .text(`${parseLocalizedNumber(response.growth).toFixed(2)}%`)
                .removeClass("growth-positive growth-negative")
                .addClass(growthClass);
            },
    });
}

function parseLocalizedNumber(str) {
    // Remove thousand separators and convert decimal separator
    return parseFloat(str.replace(/\,/g, ''));
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

function loadTopSellingProducts() {
    $.ajax({
        url: "dashboard/top-selling",
        method: "GET",
        success: function (products) {
            const tbody = $("#top-selling-table tbody");
            tbody.empty();

            products.forEach((product) => {
                const row = $("<tr>");
                const topIcon = product.rank <= 5 ? `<i class="fas fa-crown text-warning" title="Top ${product.rank}"></i>` : '';
				row.html(`
					<td class="text-center">
						${topIcon}
						${product.rank}
					</td>
					<td>
						<img src="${product.image}" alt="${product.name}" 
							class="product-thumbnail">
					</td>
					<td>${product.id}</td>
					<td>${product.name}</td>
					<td class="text-end overall-star">
						<span class="text-warning">
							${'<i class="fas fa-star"></i>'.repeat(Math.floor(product.rating))}
							${product.rating % 1 !== 0 ? '<i class="fas fa-star-half-alt"></i>' : ''}
						</span>
						<span class="rating-number">${Number(product.rating).toFixed(1)}</span>
					</td>
					<td class="text-end quantity-cell">${product.quantity}</td>
				`);
                tbody.append(row);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error loading top selling products:", error);
        },
    });
}

