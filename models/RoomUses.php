<?php

class RoomUses extends clsModel {
    public $table_name = "RoomUses";
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
            'Field'=>"room_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"type",
            'Type'=>"varchar(10)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"bedroom",
            'Extra'=>""
        ],[
            'Field'=>"month",
            'Type'=>"int(11)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"day_of_week",
            'Type'=>"int(11)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"start",
            'Type'=>"time",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"stop",
            'Type'=>"time",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ]
    ];
    private static $instance = null;
    /**
     * @return RoomUses|clsModel
     */
    private static function GetInstance(){
        if(is_null(RoomUses::$instance)) RoomUses::$instance = new RoomUses();
        return RoomUses::$instance;
    }
    public static function UserId($user_id){
        $instance = RoomUses::GetInstance();
        return $instance->LoadAllWhere(['user_id'=>$user_id]);
    }
    public static function RoomTime($room_id,$month,$day_of_week){
        $instance = RoomUses::GetInstance();
        $room =  $instance->LoadWhere(['room_id'=>$room_id,'month'=>$month,'day_of_week'=>$day_of_week]);
        if(is_null($room)) $room =  $instance->LoadWhere(['room_id'=>$room_id,'day_of_week'=>$day_of_week]);
        if(is_null($room)) $room =  $instance->LoadWhere(['room_id'=>$room_id,'month'=>$month]);
        return $room;
    }
    public static function SaveRoomUse($data){
        $instance = RoomUses::GetInstance();
        $data = $instance->CleanData($data);
        if(isset($data['id'])) return $instance->Save($data,['id'=>$data['id']]);
        return $instance->Save($data);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new RoomUses();
}
?>