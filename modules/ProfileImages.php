<?php
class ProfileImageStamp {
    public static function ProfileImages($user_id){
        $images = ProfileImageStamp::ParseImages(ProfileImage::UserImages($user_id));
        $profile = [];
        foreach($images as $image){
            if(!isset($profile[$image['type']])){
                $profile[$image['type']] = [];
            }
            $profile[$image['type']][] = $image;
        }
        return $profile;
    }
    private static function ParseImages($images){
        for($i = 0; $i < count($images); $i++){
            $server = Servers::ServerMacAddress($images[$i]['mac_address']);
            $images[$i]['url'] = "http://".$server['url'].$images[$i]['url'];
        }
        return $images;
    }
    public static function FindDefaults(){
        global $root_path;
        $path = $root_path."/plugins/NullProfiles/img/default/";
        $shared_models_dir = opendir($path);
        $images = [];
        
        while ($file = readdir($shared_models_dir)) { 
            //echo "$path$file\n";
            // IF IT IS NOT A FOLDER, AND ONLY IF IT IS A .php WE ACCESS IT
            if(is_dir($path.$file) && $file != ".." && $file != "."){ 
                //require_once($path.$file);
                $images = ProfileImageStamp::CrawlFolder($images,$file);
                //echo "included\n";
            }
        }
        foreach($images as $image){
            if(is_null(ProfileImage::UserImageUrl($image['url']))){
                ProfileImage::SaveImage($image);
            }
        }
        return $images;
    }
    private static function CrawlFolder($images,$type,$subtype = "default"){
        global $root_path;
        $path = $root_path."/plugins/NullProfiles/img/$subtype/$type";
        $shared_models_dir = opendir($path);
        // LOOP OVER ALL OF THE  FILES    
        while ($file = readdir($shared_models_dir)) { 
            //echo "$path$file\n".!is_dir($file)." & ".(endsWith($file, '.png') || endsWith($file, '.jpg'))." & ".is_file($file)."\n";
            // IF IT IS NOT A FOLDER, AND ONLY IF IT IS A .php WE ACCESS IT
            if(!is_dir($file) && (endsWith($file, '.png') || endsWith($file, '.jpg'))) { 
                //require_once($path.$file);
                $images[] = ["mac_address"=>LocalMac(),"type"=>$type,"subtype"=>$subtype,"file"=>$file,"url"=>"/plugins/NullProfiles/img/$subtype/$type/$file"];
                //echo "included\n";
            }
        }
        // CLOSE THE DIRECTORY
        closedir($shared_models_dir);
        return $images;    
    }
}
?>