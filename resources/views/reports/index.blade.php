@extends('layouts.app')

@section('content')

<style>
    /* Consistent styling with app.blade.php */
    .container-fluid {
        max-width: 100%;
    }

    .report-card {
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        margin-bottom: 30px;
    }

    .report-header {
        background-color: #007bff;
        color: white;
        padding: 15px;
        border-radius: 10px 10px 0 0;
        font-weight: bold;
        font-size: 18px;
    }

    .chart-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
        max-width: 1000px; /* Limit chart width for better visibility */
        margin-left: auto;
        margin-right: auto;
        height: 500px; /* Increased height for better visibility */
    }

    canvas {
        width: 100% !important; /* Ensures canvas takes full container width */
        height: 100% !important; /* Ensures canvas height matches container */
    }

    .form-group label {
        font-weight: bold;
        color: #4b5563;
    }

    .btn-primary, .btn-info {
        background-color: #007bff;
        border: none;
    }

    .btn-success {
        background-color: #28a745;
        border: none;
    }

    .form-control, .form-group select {
        height: 40px;
        border-radius: 5px;
    }
</style>

<div class="container-fluid mt-4">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">System Reports</h2>

    <!-- Report Filter Form -->
    <div class="card p-4 mb-4">
        <h3 class="text-info">Generate Report by Date Range</h3>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control">
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-center">
            <button type="button" class="btn btn-primary w-100" id="filterButton">Filter</button>
        </div>

        </div>

        <!-- Chart Type Dropdown -->
        <div class="form-group mt-4">
            <label for="chartType">Select Chart Type:</label>
            <select id="chartType" class="form-control">
                <option value="pie">Pie Chart</option>
                <option value="bar">Bar Chart</option>
                <option value="line">Line Chart</option>
            </select>
        </div>

        <!-- Quick Filters for "Today", "This Week", "This Month" -->
        <div class="form-group mt-4">
            <label for="quickFilters">Quick Filters:</label>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-info" id="todayFilter">Today</button>
                <button type="button" class="btn btn-info" id="weekFilter">This Week</button>
                <button type="button" class="btn btn-info" id="monthFilter">This Month</button>
            </div>
        </div>
    </div>

    <!-- Task Status Chart Card -->
    <div class="card report-card">
        <div class="report-header">Task Statuses</div>
        <div class="card-body chart-container">
            <canvas id="taskStatusChart"></canvas>
        </div>
    </div>

    <!-- Task Priority Chart Card -->
    <div class="card report-card">
        <div class="report-header">Task Priorities</div>
        <div class="card-body chart-container">
            <canvas id="taskPriorityChart"></canvas>
        </div>
    </div>

    <!-- Task Due Dates Chart Card -->
    <div class="card report-card">
        <div class="report-header">Task Due Dates</div>
        <div class="card-body chart-container">
            <canvas id="taskDueDateChart"></canvas>
        </div>
    </div>

    <!-- Generate PDF Button -->
    <button type="button" class="btn btn-success w-100 mt-4" id="generatePDFButton">Generate PDF</button>
</div>

<!-- Include Chart.js and jsPDF -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
// Global variables to hold the chart objects and filtered data
let taskStatusChart, taskPriorityChart, taskDueDateChart, filteredData = null;

// Function to render the charts with the selected chart type
function renderCharts(chartType, data) {
    if (taskStatusChart) taskStatusChart.destroy();
    if (taskPriorityChart) taskPriorityChart.destroy();
    if (taskDueDateChart) taskDueDateChart.destroy();

    const ctxStatus = document.getElementById('taskStatusChart').getContext('2d');
    const ctxPriority = document.getElementById('taskPriorityChart').getContext('2d');
    const ctxDueDate = document.getElementById('taskDueDateChart').getContext('2d');

    // Shared scales configuration for bar and other chart types
    const scalesConfig = {
        x: {
            display: true,
            grid: {
                display: true
            },
            title: {
                display: true,
                text: chartType === 'bar' ? 'Categories' : ''
            }
        },
        y: {
            display: true,
            beginAtZero: true,
            grid: {
                display: true
            },
            title: {
                display: true,
                text: chartType === 'bar' ? 'Values' : ''
            }
        }
    };

    // Render Task Status Chart
taskStatusChart = new Chart(ctxStatus, {
    type: chartType, // Use the chartType variable for dynamic chart selection
    data: {
        labels: ['Not Started', 'In Progress', 'Done'],
        datasets: [{
            label: 'Task Status',
            data: [data.notStarted, data.inProgress, data.done],
            backgroundColor: ['#54a0ff', '#f6b93b', '#78e08f'],
            borderColor: '#000000',
            borderWidth: 1 // Adjust the border width for clarity if needed
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // Adjust to container size
        plugins: {
            legend: {
                display: true,
                position: 'top' // Legend position
            }
        }
    }
});

// Render Task Priority Chart
taskPriorityChart = new Chart(ctxPriority, {
    type: chartType, // Use the chartType variable for dynamic chart selection
    data: {
        labels: ['Low', 'Medium', 'High'],
        datasets: [{
            label: 'Task Priority',
            data: [data.lowPriority, data.mediumPriority, data.highPriority],
            backgroundColor: ['#ff6b6b', '#feca57', '#1dd1a1'],
            borderColor: '#000000',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        }
    }
});

// Render Task Due Date Chart
taskDueDateChart = new Chart(ctxDueDate, {
    type: chartType, // Use the chartType variable for dynamic chart selection
    data: {
        labels: ['Overdue', 'Due This Week', 'Due Today'],
        datasets: [{
            label: 'Task Due Dates',
            data: [data.overdue, data.dueThisWeek, data.dueToday],
            backgroundColor: ['#d32f2f', '#f57c00', '#388e3c'],
            borderColor: '#000000',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        }
    }
});

}

// Fetch and update chart data
function fetchFilteredData() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const chartType = document.getElementById('chartType').value;

    if (startDate && endDate) {
        fetch(`{{ route('reports.generate') }}?start_date=${startDate}&end_date=${endDate}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            filteredData = data;
            renderCharts(chartType, data);
        })
        .catch(error => console.error('Error fetching data:', error));
    } else {
        const defaultData = {
            notStarted: {{ $notStarted }},
            inProgress: {{ $inProgress }},
            done: {{ $done }},
            lowPriority: {{ $lowPriority }},
            mediumPriority: {{ $mediumPriority }},
            highPriority: {{ $highPriority }},
            overdue: {{ $overdue }},
            dueThisWeek: {{ $dueThisWeek }},
            dueToday: {{ $dueToday }}
        };
        renderCharts(chartType, defaultData);
    }
}

// Function to format date as "Month Day, Year"
function formatDateString(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString(undefined, options);
}

// Generate PDF functionality
// Generate PDF functionality
document.getElementById('generatePDFButton').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const currentDate = formatDateString(new Date().toISOString().split('T')[0]);
    let reportTitle = 'Report for All Tasks'; // Default title

    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;

    if (startDate && endDate) {
        const formattedStartDate = formatDateString(startDate);
        const formattedEndDate = formatDateString(endDate);
        reportTitle = `Report for Tasks Between ${formattedStartDate} and ${formattedEndDate}`;
    }

    const dataToUse = filteredData || {
        notStarted: {{ $notStarted }},
        inProgress: {{ $inProgress }},
        done: {{ $done }},
        lowPriority: {{ $lowPriority }},
        mediumPriority: {{ $mediumPriority }},
        highPriority: {{ $highPriority }},
        overdue: {{ $overdue }},
        dueThisWeek: {{ $dueThisWeek }},
        dueToday: {{ $dueToday }}
    };

    // Helper function to set fixed width and height for images to avoid shrinking
    function setImageDimensions(width, height) {
        return { imgWidth: width, imgHeight: height };
    }

    // Using fixed dimensions to ensure charts are proportional
    const fixedDimensions = setImageDimensions(160, 120); // Adjust as necessary for a balanced look

    const taskStatusImg = document.getElementById('taskStatusChart').toDataURL('image/png');
    const taskPriorityImg = document.getElementById('taskPriorityChart').toDataURL('image/png');
    const taskDueDateImg = document.getElementById('taskDueDateChart').toDataURL('image/png');

    doc.text(reportTitle, 20, 20);
    doc.text(`Generated on: ${currentDate}`, 20, 30);

    // Task Statuses on page 1
    doc.addImage(taskStatusImg, 'PNG', 20, 40, fixedDimensions.imgWidth, fixedDimensions.imgHeight);
    doc.text(`Not Started: ${dataToUse.notStarted} In Progress: ${dataToUse.inProgress} Done: ${dataToUse.done}`, 20, 170);

    // Task Priorities on page 2
    doc.addPage();
    doc.text('Task Priorities', 20, 20);
    doc.addImage(taskPriorityImg, 'PNG', 20, 40, fixedDimensions.imgWidth, fixedDimensions.imgHeight);
    doc.text(`Low: ${dataToUse.lowPriority} Medium: ${dataToUse.mediumPriority} High: ${dataToUse.highPriority}`, 20, 170);

    // Task Due Dates on page 3
    doc.addPage();
    doc.text('Task Due Dates', 20, 20);
    doc.addImage(taskDueDateImg, 'PNG', 20, 40, fixedDimensions.imgWidth, fixedDimensions.imgHeight);
    doc.text(`Overdue: ${dataToUse.overdue} Due This Week: ${dataToUse.dueThisWeek} Due Today: ${dataToUse.dueToday}`, 20, 170);

    doc.save('report.pdf');
});

// Initial chart rendering with default data
renderCharts('pie', {
    notStarted: {{ $notStarted }},
    inProgress: {{ $inProgress }},
    done: {{ $done }},
    lowPriority: {{ $lowPriority }},
    mediumPriority: {{ $mediumPriority }},
    highPriority: {{ $highPriority }},
    overdue: {{ $overdue }},
    dueThisWeek: {{ $dueThisWeek }},
    dueToday: {{ $dueToday }}
});

// Event listeners for date filter and chart type change
document.getElementById('filterButton').addEventListener('click', fetchFilteredData);
document.getElementById('chartType').addEventListener('change', fetchFilteredData);

// Quick Filters for "Today", "This Week", "This Month"
document.getElementById('todayFilter').addEventListener('click', function () {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('start_date').value = today;
    document.getElementById('end_date').value = today;
    fetchFilteredData();
});

document.getElementById('weekFilter').addEventListener('click', function () {
    const today = new Date();
    const firstDayOfWeek = new Date(today.setDate(today.getDate() - today.getDay()));
    const lastDayOfWeek = new Date(today.setDate(today.getDate() - today.getDay() + 6));

    document.getElementById('start_date').value = firstDayOfWeek.toISOString().split('T')[0];
    document.getElementById('end_date').value = lastDayOfWeek.toISOString().split('T')[0];
    fetchFilteredData();
});

document.getElementById('monthFilter').addEventListener('click', function () {
    const today = new Date();
    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

    document.getElementById('start_date').value = firstDayOfMonth.toISOString().split('T')[0];
    document.getElementById('end_date').value = lastDayOfMonth.toISOString().split('T')[0];
    fetchFilteredData();
});

</script>


@endsection
