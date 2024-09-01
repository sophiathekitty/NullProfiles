<?php
if(!isset($_GET['room_id'])) die();
require_once("../../../includes/main.php");
$room_uses = RoomUses::RoomId($_GET['room_id']);
?>
<ul class="room-uses-list">
    <?php foreach($room_uses as $room_use){ ?>
    <li>
        <a href="#" action="view" room_use_id="<?=$room_use['id'];?>"><i><?=$room_use['schedule'];?></i> <?=$room_use['type'];?></a>
    </li><?php } ?>
    <li>
        <a href="#" action="create" room_id="<?=$_GET['room_id'];?>">Create New</a>
    </li>
</ul>