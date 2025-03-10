document.addEventListener("DOMContentLoaded", function() {
    // Select all links with the 'load-content' class
    document.querySelectorAll(".load-content").forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault(); // Prevent page reload

            const page = this.getAttribute("data-page"); // Get the target page
            const contentDiv = document.querySelector(".contents");

            // Load content using fetch
            fetch(page)
                .then(response => response.text())
                .then(data => {
                    contentDiv.innerHTML = data; // Update content

                    // Delay chart initialization to ensure DOM is updated
                    setTimeout(() => chartjs.init(), 0);
                })
                .catch(error => console.error("Error loading content:", error));
        });
    });
});

const labels = ["Red", "Blue", "Yellow"];
const data = [12, 19, 3];

const chartjs = {
    init: () => {
        chartjs.bar();
        chartjs.doughnut();
        chartjs.line();
    },
    bar: () => {
        const barChart = document.getElementById('barChart');
        if (!barChart) return; // Check if element exists
        new Chart(barChart.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sample Data',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)'
                    ],
                    borderWidth: 2
                }]
            },
            options: { responsive: false }
        });
    },
    doughnut: () => {
        const doughnutChart = document.getElementById('doughnutChart');
        if (!doughnutChart) return; // Check if element exists
        new Chart(doughnutChart.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sample Data',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)'
                    ],
                    borderWidth: 2
                }]
            },
            options: { responsive: false }
        });
    },
    line: () => {
        const lineChart = document.getElementById('lineChart');
        if (!lineChart) return; // Check if element exists
        new Chart(lineChart.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar'],
                datasets: [{
                    label: 'Sample Data',
                    data: data,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    fill: true
                }]
            },
            options: { responsive: false }
        });
    }
};


document.addEventListener("DOMContentLoaded", function() {
    chartjs.init();
});