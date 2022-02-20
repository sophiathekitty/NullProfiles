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
            $images[$i]['url'] = "http://".$server['url']."/plugins/NullProfiles/img/users/".$images[$i]['file'];
        }
        return $images;
    }
}
?>