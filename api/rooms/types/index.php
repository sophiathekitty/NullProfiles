<?php
require_once("../../../../../includes/main.php");
$types = CrawlRoomUseImages();

function CrawlRoomUseImages(){
    global $root_path;
    $types = [];
    $path = "plugins/NullProfiles/img/rooms/";
    Debug::Log("CrawlRoomUseImages",$root_path.$path);
    $shared_models_dir = opendir($root_path.$path);
    // LOOP OVER ALL OF THE  FILES    
    while ($file = readdir($shared_models_dir)) { 
        Debug::Log("CrawlRoomUseImages",$root_path.$path.$file);
        if(!is_dir($file) && endsWith($file, '.png')>0 && is_file($root_path.$path.$file)) { 
            if($file != "person.png") $types[] = str_replace(".png","",$file);
        }
        
    }
    Debug::Log("CrawlRoomUseImages",$types);
    // CLOSE THE DIRECTORY
    closedir($shared_models_dir);
    return $types;
}
sort($types);
OutputJson(['types'=>$types]);
?>