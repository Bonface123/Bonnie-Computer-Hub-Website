<?php
// public/traffic-statistics.php
include_once '../includes/header.php';
include_once '../includes/config.php'; // Database connection

// Retrieve data for charting
// Example: Count of incidents by type
$query = "SELECT incident_type, COUNT(*) AS count FROM incidents GROUP BY incident_type";
$stmt = $pdo->prepare($query);
$stmt->execute();
$incident_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for JavaScript
$incident_types = [];
$incident_counts = [];
foreach ($incident_data as $row) {
    $incident_types[] = $row['incident_type'];
    $incident_counts[] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Statistics</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<main>
    <h2>Traffic Statistics</h2>
    
    <!-- Chart Container -->
    <section class="chart-container">
        <canvas id="incidentChart" width="400" height="200"></canvas>
    </section>

    <script>
        // JavaScript data for Chart.js
        const incidentTypes = <?php echo json_encode($incident_types); ?>;
        const incidentCounts = <?php echo json_encode($incident_counts); ?>;

        // Create Chart
        const ctx = document.getElementById('incidentChart').getContext('2d');
        const incidentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: incidentTypes,
                datasets: [{
                    label: 'Number of Incidents by Type',
                    data: incidentCounts,
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</main>

<?php include_once '../includes/footer.php'; ?>
</body>
</html>
