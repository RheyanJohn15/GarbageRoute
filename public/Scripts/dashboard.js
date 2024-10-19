window.onload = () => {
    $.ajax({
        type: "GET",
        url: "/api/get/adminaccount/dashboard",
        dataType: "json",
        success: res => {
            const data = res.result.data;

            setText('truckNum', data[0].length);
            setText('driverNum', data[1].length);
            setText('complaintNum', data[2].length);
            setText('resolvedComplaintNum', data[4].length);
            console.log(data[8]);
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
                            backgroundColor: "rgb(23, 125, 255)",
                            borderColor: "rgb(23, 125, 255)",
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
                },
            });


            const collectorTotalTurnOver = document
                .getElementById("collectorTotalTurnOver")
                .getContext("2d");

            const garbagePerZone = document.getElementById("garbagePerZone").getContext("2d");

            var garbagePerZoneChart = new Chart(garbagePerZone, {
                type: "bar",
                data: {
                    labels: data[6],
                    datasets: [
                        {
                            label: "Garbage(Tons)",
                            backgroundColor: "rgb(23, 125, 255)",
                            borderColor: "rgb(23, 125, 255)",
                            data: data[7],
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
