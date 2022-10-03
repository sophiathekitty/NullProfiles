<?php
/**
 * profile image stamp generator
 */
class ProfileImageStamp {
    /**
     * get the profile image stamp for a specific user
     * @param int $user_id the user's id
     * @return array the user's profile images
     */
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
    /**
     * parse the images to get their full url
     * @param array list of profile image data arrays
     * @return array the list of image data arrays with the full url to the image
     */
    private static function ParseImages($images){
        for($i = 0; $i < count($images); $i++){
            $server = Servers::ServerMacAddress($images[$i]['mac_address']);
            $images[$i]['url'] = "http://".$server['url'].$images[$i]['url'];
        }
        return $images;
    }
    /**
     * find the default profile images in the folder `/plugins/NullProfiles/img/default/`
     * @return array list of profile image data arrays for default images
     * @note default images need to be subtypes /plugins/NullProfiles/img/default/`subtype`
     */
    public static function FindDefaults(){
        global $root_path;
        $path = $root_path."/plugins/NullProfiles/img/default/";
        $shared_models_dir = opendir($path);
        $images = [];
        
        while ($file = readdir($shared_models_dir)) { 
            if(is_dir($path.$file) && $file != ".." && $file != "."){ 
                $images = ProfileImageStamp::CrawlFolder($images,$file);
            }
        }
        foreach($images as $image){
            if(is_null(ProfileImage::UserImageUrl($image['url']))){
                ProfileImage::SaveImage($image);
            }
        }
        return $images;
    }
    /**
     * crawl the image folder /plugins/NullProfiles/img/`$subtype`/`$type`
     * @param array $images the list of images
     * @param string $type the type of image
     * @param string $subtype the subtype of image
     * @param array the list of images
     */
    private static function CrawlFolder($images,$type,$subtype = "default"){
        global $root_path;
        $path = $root_path."/plugins/NullProfiles/img/$subtype/$type";
        $shared_models_dir = opendir($path);
        // LOOP OVER ALL OF THE  FILES    
        while ($file = readdir($shared_models_dir)) { 
            if(!is_dir($file) && (endsWith($file, '.png') || endsWith($file, '.jpg'))) { 
                $images[] = ["mac_address"=>LocalMac(),"type"=>$type,"subtype"=>$subtype,"file"=>$file,"url"=>"/plugins/NullProfiles/img/$subtype/$type/$file"];
            }
        }
        // CLOSE THE DIRECTORY
        closedir($shared_models_dir);
        return $images;    
    }
}
?>