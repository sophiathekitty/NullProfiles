<?php
/**
 * define a room use for a user that can be set for specific periods of time.
 * that are like recurring periods of time.
 */
class RoomUseLog extends clsModel {
    public $table_name = "RoomUseLog";
    public $fields = [
        [
            'Field'=>"id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>"auto_increment"
        ],[
            'Field'=>"room_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"user_id",
            'Type'=>"int(11)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"routine_id",
            'Type'=>"int(11)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"type",
            'Type'=>"varchar(10)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"home",
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
            'Field'=>"start",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"current_timestamp()",
            'Extra'=>""
        ],[
            'Field'=>"stop",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ]
    ];
    private static $instance = null;
    /**
     * @return RoomUseLog|clsModel
     */
    private static function GetInstance(){
        if(is_null(RoomUseLog::$instance)) RoomUseLog::$instance = new RoomUseLog();
        return RoomUseLog::$instance;
    }
    /**
     * save a room use
     * @param array $data the room use data array to be saved
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
    public static function LogRoomUse($data){
        $instance = RoomUseLog::GetInstance();
        $instance->PruneField("stop",DaysToSeconds(1));
        if(!isset($data['stop']) && isset($data['stop_time'])) $data['stop'] = date('Y-m-d ').$data['stop_time'];
        if(!isset($data['stop']) && isset($data['length_minutes'])) $data['stop'] = date('Y-m-d H:i:s',time()+MinutesToSeconds($data['length_minutes']));
        $data = $instance->CleanDataSkipId($data);
        if(!isset($data['stop'])) $data['stop'] = date('Y-m-d H:i:s',time()+HoursToSeconds(2));
        return $instance->Save($data);
    }
    /**
     * Current Room Uses
     * @param int $room_id
     * @return array|null the current room use
     */
    public static function CurrentRoomUses($room_id){
        $instance = RoomUseLog::GetInstance();
        $uses = $instance->LoadWhereFieldAfter(['room_id'=>$room_id],'stop',date('Y-m-d H:i:s'));
        if(count($uses) == 0) return null;
        //usort($uses,"sort_room_uses_by_priority_desc");
        $active_uses = [];
        $now = time();
        foreach($uses as $use){
            if(strtotime($use['start']) < $now && $now < strtotime($use['stop'])) $active_uses[] = $use;
        }
        if(count($active_uses) == 0) return null;
        return $active_uses;
    }
    /**
     * get the room uses for a specific routine
     * @param int $routine_id the routine id
     * @return array the list of room uses
     */
    public static function RoutineRoomUses($routine_id){
        $instance = RoomUseLog::GetInstance();
        return $instance->LoadAllWhere(['routine_id'=>$routine_id]);
    }
    /**
     * get the room uses for a specific user
     * @param int $user_id the user id
     * @return array the list of room uses
     */
    public static function UserRoomUses($user_id){
        $instance = RoomUseLog::GetInstance();
        return $instance->LoadAllWhere(['user_id'=>$user_id]);
    }
    /**
     * get the room uses for a specific routine
     * @param int $routine_id the routine id
     * @return array the list of room uses
     */
    public static function StartedRoutineRoomUses($routine_id){
        $instance = RoomUseLog::GetInstance();
        return $instance->LoadWhereFieldAfter(['routine_id'=>$routine_id],"start",date("Y-m-d H:i:s"));
    }
    /**
     * get the room uses for a specific user
     * @param int $user_id the user id
     * @return array the list of room uses
     */
    public static function StartedUserRoomUses($user_id){
        $instance = RoomUseLog::GetInstance();
        return $instance->LoadWhereFieldAfter(['user_id'=>$user_id],"start",date("Y-m-d H:i:s"));
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new RoomUseLog();
}
?>