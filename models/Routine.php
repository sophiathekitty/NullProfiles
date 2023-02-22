<?php
/**
 * define a room use for a user that can be set for specific periods of time.
 * that are like recurring periods of time.
 */
class Routines extends clsModel {
    public $table_name = "Routines";
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
            'Type'=>"varchar(20)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
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
     * @return Routines|clsModel
     */
    private static function GetInstance(){
        if(is_null(Routines::$instance)) Routines::$instance = new Routines();
        return Routines::$instance;
    }
    /**
     * get all routines
     * @return array list of all routines data arrays
     */
    public static function AllRoutines(){
        $instance = Routines::GetInstance();
        return $instance->LoadAll();
    }
    /**
     * get room uses for a specific user
     * @param int $user_id the user's id
     * @return array list of user's routines data arrays
     */
    public static function UserId($user_id){
        $instance = Routines::GetInstance();
        return $instance->LoadAllWhere(['user_id'=>$user_id]);
    }
    /**
     * get a specific routine
     * @param int $routine_id the routine's id
     * @return array routine data arrays
     */
    public static function RoutineId($routine_id){
        $instance = Routines::GetInstance();
        return $instance->LoadWhere(['id'=>$routine_id]);
    }
    /**
     * save a room use
     * @param array $data the room use data array to be saved
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
    public static function SaveRoutine($data){
        $instance = Routines::GetInstance();
        $data = $instance->CleanData($data);
        if(isset($data['id'])) return $instance->Save($data,['id'=>$data['id']]);
        return $instance->Save($data);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new Routines();
}
?>