<?php


class ProfileImage extends clsModel {
    public $table_name = "ProfileImage";
    public $fields = [
        [
            'Field'=>"id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>"auto_increment"
        ],[
            'Field'=>"mac_address",
            'Type'=>"varchar(100)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"file",
            'Type'=>"varchar(50)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>"0"
        ],[
            'Field'=>"url",
            'Type'=>"varchar(200)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>"0"
        ],[
            'Field'=>"user_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"type",
            'Type'=>"varchar(12)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"icon",
            'Extra'=>""
        ],[
            'Field'=>"created",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"current_timestamp()",
            'Extra'=>"on update current_timestamp()"
        ]
    ];
    private static $instance = null;
    /**
     * @return ProfileImage|clsModel
     */
    private static function GetInstance(){
        if(is_null(ProfileImage::$instance)) ProfileImage::$instance = new ProfileImage();
        return ProfileImage::$instance;
    }
    public static function UserImages($user_id){
        $instance = ProfileImage::GetInstance();
        return $instance->LoadAllWhere(['user_id'=>$user_id]);
    }
    public static function Images(){
        $instance = ProfileImage::GetInstance();
        return $instance->LoadAll();
    }
    public static function SaveImage($data){
        $instance = ProfileImage::GetInstance();
        $data = $instance->CleanData($data);
        if(isset($data['id'])) return $instance->Save($data,['id'=>$data['id']]);
        return $instance->Save($data);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new ProfileImage();
}
?>