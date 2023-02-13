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
     * get room uses for a routine
     * @param int $routine_id the routine's id
     * @return array list of user's routinesLog data arrays
     */
    public static function RoutineId($routine_id){
        $instance = RoutinesLog::GetInstance();
        return $instance->LoadAllWhere(['routine_id'=>$routine_id]);
    }
    /**
     * save a room use
     * @param array $data the room use data array to be saved
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
    public static function SaveRoutine($data){
        $instance = RoutinesLog::GetInstance();
        $data = $instance->CleanData($data);
        if(isset($data['id'])) return $instance->Save($data,['id'=>$data['id']]);
        return $instance->Save($data);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new RoutinesLog();
}
?>