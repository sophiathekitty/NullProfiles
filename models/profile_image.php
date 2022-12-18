<?php

/**
 * stores the location of a profile image that could be on this device or another device
 * as well as additional information about the image
 */
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
            'Field'=>"guid",
            'Type'=>"varchar(100)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
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
            'Default'=>"icons",
            'Extra'=>""
        ],[
            'Field'=>"subtype",
            'Type'=>"varchar(12)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"uploads",
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
    /**
     * get all of the profile pictures for a specific user
     * @param int $user_id the user's id
     * @return array array of profile image data arrays
     */
    public static function UserImages($user_id){
        $instance = ProfileImage::GetInstance();
        return $instance->LoadAllWhere(['user_id'=>$user_id]);
    }
    /**
     * get an image by it's guid
     * @param string $guid the image's guid from ImageFile::MakeGUID()
     * @return array|null returns data array for image or null if it wasn't found
     */
    public static function UserImageGUID($guid){
        $instance = ProfileImage::GetInstance();
        return $instance->LoadWhere(['guid'=>$guid]);
    }
    /**
     * get all the images
     * @return array an array of all the profile image data arrays
     */
    public static function Images(){
        $instance = ProfileImage::GetInstance();
        return $instance->LoadAll();
    }
    /**
     * save a profile image
     * @param array $data the data array of profile image to save
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
    public static function SaveImage($data){
        $instance = ProfileImage::GetInstance();
        if(isset($data['path'],$data['file'])) $data['guid'] == ImageFile::MakeGUID($data['path'],$data['file']);
        $data = $instance->CleanData($data);
        if(isset($data['id'])) return $instance->Save($data,['id'=>$data['id']]);
        return $instance->Save($data);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new ProfileImage();
}
?>