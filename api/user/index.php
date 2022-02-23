<?php
require_once("../../../../includes/main.php");
$data = [];
if($_GET['id']){
    $data['profile'] = UserProfileStamp::UserProfile($_GET['id']);
}
OutputJson($data);
?>