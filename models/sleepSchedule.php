<?php
/**
 * the sleep schedule for a user so they can do stuff like get up at different times
 * depending on what day of the week it is.
 */
class SleepSchedule extends clsModel {
    public $table_name = "SleepSchedule";
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
            'Field'=>"month",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"day_of_week",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"wake_up",
            'Type'=>"time",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"06:00:00",
            'Extra'=>""
        ],[
            'Field'=>"bedtime",
            'Type'=>"time",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"22:00:00",
            'Extra'=>""
        ]
    ];
    private static $instance = null;
    /**
     * @return SleepSchedule|clsModel
     */
    private static function GetInstance(){
        if(is_null(SleepSchedule::$instance)) SleepSchedule::$instance = new SleepSchedule();
        return SleepSchedule::$instance;
    }
    /**
     * get the sleep schedule for a specific user
     * @param int $user_id the user's id
     * @return array list of sleep schedule data arrays
     */
    public static function UserId($user_id){
        $instance = SleepSchedule::GetInstance();
        return $instance->LoadAllWhere(['user_id'=>$user_id]);
    }
    /**
     * get the bedtime for a specific user on a specific month and day of the week
     * @param int $user_id the user's id
     * @param int $month the month
     * @param int $day_of_week the day of the week
     * @return array sleep schedule data array
     */
    public static function Bedtime($user_id,$month,$day_of_week){
        $instance = SleepSchedule::GetInstance();
        $bedtime =  $instance->LoadWhere(['user_id'=>$user_id,'month'=>$month,'day_of_week'=>$day_of_week]);
        if(is_null($bedtime)) $bedtime =  $instance->LoadWhere(['user_id'=>$user_id,'day_of_week'=>$day_of_week]);
        if(is_null($bedtime)) $bedtime =  $instance->LoadWhere(['user_id'=>$user_id,'month'=>$month]);
        return $bedtime;
    }
    /**
     * save a sleep schedule
     * @param array $data the sleep schedule data array
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
    public static function SaveBedtime($data){
        $instance = SleepSchedule::GetInstance();
        $data = $instance->CleanData($data);
        if(isset($data['id'])) return $instance->Save($data,['id'=>$data['id']]);
        return $instance->Save($data);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new SleepSchedule();
}
?>