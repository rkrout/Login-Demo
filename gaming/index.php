<?php

$games = json_decode(file_get_contents("games.json"), true) ?? [];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Blue Summit Technologies - Demo Page</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <style>
    .circles {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: 0;
    }

    .circles li {
      position: absolute;
      display: block;
      list-style: none;
      width: 20px;
      height: 20px;
      background: rgba(156, 39, 176, 0.2);
      animation: animate 25s linear infinite;
      bottom: -150px;

    }

    .circles li:nth-child(1) {
      left: 25%;
      width: 80px;
      height: 80px;
      animation-delay: 0s;
    }

    .circles li:nth-child(2) {
      left: 10%;
      width: 20px;
      height: 20px;
      animation-delay: 2s;
      animation-duration: 12s;
    }

    .circles li:nth-child(3) {
      left: 70%;
      width: 20px;
      height: 20px;
      animation-delay: 4s;
    }

    .circles li:nth-child(4) {
      left: 40%;
      width: 60px;
      height: 60px;
      animation-delay: 0s;
      animation-duration: 18s;
    }

    .circles li:nth-child(5) {
      left: 65%;
      width: 20px;
      height: 20px;
      animation-delay: 0s;
    }

    .circles li:nth-child(6) {
      left: 75%;
      width: 110px;
      height: 110px;
      animation-delay: 3s;
    }

    .circles li:nth-child(7) {
      left: 35%;
      width: 150px;
      height: 150px;
      animation-delay: 7s;
    }

    .circles li:nth-child(8) {
      left: 50%;
      width: 25px;
      height: 25px;
      animation-delay: 15s;
      animation-duration: 45s;
    }

    .circles li:nth-child(9) {
      left: 20%;
      width: 15px;
      height: 15px;
      animation-delay: 2s;
      animation-duration: 35s;
    }

    .circles li:nth-child(10) {
      left: 85%;
      width: 150px;
      height: 150px;
      animation-delay: 0s;
      animation-duration: 11s;
    }

    @keyframes animate {
      0% {
        transform: translateY(0) rotate(0deg);
        opacity: 1;
        border-radius: 0;
      }

      100% {
        transform: translateY(-1000px) rotate(720deg);
        opacity: 0;
        border-radius: 50%;
      }

    }
  </style>
</head>

<body class="section-bg" style="background: #d8e7f5;">

  <!-- ======= Hero Section ======= -->
  <!-- <section id="hero">

    <div class="container">
      <div class="row">
        <div class="col-lg-6 pb-2 pb-lg-0 d-flex flex-column justify-content-center" data-aos="fade-up">
          <div>
            <h1>Welcome to the world of gaming !</h1>
            <h2>Get ready to embark on exciting adventures, battle fierce enemies, and explore immersive virtual worlds.</h2>
            <a href="#gamingviewsec" class="btn-get-started scrollto mb-5">Get Started</a>
          </div>
        </div>
        <div class="col-lg-6 hero-img text-center d-flex flex-column justify-content-center align-items-center" data-aos="fade-left">
          <img src="assets/img/icon/hounslow_council.png" class="img-fluid" alt="">
        </div>
      </div>
    </div>

  </section> -->
  <div class="area">
    <ul class="circles">
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
    </ul>
  </div>
  <main id="main" style="z-index: 1000;">


    <!-- ======= Services Section ======= -->
    <section id="gamingviewsec" class="services section-bg d-flex align-items-center justify-content-center flex-row"
      style="min-height: 100vh; background: #d8e7f5;">
      <div class="container">

        <div class="section-title" data-aos="fade-up">
          <h2><img src="assets/img/icon/gaming_img.png" alt="" style="width:200px; margin:10px auto 10px auto;"></h2>
          <p class="fw-bold">Powered by <span style="color:#7a2a83;"><img src="assets/img/icon/hounslow_council.png"
                style="width: 150px;" /></span></p>
        </div>

        <div class="row">
          <!--<div class="col-xs-6 col-sm-6 col-md-4 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-4" data-aos="zoom-in">
            <div class="icon-box icon-box-pink">
              <a href="https://gameforge.com/en-US/littlegames/minecraft-games/" target="_blank"><img src="assets/img/icon/image1.png" class="img-fluid" alt="" /></a>
            </div>
	  </div>-->

          <?php foreach($games as $game): ?>
            <?php if($game["show"]): ?>
              <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-4" data-aos="zoom-in"
              data-aos-delay="200"
            >
              <div class="icon-box icon-box-green">
                <a href="<?= $game["link"] ?>" <?= $game["target"] == "New Tab" ? "target='_blank'" : "" ?>><img src="<?= "/gaming/uploads/" . $game["logo_url"] ?>"
                    class="img-fluid" alt="" /></a>
              </div>
            </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
    </section><!-- End Services Section -->







  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>



  <!-- Vendor JS Files -->
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>