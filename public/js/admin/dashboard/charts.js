$(document).ready(function () {
    let salesChart = null;

    function initSalesChart() {
        // Verify Chart.js is loaded
        
        $.ajax({
            url: "dashboard/sales-data",
            method: "GET",
            success: function (response) {
				
                const canvas = $("#salesChart")[0];
                if (!canvas) {
                    console.error("Canvas element not found");
                    return;
                }
                const ctx = canvas.getContext("2d");
				
                salesChart = new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: response.labels,
                        datasets: [
                            {
                                label: "Orders",
                                data: response.data,
                                borderColor: "#C78B5E",
                                backgroundColor: "rgba(30, 54, 45, 0.1)",
                                tension: 0.4,
                                fill: true,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                },
                            },
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: "top",
                            },
                        },
                    },
                });
            },
            error: function (xhr, status, error) {
                console.error("Error details:", {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                });
            },
        });
    }

    // Call initialization
    initSalesChart();
});
