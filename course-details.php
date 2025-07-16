<?php
session_start();
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
          <li><a href="pricing.php" class="active">FAQs</a></li>
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
              <h3><span>Frequently </span> Asked Questions</h3>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">FAQs</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <div class="faq_area section_padding_130" id="faq">
      <div class="container">
          <div class="row justify-content-center">
              <!-- iCreate Cafe FAQ Section -->
              <div class="col-12 col-sm-10 col-lg-8 mb-5">
                  <h2 class="text-center mb-4">iCreate Cafe FAQs</h2>
                  <div class="accordion faq-accordian" id="icreateFaqAccordion">
                    <!-- FAQ 1 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="card-header" id="icreateHeadingOne">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#icreateCollapseOne" aria-expanded="false" aria-controls="icreateCollapseOne">
                            1. What is iCreate Cafe Manila?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="icreateCollapseOne" aria-labelledby="icreateHeadingOne" data-bs-parent="#icreateFaqAccordion">
                            <div class="card-body">
                                <p>iCreate Cafe Manila is a Robotics Training Center that aims to promote Robotics for all. We are committed to making robotics education accessible to everyone, helping to inspire future innovators through hands-on learning and creative exploration.</p>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 2 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.15s">
                        <div class="card-header" id="icreateHeadingTwo">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#icreateCollapseTwo" aria-expanded="false" aria-controls="icreateCollapseTwo">
                            2. What age group is eligible for the robotics classes?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="icreateCollapseTwo" aria-labelledby="icreateHeadingTwo" data-bs-parent="#icreateFaqAccordion">
                            <div class="card-body">
                                <p>Our robotics classes are open for children aged <strong>5 years old and above</strong>.</p>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 3 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="card-header" id="icreateHeadingThree">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#icreateCollapseThree" aria-expanded="false" aria-controls="icreateCollapseThree">
                            3. What are the prices for the classes?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="icreateCollapseThree" aria-labelledby="icreateHeadingThree" data-bs-parent="#icreateFaqAccordion">
                            <div class="card-body">
                                <ul>
                                  <li><strong>Membership Package:</strong> ₱3,500 for 10 hours of consumable class time</li>
                                  <li><strong>Non-Member Rate:</strong> ₱500 per hour</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 4 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.25s">
                        <div class="card-header" id="icreateHeadingFour">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#icreateCollapseFour" aria-expanded="false" aria-controls="icreateCollapseFour">
                            4. How are the classes conducted?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="icreateCollapseFour" aria-labelledby="icreateHeadingFour" data-bs-parent="#icreateFaqAccordion">
                            <div class="card-body">
                                <p>Each class is a <strong>one-on-one session</strong>, allowing personalized instruction for each student.</p>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 5 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="card-header" id="icreateHeadingFive">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#icreateCollapseFive" aria-expanded="false" aria-controls="icreateCollapseFive">
                            5. What is the class schedule?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="icreateCollapseFive" aria-labelledby="icreateHeadingFive" data-bs-parent="#icreateFaqAccordion">
                            <div class="card-body">
                                <p>Classes are strictly by booking. Bookings must be made at least <strong>3 days prior</strong> to your desired schedule.</p>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 6 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.35s">
                        <div class="card-header" id="icreateHeadingSix">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#icreateCollapseSix" aria-expanded="false" aria-controls="icreateCollapseSix">
                            6. How can I make a booking?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="icreateCollapseSix" aria-labelledby="icreateHeadingSix" data-bs-parent="#icreateFaqAccordion">
                            <div class="card-body">
                                <p>You can book a class in any of the following ways:</p>
                                <ul>
                                  <li>Send us a DM via our <a href="https://www.facebook.com/icreatecafemanila" target="_blank">iCreate Cafe Manila Facebook Page</a></li>
                                  <li>Call us at <strong>02-79035379</strong></li>
                                  <li>Viber us at <strong>09563709654</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 7 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.4s">
                        <div class="card-header" id="icreateHeadingSeven">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#icreateCollapseSeven" aria-expanded="false" aria-controls="icreateCollapseSeven">
                            7. Where are we located?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="icreateCollapseSeven" aria-labelledby="icreateHeadingSeven" data-bs-parent="#icreateFaqAccordion">
                            <div class="card-body">
                                <p><strong>Location:</strong> Felta Multi-Media Inc. Center</p>
                                <p><strong>Address:</strong> #18 Notre Dame St., Brgy Silangan, Quezon City</p>
                                <p><a href="https://bit.ly/4eynar4" target="_blank">Waze Link</a></p>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 8 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.45s">
                        <div class="card-header" id="icreateHeadingEight">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#icreateCollapseEight" aria-expanded="false" aria-controls="icreateCollapseEight">
                            8. Can I change or cancel my booking?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="icreateCollapseEight" aria-labelledby="icreateHeadingEight" data-bs-parent="#icreateFaqAccordion">
                            <div class="card-body">
                                <p>Cancellations or rescheduling must be done at least <strong>24 hours prior</strong> to the scheduled class to avoid any penalties.</p>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 9 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="card-header" id="icreateHeadingNine">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#icreateCollapseNine" aria-expanded="false" aria-controls="icreateCollapseNine">
                            9. What are the operations details?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="icreateCollapseNine" aria-labelledby="icreateHeadingNine" data-bs-parent="#icreateFaqAccordion">
                            <div class="card-body">
                                <p><strong>Operating Days:</strong></p>
                                <ul>
                                  <li>Weekdays only (Monday to Friday)</li>
                                  <li>10:00 am - 5:00 pm</li>
                                  <li>No classes on weekends</li>
                                  <li>No classes during holidays</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 10 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.55s">
                        <div class="card-header" id="icreateHeadingTen">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#icreateCollapseTen" aria-expanded="false" aria-controls="icreateCollapseTen">
                            10. What are the payment details?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="icreateCollapseTen" aria-labelledby="icreateHeadingTen" data-bs-parent="#icreateFaqAccordion">
                            <div class="card-body">
                                <p><strong>Mode of Payment:</strong> Pay Online</p>
                                <ul>
                                  <li><strong>Bank:</strong> BDO Unibank</li>
                                  <li><strong>Account Name:</strong> Felta Techvoc Academy Inc.</li>
                                  <li><strong>Account No.:</strong> 00-282-018-6595</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                  </div>
              </div>

              <!-- Felta Techvoc Academy FAQ Section -->
              <div class="col-12 col-sm-10 col-lg-8">
                  <h2 class="text-center mb-4">Felta Techvoc Academy FAQs</h2>
                  <div class="accordion faq-accordian" id="feltaFaqAccordion">
                    <!-- FAQ 1 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="card-header" id="feltaHeadingOne">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#feltaCollapseOne" aria-expanded="false" aria-controls="feltaCollapseOne">
                            1. Who can enroll in this Robotics Technology Level II program?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="feltaCollapseOne" aria-labelledby="feltaHeadingOne" data-bs-parent="#feltaFaqAccordion">
                            <div class="card-body">
                                <p>This program is open to:</p>
                                <ul>
                                  <li>Senior High School (SHS) Graduates</li>
                                  <li>Licensed Professional Teachers</li>
                                  <li>Robotics Coaches and Mentors</li>
                                  <li>Robotics Enthusiasts (with basic technical knowledge preferred)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 2 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.15s">
                        <div class="card-header" id="feltaHeadingTwo">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#feltaCollapseTwo" aria-expanded="false" aria-controls="feltaCollapseTwo">
                            2. What is the format of the classes?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="feltaCollapseTwo" aria-labelledby="feltaHeadingTwo" data-bs-parent="#feltaFaqAccordion">
                            <div class="card-body">
                                <p>Classes are hybrid:</p>
                                <ul>
                                  <li><strong>Tuesdays and Thursdays:</strong> Online Theory Classes (via Microsoft Teams)</li>
                                  <li><strong>Saturdays:</strong> Face-to-Face Hands-On Robotics Training at FELTA Techvoc Academy</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 3 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="card-header" id="feltaHeadingThree">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#feltaCollapseThree" aria-expanded="false" aria-controls="feltaCollapseThree">
                            3. Where will the Face-to-Face classes be held?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="feltaCollapseThree" aria-labelledby="feltaHeadingThree" data-bs-parent="#feltaFaqAccordion">
                            <div class="card-body">
                                <p>Face-to-Face sessions will be conducted at:</p>
                                <p><strong>FELTA TECHVOC ACADEMY</strong><br>No. 18 Notre Dame Street, Barangay Silangan, Quezon City</p>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 4 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.25s">
                        <div class="card-header" id="feltaHeadingFour">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#feltaCollapseFour" aria-expanded="false" aria-controls="feltaCollapseFour">
                            4. What robotics platform will be used in the course?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="feltaCollapseFour" aria-labelledby="feltaHeadingFour" data-bs-parent="#feltaFaqAccordion">
                            <div class="card-body">
                                <p>The course will use <strong>Matrix Robotics Technology</strong>, a global-standard platform for mechanical design, electronics, and programming.</p>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 5 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="card-header" id="feltaHeadingFive">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#feltaCollapseFive" aria-expanded="false" aria-controls="feltaCollapseFive">
                            5. Will I get a Certificate after completing the course?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="feltaCollapseFive" aria-labelledby="feltaHeadingFive" data-bs-parent="#feltaFaqAccordion">
                            <div class="card-body">
                                <p>Yes! Upon completing the program, participants will receive a <strong>TESDA-Accredited Certificate for Robotics Technology Level II</strong>.</p>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 6 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.35s">
                        <div class="card-header" id="feltaHeadingSix">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#feltaCollapseSix" aria-expanded="false" aria-controls="feltaCollapseSix">
                            6. Can Professional Teachers earn CPD Points?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="feltaCollapseSix" aria-labelledby="feltaHeadingSix" data-bs-parent="#feltaFaqAccordion">
                            <div class="card-body">
                                <p>Yes! Licensed Professional Teachers who complete the course will earn <strong>Continuing Professional Development (CPD) Points</strong>, useful for PRC license renewal and career development.</p>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 7 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.4s">
                        <div class="card-header" id="feltaHeadingSeven">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#feltaCollapseSeven" aria-expanded="false" aria-controls="feltaCollapseSeven">
                            7. How long will the course run?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="feltaCollapseSeven" aria-labelledby="feltaHeadingSeven" data-bs-parent="#feltaFaqAccordion">
                            <div class="card-body">
                                <p>The course will run for <strong>4 months</strong> from July to October 2025.<br>It requires a total of <strong>400 hours of training</strong> (combined online and hands-on sessions).</p>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 8 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.45s">
                        <div class="card-header" id="feltaHeadingEight">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#feltaCollapseEight" aria-expanded="false" aria-controls="feltaCollapseEight">
                            8. What will I learn in this program?<span class="lni-chevron-up"></span></h6>
                        </div>
                        <div class="collapse" id="feltaCollapseEight" aria-labelledby="feltaHeadingEight" data-bs-parent="#feltaFaqAccordion">
                            <div class="card-body">
                                <p>You will learn:</p>
                                <ul>
                                  <li>The physics behind simple machines and robotics</li>
                                  <li>Programming robot movements, selection mechanisms, and automated processes</li>
                                  <li>Building functional robot prototypes</li>
                                  <li>Practical applications for education, competitions, and industry projects</li>
                                  <li>Critical thinking, innovation, and problem-solving skills</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ 9 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="card-header" id="feltaHeadingNine">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#feltaCollapseNine" aria-expanded="false" aria-controls="feltaCollapseNine">
                            9. What online platforms and tools will be used?<span class="lni-chevron-up"></span></h6>
                          </div>
                        <div class="collapse" id="feltaCollapseNine" aria-labelledby="feltaHeadingNine" data-bs-parent="#feltaFaqAccordion">
                              <div class="card-body">
                                <p>During the online sessions, students will explore and work with:</p>
                                <ul>
                                  <li>Virtual Robotics Toolkit (VRT) – for simulation and programming practice</li>
                                  <li>TinkerCAD – for 3D design, electronics, and Arduino simulations</li>
                                  <li>Studio Software – for coding and robotics programming exercises</li>
                                </ul>
                              </div>
                          </div>
                      </div>
                    <!-- FAQ 10 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.55s">
                        <div class="card-header" id="feltaHeadingTen">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#feltaCollapseTen" aria-expanded="false" aria-controls="feltaCollapseTen">
                            10. Do I need to have my own robotics kit?<span class="lni-chevron-up"></span></h6>
                          </div>
                        <div class="collapse" id="feltaCollapseTen" aria-labelledby="feltaHeadingTen" data-bs-parent="#feltaFaqAccordion">
                              <div class="card-body">
                                <p>No need to buy a robotics kit! <strong>Matrix Robotics Kits</strong> will be provided for use during the hands-on training sessions.</p>
                              </div>
                          </div>
                      </div>
                    <!-- FAQ 11 -->
                    <div class="card border-0 wow fadeInUp" data-wow-delay="0.6s">
                        <div class="card-header" id="feltaHeadingEleven">
                          <h6 class="mb-0" data-bs-toggle="collapse" data-bs-target="#feltaCollapseEleven" aria-expanded="false" aria-controls="feltaCollapseEleven">
                            11. How can I enroll?<span class="lni-chevron-up"></span></h6>
                          </div>
                        <div class="collapse" id="feltaCollapseEleven" aria-labelledby="feltaHeadingEleven" data-bs-parent="#feltaFaqAccordion">
                              <div class="card-body">
                                <p>Send us a Direct Message (DM) through the <a href="https://www.facebook.com/feltamultimedia/" target="_blank">Felta Multi Media Facebook Page</a> or Email us at <a href="mailto:felta@gmail.com">felta@gmail.com</a>.</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

                  <!-- Support Button-->
              <div class="support-button text-center d-flex align-items-center justify-content-center mt-4 wow fadeInUp" data-wow-delay="0.7s">
                      <i class="lni-emoji-sad"></i>
                      <p class="mb-0 px-2">Can't find your answers?</p>
                      <a href="contact.php"> Contact us</a>
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
    body {
        margin-top: 20px;
        background-color: #f8f9fa;
    }

.section_padding_130 {
        padding-top: 100px;
        padding-bottom: 100px;
}

.faq_area {
    position: relative;
    z-index: 1;
    background-color: #ffffff;
        border-radius: 20px;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.05);
        margin: 20px 0;
    }

    .faq_area h2 {
        color: #241e62;
        font-size: 2.2rem;
        margin-bottom: 2.5rem;
        font-weight: 700;
        position: relative;
        padding-bottom: 15px;
    }

    .faq_area h2:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #241e62, #4a45b1);
        border-radius: 2px;
}

.faq-accordian {
    position: relative;
    z-index: 1;
}

.faq-accordian .card {
    position: relative;
    z-index: 1;
    margin-bottom: 1.5rem;
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    }

    .faq-accordian .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

.faq-accordian .card:last-child {
    margin-bottom: 0;
}

.faq-accordian .card .card-header {
        background-color: #ffffff;
    padding: 0;
        border: none;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.faq-accordian .card .card-header h6 {
    cursor: pointer;
        padding: 1.5rem 2rem;
    color: #241e62;
    display: flex;
    align-items: center;
    justify-content: space-between;
        font-weight: 600;
        font-size: 1.1rem;
        margin: 0;
        transition: all 0.3s ease;
}

.faq-accordian .card .card-header h6 span {
        font-size: 1.2rem;
        transition: transform 0.3s ease;
        color: #4a45b1;
}

.faq-accordian .card .card-header h6.collapsed {
    color: #241e62;
}

.faq-accordian .card .card-header h6.collapsed span {
    transform: rotate(-180deg);
}

.faq-accordian .card .card-body {
        padding: 1.5rem 2rem;
        background-color: #ffffff;
        color: #666;
        font-size: 1rem;
        line-height: 1.6;
    }

.faq-accordian .card .card-body p:last-child {
    margin-bottom: 0;
}

    .faq-accordian .card .card-body ul {
        padding-left: 20px;
        margin-bottom: 0;
    }

    .faq-accordian .card .card-body ul li {
        margin-bottom: 8px;
        position: relative;
    }

    .faq-accordian .card .card-body ul li:last-child {
        margin-bottom: 0;
    }

    .faq-accordian .card .card-body a {
        color: #4a45b1;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .faq-accordian .card .card-body a:hover {
    color: #241e62;
        text-decoration: underline;
}

.collapse {
        transition: all 0.3s ease-out;
    }

    .collapse.show {
        background-color: #f8f9fa;
}

@media only screen and (max-width: 575px) {
        .faq_area h2 {
            font-size: 1.8rem;
        }

        .faq-accordian .card .card-header h6 {
            padding: 1.25rem 1.5rem;
            font-size: 1rem;
        }

        .faq-accordian .card .card-body {
            padding: 1.25rem 1.5rem;
        }
    }

    /* Animation for FAQ items */
    .wow.fadeInUp {
        animation-duration: 0.6s;
        animation-fill-mode: both;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translate3d(0, 20px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }

    /* Custom scrollbar for FAQ content */
    .faq-accordian .card .card-body {
        max-height: 300px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #4a45b1 #f0f0f0;
    }

    .faq-accordian .card .card-body::-webkit-scrollbar {
        width: 6px;
    }

    .faq-accordian .card .card-body::-webkit-scrollbar-track {
        background: #f0f0f0;
        border-radius: 3px;
    }

    .faq-accordian .card .card-body::-webkit-scrollbar-thumb {
        background-color: #4a45b1;
        border-radius: 3px;
    }

    /* Section divider */
    .faq-section-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #4a45b1, transparent);
        margin: 4rem 0;
        opacity: 0.3;
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
  <script>
document.addEventListener("DOMContentLoaded", function () {
    // Add animation classes
    const faqItems = document.querySelectorAll('.faq-accordian .card');
    faqItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.1}s`;
    });

    // Handle Felta Techvoc FAQs with smooth animation
    let feltaFaqItems = document.querySelectorAll("#feltaFaqAccordion .card-header h6");
    feltaFaqItems.forEach((item) => {
        item.addEventListener("click", function (e) {
            e.preventDefault();
            let target = document.querySelector(this.getAttribute("data-bs-target"));
            
            if (target.classList.contains("show")) {
                target.style.height = "0px";
                setTimeout(() => {
                    target.classList.remove("show");
                }, 300);
                return;
            }
            
            // Close all other items
            document.querySelectorAll("#feltaFaqAccordion .collapse").forEach((collapse) => {
                if (collapse.classList.contains("show")) {
                    collapse.style.height = "0px";
                    setTimeout(() => {
                        collapse.classList.remove("show");
                    }, 300);
                }
            });

            // Open clicked item with smooth animation
            target.classList.add("show");
            target.style.height = target.scrollHeight + "px";
        });
    });

    // Handle iCreate Cafe FAQs with smooth animation
    let icreateFaqItems = document.querySelectorAll("#icreateFaqAccordion .card-header h6");
    icreateFaqItems.forEach((item) => {
        item.addEventListener("click", function (e) {
            e.preventDefault();
            let target = document.querySelector(this.getAttribute("data-bs-target"));

            if (target.classList.contains("show")) {
                target.style.height = "0px";
                setTimeout(() => {
                    target.classList.remove("show");
                }, 300);
                return;
            }
            
            // Close all other items
            document.querySelectorAll("#icreateFaqAccordion .collapse").forEach((collapse) => {
                if (collapse.classList.contains("show")) {
                    collapse.style.height = "0px";
                    setTimeout(() => {
                        collapse.classList.remove("show");
                    }, 300);
                }
            });

            // Open clicked item with smooth animation
            target.classList.add("show");
            target.style.height = target.scrollHeight + "px";
        });
    });

    // Add hover effect to FAQ cards
    const cards = document.querySelectorAll('.faq-accordian .card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });
});
</script>


</body>

</html>