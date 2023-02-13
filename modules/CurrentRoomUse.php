<?php
/**
 * gets the current room use
 * @param int $room_id the room id
 * @return array|null the data array of the current room use or null if none
 */
function CurrentRoomUse($room_id){
    $uses = [];
    // scheduled room uses
    $room_uses = RoomUses::RoomNow($room_id);
    $now = time();
    foreach($room_uses as $use){
        $use = ParseRoomUseScheduleTimes($use);
        $start_time = strtotime($use['start']);
        $stop_time = strtotime($use['stop']);
        if($start_time < $now && $now < $stop_time) $uses[] = $use;
    }
    $routines = RoomUseLog::CurrentRoomUses($room_id);
    if(!is_null($routines)){
        $uses = array_merge($uses,$routines);
    }
    usort($uses,"sort_room_uses_by_priority_desc");
    if(count($uses) > 0) return $uses[0];
    return null;
}
function ParseRoomUseScheduleTimes($use){
    $start_time = (int)strtotime("2010-01-02 ".$use['start_time']);
    $stop_time = (int)strtotime("2010-01-02 ".$use['stop_time']);
    Debug::Log("ParseRoomUseScheduleTimes",$start_time,$stop_time,$use);
    if($start_time < $stop_time){
        // stops the same day
        $use['start'] = date("Y-m-d ").$use['start_time'];
        $use['stop'] = date("Y-m-d ").$use['stop_time'];
    } else {
        // stops the next day
        $now_time = (int)strtotime("2010-01-02 ".date("H:i:s"));
        if($now_time > $start_time){
            // started today
            $use['start'] = date("Y-m-d ").$use['start_time'];
            $use['stop'] = date("Y-m-d ",time()+DaysToSeconds(1)).$use['stop_time'];
        } elseif($now_time < $stop_time) {
            // started yesterday
            $use['start'] = date("Y-m-d ",time()-DaysToSeconds(1)).$use['start_time'];
            $use['stop'] = date("Y-m-d ").$use['stop_time'];
        } else {
            // has not started
            $use['start'] = date("Y-m-d ").$use['start_time'];
            $use['stop'] = date("Y-m-d ").$use['stop_time'];
        }
    }
    return $use;
}
/**
 * will check for scheduled room uses that are starting this minute and log those room uses
 */
function CheckForRoomUseStart(){
    Services::Start("NullProfile::CheckForRoomUseStart");
    $uses = RoomUses::StartingNow();
    foreach($uses as $use){
        $type = $use['type'];
        Services::Start("NullProfile::CheckForRoomUseStart::$type");
        $use = ParseRoomUseScheduleTimes($use);
        $user = Users::UserId($use['user_id']);
        $room = Rooms::RoomId($use['room_id']);
        $save = RoomUseLog::LogRoomUse($use);
        Services::Log("NullProfile::CheckForRoomUseStart::$type",$room['name'].": ".$user['username']." ".$use['type'].". ".$save['error']);
        Services::Log("NullProfile::CheckForRoomUseStart",$room['name'].": ".$user['username']." ".$use['type'].". ".$save['error']);
        Services::Complete("NullProfile::CheckForRoomUseStart::$type");
    }
    Services::Complete("NullProfile::CheckForRoomUseStart");
}

function sort_room_uses_by_priority_desc($a,$b){
    if($a['priority'] == $b['priority']){
        if(isset($a['stop'])) $stopA = strtotime($a['stop']);
        if(isset($b['stop'])) $stopB = strtotime($b['stop']);
        if(isset($a['stop_time'])) $stopA = strtotime(date("Y-m-d ").$a['stop_time']);
        if(isset($b['stop_time'])) $stopB = strtotime(date("Y-m-d ").$b['stop_time']);
        if($stopA == $stopB) {
            if(isset($a['start'])) $startA = strtotime(date("Y-m-d ").$a['start']);
            if(isset($b['start'])) $startB = strtotime(date("Y-m-d ").$b['start']);
            if(isset($a['start_time'])) $startA = strtotime(date("Y-m-d ").$a['start_time']);
            if(isset($b['start_time'])) $startB = strtotime(date("Y-m-d ").$b['start_time']);
            if($startA == $startB) return 0;
            return ($startA > $startB) ? -1 : 1;
        } 
        return ($stopA < $stopB) ? -1 : 1;
    } 
    return ($a['priority'] > $b['priority']) ? -1 : 1;
}
?>