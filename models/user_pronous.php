<?php


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
    public static function PronounsId($id){
        $instance = UserPronouns::GetInstance();
        return $instance->LoadWhere(['id'=>$id]);
    }
    public static function Pronouns($user_id){
        $instance = UserPronouns::GetInstance();
        return $instance->LoadAllWhere(['user_id'=>$user_id],['preferred'=>"DESC","ordering"=>"ASC"]);
    }
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