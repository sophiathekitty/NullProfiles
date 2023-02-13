<?php
Services::Start("NullProfiles::EveryMinute");

Services::Log("NullProfiles::EveryMinute","CheckForRoomUseStart");
CheckForRoomUseStart();

Services::Complete("NullProfiles::EveryMinute");
?>