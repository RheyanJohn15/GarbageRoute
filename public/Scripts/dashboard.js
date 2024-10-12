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
            console.log(data[5]);
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
                            label: function(tooltipItem, data) {
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
                datasets: [
                  {
                    label: "Data",
                    backgroundColor: "rgb(23, 125, 255)",
                    borderColor: "rgb(23, 125, 255)",
                    data: [3, 2, 9, 5, 4, 6, 4, 6, 7, 8, 7, 4],
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
                datasets: [
                  {
                    label: "Collector 3",
                    borderColor: "#1d7af3",
                    pointBorderColor: "#FFF",
                    pointBackgroundColor: "#1d7af3",
                    pointBorderWidth: 2,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 1,
                    pointRadius: 4,
                    backgroundColor: "transparent",
                    fill: true,
                    borderWidth: 2,
                    data: [30, 45, 45, 68, 69, 90, 100, 158, 177, 200, 245, 256],
                  },
                  {
                    label: "Collector 2",
                    borderColor: "#59d05d",
                    pointBorderColor: "#FFF",
                    pointBackgroundColor: "#59d05d",
                    pointBorderWidth: 2,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 1,
                    pointRadius: 4,
                    backgroundColor: "transparent",
                    fill: true,
                    borderWidth: 2,
                    data: [10, 20, 55, 75, 80, 48, 59, 55, 23, 107, 60, 87],
                  },
                  {
                    label: "Collector 1",
                    borderColor: "#f3545d",
                    pointBorderColor: "#FFF",
                    pointBackgroundColor: "#f3545d",
                    pointBorderWidth: 2,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 1,
                    pointRadius: 4,
                    backgroundColor: "transparent",
                    fill: true,
                    borderWidth: 2,
                    data: [10, 30, 58, 79, 90, 105, 117, 160, 185, 210, 185, 194],
                  },
                ],
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