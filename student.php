<?php
session_start();
include 'forms/db_connect.php';

// Check if user is logged in and is a student
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: forms/login.php");
    exit();
}

// Get student information
$student_id = $_SESSION['user_id'];
$student_query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($student_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Check if student data exists
if (!$student) {
    // Redirect to login if student data not found
    session_destroy();
    header("Location: forms/login.php");
    exit();
}

// Get student progress
$progress_query = "SELECT * FROM student_progress WHERE student_id = ?";
$stmt = $conn->prepare($progress_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$progress = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Felta Techvoc Academy</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/pics/felta-logo (2).png" rel="icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

</head>

<body class="pricing-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.php" class="logo d-flex align-items-center me-auto">
        <img src="assets/pics/felta-logo (2).png" alt="">
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php">Home<br></a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="courses.php">Courses</a></li>
          <li><a href="trainers.php">Teachers</a></li>
          <li><a href="events.php">Events</a></li>
          <li><a href="pricing.php" >FAQs</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="student.php" class="active">Student Progress</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="forms/logout.php">Logout</a>

    </div>
  </header>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title" data-aos="fade">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8">
              <h3><span>Student </span> Progress</h3>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">Student Progress</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <div class="faq_area section_padding_130" id="faq">
      <div class="container">
          <div class="row justify-content-center">
              <!-- FAQ Area-->
              <div class="profile-container">
                  <div class="profile-header">
                      <div class="profile-details">
                          <h2><?php echo htmlspecialchars($student['name']); ?></h2>
                          <p>ID: <?php echo htmlspecialchars($student['username']); ?></p>
                          <p>Email: <?php echo htmlspecialchars($student['email']); ?></p>
                      </div>
                  </div>

                    <h3>Course Progress</h3>
                    <?php if ($progress): ?>
                  <div class="task">
                      <div class="task-title">Current Progress</div>
                      <div class="progress-bar-container">
                          <div class="progress-bar" style="width: <?php echo ($progress['hours_completed'] / $progress['total_hours']) * 100; ?>%;">
                              <?php echo $progress['hours_completed']; ?>h
                          </div>
                      </div>
                      <div class="hours-label"><?php echo $progress['hours_completed']; ?> out of <?php echo $progress['total_hours']; ?> hours</div>
                      
                      <?php if ($progress['description']): ?>
                      <div class="timeline">
                          <?php 
                          $descriptions = explode("\n", $progress['description']);
                          foreach ($descriptions as $desc): 
                          ?>
                          <li><?php echo htmlspecialchars($desc); ?></li>
                          <?php endforeach; ?>
                      </div>
                      <?php endif; ?>
                  </div>
                  <?php else: ?>
                  <div class="alert alert-info">
                      No progress records found. Please contact your administrator.
                  </div>
                  <?php endif; ?>
              </div>
          </div>
      </div>
  </div>

        </div>

      </div>

    </section><!-- /Pricing Section -->

  </main>

  <footer id="footer" class="footer position-relative light-background">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.php" class="logo d-flex align-items-center">
            <span class="sitename">Felta TechVoc Academy</span>
          </a>
          <div class="footer-contact pt-3">
            <p>18 Notre Dame St., Cubao,</p>
            <p>Quezon City, Philippines</p>
            <p class="mt-3"><i class="bi bi-telephone-fill me-2 text-dark"></i><span> 02-79035379</span></p>
            <p><i class="bi bi-envelope-fill me-2 text-dark"></i><span> feltamultimediainc@gmail.com</span></p>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 footer-links">
          <div class="social-links mt-3 d-flex justify-content-center gap-3">
            <a href="https://www.facebook.com/feltamultimedia/" target="_blank" class="facebook"><i class="bi bi-facebook"></i></a>
            <a href="https://www.youtube.com/@feltamultimediainc2870" target="_blank" class="youtube"><i class="bi bi-youtube"></i></a>
            <a href="https://www.linkedin.com/company/felta-multi-media-inc/" target="_blank" class="linkedin"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-md-12 footer-newsletter">
          <h4>Our Newsletter</h4>
          <p>Subscribe to our newsletter and receive the latest news about our products and services!</p>
          <form action="forms/newsletter.php" method="post" class="php-email-form">
            <div class="newsletter-form"><input type="email" name="email"><input type="submit" value="Subscribe"></div>
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Your subscription request has been sent. Thank you!</div>
          </form>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">2010-2025 Felta Multimedia Inc.</strong> <span>All Rights Reserved</span></p>
    </div>

  </footer>

  <style>

    /* Student Section Styles */
    .profile-container {
      background-color: #ffffff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      margin-top: 40px;
      margin-bottom: 40px;
    }

    .profile-header {
      display: flex;
      align-items: center;
      margin-bottom: 35px;
      border-bottom: 2px solid #f0f0f0;
      padding-bottom: 25px;
    }

    .profile-details h2 {
      font-size: 28px;
      margin: 0 0 8px;
      color: #241e62;
      font-weight: 600;
    }

    .profile-details p {
      margin: 5px 0;
      color: #666;
      font-size: 15px;
    }

    .task {
      margin-bottom: 40px;
      background: #f8f9fa;
      padding: 25px;
      border-radius: 12px;
      transition: transform 0.2s ease;
    }

    .task:hover {
      transform: translateY(-2px);
    }

    .task-title {
      font-size: 22px;
      font-weight: 600;
      color: #241e62;
      margin-bottom: 15px;
    }

    .progress-bar-container {
      background-color: #e9ecef;
      border-radius: 25px;
      overflow: hidden;
      height: 25px;
      margin-bottom: 10px;
      box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .progress-bar {
      background-color: #241e62;
      height: 100%;
      line-height: 25px;
      color: #fff;
      text-align: center;
      font-weight: 600;
      transition: width 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .progress-bar::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(
        45deg,
        rgba(255, 255, 255, 0.1) 25%,
        transparent 25%,
        transparent 50%,
        rgba(255, 255, 255, 0.1) 50%,
        rgba(255, 255, 255, 0.1) 75%,
        transparent 75%,
        transparent
      );
      background-size: 30px 30px;
      animation: progress-animation 2s linear infinite;
    }

    @keyframes progress-animation {
      0% {
        background-position: 0 0;
      }
      100% {
        background-position: 30px 0;
      }
    }

    .hours-label {
      font-size: 14px;
      color: #666;
      margin-bottom: 15px;
      font-weight: 500;
    }

    .timeline {
      list-style-type: none;
      padding: 0;
      margin: 20px 0;
    }

    .timeline li {
      padding: 12px 0;
      border-bottom: 1px solid #eee;
      color: #555;
      position: relative;
      padding-left: 25px;
    }

    .timeline li:before {
      content: '•';
      color: #241e62;
      font-size: 20px;
      position: absolute;
      left: 0;
      top: 8px;
    }

    .timeline li:last-child {
      border-bottom: none;
    }

    .alert-info {
      background-color: #e8f4f8;
      border-color: #b8e2f2;
      color: #0c5460;
      padding: 20px;
      border-radius: 10px;
      margin-top: 20px;
    }
  </style>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>



</body>

</html>