<?php

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) 
{
    $games = json_decode(file_get_contents("games.json"), true) ?? [];

    for($i = 0; $i < count($games); $i++)
    {
        if($games[$i]["id"] == $_POST["id"])
        {
            if($_FILES["logo"]["size"] > 0)
            {
                $image_name = bin2hex(random_bytes(32)) . "." . pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION);
            
                move_uploaded_file($_FILES["logo"]["tmp_name"], "uploads/$image_name");
            }
            else 
            {
                $image_name = $games[$i]["logo_url"];
            }

            $games[$i] = [
                "id" => $_POST["id"],
                "name" => $_POST["name"],
                "logo_url" => $image_name,
                "link" => $_POST["link"],
                "target" => $_POST["target"],
                "show" => $_POST["show"] == "true"
            ];   
        }
    }

    file_put_contents("games.json", json_encode($games));

    $alert = [
        "type" => "success",
        "message" => "Game updated successfully"
    ];
}

else if($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $games = json_decode(file_get_contents("games.json"), true) ?? [];

    $games_count = count($games);

    $id = $games_count > 0 ? $games[$games_count - 1]["id"] + 1 : 1;

    $image_name = bin2hex(random_bytes(32)) . "." . pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION);

    $image_destination = "uploads/$image_name";

    move_uploaded_file($_FILES["logo"]["tmp_name"], $image_destination);

    array_push($games, [
        "id" => $id,
        "name" => $_POST["name"],
        "logo_url" => $image_name,
        "link" => $_POST["link"],
        "target" => $_POST["target"],
        "show" => $_POST["show"] == "true"
    ]);

    file_put_contents("games.json", json_encode($games));

    $alert = [
        "type" => "success",
        "message" => "Game added successfully"
    ];
}

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

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-5">
        <?php if(isset($alert)): ?>
            <div class="alert alert-<?= $alert["type"] ?> alert-dismisable"><?= $alert["message"] ?></div>
        <?php endif; ?>
        
        <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal"
        data-bs-target="#createModal">Create New</button>

        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Logo</th>
                    <th>Link</th>
                    <th>Target</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($games) == 0): ?>
                    <tr>
                        <td colspan="7">No Data Found</td>
                    </tr>
                <?php endif; ?>

                <?php foreach($games as $game): ?>
                    <tr>
                        <td><?= $game["id"] ?></td>
                        <td><?= $game["name"] ?></td>
                        <td>
                            <img src="uploads/<?= $game["logo_url"] ?>" width="60px" class="img-fluid">
                        </td>
                        <td>
                            <a href="<?= $game["link"] ?>"><?= $game["link"] ?></a>
                        </td>

                        <td><?= $game["target"] ?></td>

                        <td data-game='<?= json_encode($game) ?>'>
                            <div class="d-flex align-items-center gap-2">
                                <?php if($game["show"]): ?>
                                    <button type="button" class="btn btn-sm btn-warning">
                                        <i class="fas fa-check"></i>
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-danger">
                                        <i class="fas fa-close"></i>
                                    </button>
                                <?php endif; ?>

                                <button type="button" class="btn btn-sm btn-secondary btn-edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <form enctype="multipart/form-data" method="post" class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <input type="hidden" name="id">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name">
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" class="form-control" name="logo" id="logo">
                    </div>
                    <div class="mb-3">
                        <img class="img-fluid logo-preview" width="100px" height="100px">
                    </div>
                    <div class="mb-3">
                        <label for="link" class="form-label">Link</label>
                        <input type="text" class="form-control" name="link" id="link">
                    </div>
                    <div class="mb-3">
                        <label for="target" class="form-label">Target</label>
                        <select name="target" id="target" class="form-control form-select">
                            <option value="New Tab">New Tab</option>
                            <option value="Same Page">Same Page</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="hidden" value="false" name="show">
                        <input class="form-check-input" type="checkbox" value="true" id="show" name="show">
                        <label class="form-check-label" for="show">Show</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </form>

    <form enctype="multipart/form-data" method="post" class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createModalLabel">Create New</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name">
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" class="form-control" name="logo" id="logo">
                    </div>
                    <div class="mb-3">
                        <label for="link" class="form-label">Link</label>
                        <input type="text" class="form-control" name="link" id="link">
                    </div>
                    <div class="mb-3">
                        <label for="target" class="form-label">Target</label>
                        <select name="target" id="target" class="form-control form-select">
                            <option value="New Tab">New Tab</option>
                            <option value="Same Page">Same Page</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="hidden" value="false" name="show">
                        <input class="form-check-input" type="checkbox" value="true" name="show" id="show">
                        <label class="form-check-label" for="show">Show</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </form>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

<script>
    $(".btn-edit").click(function(event){
        const game = JSON.parse($(event.target).parent().attr("data-game"));

        $("#editModal").find("input[name=name]").val(game.name)
        $("#editModal").find("input[name=link]").val(game.link)
        $("#editModal").find("select[name=target] option").prop("selected", false)
        $("#editModal").find(`select[name=target] option[value="${game.target}"]`).prop("selected", true)
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