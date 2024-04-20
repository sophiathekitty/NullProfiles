<?php
Services::Start("NullProfiles::EveryMinute");

Services::Log("NullProfiles::EveryMinute","CheckForRoomUseStart");
CheckForRoomUseStart();
Services::Log("NullProfiles::EveryMinute","CalculateLinkingRooms");
LinkingRooms::CalculateLinkingRooms();
Services::Complete("NullProfiles::EveryMinute");
?>