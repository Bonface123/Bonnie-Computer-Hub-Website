<?php
// Sample array of courses
$courses = [
    1 => [
        'title' => 'Front-End Development',
        'description' => 'is module aims to build a solid foundation in HTML and CSS, equipping students with practical skills to create professional, responsive websites.',
        'syllabus' => [
            'Week 1: Introduction to Web Development & HTML Basics',
            'Week 2: Structuring Pages with HTML5',
            'Week 3: CSS Fundamentals',
            'Week 4: Advanced CSS (Flexbox, Grid)',
            'Week 5:  Responsive Design and Media Queries',
            'Week 6: Advanced Styling (Animations, Custom Fonts)',
            'Week 7:CSS Frameworks (Bootstrap)',
            'Week 8: Final Project (Multi-page website)'
        ],
        'fee' => 'Ksh.2500',
        'instructor' => 'Onduso Bonface',
        'testimonial' => '"This module gave me the solid front-end skills I needed to create dynamic websites." – Dennis Langat.'
    ],
    2 => [
        'title' => 'Back-End Development',
        'description' => 'Learn back-end development by building APIs, managing databases, and integrating server-side logic using Node.js and Express.',
        'syllabus' => [
            'Week 1: Introduction to Server-Side Programming',
            'Week 2: PHP Basics',
            'Week 3: Building APIs with PHP',
            'Week 4: Database Management (MySQL)',
            'Week 5: Authentication and Authorization',
            'Week 6: RESTful API Design',
            'Week 7: dvanced Topics (MVC Frameworks, Error Handling)',
            'Week 8: Final Project (Full-Stack Application)'
        ],
        'fee' => 'Ksh.2500',
        'instructor' => 'Paul Ruoya',
        'testimonial' => '"The back-end concepts were clearly explained, and the projects were invaluable." – Lucy Shirley.'
    ],
    3 => [
        'title' => 'Full-Stack Development & Deployment',
        'description' => 'Learn how to create full-stack applications, handle real-time features, and deploy them to cloud environments for scalability.',
        'syllabus' => [
            'Week 1: Overview of Full-Stack Development',
            'Week 2: Connecting Front-End and Back-End',
            'Week 3: Data Handling',
            'Week 4:  User Authentication',
            'Week 5: Session Management',
            'Week 6: Building a Simple CRUD Application',
            'Week 7:  Deployment Basics',
            'Week 8: Final Project (End-to-End Full-Stack App)'
        ],
        'fee' => 'Ksh. 3500',
        'instructor' => 'Manasseh Njoroge',
        'testimonial' => '"I built my first full-stack app in this course and successfully deployed it!" – Comfort Mwanga.'
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
      <p > <b>Testimonial:</b> <?php echo htmlspecialchars($course['testimonial']); ?></p>
    </div>
</section>

<footer>
    <div class="container">
      <p>&copy; 2024 Bonnie Computer Hub. All Rights Reserved.</p>
    </div>
</footer>
</body>
</html>
