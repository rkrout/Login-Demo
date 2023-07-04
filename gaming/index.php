<?php

$games = json_decode(file_get_contents("games.json"), true) ?? [];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
          .circles{
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: 0;
  }
  
  .circles li{
      position: absolute;
      display: block;
      list-style: none;
      width: 20px;
      height: 20px;
      background: rgba(156, 39, 176, 0.2);
      animation: animate 25s linear infinite;
      bottom: -150px;
      
  }
  
  .circles li:nth-child(1){
      left: 25%;
      width: 80px;
      height: 80px;
      animation-delay: 0s;
  }
  
  
  .circles li:nth-child(2){
      left: 10%;
      width: 20px;
      height: 20px;
      animation-delay: 2s;
      animation-duration: 12s;
  }
  
  .circles li:nth-child(3){
      left: 70%;
      width: 20px;
      height: 20px;
      animation-delay: 4s;
  }
  
  .circles li:nth-child(4){
      left: 40%;
      width: 60px;
      height: 60px;
      animation-delay: 0s;
      animation-duration: 18s;
  }
  
  .circles li:nth-child(5){
      left: 65%;
      width: 20px;
      height: 20px;
      animation-delay: 0s;
  }
  
  .circles li:nth-child(6){
      left: 75%;
      width: 110px;
      height: 110px;
      animation-delay: 3s;
  }
  
  .circles li:nth-child(7){
      left: 35%;
      width: 150px;
      height: 150px;
      animation-delay: 7s;
  }
  
  .circles li:nth-child(8){
      left: 50%;
      width: 25px;
      height: 25px;
      animation-delay: 15s;
      animation-duration: 45s;
  }
  
  .circles li:nth-child(9){
      left: 20%;
      width: 15px;
      height: 15px;
      animation-delay: 2s;
      animation-duration: 35s;
  }
  
  .circles li:nth-child(10){
      left: 85%;
      width: 150px;
      height: 150px;
      animation-delay: 0s;
      animation-duration: 11s;
  }
  
  
  
  @keyframes animate {
  
      0%{
          transform: translateY(0) rotate(0deg);
          opacity: 1;
          border-radius: 0;
      }
  
      100%{
          transform: translateY(-1000px) rotate(720deg);
          opacity: 0;
          border-radius: 50%;
      }
  
  }
</style>
  </head>

<body class="bg-light" style="background: #d8e7f5;">
<!-- <div class="area">
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
      </div> -->
    <div class="container my-5" style="z-index: 1000">
        <img src="/assets/images/gaming_img.png" class="img-fluid mx-auto d-block mb-4" width="200px" alt="" style="z-index: 1000">

        <div class="d-flex align-items-center gap-2 justify-content-center mb-4" style="z-index: 1000">
            <span class="fw-semibold text-secondary-emphasis">Powered by</span>
            <img src="/assets/images/hounslow_council.png" width="150px" class="img-fluid">
        </div>

        <div class="row">
          <?php foreach($games as $game): ?>
                <?php if($game["show"] == 1): ?>
                    <a href="<?= $game["link"] ?>" <?= $game["link_type"] == "Redirect" ? "target='_blank'" : "" ?> class="col-3">
                        <img src="uploads/<?= $game["logo_url"] ?>" class="img-fluid shadow" style="z-index: 1000000" alt="">
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

<script>
    $(".btn-edit").click(function(event){
        const game = JSON.parse($(event.target).parent().attr("data-game"));

        $("#editModal").find("input[name=name]").val(game.name)
        $("#editModal").find("input[name=link]").val(game.link)
        $("#editModal").find("select[name=link_type] option").prop("selected", false)
        $("#editModal").find(`select[name=link_type] option[value="${game.link_type}"]`).prop("selected", true)
        $("#editModal").find("input[name=show]").prop("checked", game.show)
        $("#editModal").find("img").attr("src", `uploads/${game.logo_url}`)
        $("#editModal input[type=hidden]").val(game.id)
        $("#editModal").modal("show")
    })

    $("#editModal input[type=file]").change(function(){
        $("#editModal img").attr("src", URL.createObjectURL($("#editModal input[type=file]").get(0).files[0]))
    })
</script>

</html>