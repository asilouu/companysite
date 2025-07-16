<?php
// Include the database connection
require('forms/db_connect.php');

// Define the query to fetch teachers
$query = "SELECT * FROM teachers ORDER BY id DESC";

// Fetch teachers from the database
$teachers = mysqli_query($conn, $query);

if (!$teachers) {
    // Handle any errors that might occur while querying
    die("Error fetching teachers: " . mysqli_error($conn));
}
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

  <style>
    .page-title .heading .btn-getstarted {
      background-color: #ffffff;
      color: var(--accent-color);
      border: 2px solid var(--accent-color);
    }

    .page-title .heading .btn-getstarted:hover {
      background-color: var(--accent-color);
      color: #ffffff;
    }
  </style>

</head>

<body class="trainers-page">

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
          <li><a href="trainers.php" class="active">Teachers</a></li>
          <li><a href="events.php">Events</a></li>
          <li><a href="pricing.php">FAQs</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title" data-aos="fade">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8">
              <h1>Teachers</h1>
            </div> 
              <div class="text-center">
                <a href="forms/login.php" class="btn-getstarted">Login</a>
              </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">Teachers</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

<!-- Trainers Section -->
<section id="trainers" class="section trainers">
  <div class="container">
    <div class="row gy-5">

      <?php while ($row = mysqli_fetch_assoc($teachers)) { ?>
        <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="100">
          <div class="member-img">
          <img src="forms/uploads/<?php echo $row['image']; ?>" class="img-fluid" alt="<?php echo $row['name']; ?>">
          </div>
          <div class="member-info text-center">
            <h4><?php echo $row['name']; ?></h4>
            <span><?php echo $row['subject']; ?></span>
            <p><?php echo $row['bio']; ?></p>
          </div>
        </div><!-- End Team Member -->
      <?php } ?>

    </div>
  </div>
</section><!-- /Trainers Section -->

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