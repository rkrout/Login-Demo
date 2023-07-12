<?php

$api_key = "6acf1c131c0d443ea3af302310fdd083";
$auth_code = "b2d4074c71a647488d673ef04c1051e8";
include 'DB.class.php';
/*https://support.happyfox.com/kb/article/360-api-for-happyfox/
https://support.happyfox.com/kb/article/1039-tickets-endpoint/

Ticket Category - https://bluesummittech.happyfox.com/api/1.1/json/categories/
Staff - https://bluesummittech.happyfox.com/api/1.1/json/staff/
Contacts - https://bluesummittech.happyfox.com/api/1.1/json/users/?size=<size>&page=<page>
Tickets - https://bluesummittech.happyfox.com/api/1.1/json/tickets/
*/
$db = new DB();
$table = "happyfox_export";
$conditions["where"] = array("status"=>0);
$conditions['order_by'] = "Id ASC";
$conditions['return_type'] = "single";
$row = $db->getRows($table, $conditions);
// print_r($row);exit;

if(!empty($row)):
    $remark = array();
    $filter = array();
    if(!empty($row["SortBy"])){
        //$filter[] = $row["SortBy"].$row["OrderBy"]; //sort_by
        $SortBy = $row["SortBy"];
        //$OrderBy = ($row["OrderBy"]=='a')?"asc":"desc";
        $sort_order = "&sort_by=".$SortBy."&sort_order= asc";
    }
    if(!empty($row["StartDate"])){
        $filter[] = "created-on-or-after:".$row["StartDate"];
    }
    if(!empty($row["EndDate"])){
        $filter[] = "created-on-or-before:".$row["EndDate"];
    }
    $filter_set = implode(",",$filter);
    //echo $filter_set;exit;
    //update status
    $data = array("Status" => 1 , "Params" => $sort_order . "|" . $filter_set);
    $condition = array("Id" => $row['Id']);
    $update = $db->update($table,$data, $condition);
    //start process
    $file = strtotime("now").".csv";
    //header('Content-Type: text/csv');
    //header('Content-Disposition: attachment; filename="'.$file.'"');
    //$fp = fopen('php://output', 'wb');
    $fp = fopen('download/'.$file, 'w');
    if ($fp === false) {
        $remark[] = "Error opening the file.";
    }
    $size = 50; //page size
    $page = 1; //initial page number
    $page_count = 1; //initial total page count
    //$items = array();
    //print_r($remark);exit;
    do{
        $url = 'https://bluesummittech.happyfox.com/api/1.1/json/tickets/?size='.$size.'&page='.$page . $sort_order.'&q='.$filter_set;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$auth_code");
        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response);
        //print_r($result);exit;
        if($page==1){
            $page_count = $result->page_info->page_count;
            //fputcsv($fp, array("time_spent","created_at","updated_at","id","display_id","Status","Assignee","Contact")); //old format
            fputcsv($fp, array("ID", "Display ID","Category","Assignee", "Contact", "Status", "Time Spent", "Created On", "Updated On"));
        }
        if(!empty($result->data)){
            foreach($result->data as $key=>$value){
                $item['id'] = $value->id;
                $item['display_id'] = $value->display_id;
                $item['category'] = $value->category->name;
                $item['Assignee'] = $value->assigned_to->name;
                $item['Contact'] = $value->user->name;
                $item['Status'] = $value->status->name;
                $item['time_spent'] = $value->time_spent;
                $item['created_at'] = $value->created_at;
                $item['updated_at'] = $value->last_updated_at;
            
                //$val = explode(",", $item);
                fputcsv($fp, $item);
                //$items[] = $item;
            }
        }else{
            $remark[] = $result;
        }
        $page++;
        //sleep(10);  
    //$rawdata = json_decode($result);
    }while($page <= $page_count);

    fclose($fp);
    $remark = (!empty($remark))?json_encode($remark):"Success";
    //update status after complete
    $data = array("Status" => 2 , "File" => $file, "Remark" => $remark);
    $update = $db->update($table,$data, $condition);
endif;

?>