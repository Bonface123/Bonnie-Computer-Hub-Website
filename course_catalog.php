<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Course Catalog - Bonnie Computer Hub</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">BCH</div>
            <ul class="nav-links">
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="login_register.html">Login / Register</a></li>
            </ul>
            <a href="#enroll" class="cta-btn">Enroll Now</a>
        </nav>
    </header>

    <main>
        <section class="course-catalog">
            <h2>Full-Stack Web Development Program</h2>
            <p>This program is divided into three modules, each lasting 2 months. Enroll in one or all modules to master full-stack development.</p>
            
            <div class="course-grid">
                <div class="course-card">
                    <h3>Module 1: Front-End Development</h3>
                    <p>Learn essential front-end web development skills like HTML, CSS, JavaScript, and React to build dynamic, responsive websites.</p>
                    <a href="course_details.php?id=1" class="cta-btn">View Details</a>
                </div>
                
                <div class="course-card">
                    <h3>Module 2: Back-End Development</h3>
                    <p>Master server-side programming with Node.js, Express, databases (SQL & NoSQL), and authentication systems to build robust back-end applications.</p>
                    <a href="course_details.php?id=2" class="cta-btn">View Details</a>
                </div>
                
                <div class="course-card">
                    <h3>Module 3: Full-Stack Development & Deployment</h3>
                    <p>Combine front-end and back-end skills to create full-stack applications, implement real-time features, and deploy scalable web apps.</p>
                    <a href="course_details.php?id=3" class="cta-btn">View Details</a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Bonnie Computer Hub. All rights reserved.</p>
    </footer>
</body>
</html>
