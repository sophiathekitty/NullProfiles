<?php
require_once("../../../includes/main.php");
$user_id = UserSession::CurrentUserId();
if($user_id == false) die();
$routines = Routines::UserId($user_id);
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?><dialog id="routines_run_list">
    <nav class="routines">
        <?php foreach($routines as $routine){ ?><a href="#routine" action="run" routine_id="<?=$routine['id'];?>"><?=$routine['name'];?></a><?php } ?>
        <a href="#routine" action="cancel">close</a>
    </nav>
</dialog>