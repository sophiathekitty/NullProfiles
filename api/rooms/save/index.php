<?php
require_once("../../../../../includes/main.php");
$data = [];
if(isset($_GET['room_id'],$_GET['type'])){
    $data['save'] = RoomUses::SaveRoomUse($_GET);
} else if(count($_POST)){
    $json = GetPostJSON();
    $data['post_json'] = $json;
    $data['save'] = RoomUses::SaveRoomUse($json);
} else {
    $data['error'] = "no room use data found";
}
OutputJson($data);
?>