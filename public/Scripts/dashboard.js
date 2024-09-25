window.onload = () => {
    $.ajax({
        type: "GET",
        url: "/api/get/adminaccount/dashboard",
        dataType: "json",
        success: res => {
            const data = res.result.data;

            setText('truckNum', data[0].length);
            setText('driverNum', data[1].length);
            setText('routeNum', data[2].length);
            setText('complaintNum', data[3].length);
            console.log(data[4]);
            const complaintChart = document.getElementById('complaintChart').getContext('2d');
            var myPieChart = new Chart(complaintChart, {
                type: "pie",
                data: {
                    datasets: [
                        {
                            data: data[4],
                            backgroundColor: [
                                "#ff4500", // orange-red
                                "#1e90ff", // dodger blue
                                "#32cd32", // lime green
                                "#ff1493", // deep pink
                                "#8a2be2", // blue violet
                                "#ff6347", // tomato red
                                "#00fa9a", // medium spring green
                                "#dda0dd", // plum
                                "#4682b4", // steel blue
                                "#ffd700", // gold
                                "#40e0d0", // turquoise
                                "#ff69b4"  // hot pink
                            ],
                            borderWidth: 1,
                            borderColor: "#ffffff", // adds white border between segments for clarity
                        },
                    ],
                    labels: [
                        "Missed Collection",
                        "Late Irregular Service",
                        "Improper Handling of Waste",
                        "Overfilled Bins or Dumpsters",
                        "Unclean Service",
                        "Noise Complaints",
                        "Missorted Waste",
                        "Non-compliance with Special Waste Services",
                        "Bin Request or Replacement Issue",
                        "Unpleasant Odor",
                        "Route Issue",
                        "Poor Customer Service"
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
            
        }, error: xhr => console.log(xhr.responseText)
    })
}