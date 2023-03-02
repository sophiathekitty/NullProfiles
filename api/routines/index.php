<?php
require_once("../../../../includes/main.php");
$data = [];
if(isset($_GET['user_id'])){
    $data['routines'] = UserRoutines($_GET['user_id']);
    $data['quick_run'] = RoutineWindow($_GET['user_id']);
} else {
    $data['routines'] = AllRoutines();
}
OutputJson($data);
?>