<?php
// public/index.php
include_once '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Management System</title>
    <link rel="stylesheet" href="../assets/css/styles.css"> <!-- Link to external CSS file -->
    <style>
        /* General Styles */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        /* Hero Section */
        .hero {
            position: relative;
            background: url('../assets/images/image.png') no-repeat center center/cover;
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-align: center;
            border-bottom: 4px solid #007bff; /* Adding a border for theme alignment */
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6); /* Dark overlay for text readability */
        }

        .hero-content {
            position: relative;
            max-width: 700px;
            padding: 20px;
            z-index: 2;
        }

        .hero-content h1 {
            font-size: 2.8em;
            font-weight: 700;
            line-height: 1.2;
            margin: 0 0 15px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
        }

        .hero-content p {
            font-size: 1.2em;
            line-height: 1.5;
            margin: 10px 0 20px;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        /* Button Styles */
        .btn-primary, .btn-secondary {
            padding: 12px 24px;
            font-size: 1em;
            font-weight: 600;
            color: #fff;
            text-decoration: none;
            border-radius: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: #007bff; /* Blue for primary actions */
        }

        .btn-secondary {
            background-color: #28a745; /* Green for secondary actions */
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.3);
        }

        .btn-secondary:hover {
            background-color: #1e7e34; /* Darker green on hover */
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.2em;
            }

            .hero-content p {
                font-size: 1em;
            }

            .btn-primary, .btn-secondary {
                padding: 10px 20px;
                font-size: 0.9em;
            }
        }

        /* About Section */
        .about {
            padding: 60px 20px;
            background-color: #f8f9fa; /* Light background for contrast */
            text-align: center;
        }

        .about-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .about h2 {
            font-size: 2.4em;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
            position: relative;
        }

        .about h2::after {
            content: "";
            display: block;
            width: 60px;
            height: 3px;
            background-color: #007bff;
            margin: 8px auto;
            border-radius: 3px;
        }

        .about p {
            font-size: 1.1em;
            color: #555;
            line-height: 1.6;
            margin-top: 10px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .about h2 {
                font-size: 2em;
            }

            .about p {
                font-size: 1em;
            }
        }

        /* Services Section */
        .services {
            padding: 60px 20px;
            background-color: #fff; /* White background for clarity */
            text-align: center;
        }

        .services h2 {
            font-size: 2.4em;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .service-cards {
            display: flex;
            justify-content: center;
            gap: 20px; /* Space between cards */
            flex-wrap: wrap; /* Wrap cards on smaller screens */
        }

        .card {
            background-color: #007bff; /* Blue background for the cards */
            color: #fff; /* White text color */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s; /* Smooth transition for hover effect */
            width: 300px; /* Fixed width for uniformity */
            text-align: left; /* Align text to the left for better readability */
        }

        .card:hover {
            transform: translateY(-5px); /* Lift effect on hover */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); /* Enhanced shadow on hover */
        }

        .card h3 {
            font-size: 1.6em;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .card p {
            font-size: 1em;
            line-height: 1.5;
            margin-bottom: 15px; /* Space below paragraph */
        }

        /* Button Styles */
        .btn-primary {
            display: inline-block; /* Make it a block element */
            padding: 10px 15px;
            background-color: #28a745; /* Green background for buttons */
            color: #fff; /* White text */
            border-radius: 5px;
            text-decoration: none; /* Remove underline */
            transition: background-color 0.3s, transform 0.3s; /* Smooth transition */
        }

        .btn-primary:hover {
            background-color: #218838; /* Darker green on hover */
            transform: translateY(-2px); /* Lift effect on hover */
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .services h2 {
                font-size: 2em;
            }

            .card {
                width: 90%; /* Adjust card width for smaller screens */
            }
        }

        /* Statistics Preview Section */
        .statistics-preview {
            padding: 60px 20px;
            background-color: #f8f9fa; /* Light background for contrast */
            text-align: center;
        }

        .statistics-preview h2 {
            font-size: 2.4em;
            color: #333;
            margin-bottom: 40px;
            font-weight: 600;
        }

        .statistics-cards {
            display: flex;
            justify-content: center;
            gap: 20px; /* Space between cards */
            flex-wrap: wrap; /* Wrap cards on smaller screens */
        }

        .stat-card {
            background-color: #007bff; /* Blue background for the cards */
            color: #fff; /* White text color */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s; /* Smooth transition for hover effect */
            width: 300px; /* Fixed width for uniformity */
            text-align: left; /* Align text to the left for better readability */
        }

        .stat-card:hover {
            transform: translateY(-5px); /* Lift effect on hover */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); /* Enhanced shadow on hover */
        }

        .stat-card h3 {
            font-size: 1.6em;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .stat-card p {
            font-size: 1em;
            line-height: 1.5;
            margin-bottom: 15px; /* Space below paragraph */
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .statistics-preview h2 {
                font-size: 2em;
            }

            .stat-card {
                width: 90%; /* Adjust card width for smaller screens */
            }
        }

        /* Footer Section */
        footer {
            background-color: #007bff; /* Blue background for footer */
            color: #fff; /* White text color */
            text-align: center;
            padding: 20px 0;
            margin-top: auto; /* Push footer to the bottom */
        }

        footer p {
            margin: 0;
            font-size: 1em;
        }

    </style>
</head>
<body>
    <header>
        <!-- Navigation -->
        <nav>
            <ul>
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#statistics">Statistics</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
        <h1>Welcome to the Traffic Management System (TMS)</h1>
            <p>Stay informed with real-time traffic reports, report incidents instantly, and explore comprehensive traffic statistics.</p>
            <div class="hero-buttons">
                <a href="traffic-reports.php" class="btn-primary">View Traffic Reports</a>
                <a href="incident-report.php" class="btn-secondary">Report an Incident</a>
            </div>
        </div>
    </section>

    <section id="about" class="about">
        <div class="about-content">
            <h2>About Us</h2>
            <p>We are dedicated to improving traffic management systems to enhance safety, efficiency, and reliability on our roads. Our solutions leverage cutting-edge technology to streamline operations and improve user experiences.</p>
        </div>
    </section>

    <section id="services" class="services">
        <h2>Our Services</h2>
        <div class="service-cards">
            <div class="card">
                <h3>Real-Time Monitoring</h3>
                <p>Stay updated with real-time traffic conditions and alerts.</p>
                <a href="#" class="btn-primary">Read More</a>
            </div>
            <div class="card">
                <h3>Data Analysis</h3>
                <p>Utilize data-driven insights to enhance traffic flow.</p>
                <a href="#" class="btn-primary">Read More</a>
            </div>
            <div class="card">
                <h3>Emergency Services Coordination</h3>
                <p>Improve response times and coordination for emergencies.</p>
                <a href="#" class="btn-primary">Read More</a>
            </div>
        </div>
    </section>

    

    <section id="statistics" class="statistics-preview">
        <h2>Traffic Management Statistics</h2>
        <div class="statistics-cards">
            <div class="stat-card">
                <h3>Incidents Managed</h3>
                <p>Over 1,200 incidents managed this year.</p>
            </div>
            <div class="stat-card">
                <h3>Users Served</h3>
                <p>Serving 100,000+ users daily.</p>
            </div>
            <div class="stat-card">
                <h3>Response Time</h3>
                <p>Average response time of 5 minutes.</p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Traffic Management System. All rights reserved.</p>
    </footer>
</body>
</html>
