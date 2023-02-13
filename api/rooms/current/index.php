<?php
require_once("../../../../../includes/main.php");
$data = [];
if(isset($_GET['room_id'])){
    $data['room_use'] = CurrentRoomUse($_GET['room_id']);
} else {
    $room_id = Settings::LoadSettingsVar("room_id",0);
    $data['room_use'] = CurrentRoomUse($room_id);
}
OutputJson($data);
?>