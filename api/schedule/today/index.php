<?php
require_once("../../../../../includes/main.php");
$data = [];
if(isset($_GET['user_id'])){
    $data['schedule'] = DailySchedule::TodayUserId($_GET['user_id']);
    $data['overrides'] = DailyScheduleOverrides::TodayUserId($_GET['user_id']);
} else {
    $data['schedule'] = DailySchedule::Today();
    $data['overrides'] = DailyScheduleOverrides::Today();
}
OutputJson($data);
?>