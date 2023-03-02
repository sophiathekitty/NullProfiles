<?php
/**
 * gets the current room use
 * @param int $room_id the room id
 * @return array|null the data array of the current room use or null if none
 */
function CurrentRoomUse($room_id){
    $uses = [];
    $routines = RoomUseLog::CurrentRoomUses($room_id);
    if(!is_null($routines)){
        $uses = array_merge($uses,$routines);
    }
    //usort($uses,"sort_room_uses_by_priority_desc");
    if(count($uses) > 0) return MergeRoomUsesOfTopPriority($uses);
    return null;
}
/**
 * adds the start datetime and stop datetime from the start_time and stop_time
 * @param array $use RoomUses data array
 * @return array the RoomUses with the RoomUseLog start and stop fields
 */
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
 * merges the room uses with the highest priority skips all with a lesser priority
 * @param array $uses a list of RoomUses/RoomUseLog data arrays
 * @return array a data array with the merged data
 */
function MergeRoomUsesOfTopPriority($uses){
    if(!is_array($uses) ||count($uses) == 0) return null;
    if(count($uses) == 1){
        $uses[0]['title'] = $uses[0]['type'];
        if(!is_null($uses[0]['light_min'])) $uses[0]['light_level'] = $uses[0]['light_min'];
        if(!is_null($uses[0]['light_max'])) $uses[0]['light_level'] = $uses[0]['light_max'];
        return $uses[0];
    } 
    usort($uses,"sort_room_uses_by_priority_desc");
    $roomUses = [];
    $priority = $uses[0]['priority'];
    foreach($uses as $use){
        if($use['priority'] >= $priority) $roomUses[] = $use;
    }
    $room_use = ['light_level'=>0,'light_min'=>null,'light_max'=>null,'light_end'=>null];
    $useTypes = [];
    $light_end_count = 0;
    foreach($roomUses as $use){
        if(isset($useTypes[$use['type']])) $useTypes[$use['type']]++;
        else $useTypes[$use['type']] = 1;
        if(!is_null($use['light_min'])){
            if(is_null($room_use['light_min']) || $use['light_min'] > $room_use['light_min']) $room_use['light_min'] = $use['light_min'];
        }
        if(!is_null($use['light_max'])){
            if(is_null($room_use['light_max']) || $use['light_max'] < $room_use['light_max']) $room_use['light_max'] = $use['light_max'];
        }
        if(!is_null($use['light_end'])){
            if(is_null($room_use['light_end'])){
                $room_use['light_end'] = $use['light_end'];
                $light_end_count = 1;
            } else {
                $light_end_count++;
                $room_use['light_end'] += $use['light_end'];
            }
        }
        if($light_end_count > 0){
            $room_use['light_end'] /= $light_end_count;
        }
    }
    $first = true;
    $type = "";
    $count = 0;
    Debug::Log("MergeRoomUsesOfTopPriority","useTypes",$useTypes);
    foreach($useTypes as $type => $value){
        if($first){
            $room_use['type'] = $room_use['title'] = $type;
            $count = $value;
            $first = false;
        } else {
            Debug::Log("MergeRoomUsesOfTopPriority","title:type",$room_use['title'],$type,strpos($room_use['title'],$type));
            if(strpos($room_use['title'],$type) === false) $room_use['title'] .= ", ".$type;
            if($value > $count){
                $room_use['type'] = $type;
                $count = $value;
            }
        }
    }
    if(!is_null($room_use['light_min'])) $room_use['light_level'] = $room_use['light_min'];
    if(!is_null($room_use['light_max']) &&
        isset($room_use['light_level']) && 
        $room_use['light_level'] > $room_use['light_max']) 
            $room_use['light_level'] = $room_use['light_max'];
    if(!isset($room_use['light_level'])){
        if(!is_null($room_use['light_end'])) $room_use['light_level'] = $room_use['light_end'];
        else $room_use['light_level'] = 0;
    } 
    return $room_use;
}
/**
 * [service|NullProfile::CheckForRoomUseStart] 
 * will check for scheduled room uses that are starting this minute and log those room uses
 * creates RoomUseLog for any RoomUse that is starting this minute
 */
function CheckForRoomUseStart(){
    Services::Start("NullProfile::CheckForRoomUseStart");
    StartRoomUses(RoomUses::StartingNow());
    /*
    $uses = RoomUses::StartingNow();
    foreach($uses as $use){
        $type = $use['type'];
        //Services::Start("NullProfile::CheckForRoomUseStart::$type");
        $use = ParseRoomUseScheduleTimes($use);
        $user = Users::UserId($use['user_id']);
        $room = Rooms::RoomId($use['room_id']);
        $save = RoomUseLog::LogRoomUse($use);
        //Services::Log("NullProfile::CheckForRoomUseStart::$type",$room['name'].": ".$user['username']." ".$use['type'].". ".$save['error']);
        //Services::Log("NullProfile::CheckForRoomUseStart",$room['name'].": ".$user['username']." ".$use['type'].". ".$save['error']);
        //Services::Complete("NullProfile::CheckForRoomUseStart::$type");
    }
    */
    $schedules = DailySchedule::Today();
    $user_schedules = [];
    foreach($schedules as $schedule){
        if(!isset($user_schedules[$schedule['user_id']]) || DailySchedule::CompareCoverage($user_schedules[$schedule['user_id']],$schedule)) {
            $user_schedules[$schedule['user_id']] = $schedule;
        }
    }
    $schedules = DailyScheduleOverrides::Today();
    $user_schedule_overrides = []; // holder so only the first override for each user get's applied
    foreach($schedules as $schedule){
        if(!isset($user_schedule_overrides[$schedule['user_id']])) {
            $user_schedule_overrides[$schedule['user_id']] = $schedule;
            $user_schedules[$schedule['user_id']] = $schedule;
        }
    }
    foreach($user_schedules as $schedule){
        // start the room use that starts now with the matching schedule tag
        StartRoomUses(RoomUses::StartingNowUser($schedule['user_id'],$schedule['schedule']));
    }
    Services::Complete("NullProfile::CheckForRoomUseStart");
}
/**
 * [service|NullProfile::CheckForRoomUseStart::$type]
 * start a list of room uses
 */
function StartRoomUses($uses){
    foreach($uses as $use){
        $type = $use['type'];
        Services::Start("NullProfile::StartRoomUses::$type");
        $use = ParseRoomUseScheduleTimes($use);
        $user = Users::UserId($use['user_id']);
        $room = Rooms::RoomId($use['room_id']);
        $save = RoomUseLog::LogRoomUse($use);
        Services::Log("NullProfile::StartRoomUses::$type",$room['name'].": ".$user['username']." ".$use['type'].". ".$save['error']);
        Services::Log("NullProfile::CheckForRoomUseStart",$room['name'].": ".$user['username']." ".$use['type'].". ".$save['error']);
        Services::Complete("NullProfile::StartRoomUses::$type");
    }
}
/**
 * sort function for room uses by priority and when they stop and start
 */
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