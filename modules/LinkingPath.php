<?php
/**
 * LinkingRooms
 * finds the rooms between the rooms that are currently in use
 */
class LinkingRooms {
    /**
     * findPath
     * uses the BFS algorithm to find the shortest path between two rooms
     * @param  int $room1 the room_id of the first room
     * @param  int $room2 the room_id of the second room
     * @return array the list of room_ids in the path
     */
    public static function FindPath($room1, $room2){
        $path = [];
        if($room1 == $room2) return $path;
        $queue = new SplQueue();
        $queue->enqueue([$room1]);
        $visited = [$room1];
        $i = 0;
        while(!$queue->isEmpty() && $i++ < 1000){
            $path = $queue->dequeue();
            $last = $path[count($path)-1];
            $rooms = RoomNeighbors::Neighbors($last);
            Debug::Log("neighbors: ",$rooms);
            foreach($rooms as $room){
                $room_id = $room['room_id'] == $last ? $room['neighbor_id'] : $room['room_id'];
                if(!in_array($room_id,$visited)){
                    $visited[] = $room_id;
                    $new_path = $path;
                    $new_path[] = $room_id;
                    $queue->enqueue($new_path);
                    if($room_id == $room2) return $new_path;
                }
            }
        }
        return ["error"=>"path not found? did $i / 1000 loops?",'start'=>$room1,'end'=>$room2,"path"=>$path];
    }
    /**
     * verboseFindPath
     * returns the room data instead of the room_ids
     * @param  int $room1 the room_id of the first room
     * @param  int $room2 the room_id of the second room
     * @return array the list of room names in the path
     */
    public static function VerboseFindPath($room1, $room2){
        $path = self::FindPath($room1,$room2);
        if(isset($path['error'])) return $path;
        $rooms = [];
        foreach($path as $room){
            $rooms[] = Rooms::RoomId($room);
        }
        return $rooms;
    }
    /**
     * Find All Paths
     * finds all paths between the rooms currently in use
     */
    public static function FindAllPaths($unique_rooms = null){
        if($unique_rooms == null){
            $rooms = RoomUseLog::ActiveRoomUses();
            $unique_rooms = [];
            foreach($rooms as $room){
                if(!in_array($room['room_id'],$unique_rooms)) $unique_rooms[] = $room['room_id'];
            }    
        } 
        $paths = [];
        //$paths[] = $unique_rooms;
        foreach($unique_rooms as $room1){
            // remove room1 from the list of rooms
            $unique_rooms = array_diff($unique_rooms,[$room1]);
            foreach($unique_rooms as $room2){
                if($room1 != $room2){
                    $path = self::FindPath($room1,$room2);
                    // if not error add the path to the list of paths
                    if(!isset($path['error'])) $paths[] = $path;
                }
            }
        }        
        return $paths;
    }
    /**
     * Find All Linking Rooms
     * finds all rooms that are linked to the rooms currently in use
     */
    public static function FindAllLinkingRooms($unique_rooms = null){
        $linking_rooms = [];
        if($unique_rooms == null){
            $rooms = RoomUseLog::ActiveRoomUses();
            $unique_rooms = [];
            foreach($rooms as $room){
                if(!in_array($room['room_id'],$unique_rooms)) $unique_rooms[] = $room['room_id'];
            }
        }
        $paths = self::FindAllPaths($unique_rooms);
        // find all the rooms in the paths that aren't in the rooms array ($rooms[0]['room_id'])
        foreach($paths as $path){
            foreach($path as $room){
                if(!in_array($room,$unique_rooms)) $linking_rooms[] = $room;
            }
        }
        return $linking_rooms;
    }
    /**
     * Find All Linking Rooms Verbose
     * finds all rooms that are linked to the rooms currently in use
     */
    public static function FindAllLinkingRoomsVerbose($cache = true){
        $rooms = RoomUseLog::ActiveRoomUses();
        $unique_rooms = [];
        $light_max = null;
        $light_min = null;
        $light_end = null;
        foreach($rooms as $room){
            if(!in_array($room['room_id'],$unique_rooms)) $unique_rooms[] = $room['room_id'];
            if(!is_null($room['light_max']) && (is_null($light_max) || $room['light_max'] < $light_max)) $light_max = $room['light_max'];
            if(!is_null($room['light_min']) && (is_null($light_min) || $room['light_min'] > $light_min)) $light_min = $room['light_min'];
            if(!is_null($room['light_end'])){
                if(is_null($light_end)) $light_end = $room['light_end'];
                else $light_end += $room['light_end'];
            }
        }
        if(!is_null($light_end)) $light_end /= count($rooms);
        $linking_rooms = self::FindAllLinkingRooms($unique_rooms);
        $rooms = [];
        foreach($linking_rooms as $room){
            $rooms[] = Rooms::RoomId($room);
            if($cache) LinkingRoom::SaveLinkingRoom(['room_id'=>$room,'light_min'=>$light_min,'light_max'=>$light_max,'light_end'=>$light_end]);
        }
        LinkingRoom::ClearUnusedLinkingRooms($linking_rooms);
        return $rooms;
    }
    /**
     * Calculate Linking Rooms
     * finds all rooms that are linked to the rooms currently in use and caches the results
     */
    public static function CalculateLinkingRooms(){
        $rooms = RoomUseLog::ActiveRoomUses();
        $unique_rooms = [];
        $light_max = null;
        $light_min = null;
        $light_end = null;
        $light_end_count = 0;
        foreach($rooms as $room){
            if(!is_null($room['light_max']) || (!is_null($room['light_min']) && (int)$room['light_min'] == 0)) continue;
            if(!in_array($room['room_id'],$unique_rooms)) $unique_rooms[] = $room['room_id'];
            //if(!is_null($room['light_max']) && (is_null($light_max) || $room['light_max'] < $light_max)) $light_max = $room['light_max'];
            if(!is_null($room['light_min']) && (is_null($light_min) || $room['light_min'] > $light_min)) $light_min = $room['light_min'];
            if(!is_null($room['light_end'])){
                if(is_null($light_end)) $light_end = $room['light_end'];
                else $light_end += $room['light_end'];
                $light_end_count++;
            }
        }
        if(!is_null($light_end)) $light_end /= $light_end_count;
        $linking_rooms = self::FindAllLinkingRooms($unique_rooms);
        $rooms = [];
        foreach($linking_rooms as $room){
            LinkingRoom::SaveLinkingRoom(['room_id'=>$room,'light_min'=>$light_min,'light_max'=>$light_max,'light_end'=>$light_end]);
        }
        LinkingRoom::ClearUnusedLinkingRooms($linking_rooms);
    }
}
?>