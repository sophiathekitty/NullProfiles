<?php
class UserProfileStamp {
    public static function UserProfile($user_id){
        $profile = ['id'=>$user_id];
        $user = new Users();
        $user = $user->GetUserId($user_id);
        $profile['username'] = $user['username'];
        $profile['bedtime'] = $user['bedtime'];
        $profile['awake_time'] = $user['awake_time'];
        $profile['pronouns'] = UserProfileStamp::Pronouns($user_id);
        $profile['images'] = ProfileImageStamp::ProfileImages($user_id);
        $profile['rooms'] = UserProfileStamp::Rooms($user_id);
        $profile['schedule'] = SleepSchedule::Bedtime($user_id,date("n"),date("w"));
        if(count($profile['rooms']) == 0){
            if($user['bedroom_id']){
                RoomUses::SaveRoomUse(['user_id'=>$user_id,'room_id'=>$user['bedroom_id']]);
                $profile['rooms'] = RoomUses::UserId($user_id);
            }
        }

        return $profile;
    }
    public static function Pronouns($user_id){
        $profile = UserPronouns::Pronouns($user_id);
        for($i = 0; $i < count($profile); $i++){
            $pronouns = PronounSets::PronounsId($profile[$i]['pronouns_id']);
            $profile[$i]['they'] = $pronouns['they'];
            $profile[$i]['them'] = $pronouns['them'];
            $profile[$i]['their'] = $pronouns['their'];
            $profile[$i]['theirs'] = $pronouns['theirs'];
            $profile[$i]['themselves'] = $pronouns['themselves'];
        }
        return $profile;
    }
    public static function Rooms($user_id){
        $rooms = RoomUses::UserId($user_id);
        if(count($rooms) == 0){
            $user = new Users();
            $user = $user->GetUserId($user_id);
            if($user['bedroom_id']){
                RoomUses::SaveRoomUse(['user_id'=>$user_id,'room_id'=>$user['bedroom_id']]);
                $rooms = RoomUses::UserId($user_id);
            }
        }
        for($i = 0; $i < count($rooms); $i++){
            $rooms[$i]['room'] = Rooms::RoomId($rooms[$i]['room_id']);
        }
        return $rooms;
    }
}
?>