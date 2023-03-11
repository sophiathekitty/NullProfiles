<?php
/**
 * get the user's routines
 * @param int $user_id the user id
 * @return array the list of routines with their steps (RoutineRoom)
 */
function UserRoutines($user_id){
    $routines = Routines::UserId($user_id);
    return LoadRoutinesSteps($routines);
}
/**
 * gets all the routines
 * @return array the list of routines with their steps (RoutineRoom)
 */
function AllRoutines(){
    $routines = Routines::AllRoutines();
    return LoadRoutinesSteps($routines);
}
/**
 * add the steps (RoutineRoom) to the routines in a list of routines
 * @param array $routines a list of routine data arrays
 * @return array the $routines array with ['step'] array added
 */
function LoadRoutinesSteps($routines){
    for($i = 0; $i < count($routines); $i++){
        $routines[$i]['steps'] = RoutineRoom::RoutineId($routines[$i]['id']);
    }
    return $routines;    
}
/**
 * start a routine
 * @param int $routine_id the routine id
 * @return array the started routine
 */
function StartRoutine($routine_id){
    $routine = Routines::RoutineId($routine_id);
    $save = RoutinesLog::LogRoutine($routine);
    Debug::Log("StartRoutine",$routine,$save);
    $routine['steps'] = [];
    $steps = RoutineRoom::RoutineId($routine_id);
    $now = time();
    foreach($steps as $step){
        $step['start'] = date("Y-m-d H:i:s",$now + MinutesToSeconds($step['start_delay']));
        $step['stop'] = date("Y-m-d H:i:s",$now + MinutesToSeconds($step['start_delay']) + MinutesToSeconds($step['length_minutes']));
        $save = RoomUseLog::LogRoomUse($step);
        $routine['steps'][] = $step;
        Debug::Log("StartRoutine",$step,$save);
    }
    return $routine;
}
/**
 * get the routine that is within it's quick run window...
 * @param int $user_id the user id
 * @return array|null the routine within the window
 */
function RoutineWindow($user_id){
    $routines = Routines::UserId($user_id);
    $current = null;
    $now_time = (int)strtotime("2010-01-02 ".date("H:i:s"));
    $last_window_size = null;
    foreach($routines as $routine){
        if(!is_null($routine['start_time']) && !is_null($routine['stop_time'])){
            $start_time = (int)strtotime("2010-01-02 ".$routine['start_time']);
            $stop_time = (int)strtotime("2010-01-02 ".$routine['stop_time']);
            if(
                ($start_time > $stop_time && ($now_time > $start_time || $now_time < $stop_time)) || 
                ($start_time < $stop_time && ($now_time > $start_time && $now_time < $stop_time))
            ) {
                if(is_null($current)){
                    $current = $routine;
                    $last_window_size = RoutineWindowSize($routine,$now_time,$start_time,$stop_time);
                } else {
                    $window_size = RoutineWindowSize($routine,$now_time,$start_time,$stop_time);
                    if($window_size < $last_window_size){
                        $current = $routine;
                        $last_window_size = $window_size;
                    }
                }
            }
        }
    }
    return $current;
}
function RoutineWindowSize($routine,$now_time,$start_time,$stop_time){
    if($now_time > $start_time) $start_delta = $now_time - $start_time;
    else {
        $start_time = (int)strtotime("2010-01-01 ".$routine['start_time']);
        $start_delta = $now_time - $start_time;
    }
    if($now_time < $stop_time) $stop_delta = $start_time - $now_time;
    else {
        $stop_time = (int)strtotime("2010-01-03 ".$routine['start_time']);
        $stop_delta = $start_time - $now_time;
    }
    return $stop_delta + $start_delta;
}
function RoutineRunning($routine_id){
    $routines = RoomUseLog::RoutineRoomUses($routine_id);
    if(count($routines) > 0){
        $first_start = $routines[0]['start'];
        $last_stop = $routines[0]['stop'];
        foreach($routines as $routine){
            if(strtotime($first_start) > strtotime($routine['start'])) $first_start = $routine['start'];
            if(strtotime($last_stop) > strtotime($routine['stop'])) $last_stop = $routine['stop'];
        }
        return (time() > strtotime($first_start) && time() < strtotime($last_stop));
    }
    return false;
}
?>