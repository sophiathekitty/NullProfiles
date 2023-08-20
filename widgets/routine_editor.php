<?php
require_once("../../../includes/main.php");
$rooms = Rooms::ActiveRooms();
$users = Users::Residence();
$room_use_types = ImageFolderToList("../img/rooms/");
if(isset($_GET['routine_id'])){
    $routine = Routines::RoutineId($_GET['routine_id']);
    $routine['rooms'] = RoutineRoom::RoutineId($_GET['routine_id']);
} else {
    $routine = [
        "id" => 0,
        "user_id" => UserSession::GetUserId(),
        "name" => "",
        "start_time" => "",
        "end_time" => "",
        "rooms" => []
    ];
}
function RoomName($room_id){
    global $rooms;
    foreach($rooms as $room){
        if($room['id'] == $room_id){
            return $room['name'];
        }
    }
    return "";
}
?>
<dialog id="routine_editor" class="popup">
    <header>
        <h1>Routine</h1>
    </header>
    <div module="Routine">
        <input type="text" id="routine_name" placeholder="Name" required value="<?=$routine['name'];?>" />
        <input type="hidden" id="routine_id" value="<?=$routine['id'];?>" />
        <select id="routine_user_id">
            <?php foreach($users as $user){ ?>
                <option value="<?=$user['id'];?>" <?php if($user['id'] == $routine['user_id']){ echo "selected"; } ?>><?=$user['username'];?></option>
            <?php } ?>
        </select>
        <input type="hidden" id="routine_user_id" value="<?=$routine['user_id'];?>" />
        <fieldset>
            <legend>Suggest Time Window (optional)</legend>
            <input type="time" id="routine_start_time" placeholder="Start Time" value="<?=$routine['start_time'];?>" />
            <input type="time" id="routine_end_time" placeholder="End Time" value="<?=$routine['end_time'];?>" />
        </fieldset>
    </div>
    <div class="timeline" duration="60">
        <ul>
            <?php foreach($routine['rooms'] as $room){ ?>
                <li room_use_id="<?=$room['id'];?>" room_id="<?=$room['room_id'];?>" type="<?=$room['type'];?>" start_delay="<?=$room['start_delay'];?>" length_minutes="<?=$room['length_minutes'];?>" priority="<?=$room['priority'];?>" light_min="<?=$room['light_min'];?>" light_max="<?=$room['light_max'];?>" light_end="<?=$room['light_end'];?>" style="background-image:url(/plugins/NullProfiles/img/rooms/<?=$room['type'];?>.png)"><?=RoomName($room['room_id']);?></li>
            <?php } ?>
        </ul>
    </div>
    <nav>
        <button action="save">Save</button>
        <button action="cancel">Cancel</button>
        <button action="delete">Delete</button>
    </nav>
</dialog>
<dialog id="routine_room_use_editor">
    <select id="routine_room_use_room" var="room_use_id">
        <?php foreach($rooms as $room){ ?>
            <option value="<?=$room['id'];?>"><?=$room['name'];?></option>
        <?php } ?>
    </select>
    <select id="routine_room_use_type" var="type">
        <?php foreach($room_use_types as $type){ ?>
            <option value="<?=$type;?>"><?=$type;?></option>
        <?php } ?>
    </select>
    <input type="number" id="routine_room_use_start_delay" var="start_delay" placeholder="Start Delay" />
    <input type="number" id="routine_room_use_length_minutes" var="length_minutes" placeholder="Length (minutes)" />
    <input type="number" id="routine_room_use_priority" var="priority" placeholder="Priority" />
    <fieldset>
        <legend>Lighting (pick one)</legend>
        <input type="number" id="routine_room_use_light_min" var="light_min" placeholder="Light Min" />
        <input type="number" id="routine_room_use_light_max" var="light_max" placeholder="Light Max" />
        <input type="number" id="routine_room_use_light_end" var="light_end" placeholder="Light End" />
    </fieldset>
    <nav>
        <button action="save_room_use">Save</button>
        <button action="delete_room_use">Delete</button>
        <button action="cancel_room_use">Cancel</button>
    </nav>
</dialog>