<?php
require_once("../../../../../includes/main.php");
$data = [];
if(isset($_GET['routine_id'])){
    $data['routine'] = StartRoutine($_GET['routine_id']);
} else {
    $data['error'] = "missing routine_id";
}
OutputJson($data);
?>