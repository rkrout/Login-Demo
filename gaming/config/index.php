<?php

session_start();

if(!isset($_SESSION["username"]))
{
    header("Location: /gaming/login.php");
    die;
}

$games = json_decode(file_get_contents("../games.json"), true) ?? [];

if(isset($_GET["action"]) && $_GET["action"] == "logout")
{
    unset($_SESSION["username"]);
    header("Location: /gaming/login.php");
    die();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        .page-loader {
            background-color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>

<body>
    <div class="container my-5">
    <div class="fw-bold text-primary text-center h3 mb-4">Gaming Configuration</div>
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <img src="/gaming/assets/img/bst_applogo.png" width="100px">

                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-primary d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fa-solid fa-plus"></i>
                        <span>Create New</span>
                    </button>
                    <a href="?action=logout" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-title="Logout">
                        <i class="fa fa-sign-out"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
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
                                    <img src="/gaming/uploads/<?= $game["logo_url"] ?>" width="60px" class="img-fluid">
                                </td>
                                <td>
                                    <a href="<?= $game["link"] ?>"><?= $game["link"] ?></a>
                                </td>

                                <td><?= $game["target"] ?></td>

                                <td>
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <?php if($game["show"]): ?>
                                            <button type="submit" class="btn btn-sm btn-success btn-toggle" data-type="published" data-id='<?= $game["id"] ?>' data-bs-toggle="tooltip" data-bs-title="Click to unpublish">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-sm btn-danger btn-toggle" data-type="un_published" data-id='<?= $game["id"] ?>' data-bs-toggle="tooltip" data-bs-title="Click to publish">
                                                <i class="fas fa-close"></i>
                                            </button>
                                        <?php endif; ?>

                                        <button data-game='<?= json_encode($game) ?>' type="button" class="btn btn-sm btn-secondary btn-edit" data-bs-toggle="tooltip" data-bs-title="Edit Game">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="page-loader position-absolute start-0 top-0 end-0 bottom-0 d-none align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <form enctype="multipart/form-data" method="post" class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 name-error">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name">
                        <span class="invalid-feedback"></span>
                    </div>
                    <div class="mb-3 logo-error">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" class="form-control" name="logo" id="logo">
                        <span class="invalid-feedback"></span>
                    </div>
                    <div class="mb-3">
                        <img class="img-fluid logo-preview" width="100px" height="100px">
                    </div>
                    <div class="mb-3 link-error">
                        <label for="link" class="form-label">Link</label>
                        <input type="text" class="form-control" name="link" id="link">
                        <span class="invalid-feedback"></span>
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
                    <div class="mb-3 name-error">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name">
                        <span class="invalid-feedback"></span>
                    </div>
                    <div class="mb-3 logo-error">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" class="form-control" name="logo" id="logo">
                        <span class="invalid-feedback"></span>
                    </div>
                    <div class="mb-3 link-error">
                        <label for="link" class="form-label">Link</label>
                        <input type="text" class="form-control" name="link" id="link">
                        <span class="invalid-feedback"></span>
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
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.js"></script>

<script>
    $("#createModal").submit(async function(event){
        event.preventDefault()

        const payload = new FormData(event.target)
        payload.append("action", "create");

        $(this).find("input").removeClass("is-invalid")
        $(this).find(".invalid-feedback").html("")
        $(this).find("button[type=submit]").attr("disabled", true)
        
        const response = await fetch("/gaming/ajax.php?action=create", {
            method: "post",
            body: payload
        })

        if(response.status == 422)
        {
            const errors = await response.json()

            Object.keys(errors).forEach(key => {
                $(this).find(`.${key}-error`).find(`[id=${key}`).addClass("is-invalid")
                $(this).find(`.${key}-error`).find(`.invalid-feedback`).html(errors[key])
            })
        }
        else if(response.status == 200)
        {
            $(this).modal("hide")
            
            $(this).get(0).reset()

            Toastify({ text: "Game added successfully", duration: 3000 }).showToast()

            reload_table()
        }
        else 
        {
            Toastify({ text: "Sorry, An unknown error occurred", duration: 3000 }).showToast()
        }

        $(this).find("button[type=submit]").attr("disabled", false)
    })

    $("#editModal").submit(async function(event){
        event.preventDefault()

        $(this).find("input").removeClass("is-invalid")
        $(this).find(".invalid-feedback").html("")
        $(this).find("button[type=submit]").attr("disabled", true)

        const payload = new FormData(event.target)
        payload.append("id", $(this).attr("data-id"))
        
        const response = await fetch("/gaming/ajax.php?action=edit", {
            method: "post",
            body: payload
        })

        if(response.status == 422)
        {
            const errors = await response.json()

            Object.keys(errors).forEach(key => {
                $(this).find(`.${key}-error`).find(`[id=${key}]`).addClass("is-invalid")
                $(this).find(`.${key}-error`).find(`.invalid-feedback`).html(errors[key])
            })
        }
        else if(response.status == 200)
        {
            $(this).modal("hide")

            $(this).find("input[type=file]").val("")

            Toastify({ text: "Game updated successfully", duration: 3000 }).showToast()

            reload_table()
        }
        else 
        {
            Toastify({ text: "Sorry, An unknown error occurred", duration: 3000 }).showToast()
        }

        $(this).find("button[type=submit]").attr("disabled", false)
    })

    $("#createModal").on("hidden.bs.modal", function () {
        $("#createModal").find("input").removeClass("is-invalid")
        $("#createModal").find(".invalid-feedback").html("")
        $("#createModal").get(0).reset()
    })

    $("table").on("click", ".btn-edit", async function(event){
        const game = JSON.parse($(event.target).closest("button").attr("data-game"));

        $("#editModal").find("input").removeClass("is-invalid")

        $("#editModal").find(".invalid-feedback").html("")

        $("#editModal").find("input[name=name]").val(game.name)

        $("#editModal").find("input[name=link]").val(game.link)

        $("#editModal").find("select[name=target] option").prop("selected", false)

        $("#editModal").find(`select[name=target] option[value="${game.target}"]`).prop("selected", true)

        $("#editModal").find("[id=show]").prop("checked", game.show == 1)

        $("#editModal").find("img").attr("src", `/gaming/uploads/${game.logo_url}`)

        $("#editModal").attr("data-id", game.id)

        $("#editModal").modal("show")
    })

    $("#editModal input[type=file]").change(function(){
        $("#editModal img").attr("src", URL.createObjectURL($("#editModal input[type=file]").get(0).files[0]))
    })

    $("table").on("click", ".btn-toggle", async function(){
        $(this).attr("disabled", true)

        const payload = new FormData()
        payload.append("id", $(this).attr("data-id"))

        const response = await fetch("/gaming/ajax.php?action=toggle_show", {
            method: "post",
            body: payload
        })

        if(response.status == 200)
        {
            Toastify({ text: $(this).attr("data-type") == "un_published" ? "Game published successfully" : "Game unpublished successfully", duration: 2000 }).showToast()

            reload_table()
        }
        else 
        {
            Toastify({ text: "Sorry, An unknown error occurred", duration: 2000 }).showToast()
        }

        $(this).attr("disabled", false)
    })

    function reload_table() 
    {
        $(".page-loader").removeClass("d-none")
        $(".page-loader").addClass("d-flex")
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        tooltipTriggerList.forEach(tooltipTriggerEl => {
            const tooltip = bootstrap.Tooltip.getOrCreateInstance(tooltipTriggerEl)
            tooltip.hide()
        }) 

        $("table").load("/gaming/config/ table", () => {
            $(".page-loader").removeClass("d-flex")
            $(".page-loader").addClass("d-none")    
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))   
        })
    }

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))   
</script>

</html>