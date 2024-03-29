<?php
/**
 * linking table for user's pronouns. stores a user's relation to a set of pronouns
 * like is this their preferred pronouns (if they have more than one set but like one set the most)
 */
class UserPronouns extends clsModel {
    public $table_name = "UserPronouns";
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
            'Field'=>"pronouns_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"ordering",
            'Type'=>"int(11)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"preferred",
            'Type'=>"tinyint(2)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"modified",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"current_timestamp()",
            'Extra'=>"on update current_timestamp()"
        ]
    ];
    private static $instance = null;
    /**
     * @return UserPronouns|clsModel
     */
    private static function GetInstance(){
        if(is_null(UserPronouns::$instance)) UserPronouns::$instance = new UserPronouns();
        return UserPronouns::$instance;
    }
    /**
     * get the user pronouns by the user pronouns id. why though?
     * @param int $id the user pronouns id
     * @return array the data array for a user pronouns
     */
    public static function PronounsId($id){
        $instance = UserPronouns::GetInstance();
        return $instance->LoadWhere(['id'=>$id]);
    }
    /**
     * get a user's pronouns. will give them default pronouns
     * if their pronouns are unknown. multiple pronouns are sorted by preferred value
     * @param int $user_id the user's id
     * @return array ordered list of the user's pronouns
     */
    public static function Pronouns($user_id){
        $instance = UserPronouns::GetInstance();
        $pronouns = $instance->LoadAllWhere(['user_id'=>$user_id],['preferred'=>"DESC","ordering"=>"ASC"]);
        if(count($pronouns) == 0){
            $instance->Save(['user_id'=>$user_id]);
            $pronouns = $instance->LoadAllWhere(['user_id'=>$user_id],['preferred'=>"DESC","ordering"=>"ASC"]);
        }
        return $pronouns;
    }
    /**
     * save user pronouns
     * @param array $data the data array for user pronouns
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
    public static function SavePronouns($data){
        $instance = UserPronouns::GetInstance();
        $data = $instance->CleanData($data);
        if(isset($data['id'])) return $instance->Save($data,['id'=>$data['id']]);
        return $instance->Save($data);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new UserPronouns();
}
?>