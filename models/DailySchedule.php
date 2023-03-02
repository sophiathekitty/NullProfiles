<?php
/**
 * define a room use for a user that can be set for specific periods of time.
 * that are like recurring periods of time.
 */
class DailySchedule extends clsModel {
    public $table_name = "DailySchedule";
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
            'Default'=>"workday",
            'Extra'=>""
        ],[
            'Field'=>"January",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"February",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"March",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"April",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"May",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"June",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"July",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"August",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"September",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"October",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"November",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"December",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"Sunday",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"Monday",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"Tuesday",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"Wednesday",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"Thursday",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"Friday",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"Saturday",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ]
    ];
    private static $instance = null;
    /**
     * @return DailySchedule|clsModel
     */
    private static function GetInstance(){
        if(is_null(DailySchedule::$instance)) DailySchedule::$instance = new DailySchedule();
        return DailySchedule::$instance;
    }
    /**
     * get all room uses
     * @param int $user_id the user's id
     * @return array list of user's room uses data arrays
     */
    public static function AllDailySchedules(){
        $instance = DailySchedule::GetInstance();
        return $instance->LoadAll();
    }
    /**
     * get room uses for a specific user
     * @param int $user_id the user's id
     * @return array list of user's room uses data arrays
     */
    public static function UserId($user_id){
        $instance = DailySchedule::GetInstance();
        return $instance->LoadAllWhere(['user_id'=>$user_id]);
    }
    /**
     * get room uses that need to start this minute
     * @return array a list of room uses
     */
    public static function Today(){
        $instance = DailySchedule::GetInstance();
        return $instance->LoadAllWhere([date("l")=>1,date("F")=>1]);
    }
    /**
     * get room uses that need to start this minute
     * @return array a list of room uses
     */
    public static function TodayUserId($user_id){
        $instance = DailySchedule::GetInstance();
        return $instance->LoadAllWhere(['user_id'=>$user_id,date("l")=>1,date("F")=>1]);
    }
    /**
     * calculate the weekly coverage score (how many days of the week this schedule impacts)
     * @param array $data the DailySchedule data array
     * @return int the number of days of the week this runs
     */
    public static function CalculateWeeklyCoverage($data){
        return $data['Sunday'] + $data['Monday'] + $data['Tuesday'] + $data['Wednesday'] + $data['Thursday'] + $data['Friday'] + $data['Saturday'];
    }
    /**
     * calculate the monthly coverage score (how many months out of the year this schedule impacts)
     * @param array $data the DailySchedule data array
     * @return int the number of months out of the year this runs
     */
    public static function CalculateMonthlyCoverage($data){
        return $data['January'] + $data['February'] + $data['March'] + $data['April'] + $data['May'] + $data['June'] + $data['July'] + $data['August'] + $data['September'] + $data['October'] + $data['November'] + $data['December'];
    }
    /**
     * compare coverage between two schedules
     * @param array $dataA the data array for the first schedule
     * @param array $dataB the data array for the second schedule
     * @return bool true if $dataA has more coverage than $dataB
     */
    public static function CompareCoverage($dataA,$dataB){
        return DailySchedule::CalculateCoverage($dataA) > DailySchedule::CalculateCoverage($dataB);
    }
    /**
     * calculate the coverage score (month coverage * weekly)
     * @param array $data the DailySchedule data array
     * @return int the number of days this runs (sorta)
     */
    public static function CalculateCoverage($data){
        return DailySchedule::CalculateMonthlyCoverage($data) * DailySchedule::CalculateWeeklyCoverage($data);
    }
    /**
     * save a room use
     * @param array $data the room use data array to be saved
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
    public static function SaveSchedule($data){
        $instance = DailySchedule::GetInstance();
        $data = $instance->CleanData($data);
        if(isset($data['id'])) return $instance->Save($data,['id'=>$data['id']]);
        return $instance->Save($data);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new DailySchedule();
}
?>