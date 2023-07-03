<?php

require_once("db-utils.php");

if($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $insert_id = insert("schedules", [
        "name" => $_POST["name"]
    ]);

    foreach ($_POST["schedule"] as $schedule) 
    {
        insert("schedule_days", [
            "day" => $schedule["day"],
            "in_time" => $schedule["in_time"] ? [
                $schedule["in_time"],
                PDO::PARAM_STR
            ] : NULL,
            "out_time" => $schedule["out_time"] ? [
                $schedule["out_time"],
                PDO::PARAM_STR
            ] : NULL,
            "schedule_id" => $insert_id
        ]);
    }

    die("<script>window.location.href='/multiple-punch/schedules.php'</script>");
}

?>

<?php require("header.php") ?>

<form method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">CREATE SCHEDULE</h2>

    <div class="mb-3">
        <label for="name" class="block mb-1">Name</label>
        <input type="text" name="name" id="name" class="form-control">
    </div>

    <table class="table">
        <tr>
            <td>Day</td>
            <td>In Time</td>
            <td>Out Time</td>
        </tr>

        <tr>
            <td>Monday</td>

            <td>
                <input type="time" name="schedule[0][in_time]" id="in_time" class="form-control">
            </td>

            <td>
                <input type="time" name="schedule[0][out_time]" id="out_time" class="form-control">
            </td>

            <input type="hidden" name="schedule[0][day]" value="monday">
        </tr>

        <tr>
            <td>Tuesday</td>

            <td>
                <input type="time" name="schedule[1][in_time]" id="in_time" class="form-control">
            </td>

            <td>
                <input type="time" name="schedule[1][out_time]" id="out_time" class="form-control">
            </td>

            <input type="hidden" name="schedule[1][day]" value="tuesday">
        </tr>

        <tr>
            <td>Wednessday</td>

            <td>
                <input type="time" name="schedule[2][in_time]" id="in_time" class="form-control">
            </td>

            <td>
                <input type="time" name="schedule[2][out_time]" id="out_time" class="form-control">
            </td>

            <input type="hidden" name="schedule[2][day]" value="wednesday">
        </tr>

        <tr>
            <td>Thursday</td>

            <td>
                <input type="time" name="schedule[3][in_time]" id="in_time" class="form-control">
            </td>

            <td>
                <input type="time" name="schedule[3][out_time]" id="out_time" class="form-control">
            </td>

            <input type="hidden" name="schedule[3][day]" value="thursday">
        </tr>

        <tr>
            <td>Friday</td>

            <td>
                <input type="time" name="schedule[4][in_time]" id="in_time" class="form-control">
            </td>

            <td>
                <input type="time" name="schedule[4][out_time]" id="out_time" class="form-control">
            </td>

            <input type="hidden" name="schedule[4][day]" value="friday">
        </tr>

        <tr>
            <td>Saturday</td>

            <td>
                <input type="time" name="schedule[5][in_time]" id="in_time" class="form-control">
            </td>

            <td>
                <input type="time" name="schedule[5][out_time]" id="out_time" class="form-control">
            </td>

            <input type="hidden" name="schedule[5][day]" value="saturday">
        </tr>

        <tr>
            <td>Sunday</td>

            <td>
                <input type="time" name="schedule[6][in_time]" id="in_time" class="form-control">
            </td>

            <td>
                <input type="time" name="schedule[6][out_time]" id="out_time" class="form-control">
            </td>

            <input type="hidden" name="schedule[6][day]" value="sunday">
        </tr>
    </table>

    <button class="btn btn-primary">Save</button>
</form>

<?php require("footer.php") ?>