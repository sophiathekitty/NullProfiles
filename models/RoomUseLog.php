<?php
/**
 * define a room use for a user that can be set for specific periods of time.
 * that are like recurring periods of time.
 */
class RoomUseLog extends clsModel {
    public $table_name = "RoomUseLog";
    public $fields = [
        [
            'Field'=>"id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>"auto_increment"
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
            'Default'=>"home",
            'Extra'=>""
        ],[
            'Field'=>"priority",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"light_level",
            'Type'=>"float",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"light_end",
            'Type'=>"float",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"start",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"current_timestamp()",
            'Extra'=>""
        ],[
            'Field'=>"stop",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ]
    ];
    private static $instance = null;
    /**
     * @return RoomUseLog|clsModel
     */
    private static function GetInstance(){
        if(is_null(RoomUseLog::$instance)) RoomUseLog::$instance = new RoomUseLog();
        return RoomUseLog::$instance;
    }
    /**
     * save a room use
     * @param array $data the room use data array to be saved
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
    public static function LogRoomUse($data){
        $instance = RoomUseLog::GetInstance();
        $instance->PruneField("stop",DaysToSeconds(2));
        if(!isset($data['stop']) && isset($data['stop_time'])) $data['stop'] = date('Y-m-d ').$data['stop_time'];
        if(!isset($data['stop']) && isset($data['length_minutes'])) $data['stop'] = date('Y-m-d H:i:s',time()+MinutesToSeconds($data['length_minutes']));
        $data = $instance->CleanDataSkipId($data);
        if(!isset($data['stop'])) $data['stop'] = date('Y-m-d H:i:s',time()+HoursToSeconds(2));
        return $instance->Save($data);
    }
    /**
     * Current Room Uses
     * @param int $room_id
     * @return array|null the current room use
     */
    public static function CurrentRoomUses($room_id){
        $instance = RoomUseLog::GetInstance();
        $uses = $instance->LoadWhereFieldAfter(['room_id'=>$room_id],'stop',date('Y-m-d H:i:s'));
        if(count($uses) == 0) return null;
        //usort($uses,"sort_room_uses_by_priority_desc");
        $active_uses = [];
        $now = time();
        foreach($uses as $use){
            if(strtotime($use['start']) < $now && $now < strtotime($use['stop'])) $active_uses[] = $use;
        }
        if(count($active_uses) == 0) return null;
        return $active_uses;
        /*
        if(count($active_uses) == 1) return $active_uses[0];
        $light_max = 0;
        $light_max_count = 0;
        $light_min = 0;
        $light_min_count = 0;
        $light_end = 0;
        $light_end_count = 0;
        $end = time();
        $types = [];
        $priority = 0;
        foreach($active_uses as $use){
            if(isset($types[$use['type']])) $types[$use['type']]++;
            else $types[$use['type']] = 1;
            if(!is_null($use['light_max'])){
                $light_max += $use['light_max'];
                $light_max_count++;
            }
            if(!is_null($use['light_min'])){
                $light_min += $use['light_min'];
                $light_min_count++;
            }
            if(!is_null($use['light_end'])){
                $light_end += $use['light_end'];
                $light_end_count++;
            }
            $stop = strtotime($use['stop']);
            if($stop > $end) $end = $stop;
            if($priority < $use['priority']) $priority = $use['priority'];
        }
        if($light_max_count + $light_min_count + $light_end_count > 0){
            $type = "home";
            $c = 0;
            foreach($types as $t => $count){
                if($count > $c){
                    $type = $t;
                    $c = $count;
                }
            }
            $use = ['type'=>$type,'light_min'=>null,'light_max'=>null,'light_end'=>null,'stop'=>date('Y-m-d H:i:s',$end),'priority'=>$priority];
            if($light_max_count > 0) $use['light_max'] /= $light_max_count;
            if($light_min_count > 0) $use['light_min'] /= $light_min_count;
            if($light_end_count > 0) $use['light_end'] /= $light_end_count;
            return $use;
        }
        */
        return null;
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new RoomUseLog();
}
?>