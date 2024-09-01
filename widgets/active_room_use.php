<?php
if(!isset($_GET['room_id'])) die();
require_once("../../../includes/main.php");
$room_use = CurrentRoomUse($_GET['room_id']);
if(is_null($room_use)){
    $room_use = ['title'=>'None'];
}
$room_uses = RoomUses::RoomId($_GET['room_id']);
?>
<ul class="active-room-use">
    <li>
        <span class="key">Current</span>
        <span class="value"><?=$room_use['title'];?></span>
    </li><?php if(!is_null($room_use['type'])){ ?>
    <li>
        <span class="key">Type</span>
        <span class="value <?=$room_use['type'];?>"><?=$room_use['type'];?></span>
    </li><?php } if(!is_null($room_use['light_level'])){ ?>
    <li>
        <span class="key">Light Level</span>
        <span class="value"><?=$room_use['light_level'];?></span>
    </li><?php } if(!is_null($room_use['light_min'])){ ?>
    <li>
        <span class="key">Light Min</span>
        <span class="value"><?=$room_use['light_min'];?></span>
    </li><?php } if(!is_null($room_use['light_max'])){ ?>
    <li>
        <span class="key">Light Max</span>
        <span class="value"><?=$room_use['light_max'];?></span>
    </li><?php } if(!is_null($room_use['light_end'])){ ?>
    <li>
        <span class="key">Light End</span>
        <span class="value"><?=$room_use['light_end'];?></span>
    </li><?php } ?>
</ul>
<ul class="room-uses-list">
    <?php foreach($room_uses as $room_use){ ?>
    <li>
        <a href="#" action="view" room_use_id="<?=$room_use['id'];?>"><i><?=$room_use['schedule'];?></i> <?=$room_use['type'];?></a>
    </li><?php } ?>
    <li>
        <a href="#" action="create" room_id="<?=$_GET['room_id'];?>">Create New</a>
    </li>
</ul>