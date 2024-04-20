<?php
/**
 * define a room use for a user that can be set for specific periods of time.
 * that are like recurring periods of time.
 */
class LinkingRoom extends clsModel {
    public $table_name = "LinkingRoom";
    public $fields = [
        [
            'Field'=>"room_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"light_min",
            'Type'=>"float",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"light_max",
            'Type'=>"float",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"light_end",
            'Type'=>"float",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ]
    ];
    private static $instance = null;
    /**
     * @return LinkingRoom|clsModel
     */
    private static function GetInstance(){
        if(is_null(LinkingRoom::$instance)) LinkingRoom::$instance = new LinkingRoom();
        return LinkingRoom::$instance;
    }
    /** 
     * save a linking room
     * @param array $data json array of linking room (room use)
     * @return array save report
     */
    public static function SaveLinkingRoom($data){
        $linking_room = LinkingRoom::GetInstance();
        $data = $linking_room->CleanData($data);
        $room = $linking_room->LoadWhere(['room_id'=>$data['room_id']]);
        if(is_null($room)){
            return $linking_room->Save($data);
        } else {
            return $linking_room->Save($data,['room_id'=>$data['room_id']]);
        }
    }
    /**
     * get the linking rooms
     * @return array linking rooms
     */
    public static function GetLinkingRooms(){
        $linking_room = LinkingRoom::GetInstance();
        return $linking_room->LoadAll();
    }
    /**
     * get a linking room by room id
     * @param int $room_id the room id
     * @return array linking room
     */
    public static function GetLinkingRoom($room_id){
        $linking_room = LinkingRoom::GetInstance();
        return $linking_room->LoadWhere(['room_id'=>$room_id]);
    }
    /**
     * clear unused linking rooms
     * @param array $rooms the rooms that are currently in use
     * @return array the report of the delete
     */
    public static function ClearUnusedLinkingRooms($rooms){
        $linking_room = LinkingRoom::GetInstance();
        $linking_rooms = $linking_room->LoadAll();
        $delete = [];
        foreach($linking_rooms as $linking_room){
            if(!in_array($linking_room['room_id'],$rooms)) $delete[] = $linking_room['room_id'];
        }
        $report = [];
        foreach($delete as $room_id){
            $report[] = $linking_room->Delete(['room_id'=>$room_id]);
        }
        return $report;
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new LinkingRoom();
}
?>