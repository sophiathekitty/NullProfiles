<?php
require_once("../../../../../includes/main.php");
$data = [];
$data['profiles'] = UserProfileStamp::Residence();
OutputJson($data);
?>