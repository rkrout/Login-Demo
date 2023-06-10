<?php 

require_once("db-utils.php");
require_once("date-utils.php");
require_once("session-utils.php");
require_once("auth-utils.php");

auth_or_redirect();

$timings = find_all("SELECT * FROM timings WHERE user_id = ". get_session("user_id"));

$result = [];

foreach ($timings as $timing) 
{
    $contains = false;

    for($i = 0; $i < count($result); $i++)
    {
        if($result[$i]["date"] == $timing["date"])
        {
            array_push($result[$i]["timings"], [
                "punch_in_time" => $timing["punch_in_time"],
                "punch_out_time" => $timing["punch_out_time"],
            ]);

            $contains = true;
        }
    }

    if(!$contains)
    {
        array_push($result, [
            "date" => $timing["date"],
            "break_time" => 0,
            "working_time" => 0,
            "timings" => [[
                "punch_in_time" => $timing["punch_in_time"],
                "punch_out_time" => $timing["punch_out_time"]
            ]]
        ]);
    }
}

for($i = 0; $i < count($result); $i++)
{
    $break_time = 0;
    $office_time = 0;
    $working_time = 0;

    for($j = 0; $j < count($result[$i]["timings"]) - 1; $j++)
    {
        $punch_out_time = $result[$i]["timings"][$j]["punch_out_time"];

        $punch_in_time = $result[$i]["timings"][$j + 1]["punch_in_time"];

        if($punch_in_time && $punch_out_time)
        {
            $break_time += strtotime($punch_in_time) - strtotime($punch_out_time);
        }
    }

    $punch_in_time = $result[$i]["timings"][0]["punch_in_time"];
    $punch_out_time = $result[$i]["timings"][count($result[$i]["timings"]) - 1]["punch_out_time"];

    if($punch_in_time && $punch_out_time)
    {
        $office_time = strtotime($punch_out_time) - strtotime($punch_in_time);

        $working_time = $office_time - $break_time;
    }

    $result[$i]["break_time"] = $break_time;
    $result[$i]["office_time"] = $office_time;
    $result[$i]["working_time"] = $working_time;
}
// echo "<pre>";
// print_r($result);
// echo "</pre>";
?>

<?php require("header.php") ?>

<div class="table-responsive">
    <table class="table min-w-[1024px]">
        <thead>
            <tr>
                <th>Date</th>
                <th>Punches</th>
                <th>Break Time</th>
                <th>Working Time</th>
                <th>Office Time</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($result) == 0): ?>
                <tr>
                    <td colspan="5">No Data Found</td>
                </tr>
            <?php endif; ?>

            <?php foreach($result as $timing): ?>
                <tr>
                    <td><?= date("d-m-Y", strtotime($timing["date"])) ?></td>

                    <td>
                        <?php foreach($timing["timings"] as $t): ?>
                            <p><?php echo date("h:i A", strtotime($t["punch_in_time"])) . " - " . ($t["punch_out_time"] ? date("h:i A", strtotime($t["punch_out_time"])) : "NA") ?></p>
                        <?php endforeach; ?> 
                    </td>

                    <td><?= get_sec_to_time($timing["break_time"]) ?></td>

                    <td><?= get_sec_to_time($timing["working_time"]) ?></td>

                    <td><?= get_sec_to_time($timing["office_time"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require("footer.php") ?>