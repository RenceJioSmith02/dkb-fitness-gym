<?php
require "db/db.php";
$mydb = new myDB();

include_once 'partials/session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym MS | Dashboard</title>
    <link rel="icon" href="assets/images/system_image/favicon.png" type="image/png">
    <link rel="stylesheet" href="assets/css/main.css">
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script src="assets/js/chart.umd.min.js"></script>
</head>

<body>

    <div class="container flex flex-col jus-start al-center">

        <!-- Header -->
        <?php include_once 'partials/header.php' ?>

        <div class="content userspage">

            <div class="dashboard-container flex al-center jus-center flex-col">

                <div class="box-container flex al-center jus-center">
                    <div class="box one flex al-center jus-center flex-row">
                        <!-- Pie chart for current members -->
                        <canvas id="membersPie" width="120" height="120"></canvas>

                        <div class="row-1 flex al-center jus-center flex-col">
                            <div class="box-title">Current Members</div>
                            <div class="box-data flex al-center jus-center">
                                <div class="col flex al-center jus-center flex-col">
                                    <span>0</span>
                                    <p>Monthly</p>
                                </div>
                                <div class="col flex al-center jus-center flex-col">
                                    <span>0</span>
                                    <p>Walk-in</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box two flex al-center jus-center flex-col">
                        <div class="box-title">
                            Total Visitors
                        </div>
                        <div class="box-data flex al-center jus-center">
                            <div class="col flex al-center jus-center flex-col">
                                <span>0</span>
                                <p>Members</p>
                            </div>
                        </div>
                    </div>
                    <div class="box three flex al-center jus-center flex-row">
                        <!-- Pie chart for status -->
                        <canvas id="statusPie" width="120" height="120"></canvas>

                        <div class="flex al-center jus-center flex-col">
                            <div class="box-title">Status</div>
                            <div class="box-data flex al-center jus-center">
                                <div class="col flex al-center jus-center flex-col">
                                    <span>0</span>
                                    <p>Active</p>
                                </div>
                                <div class="col flex al-center jus-center flex-col">
                                    <span>0</span>
                                    <p>Inactive</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="analytics-container scroller-format">
                    <div class="analytics-header flex al-center jus-between">
                        <h3>Visitors Analytics</h3>
                        <select id="chartFilter">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="annual">Annual</option>
                        </select>
                    </div>

                    <div class="chart-wrapper">
                        <canvas id="visitorsChart"></canvas>
                    </div>
                </div>


            </div>

        </div>
    </div>


    <script type="text/javascript">
        $(document).ready(function () {

            function checkMonthlyExpiry() {
                $.post("db/request.php", {
                    action: "check_monthly_expiry"
                }, function (response) {
                    if (response.status === "success") {
                        console.log("User status updated. Active:", response.activeCount, "Inactive:", response.inactiveCount);
                    } else {
                        console.error("Error checking expiry:", response.message);
                    }
                }, "json");
            }

            checkMonthlyExpiry();

            // =============================
            // Animate count-up for boxes
            // =============================
            function animateCountUp(element, target) {
                let duration = 1000; // 1.5s animation
                let start = 0;
                let stepTime = Math.max(20, Math.floor(duration / (target || 1)));
                let current = start;

                let timer = setInterval(() => {
                    current++;
                    element.text(current.toLocaleString());
                    if (current >= target) {
                        clearInterval(timer);
                        element.text(target.toLocaleString());
                    }
                }, stepTime);
            }

            // === Pie Charts ===
            function renderPieChart(canvasId, labels, data, colors) {
                let ctx = document.getElementById(canvasId).getContext('2d');
                return new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: colors,
                            borderWidth: 0,
                            borderRadius: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: "60%",
                        plugins: {
                            legend: {
                                display: false,
                                position: "bottom",
                                labels: {
                                    color: "#fff",
                                    boxWidth: 15,
                                    padding: 10
                                }
                            }
                        }
                    }
                });
            }



            // =============================
            // Chart loader function
            // =============================
            let chartInstance = null;
            let membersPie = null;
            let statusPie = null;

            function loadDashboard(filter = "daily") {
                $.ajax({
                    url: "db/request.php",
                    method: "POST",
                    dataType: "json",
                    data: { action: "get_dashboard", filter: filter },
                    success: function (response) {
                        console.log(response);

                        // === Chart ===
                        let ctx = document.getElementById('visitorsChart').getContext('2d');

                        function getColor(value, filter) {
                            switch (filter) {
                                case "daily":
                                    if (value >= 50) return '#BC7500';   // high
                                    if (value >= 25) return '#E59522';   // medium
                                    return '#FFEDD4';                    // low
                                case "weekly":
                                    if (value >= 200) return '#BC7500';
                                    if (value >= 100) return '#E59522';
                                    return '#FFEDD4';
                                case "monthly":
                                    if (value >= 800) return '#BC7500';
                                    if (value >= 500) return '#E59522';
                                    return '#FFEDD4';
                                case "annual":
                                    if (value >= 10000) return '#BC7500';
                                    if (value >= 6000) return '#E59522';
                                    return '#FFEDD4';
                                default:
                                    return '#FFEDD4'; // fallback
                            }
                        }

                        let barColors = response.chart.data.map(val => getColor(val, filter));

                        if (chartInstance) {
                            chartInstance.destroy();
                        }

                        chartInstance = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: response.chart.labels,
                                datasets: [{
                                    label: 'Visitors',
                                    data: response.chart.data,
                                    backgroundColor: barColors,
                                    borderColor: barColors,
                                    borderWidth: 1,
                                    borderRadius: 11,
                                    borderSkipped: false,
                                    barThickness: filter === "annual" ? 60 : 40, // bigger bars for annual
                                    categoryPercentage: 0.9,
                                    barPercentage: 0.9
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    title: {
                                        display: true,
                                        text: response.chart.title,
                                        align: 'center',
                                        color: '#fff',
                                        font: {
                                            size: 25,
                                            weight: 'bold'
                                        },
                                        padding: { top: 10, bottom: 20 }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            title: function (ctx) {
                                                let i = ctx[0].dataIndex;
                                                return response.chart.dates[i];
                                            },
                                            label: function (ctx) {
                                                return "Visitors: " + ctx.formattedValue;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: { display: false },
                                        ticks: { color: '#fff' },
                                        title: {
                                            display: true,
                                            text: response.chart.xTitle,
                                            color: '#fff',
                                            font: { size: 20 },
                                            padding: { top: 10 }
                                        }
                                    },
                                    y: {
                                        grid: { display: false },
                                        ticks: { stepSize: 2, color: '#fff' },
                                        title: {
                                            display: true,
                                            text: 'Number of Visitors',
                                            color: '#fff',
                                            font: { size: 20 },
                                            padding: { bottom: 10 }
                                        }
                                    }
                                }
                            }
                        });

                        // === Boxes ===
                        animateCountUp($(".box.one .box-data .col:eq(0) span"), response.members.monthly);
                        animateCountUp($(".box.one .box-data .col:eq(1) span"), response.members.walkin);
                        animateCountUp($(".box.two .box-data .col:eq(0) span"), response.visitors);
                        animateCountUp($(".box.three .box-data .col:eq(0) span"), response.status.active);
                        animateCountUp($(".box.three .box-data .col:eq(1) span"), response.status.inactive);

                        // === Pie Charts ===
                        if (membersPie) membersPie.destroy();
                        membersPie = renderPieChart(
                            "membersPie",
                            ["Monthly", "Walk-in"],
                            [response.members.monthly, response.members.walkin],
                            ["#E59522", "#FFEDD4"] // cream + orange for box one
                        );

                        if (statusPie) statusPie.destroy();
                        statusPie = renderPieChart(
                            "statusPie",
                            ["Active", "Inactive"],
                            [response.status.active, response.status.inactive],
                            ["#4CAF50", "#7A7A7A"] // green + gray for box three
                        );

                    }
                });
            }

            // =============================
            // Initial load (daily)
            // =============================
            loadDashboard("daily");

            // =============================
            // Dropdown filter handler
            // =============================
            $("#chartFilter").on("change", function () {
                loadDashboard($(this).val());
            });

        });
    </script>

</body>

</html>