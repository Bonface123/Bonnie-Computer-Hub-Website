<?php
// public/traffic-reports.php
include_once '../includes/header.php';
include_once '../includes/config.php'; // Database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Reports</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        /* Reset some basic styles */
body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

/* Main container */
main {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Heading styles */
h2 {
    text-align: center;
    color: #333;
}

/* Filter form styles */
.filter-form {
    display: flex;
    flex-direction: column;
    gap: 15px; /* Space between form elements */
    margin-bottom: 20px; /* Space below form */
}

.filter-form label {
    font-weight: bold;
    color: #555;
}

.filter-form input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

.filter-form button {
    padding: 10px;
    border: none;
    border-radius: 4px;
    background-color: #28a745; /* Green background */
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.filter-form button:hover {
    background-color: #218838; /* Darker green on hover */
}

.filter-form button[type="reset"] {
    background-color: #dc3545; /* Red background for clear button */
}

.filter-form button[type="reset"]:hover {
    background-color: #c82333; /* Darker red on hover */
}

/* Traffic report section styles */
.traffic-reports {
    display: flex;
    flex-direction: column;
    gap: 15px; /* Space between reports */
}

.report {
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background-color: #f9f9f9;
    transition: box-shadow 0.2s ease;
}

.report:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Shadow effect on hover */
}

.report h3 {
    margin: 0 0 10px; /* Space below report title */
    color: #333;
}

.report p {
    margin: 5px 0; /* Space between paragraphs */
    color: #666;
}

/* Responsive styles */
@media (max-width: 600px) {
    .filter-form {
        flex-direction: column; /* Stack form elements on small screens */
    }

    .filter-form input,
    .filter-form button {
        width: 100%; /* Full width on small screens */
    }

    .report {
        padding: 10px; /* Less padding on small screens */
    }
}

    </style>
</head>
<body>
<main>
    <h2>Current Traffic Reports</h2>
    
    <!-- Filter Options -->
    <form method="GET" action="traffic-reports.php" class="filter-form">
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" placeholder="Enter location">
        
        <label for="time">Time:</label>
        <input type="datetime-local" id="time" name="time" placeholder="YYYY-MM-DD HH:MM">
        
        <button type="submit">Filter</button>
        <button type="reset" onclick="window.location.href='traffic-reports.php'">Clear Filters</button>
    </form>

    <!-- Display Traffic Reports -->
    <section class="traffic-reports">
        <?php
        // Get filter values from the form
        $location = isset($_GET['location']) ? $_GET['location'] : '';
        $time = isset($_GET['time']) ? $_GET['time'] : '';

        // SQL query to retrieve traffic reports with optional filters
        $query = "SELECT * FROM traffic_reports WHERE 1=1";
        if (!empty($location)) {
            $query .= " AND location LIKE :location";
        }
        if (!empty($time)) {
            $query .= " AND report_time >= :time";
        }
        $stmt = $pdo->prepare($query);

        // Bind parameters based on filters
        if (!empty($location)) {
            $stmt->bindValue(':location', '%' . $location . '%');
        }
        if (!empty($time)) {
            $stmt->bindValue(':time', $time);
        }
        
        // Execute the query and fetch results
        try {
            $stmt->execute();
            $reports = $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "<p>Error retrieving reports: " . htmlspecialchars($e->getMessage()) . "</p>";
        }

        // Display each traffic report
        if ($reports) {
            foreach ($reports as $report) {
                echo "<div class='report'>";
                echo "<h3>Location: " . htmlspecialchars($report['location']) . "</h3>";
                echo "<p>Congestion Level: " . htmlspecialchars($report['congestion_level']) . "</p>";
                echo "<p>Road Condition: " . htmlspecialchars($report['road_condition']) . "</p>";
                echo "<p>Reported at: " . htmlspecialchars($report['report_time']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No traffic reports available for the selected filters.</p>";
        }
        ?>
    </section>
</main>

<?php include_once '../includes/footer.php'; ?>
</body>
</html>
