<?php
require_once("../../../../includes/main.php");
$data = [];
$data['images'] = ProfileImage::Images();
OutputJson($data);
?>