<?php
/**
 * define a room use for a user that can be set for specific periods of time.
 * that are like recurring periods of time.
 */
class RoutinesLog extends clsModel {
    public $table_name = "RoutinesLog";
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
            'Field'=>"created",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"current_timestamp()",
            'Extra'=>""
        ]
    ];
    private static $instance = null;
    /**
     * @return RoutinesLog|clsModel
     */
    private static function GetInstance(){
        if(is_null(RoutinesLog::$instance)) RoutinesLog::$instance = new RoutinesLog();
        return RoutinesLog::$instance;
    }
    /**
     * get routine log by routine id
     * @param int $routine_id the routine's id
     * @return array list of user's routinesLog data arrays
     */
    public static function RoutineId($routine_id){
        $instance = RoutinesLog::GetInstance();
        return $instance->LoadAllWhere(['routine_id'=>$routine_id]);
    }
    /**
     * has this routine run within the last however many minutes?
     * @param int $routine_id the routine's id
     * @param float $minutes the number of minutes back in time to check
     * @return array list of user's routinesLog data arrays
     */
    public static function RoutineIdRecent($routine_id,$minutes){
        $instance = RoutinesLog::GetInstance();
        $datetime = date("Y-m-d H:i:s",time()-MinutesToSeconds($minutes));
        return $instance->LoadWhereFieldAfter(['routine_id'=>$routine_id],"created",$datetime);
    }
    /**
     * save a room use
     * @param array $data the room use data array to be saved
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
    public static function LogRoutine($data){
        $instance = RoutinesLog::GetInstance();
        $instance->PruneField('created',DaysToSeconds(1));
        $data = $instance->CleanDataSkipId($data);
        if(isset($data['id'])) return $instance->Save($data,['id'=>$data['id']]);
        return $instance->Save($data);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new RoutinesLog();
}
?>