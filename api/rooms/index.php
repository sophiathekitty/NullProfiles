<?php
require_once("../../../../includes/main.php");
$data = [];
if(isset($_GET['user_id'])){
    $data['room_uses'] = RoomUses::UserId($_GET['user_id']);
} else if(isset($_GET['room_id'])){
    $data['room_uses'] = RoomUses::RoomId($_GET['room_id']);
} else {
    $data['room_uses'] = RoomUses::AllRoomUses();
}
OutputJson($data);
?>