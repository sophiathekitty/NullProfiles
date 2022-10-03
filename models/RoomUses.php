<?php
/**
 * define a room use for a user that can be set for specific periods of time.
 * that are like recurring periods of time.
 */
class RoomUses extends clsModel {
    public $table_name = "RoomUses";
    public $fields = [
        [
            'Field'=>"id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>"auto_increment"
        ],[
            'Field'=>"user_id",
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
            'Field'=>"month",
            'Type'=>"int(11)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"day_of_week",
            'Type'=>"int(11)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"start",
            'Type'=>"time",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"stop",
            'Type'=>"time",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ]
    ];
    private static $instance = null;
    /**
     * @return RoomUses|clsModel
     */
    private static function GetInstance(){
        if(is_null(RoomUses::$instance)) RoomUses::$instance = new RoomUses();
        return RoomUses::$instance;
    }
    /**
     * get room uses for a specific user
     * @param int $user_id the user's id
     * @return array list of user's room uses data arrays
     */
    public static function UserId($user_id){
        $instance = RoomUses::GetInstance();
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
        $instance = RoomUses::GetInstance();
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
        $instance = RoomUses::GetInstance();
        $data = $instance->CleanData($data);
        if(isset($data['id'])) return $instance->Save($data,['id'=>$data['id']]);
        return $instance->Save($data);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new RoomUses();
}
?>