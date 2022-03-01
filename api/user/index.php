<?php
require_once("../../../../includes/main.php");
$data = [];
if($_GET['id']){
    $data['profile'] = UserProfileStamp::UserProfile($_GET['id']);
} else {
    $session = UserSEssion::GetUserSessionArray();
    $data['profile'] = UserProfileStamp::UserProfile($session['user_id']);
}
OutputJson($data);
?>