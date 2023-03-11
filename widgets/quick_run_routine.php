<?php
require_once("../../../includes/main.php");
$user_id = UserSession::CurrentUserId();
if($user_id == false) die();
$routine = RoutineWindow($user_id);
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if(is_null($routine)) { ?><a href="#routine" routine_id="" action="list" id="routine_quick_run">routines</a><?php }
else {?><a href="#routine" action="run" routine_id="<?=$routine['id'];?>" id="routine_quick_run"><?=$routine['name'];?></a><?php } ?>