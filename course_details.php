<?php
// Sample array of courses
$courses = [
    1 => [
        'title' => 'Front-End Development',
        'description' => 'This module focuses on equipping students with essential front-end web development skills and tools, helping them build dynamic and responsive websites.',
        'syllabus' => [
            'Week 1: Introduction to Web Development',
            'Week 2: HTML5 & CSS3 Basics',
            'Week 3: Advanced CSS (Flexbox, Grid)',
            'Week 4: JavaScript Basics (DOM manipulation)',
            'Week 5: JavaScript in the Browser (Fetch API)',
            'Week 6: React Basics (Components, state, JSX)',
            'Week 7: CSS Frameworks (Bootstrap, Sass)',
            'Week 8: Final Project (Multi-page website)'
        ],
        'fee' => '$300',
        'instructor' => 'Onduso Bonface',
        'testimonial' => '"This module gave me the solid front-end skills I needed to create dynamic websites." – John D.'
    ],
    2 => [
        'title' => 'Back-End Development',
        'description' => 'Learn back-end development by building APIs, managing databases, and integrating server-side logic using Node.js and Express.',
        'syllabus' => [
            'Week 1: Introduction to Server-Side Programming',
            'Week 2: Node.js Basics',
            'Week 3: Building APIs with Express.js',
            'Week 4: Database Management (SQL & NoSQL)',
            'Week 5: Authentication and Authorization',
            'Week 6: RESTful API Design',
            'Week 7: Advanced Topics (Real-time apps with WebSockets)',
            'Week 8: Final Project (Full-Stack Application)'
        ],
        'fee' => '$350',
        'instructor' => 'James Smith',
        'testimonial' => '"The back-end concepts were clearly explained, and the projects were invaluable." – Lucy W.'
    ],
    3 => [
        'title' => 'Full-Stack Development & Deployment',
        'description' => 'Learn how to create full-stack applications, handle real-time features, and deploy them to cloud environments for scalability.',
        'syllabus' => [
            'Week 1: Integrating Front-End with Back-End',
            'Week 2: Real-Time Features (WebSockets)',
            'Week 3: Full-Stack Project Setup',
            'Week 4: Cloud Deployment with Heroku & AWS',
            'Week 5: Scaling Full-Stack Applications',
            'Week 6: Advanced Database Features',
            'Week 7: Testing and Debugging Full-Stack Apps',
            'Week 8: Final Project (End-to-End Full-Stack App)'
        ],
        'fee' => '$400',
        'instructor' => 'Sarah Johnson',
        'testimonial' => '"I built my first full-stack app in this course and successfully deployed it!" – Mark T.'
    ]
];

// Get the course ID from the URL
$course_id = $_GET['id'] ?? 1; // Default to course 1 if no ID is provided

// Check if the course ID exists in the courses array
if (array_key_exists($course_id, $courses)) {
    $course = $courses[$course_id];
} else {
    // Handle the case where the course does not exist
    $course = $courses[1]; // Display default course (first course)
    echo "<p>Course not found. Displaying default course.</p>";
}

// Check if $course is set and contains the necessary information
if (!isset($course)) {
    $course = $courses[1]; // Fallback to the default course if somehow $course is not set
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($course['title']); ?> | Bonnie Computer Hub</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
  <nav class="container">
    <div class="logo">
      <a href="index.html">BONNIE COMPUTER HUB - BCH</a>
    </div>
    <ul class="nav-links">
      <li><a href="courses.html">Home</a></li>
      <li><a href="courses.html" class="active">Courses</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
    <a href="enroll.php" class="cta-btn" aria-label="Enroll Now">Enroll Now</a>
  </nav>
</header>

<section class="course-detail">
    <div class="container">
      <h2><?php echo htmlspecialchars($course['title']); ?></h2>
      <p><?php echo htmlspecialchars($course['description']); ?></p>
      
      <h3>Course Syllabus</h3>
      <ul>
        <?php if (isset($course['syllabus']) && is_array($course['syllabus'])): ?>
          <?php foreach($course['syllabus'] as $week): ?>
            <li><?php echo htmlspecialchars($week); ?></li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>No syllabus available.</li>
        <?php endif; ?>
      </ul>

      <h3>Course Fee: <?php echo htmlspecialchars($course['fee']); ?></h3>
      <a href="enroll.php?course=<?php echo $course_id; ?>" class="cta-btn">Enroll Now</a>

      <h3>Instructor: <?php echo htmlspecialchars($course['instructor']); ?></h3>
      <p><?php echo htmlspecialchars($course['testimonial']); ?></p>
    </div>
</section>

<footer>
    <div class="container">
      <p>&copy; 2024 Bonnie Computer Hub. All Rights Reserved.</p>
    </div>
</footer>
</body>
</html>
