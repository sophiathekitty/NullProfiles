<?php
Services::Start("NullProfiles::EveryDay");

Services::Log("NullProfiles::EveryDay","ProfileImageStamp::FindDefaults");
ProfileImageStamp::FindDefaults();

Services::Complete("NullProfiles::EveryDay");
?>