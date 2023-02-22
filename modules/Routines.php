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
?>