<?php
require_once("../../../../../includes/main.php");
$data = [];
if(isset($_GET['room_id'])){
    //$data['room_uses'] = RoomUses::RoomTime($_GET['room_id'],date("n"),date("N"));
    $data['room_uses'] = RoomUses::RoomId($_GET['room_id']);
} else {
    $data['room_uses'] = RoomUses::AllRoomUses();
}
OutputJson($data);
?>