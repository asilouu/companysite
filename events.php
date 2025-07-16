<?php
session_start();
require('forms/db_connect.php');

// Fetch events with their photos
$query = "SELECT e.*, GROUP_CONCAT(ep.photo_name SEPARATOR ',') as photos 
          FROM events e 
          LEFT JOIN event_photos ep ON e.event_id = ep.event_id 
          GROUP BY e.event_id, e.title, e.date, e.description, e.image 
          ORDER BY e.date DESC";
$events_result = mysqli_query($conn, $query);

if (!$events_result) {
    die("Error fetching events: " . mysqli_error($conn));
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
    .modal-gallery {
      display: none;
      position: fixed;
      z-index: 9999;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.9);
      padding: 20px;
      overflow-y: auto;
    }

    .modal-content {
      margin: auto;
      display: block;
      max-width: 90%;
      max-height: 80vh;
      margin-top: 2vh;
      border-radius: 25px;
    }

    .modal-close {
      position: fixed;
      top: 15px;
      right: 35px;
      color: #f1f1f1;
      font-size: 40px;
      font-weight: bold;
      cursor: pointer;
      z-index: 10000;
    }

    .event-card {
      cursor: pointer;
      transition: transform 0.3s ease;
    }

    .event-card:hover {
      transform: translateY(-5px);
    }

    .gallery-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 15px;
      padding: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .gallery-item {
      position: relative;
      aspect-ratio: 1;
      overflow: hidden;
      border-radius: 15px;
      cursor: pointer;
    }

    .gallery-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .gallery-item:hover img {
      transform: scale(1.05);
    }

    .fullscreen-image {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.9);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 10001;
    }

    .fullscreen-image img {
      max-width: 90%;
      max-height: 90vh;
      object-fit: contain;
      border-radius: 15px;
    }

    .gallery-nav {
      position: fixed;
      top: 50%;
      transform: translateY(-50%);
      width: 100%;
      display: flex;
      justify-content: space-between;
      padding: 0 20px;
      z-index: 10002;
    }

    .gallery-nav button {
      background: rgba(255, 255, 255, 0.2);
      border: none;
      color: white;
      padding: 10px 20px;
      cursor: pointer;
      border-radius: 25px;
    }

    .gallery-nav button:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    .gallery-title {
      color: white;
      text-align: center;
      font-size: 24px;
      margin-bottom: 20px;
      padding-top: 20px;
    }

    @media (max-width: 1200px) {
      .gallery-grid {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    @media (max-width: 768px) {
      .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 480px) {
      .gallery-grid {
        grid-template-columns: 1fr;
      }
    }

    /* Add these new styles for event spacing */
    #events .row {
        margin-bottom: 30px; /* Add space between rows */
    }

    #events .col-md-6 {
        margin-bottom: 30px; /* Add space between columns */
    }

    #events .card {
        height: 100%;
        margin-bottom: 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 20px;
        overflow: hidden;
    }

    #events .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    #events .card-img {
        height: 300px;
        overflow: hidden;
        border-radius: 20px 20px 0 0;
    }

    #events .card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    #events .card:hover .card-img img {
        transform: scale(1.05);
    }

    #events .card-body {
        padding: 25px;
        border-radius: 0 0 20px 20px;
    }

    #events .card-title {
        margin-bottom: 15px;
        font-size: 1.4rem;
        font-weight: 600;
    }

    #events .fst-italic {
        color: #666;
        margin-bottom: 15px;
    }

    #events .card-text {
        color: #555;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        #events .col-md-6 {
            margin-bottom: 20px; /* Slightly less space on mobile */
        }
    }
  </style>
</head>

<body class="events-page">

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
          <li><a href="events.php" class="active">Events</a></li>
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
              <h1>Events</h1>
              
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">Events</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

<!-- Events Section -->
<section id="events" class="events section">
  <div class="container" data-aos="fade-up">
    <div class="row">
      <?php while ($event = mysqli_fetch_assoc($events_result)) { ?>
        <div class="col-md-6 d-flex align-items-stretch">
          <div class="card event-card" onclick="openGallery(<?php echo htmlspecialchars(json_encode($event)); ?>)">
            <div class="card-img">
              <img src="forms/uploads/<?php echo $event['image']; ?>" alt="Event Image" style="object-fit: cover; width: 100%; height: 400px;">
            </div>
            <div class="card-body">
              <h5 class="card-title"><a href="javascript:void(0)"><?php echo $event['title']; ?></a></h5>
              <p class="fst-italic text-center"><?php echo date('l, F j, Y', strtotime($event['date'])); ?></p>
              <p class="card-text"><?php echo $event['description']; ?></p>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</section><!-- /Events Section -->

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

  <!-- Modal Gallery -->
  <div id="galleryModal" class="modal-gallery">
    <span class="modal-close" onclick="closeGallery()">&times;</span>
    <div class="gallery-title" id="galleryTitle"></div>
    <div class="gallery-grid" id="galleryGrid"></div>
  </div>

  <!-- Fullscreen Image View -->
  <div class="fullscreen-image" id="fullscreenView">
    <span class="modal-close" onclick="closeFullscreen()">&times;</span>
    <div class="gallery-nav">
      <button onclick="prevFullscreenImage()">❮</button>
      <button onclick="nextFullscreenImage()">❯</button>
    </div>
    <img id="fullscreenImage" src="" alt="Full size image">
  </div>

  <script>
    let currentEvent = null;
    let currentImageIndex = 0;
    let eventImages = [];

    function openGallery(event) {
        currentEvent = event;
        // Get all images including main image and photos
        eventImages = [event.image]; // Start with main image
        if (event.photos) {
            eventImages = eventImages.concat(event.photos.split(','));
        }
        currentImageIndex = 0;
        
        const modal = document.getElementById('galleryModal');
        const galleryGrid = document.getElementById('galleryGrid');
        const galleryTitle = document.getElementById('galleryTitle');
        
        modal.style.display = "block";
        galleryTitle.innerHTML = event.title;
        
        // Clear and populate the gallery grid
        galleryGrid.innerHTML = '';
        eventImages.forEach((image, index) => {
            if (image) { // Only add if image name exists
                const item = document.createElement('div');
                item.className = 'gallery-item';
                item.innerHTML = `<img src="forms/uploads/${image}" alt="Event image ${index + 1}" onclick="openFullscreen(${index})">`;
                galleryGrid.appendChild(item);
            }
        });
    }

    function closeGallery() {
        document.getElementById('galleryModal').style.display = "none";
        closeFullscreen();
    }

    function openFullscreen(index) {
        currentImageIndex = index;
        const fullscreenView = document.getElementById('fullscreenView');
        const fullscreenImage = document.getElementById('fullscreenImage');
        
        fullscreenView.style.display = "flex";
        fullscreenImage.src = `forms/uploads/${eventImages[index]}`;
    }

    function closeFullscreen() {
        document.getElementById('fullscreenView').style.display = "none";
    }

    function prevFullscreenImage() {
        if (currentImageIndex > 0) {
            currentImageIndex--;
            updateFullscreenImage();
        }
    }

    function nextFullscreenImage() {
        if (currentImageIndex < eventImages.length - 1) {
            currentImageIndex++;
            updateFullscreenImage();
        }
    }

    function updateFullscreenImage() {
        const fullscreenImage = document.getElementById('fullscreenImage');
        fullscreenImage.src = `forms/uploads/${eventImages[currentImageIndex]}`;
    }

    // Close modal when clicking outside the image
    window.onclick = function(event) {
        const modal = document.getElementById('galleryModal');
        const fullscreenView = document.getElementById('fullscreenView');
        if (event.target == modal) {
            closeGallery();
        } else if (event.target == fullscreenView) {
            closeFullscreen();
        }
    }

    // Close modal with escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeGallery();
        } else if (event.key === 'ArrowLeft') {
            prevFullscreenImage();
        } else if (event.key === 'ArrowRight') {
            nextFullscreenImage();
        }
    });
  </script>

</body>

</html>