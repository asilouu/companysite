<?php
session_start();
require_once "forms/db_connect.php";

// Fetch statistics from database
$sql = "SELECT * FROM site_stats WHERE id = 1";
$result = $conn->query($sql);
$stats = $result->fetch_assoc();
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

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.php" class="logo d-flex align-items-center me-auto">
        <img src="assets/pics/felta-logo (2).png" alt="logo">
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php" class="active">Home<br></a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="courses.php">Courses</a></li>
          <li><a href="trainers.php">Teachers</a></li>
          <li><a href="events.php">Events</a></li>
          <li><a href="pricing.php">FAQs</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <img src="assets/pics/index.jpg" alt="" data-aos="fade-in">

      <div class="container">
        <h2 data-aos="fade-up" data-aos-delay="100">We Modernize Education<br>Since 1966</h2>
        <p data-aos="fade-up" data-aos-delay="200">Empowering students and educators through innovative technology solutions</p>
        <div class="d-flex mt-4" data-aos="fade-up" data-aos-delay="300">
          <a href="courses.php" class="btn-get-started">Get Started</a>
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="100">
            <img src="assets/pics/4.jpg" class="img-fluid" alt="">
          </div>

          <div class="col-lg-6 order-2 order-lg-1 content" data-aos="fade-up" data-aos-delay="200">
            <h3>FELTA Techvoc Academy</h3>
            <p class="fst-italic">
            is a major educational manufacturer and distributor with over almost 39 years of experience in the audio-visual field. 
            Media products enable our youth to construct, learn and grow in fruitful and rewarding learning environments.
            </p>
            <p class="fst-italic">
            This product Catalog reflects our response to Your Needs and Wants…
            </p>
            <ul>
              <li><i class="bi bi-check-circle"></i> <span>Top Quality Products </span></li>
              <li><i class="bi bi-check-circle"></i> <span>DepEd Approved</span></li>
              <li><i class="bi bi-check-circle"></i> <span>Curriculum-based</span></li>
              <li><i class="bi bi-check-circle"></i> <span>Reliable Service</span></li>
              <li><i class="bi bi-check-circle"></i> <span>International Award Winning Bestsellers</span></li>
              <li><i class="bi bi-check-circle"></i> <span>Wide Range of Subject Areas and Levels</span></li>
            </ul>
          </div>

        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Counts Section -->
    <section id="counts" class="section counts light-background">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">
          <div class="col-lg-3 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="<?php echo htmlspecialchars($stats['students_count']); ?>" data-purecounter-duration="1" class="purecounter"></span>
              <p>Students</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="<?php echo htmlspecialchars($stats['courses_count']); ?>" data-purecounter-duration="1" class="purecounter"></span>
              <p>Courses</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="<?php echo htmlspecialchars($stats['events_count']); ?>" data-purecounter-duration="1" class="purecounter"></span>
              <p>Events</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="<?php echo htmlspecialchars($stats['trainers_count']); ?>" data-purecounter-duration="1" class="purecounter"></span>
              <p>Trainers</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Why Us Section -->
    <section id="why-us" class="section why-us">
      <style>
        #why-us .why-box .section-title,
        #why-us .why-box h3.section-title {
          font-size: 2.5rem !important;
          margin-bottom: 1rem !important;
          color: #000000 !important;
          font-weight: 700 !important;
        }
        #why-us .why-box .section-subtitle,
        #why-us .why-box p.section-subtitle {
          font-size: 1.25rem !important;
          line-height: 1.6 !important;
          max-width: 800px !important;
          margin: 0 auto 2rem !important;
          color: #000000 !important;
        }
        .custom-blue { color: #241e62; }
        .custom-blue-border { border: 1px solid rgba(36, 30, 98, 0.15); }
        .custom-blue-icon { color: #241e62; }
        .custom-blue-icon-bg { background-color: rgba(36, 30, 98, 0.1); }
        .why-box:hover { 
          transform: translateY(-5px);
          box-shadow: 0 0.5rem 1rem rgba(36, 30, 98, 0.1) !important;
        }
        .feature-title {
          font-size: 1.5rem;
          margin-bottom: 1rem;
          color: #241e62;
        }
        .feature-text {
          font-size: 1.1rem;
          line-height: 1.8;
          color: #241e62;
        }
        .core-values-list {
          text-align: left;
          padding-left: 1rem;
          color: #241e62;
        }
        .core-values-list li {
          margin-bottom: 0.75rem;
          line-height: 1.6;
        }
        .core-values-list li:last-child {
          margin-bottom: 0;
        }
        .core-values-list strong {
          color: #241e62;
        }
      </style>

      <div class="container">
        <!-- Why Choose Felta Title -->
        <div class="row mb-5">
          <div class="col-12 text-center" data-aos="fade-up">
            <div class="why-box p-5 mb-4 bg-white rounded-4 shadow-sm">
              <h3 class="section-title" style="color: #000000 !important;">Why Choose Felta?</h3>
              <p class="section-subtitle" style="color: #000000 !important;">Our goal is to enhance the quality and effectiveness of education by offering cutting-edge tools 
                and resources for learning, teaching, and academic success to bridge to the industry sector.</p>
            </div>
          </div>
        </div>

        <!-- Mission and Vision in Two Columns -->
        <div class="row gy-4 mb-5">
          <!-- Mission Column -->
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="why-box h-100 d-flex flex-column justify-content-center align-items-center text-center p-4 bg-white rounded-4 shadow-sm hover-shadow transition custom-blue-border">
              <div class="icon-wrapper mb-4 custom-blue-icon-bg p-3 rounded-circle">
                <i class="bi bi-clipboard-data fs-1 custom-blue-icon"></i>
              </div>
              <h4 class="feature-title">Mission</h4>
              <p class="feature-text">Our mission is to deliver impactful school technology products and educational support services that utilize the latest 
                technology and pedagogical innovations to enhance learning experiences for students and provide educators with the tools 
                they need to succeed in a digital world.</p>
            </div>
          </div>

          <!-- Vision Column -->
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="why-box h-100 d-flex flex-column justify-content-center align-items-center text-center p-4 bg-white rounded-4 shadow-sm hover-shadow transition custom-blue-border">
              <div class="icon-wrapper mb-4 custom-blue-icon-bg p-3 rounded-circle">
                <i class="bi bi-gem fs-1 custom-blue-icon"></i>
              </div>
              <h4 class="feature-title">Vision</h4>
              <p class="feature-text">To become the pioneer and leader in School Technology and educational support services provider in the Philippines, 
                known for excellence in tech-driven education solutions and comprehensive academic support across multiple learning platforms.</p>
            </div>
          </div>
        </div>

        <!-- Core Values Full Width -->
        <div class="row">
          <div class="col-12" data-aos="fade-up" data-aos-delay="300">
            <div class="why-box d-flex flex-column justify-content-center p-5 bg-white rounded-4 shadow-sm hover-shadow transition custom-blue-border">
              <div class="text-center mb-5">
                <div class="icon-wrapper mx-auto mb-4 custom-blue-icon-bg p-3 rounded-circle">
                  <i class="bi bi-inboxes fs-1 custom-blue-icon"></i>
                </div>
                <h4 class="feature-title">Core Values</h4>
              </div>
              <div class="row">
                <div class="col-12">
                  <ul class="core-values-list">
                    <li class="d-flex align-items-start">
                      <div class="core-value-content">
                        <strong>Innovation</strong> – Continuously advancing our school technology products and services to stay at the forefront of educational technology relevant to industry.
                      </div>
                    </li>
                    <li class="d-flex align-items-start">
                      <div class="core-value-content">
                        <strong>Excellence</strong> – Striving for the highest standards in both service and product offerings, ensuring the best outcomes for students and educators alike.
                      </div>
                    </li>
                    <li class="d-flex align-items-start">
                      <div class="core-value-content">
                        <strong>Partnership</strong> – Building strong collaborations with government partners, public and private schools, higher educational institutions, and businesses to create a holistic learning environment.
                      </div>
                    </li>
                    <li class="d-flex align-items-start">
                      <div class="core-value-content">
                        <strong>Adaptability</strong> – Addressing the unique needs of every school or learning center through customized solutions that fit diverse educational settings and goals.
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <style>
        .why-box {
          transition: all 0.3s ease;
        }
        .icon-wrapper {
          width: 80px;
          height: 80px;
          display: flex;
          align-items: center;
          justify-content: center;
        }
        .transition {
          transition: all 0.3s ease-in-out;
        }
        .core-values-list {
          list-style: none;
          padding: 0;
          margin: 0;
          color: #241e62;
        }
        .core-values-list li {
          margin-bottom: 1.5rem;
          padding: 1.25rem;
          background-color: rgba(36, 30, 98, 0.03);
          border-radius: 0.5rem;
          transition: all 0.3s ease;
        }
        .core-values-list li:hover {
          background-color: rgba(36, 30, 98, 0.06);
          transform: translateX(5px);
        }
        .core-values-list li:last-child {
          margin-bottom: 0;
        }
        .core-values-list strong {
          color: #241e62;
          font-weight: 600;
          font-size: 1.2rem;
          display: block;
          margin-bottom: 0.5rem;
        }
        .core-value-content {
          font-size: 1.1rem;
          line-height: 1.7;
        }
        @media (min-width: 992px) {
          .core-values-list li {
            padding: 1.5rem 2rem;
          }
        }
      </style>
    </section><!-- /Why Us Section -->



        </div>

      </div>

    </section><!-- /Features Section -->

    <!-- Courses Section -->
    <section id="courses" class="courses section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Courses</h2>
        <p>Popular Courses</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row">

          <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
            <div class="course-item">
              <img src="assets/pics/spikeprime.png" class="img-fluid" alt="...">
              <div class="course-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <p class="category">ICreate</p>
                </div>

                <h3><a href="course-details.php">LEGO Spike Prime</a></h3>
                <p class="description">It is an educational robotics kit designed to teach students (typically aged 10–14) about STEM 
                  (Science, Technology, Engineering, and Mathematics) concepts through hands-on learning. It combines colorful LEGO Technic 
                  elements with a programmable Hub, motors, sensors, and a drag-and-drop coding environment based on Scratch (with optional Python support).</p>

              </div>
            </div>
          </div> <!-- End Course Item-->

          <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="200">
            <div class="course-item">
              <img src="assets/pics/aisteam.jpg" class="img-fluid" alt="...">
              <div class="course-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <p class="category">ICreate </p>
                </div>

                <h3><a href="course-details.php">AISTEAM</a></h3>
                <p class="description">It is a revolutionary product that has undergone two years of polishing, the research and Development
                  of it is based on the fusion of AI and STEAM education concept, it is a three-in-one product line
                  combined artificial intelligence, robotics, and creators.
                </p>

              </div>
            </div>
          </div> <!-- End Course Item-->

          <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-lg-0" data-aos="zoom-in" data-aos-delay="300">
            <div class="course-item">
              <img src="assets/pics/milo.jpg" class="img-fluid" alt="...">
              <div class="course-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <p class="category">ICreate </p>
                </div>

                <h3><a href="course-details.php">LEGO We-Do</a></h3>
                <p class="description">We are a LEGO WeDo team of young builders and thinkers who love to learn through hands-on robotics. 
                  Using LEGO bricks, simple coding, and creativity, we build fun and functional projects that explore science, technology, and 
                  teamwork. Our goal is to imagine, create, and solve problems—one brick at a time!</p>
              </div>
            </div>
          </div> <!-- End Course Item-->

        </div>

      </div>

    </section><!-- /Courses Section -->

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
      <p>© <span>Copyright</span> <strong class="px-1 sitename">1966-2025 Felta Multi-Media Inc.</strong> <span>All Rights Reserved</span></p>
      <p class="mt-2">We Modernize Education</p>
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