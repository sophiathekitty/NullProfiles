<?php
/**
 * define a room use for a user that can be set for specific periods of time.
 * that are like recurring periods of time.
 */
class DailyScheduleOverrides extends clsModel {
    public $table_name = "DailyScheduleOverrides";
    public $fields = [
        [
            'Field'=>"guid",
            'Type'=>"varchar(30)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"user_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"name",
            'Type'=>"varchar(100)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"schedule",
            'Type'=>"varchar(10)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"day off",
            'Extra'=>""
        ],[
            'Field'=>"start_date",
            'Type'=>"date",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"stop_date",
            'Type'=>"date",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"created",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"current_timestamp()",
            'Extra'=>""
        ]
    ];
    public $guid_fields = ["user_id","name","schedule","start_date"];
    private static $instance = null;
    /**
     * @return DailyScheduleOverrides|clsModel
     */
    private static function GetInstance(){
        if(is_null(DailyScheduleOverrides::$instance)) DailyScheduleOverrides::$instance = new DailyScheduleOverrides();
        return DailyScheduleOverrides::$instance;
    }
    /**
     * get all room uses
     * @param int $user_id the user's id
     * @return array list of user's room uses data arrays
     */
    public static function AllDailyScheduleOverrides(){
        $instance = DailyScheduleOverrides::GetInstance();
        return $instance->LoadAll();
    }
    /**
     * get room uses for a specific user
     * @param int $user_id the user's id
     * @return array list of user's room uses data arrays
     */
    public static function UserId($user_id){
        $instance = DailyScheduleOverrides::GetInstance();
        return $instance->LoadAllWhere(['user_id'=>$user_id]);
    }
    /**
     * get room uses for a specific user
     * @param int $user_id the user's id
     * @return array list of user's room uses data arrays
     */
    public static function Today(){
        $instance = DailyScheduleOverrides::GetInstance();
        return $instance->LoadFieldsBetween("start_date","stop_date",date("Y-m-d"),['created'=>"DESC"]);
    }
    /**
     * get room uses for a specific user
     * @param int $user_id the user's id
     * @return array list of user's room uses data arrays
     */
    public static function TodayUserId($user_id){
        $instance = DailyScheduleOverrides::GetInstance();
        return $instance->LoadWhereFieldsBetween(["user_id"=>$user_id],"start_date","stop_date",date("Y-m-d H:i:s"));
    }
    /**
     * save a room use
     * @param array $data the room use data array to be saved
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
    public static function SaveDailyScheduleOverride($data){
        $instance = DailyScheduleOverrides::GetInstance();
        $data = $instance->CleanData($data);
        if(isset($data['guid'])) return $instance->Save($data,['guid'=>$data['guid']]);
        $data['guid'] = $instance->MakeGUID($data);
        return $instance->Save($data);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new DailyScheduleOverrides();
}
?>