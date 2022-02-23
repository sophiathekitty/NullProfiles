<?php

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
    public static function UserId($user_id){
        $instance = SleepSchedule::GetInstance();
        return $instance->LoadAllWhere(['user_id'=>$user_id]);
    }
    public static function Bedtime($user_id,$month,$day_of_week){
        $instance = SleepSchedule::GetInstance();
        $bedtime =  $instance->LoadWhere(['user_id'=>$user_id,'month'=>$month,'day_of_week'=>$day_of_week]);
        if(is_null($bedtime)) $bedtime =  $instance->LoadWhere(['user_id'=>$user_id,'day_of_week'=>$day_of_week]);
        if(is_null($bedtime)) $bedtime =  $instance->LoadWhere(['user_id'=>$user_id,'month'=>$month]);
        return $bedtime;
    }
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