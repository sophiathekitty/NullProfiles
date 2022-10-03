<?php
/**
 * generates user profile stamps
 */
class UserProfileStamp {
    /**
     * get user profiles for all the people who live here
     * @return array list of user profile stamp data arrays
     */
    public static function Residence(){
        $users = Users::Residence();
        $profiles = [];
        foreach($users as $user){
            $profiles[] = UserProfileStamp::UserProfile($user['id']);
        }
        return $profiles;
    }
    /**
     * user profile stamp. user:username, user:bedtime, user:awake_time, pronouns, images, rooms, bedtime schedule
     * @param int $user_id the user's id
     * @return array user profile stamp data array
     */
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
        return $profile;
    }
    /**
     * user pronouns stamp
     * @param int $user_id the user's id
     * @return array the user's pronouns
     */
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
    /**
     * gets the user's room uses
     * @param int $user_id the user's id
     * @return array the user's room uses list
     */
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