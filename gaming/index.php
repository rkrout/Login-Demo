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
</head>

<body class="bg-light" style="background: #d8e7f5;">
    <div class="container my-5">
        <img src="/assets/images/gaming_img.png" class="img-fluid mx-auto d-block mb-4" width="200px">

        <div class="d-flex align-items-center gap-2 justify-content-center mb-4">
            <span class="fw-semibold text-secondary-emphasis">Powered by</span>
            <img src="/assets/images/hounslow_council.png" width="150px" class="img-fluid">
        </div>

        <div class="row">
            <?php foreach($games as $game): ?>
                <?php if($game["show"] == 1): ?>
                    <a href="<?= $game["link"] ?>" <?= $game["link_type"] == "Redirect" ? "target='_blank'" : "" ?> class="col-3">
                        <img src="uploads/<?= $game["logo_url"] ?>" class="img-fluid shadow">
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