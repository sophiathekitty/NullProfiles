<?php
if(!isset($_GET['room_id'])) die();
require_once("../../../includes/main.php");
$room_use = CurrentRoomUse($_GET['room_id']);
$tool_tip = $room_use['title']."
light level: ".$room_use['light_level'];
if(!is_null($room_use['light_min'])) $tool_tip .= "
light min: ".$room_use['light_min'];
if(!is_null($room_use['light_max'])) $tool_tip .= "
light max: ".$room_use['light_max'];
if(!is_null($room_use['light_end'])) $tool_tip .= "
light end: ".$room_use['light_end'];
?><span class="room_uses"><?php 
if(!is_null($room_use)){ ?><span class="room_use" type="<?=$room_use['type']?>" style="background-image: url(/plugins/NullProfiles/img/rooms/<?=$room_use['type'];?>.png);" title="<?=$tool_tip;?>"></span><?php }
?></span>