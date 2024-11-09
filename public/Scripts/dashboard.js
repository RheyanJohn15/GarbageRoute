window.onload = () => {
    $.ajax({
        type: "GET",
        url: "/api/get/adminaccount/dashboard",
        dataType: "json",
        success: res => {
            const data = res.result.data;

            setText('truckNum', data[0]);
            setText('driverNum', data[1]);
            setText('complaintNum', data[2]);
            setText('resolvedComplaintNum', data[4]);
            console.log(data[0].length);
            const complaintChart = document.getElementById('complaintChart').getContext('2d');
            var myPieChart = new Chart(complaintChart, {
                type: "pie",
                data: {
                    datasets: [
                        {
                            data: data[3],
                            backgroundColor: [
                                "#ff4500", // orange-red
                                "#1e90ff", // dodger blue
                                "#32cd32", // lime green

                            ],
                            borderWidth: 1,
                            borderColor: "#ffffff", // adds white border between segments for clarity
                        },
                    ],
                    labels: [
                        "Missed Collection",
                        "Late Irregular Service",
                        "Improper Handling of Waste",

                    ],
                },
                pieceLabel: {
                    render: "percentage",
                    fontColor: "white",
                    fontSize: 14,
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: "bottom",
                        labels: {
                            fontColor: "rgb(154, 154, 154)",
                            fontSize: 11,
                            usePointStyle: true,
                            padding: 20,
                        },
                    },
                    tooltips: {
                        enabled: true,
                        callbacks: {
                            label: function (tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var label = data.labels[tooltipItem.index] || '';
                                var value = dataset.data[tooltipItem.index] || 0;
                                return label + ': ' + value + ' (' + Math.round((value / dataset._meta[Object.keys(dataset._meta)[0]].total) * 100) + '%)';
                            }
                        },
                    },
                    layout: {
                        padding: {
                            left: 20,
                            right: 20,
                            top: 20,
                            bottom: 20,
                        },
                    },
                },
            });

            complaintStatusBarchart = document.getElementById("complaintStatusBarchart").getContext("2d");
            const complaintStatusNumber = data[5];
            var myBarChart = new Chart(complaintStatusBarchart, {
                type: "bar",
                data: {
                    labels: [
                        "Pending",
                        "In Progress",
                        "Resolved",
                    ],
                    datasets: [
                        {
                            label: "No./Status",
                            backgroundColor: [
                                "rgb(255, 99, 132)",  // Color for "Pending"
                                "rgb(54, 162, 235)",  // Color for "In Progress"
                                "rgb(75, 192, 192)"   // Color for "Resolved"
                            ],
                            borderColor: [
                                "rgb(255, 99, 132)",  // Border color for "Pending"
                                "rgb(54, 162, 235)",  // Border color for "In Progress"
                                "rgb(75, 192, 192)"   // Border color for "Resolved"
                            ],
                            data: complaintStatusNumber,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                },
                            },
                        ],
                    },
                    legend: {
                        display: true,  // Show or hide the legend
                        position: 'top',  // Position of the legend: 'top', 'left', 'bottom', 'right'
                        labels: {
                            fontColor: '#333',  // Color of the legend text
                            fontSize: 12,       // Font size of the legend text
                            generateLabels: function(chart) {
                                // Custom legend labels to match colors
                                return chart.data.labels.map((label, i) => ({
                                    text: label,
                                    fillStyle: chart.data.datasets[0].backgroundColor[i],
                                    strokeStyle: chart.data.datasets[0].borderColor[i],
                                    hidden: false
                                }));
                            }
                        }
                    }
                },
            });
            
            

            const collectorTotalTurnOver = document
                .getElementById("collectorTotalTurnOver")
                .getContext("2d");

            const garbagePerZone = document.getElementById("garbagePerZone").getContext("2d");

            var garbagePerZoneChart = new Chart(garbagePerZone, {
                type: "bar",
                data: {
                    labels: data[6],  // Assuming data[6] has labels for each of the 5 zones
                    datasets: [
                        {
                            label: "Garbage (Tons)",
                            backgroundColor: [
                                "rgb(255, 99, 132)",  // Color for Zone 1
                                "rgb(54, 162, 235)",  // Color for Zone 2
                                "rgb(75, 192, 192)",  // Color for Zone 3
                                "rgb(255, 206, 86)",  // Color for Zone 4
                                "rgb(153, 102, 255)"  // Color for Zone 5
                            ],
                            borderColor: [
                                "rgb(255, 99, 132)",  // Border color for Zone 1
                                "rgb(54, 162, 235)",  // Border color for Zone 2
                                "rgb(75, 192, 192)",  // Border color for Zone 3
                                "rgb(255, 206, 86)",  // Border color for Zone 4
                                "rgb(153, 102, 255)"  // Border color for Zone 5
                            ],
                            data: data[7],  // Assuming data[7] has values for each of the 5 zones
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                },
                            },
                        ],
                    },
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            fontColor: '#333',
                            fontSize: 12,
                            generateLabels: function(chart) {
                                return chart.data.labels.map((label, i) => ({
                                    text: label,
                                    fillStyle: chart.data.datasets[0].backgroundColor[i],
                                    strokeStyle: chart.data.datasets[0].borderColor[i],
                                    hidden: false
                                }));
                            }
                        }
                    }
                },
            });
            

            let turnOverDataSet = [];
            Object.entries(data[8]).forEach(([key, value]) => {
                let turnover = {};
                turnover.label = key;
                turnover.data = value;
                turnover.borderColor = getRandomHexColor();
                turnover.pointBorderColor = getRandomHexColor();
                turnover.pointBackgroundColor = getRandomHexColor();
                turnover.pointBorderWidth = 2;
                turnover.pointHoverRadius = 4;
                turnover.pointHoverBorderWidth = 1;
                turnover.pointRadius = 4;
                turnover.backgroundColor = "transparent";
                turnover.fill = true;
                turnover.borderWidth = 2;
                turnOverDataSet.push(turnover);
            })
            function getRandomHexColor() {
                const randomColor = Math.floor(Math.random() * 16777215).toString(16); // Generate a random number and convert to hex
                return `#${randomColor.padStart(6, '0')}`; // Pad with zeros if necessary
            }

            var collectionTurnOverChart = new Chart(collectorTotalTurnOver, {
                type: "line",
                data: {
                    labels: [
                        "Jan",
                        "Feb",
                        "Mar",
                        "Apr",
                        "May",
                        "Jun",
                        "Jul",
                        "Aug",
                        "Sep",
                        "Oct",
                        "Nov",
                        "Dec",
                    ],
                    datasets:turnOverDataSet,
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: "top",
                    },
                    tooltips: {
                        bodySpacing: 4,
                        mode: "nearest",
                        intersect: 0,
                        position: "nearest",
                        xPadding: 10,
                        yPadding: 10,
                        caretPadding: 10,
                    },
                    layout: {
                        padding: { left: 15, right: 15, top: 15, bottom: 15 },
                    },
                },
            });

        }, error: xhr => console.log(xhr.responseText)
    })
}
