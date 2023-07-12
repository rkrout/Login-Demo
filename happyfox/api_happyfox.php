<?php

include 'DB.class.php';
$db = new DB();
$table = "happyfox_export";
$action = $_REQUEST['action'];
$response = array();
$api_key = "6acf1c131c0d443ea3af302310fdd083";
$auth_code = "b2d4074c71a647488d673ef04c1051e8";

switch($action){
    case "select":
        $columns = array("StartDate","EndDate","SortBy","OrderBy","Created","CreatedBy");

        $query = "SELECT 
            SQL_CALC_FOUND_ROWS h.*, 
            CONCAT(u.first_name ,' ', u.last_name) as FullName 
        FROM happyfox_export h 
        LEFT JOIN east_user u ON u.id = h.CreatedBy";

        $query .=" ORDER BY h.". $columns[$_REQUEST['order'][0]['column']]." ".$_REQUEST['order'][0]['dir']." LIMIT ".$_REQUEST['start']." ,".$_REQUEST['length']." ";

        $items = $db->getQuery($query); 

	    $total= $db->getQuery('SELECT FOUND_ROWS() as total');

        $response = array(
            "draw"            => intval( $_REQUEST['draw']),   
            "recordsTotal"    => intval($total[0]['total']),  
            "recordsFiltered" => intval($total[0]['total']),
            "data"            => $items   // total data array
            );
        break;


    case "insert":
        if(!empty($_POST['StartDate']) && !empty($_POST['EndDate'])){
            $StartDate = $_POST['StartDate']; 
            $EndDate = $_POST['EndDate'];
            $SortBy = $_POST['SortBy'];
            $OrderBy = $_POST['OrderBy'];
            $CreatedBy = $_POST['CreatedBy'];
            $data = array(
                "StartDate" => date("Y-m-d", strtotime(str_replace("/", "-", $StartDate))),
                "EndDate" => date("Y-m-d", strtotime(str_replace("/", "-", $EndDate))),
                "SortBy" => $SortBy,
                "OrderBy" => $OrderBy,
                "CreatedBy" => $CreatedBy
            );

            $result = $db->insert($table,$data);
            $response["data"] = $result;
            $response["status"] = "SUCCESS";
            $response["message"] = "Submitted your request.";
        }else{
            $response["status"] = "ERROR";
            $response["message"] = "Start Date & End Date should be mandatory.";
        }
        
        break;

        
    case "delete":
        $Id = $_POST["Id"];
        $File = $_POST["File"];
        $conditions = array("Id" => $Id);
        $result = $db->delete($table,$conditions);

        unlink("download/".$File);
        if($result){
            $response["status"] = "SUCCESS";
            $response["message"] = "File with record deleted";
        }
        break;

    case "get_staff_activities":
        $url = "https://bluesummittech.happyfox.com/api/1.1/json/report/". $_COOKIE["reportId"] ."/staffactivity?" . http_build_query($_GET);;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$auth_code");
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response);
        $response->data = $response->staff_activity;
        $response->recordsTotal = $response->last_index;
        $response->recordsFiltered = $response->last_index;

        break;


    case "get_staff_performance":
        $url = "https://bluesummittech.happyfox.com/api/1.1/json/report/". $_COOKIE["reportId"] ."/staffperformance?" . http_build_query($_GET);;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$auth_code");
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response);
        $response->data = $response->staff_performance;
        $response->recordsTotal = $response->last_index;
        $response->recordsFiltered = $response->last_index;

        break;

    case "get_all_reports":
        $url = "https://bluesummittech.happyfox.com/api/1.1/json/reports/";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$auth_code");
        $response = curl_exec($ch);
        curl_close($ch);

        break;
}

header("Content-Type: application/json");
echo is_string($response) ? $response : json_encode($response);


?>