    const labels = ["Red", "Blue", "Yellow"];
const data = [12, 19, 3];

const chartjs = {
    init: () => {
        chartjs.bar();
        chartjs.doughnut();
        chartjs.line();
    },
    bar: (e) => {
        const barChart = document.getElementById('barChart').getContext('2d');
        new Chart(barChart, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sample Data',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)', // Light red
                        'rgba(54, 162, 235, 0.7)', // Light blue
                        'rgba(255, 206, 86, 0.7)'  // Light yellow
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',   // Dark red border
                        'rgba(54, 162, 235, 1)',   // Dark blue border
                        'rgba(255, 206, 86, 1)'    // Dark yellow border
                    ],
                    borderWidth: 2, // Border thickness
                    hoverBackgroundColor: [
                        'rgba(250, 66, 106, 0.7)',    // Hover red
                        'rgba(0, 93, 155, 0.7)', // Light blue
                        'rgba(239, 254, 24, 0.7)'  // Light yellow
                    ],
                    hoverBorderColor: [
                        'rgba(200, 0, 0, 1)',      // Darker red on hover
                        'rgba(0, 0, 200, 1)',      // Darker blue on hover
                        'rgba(200, 200, 0, 1)'     // Darker yellow on hover
                    ]
                }]
            },
            options: {
                responsive: false // Keeps the chart at a fixed size
            }
        });
    },
    doughnut: (e) => {
        const doughnutChart  = document.getElementById('doughnutChart').getContext('2d');
        new Chart(doughnutChart , {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sample Data',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)', // Light red
                        'rgba(54, 162, 235, 0.7)', // Light blue
                        'rgba(255, 206, 86, 0.7)'  // Light yellow
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',   // Dark red border
                        'rgba(54, 162, 235, 1)',   // Dark blue border
                        'rgba(255, 206, 86, 1)'    // Dark yellow border
                    ],
                    borderWidth: 2, // Border thickness
                    hoverBackgroundColor: [
                        'rgba(250, 66, 106, 0.7)',    // Hover red
                        'rgba(0, 93, 155, 0.7)', // Light blue
                        'rgba(239, 254, 24, 0.7)'  // Light yellow
                    ],
                    hoverBorderColor: [
                        'rgba(200, 0, 0, 1)',      // Darker red on hover
                        'rgba(0, 0, 200, 1)',      // Darker blue on hover
                        'rgba(200, 200, 0, 1)'     // Darker yellow on hover
                    ]
                }]
            },
            options: {
                responsive: false, // Keeps chart fixed size
                cutout: '60%', // Controls the thickness of the doughnut (smaller = thicker)
                plugins: {
                    legend: {
                        labels: {
                            color: '#27445D', // Legend text color
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });
    },
    line: () => {
        const lineChart = document.getElementById('lineChart').getContext('2d');
        new Chart(lineChart, {
            type: 'line',
            data: {
                labels: ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'],   
                datasets: [{
                    label: 'Sample Data',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Light blue fill
                    borderColor: 'rgba(54, 162, 235, 1)', // Blue line
                    borderWidth: 2,
                    pointBackgroundColor: [
                        '#27445D', // Point 1
                        '#497D74', // Point 2
                        '#71BBB2'  // Point 3
                    ],
                    pointRadius: 5, // Make points visible
                    fill: true // Fill area under line
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#27445D', // Legend color
                            font: { size: 14 }
                        }
                    }
                },
                elements: {
                    line: {
                        tension: 0.4 // Smooth curved line
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#27445D' // X-axis label color
                        }
                    },
                    y: {
                        ticks: {
                            color: '#27445D' // Y-axis label color
                        }
                    }
                }
            }
        });
    }
};

chartjs.init();