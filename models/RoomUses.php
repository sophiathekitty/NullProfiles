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
            'Default'=>"home",
            'Extra'=>""
        ],[
            'Field'=>"schedule",
            'Type'=>"varchar(10)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"everyday",
            'Extra'=>""
        ],[
            'Field'=>"priority",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
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
            'Field'=>"start_time",
            'Type'=>"time",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"stop_time",
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
     * get all room uses
     * @param int $user_id the user's id
     * @return array list of user's room uses data arrays
     */
    public static function AllRoomUses(){
        $instance = RoomUses::GetInstance();
        return $instance->LoadAll(['start_time'=>"ASC",'stop_time'=>"ASC"]);
    }
    /**
     * get room uses for a specific user
     * @param int $user_id the user's id
     * @return array list of user's room uses data arrays
     */
    public static function UserId($user_id){
        $instance = RoomUses::GetInstance();
        return $instance->LoadAllWhere(['user_id'=>$user_id],['start_time'=>"ASC",'stop_time'=>"ASC"]);
    }
        /**
     * get room uses for a specific room
     * @param int $room_id the room's id
     * @return array list of user's room uses data arrays
     */
    public static function RoomId($room_id){
        $instance = RoomUses::GetInstance();
        return $instance->LoadAllWhere(['room_id'=>$room_id],['start_time'=>"ASC",'stop_time'=>"ASC"]);
    }
    /**
     * get everyday room uses that need to start this minute
     * @return array a list of room uses
     */
    public static function StartingNow($schedule = "everyday"){
        $instance = RoomUses::GetInstance();
        return $instance->LoadAllWhere(['start_time'=>date("H:i:00"),'schedule'=>$schedule]);
    }
    /**
     * get everyday room uses that need to start this minute
     * @return array a list of room uses
     */
    public static function StartingNowUser($user_id,$schedule = "everyday"){
        $instance = RoomUses::GetInstance();
        return $instance->LoadAllWhere(['user_id'=>$user_id,'start_time'=>date("H:i:00"),'schedule'=>$schedule]);
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