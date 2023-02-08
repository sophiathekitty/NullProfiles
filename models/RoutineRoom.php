<?php
/**
 * define a room use for a user that can be set for specific periods of time.
 * that are like recurring periods of time.
 */
class RoutineRoom extends clsModel {
    public $table_name = "RoutineRoom";
    public $fields = [
        [
            'Field'=>"id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>"auto_increment"
        ],[
            'Field'=>"routine_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"room_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"type",
            'Type'=>"varchar(10)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"bedroom",
            'Extra'=>""
        ],[
            'Field'=>"priority",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"2",
            'Extra'=>""
        ],[
            'Field'=>"light_min",
            'Type'=>"float",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"light_max",
            'Type'=>"float",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"light_end",
            'Type'=>"float",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"start_delay",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"length_minutes",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"60",
            'Extra'=>""
        ]
    ];
    private static $instance = null;
    /**
     * @return RoutineRoom|clsModel
     */
    private static function GetInstance(){
        if(is_null(RoutineRoom::$instance)) RoutineRoom::$instance = new RoutineRoom();
        return RoutineRoom::$instance;
    }
    /**
     * get room uses for a specific user
     * @param int $user_id the user's id
     * @return array list of user's room uses data arrays
     */
    public static function UserId($user_id){
        $instance = RoutineRoom::GetInstance();
        return $instance->LoadAllWhere(['user_id'=>$user_id]);
    }
    /**
     * get the room use for a specific month and day of the week
     * @param int $room_id the room's id
     * @param int $month the month
     * @param int $day_of_week the day of the week
     * @return array the data array for the room use
     * @warning what if there's more than one room use? 
     * is that just not allowed? how am i going to prevent that?
     */
    public static function RoomTime($room_id,$month,$day_of_week){
        $instance = RoutineRoom::GetInstance();
        $room =  $instance->LoadWhere(['room_id'=>$room_id,'month'=>$month,'day_of_week'=>$day_of_week]);
        if(is_null($room)) $room =  $instance->LoadWhere(['room_id'=>$room_id,'day_of_week'=>$day_of_week]);
        if(is_null($room)) $room =  $instance->LoadWhere(['room_id'=>$room_id,'month'=>$month]);
        return $room;
    }
    /**
     * save a room use
     * @param array $data the room use data array to be saved
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
    public static function SaveRoomUse($data){
        $instance = RoutineRoom::GetInstance();
        $data = $instance->CleanData($data);
        if(isset($data['id'])) return $instance->Save($data,['id'=>$data['id']]);
        return $instance->Save($data);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new RoutineRoom();
}
?>