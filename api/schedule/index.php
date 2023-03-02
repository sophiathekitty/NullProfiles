<?php
require_once("../../../../includes/main.php");
$data = [];
if(isset($_GET['user_id'])){
    $data['schedule'] = DailySchedule::UserId($_GET['user_id']);
    $data['overrides'] = DailyScheduleOverrides::UserId($_GET['user_id']);
} else {
    $data['schedule'] = DailySchedule::AllDailySchedules();
    $data['overrides'] = DailyScheduleOverrides::AllDailyScheduleOverrides();
}
OutputJson($data);
?>