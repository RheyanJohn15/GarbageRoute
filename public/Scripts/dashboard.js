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
                    "New",
                    "In Progress",
                    "Resolved",
                    "Closed",
                    "Cancelled",
                    "Pending",
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


        }, error: xhr => console.log(xhr.responseText)
    })
}