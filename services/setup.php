<?php
Services::Start("NullProfiles::Setup");

Services::Log("NullProfiles::Setup","ProfileImageStamp::FindDefaults");
ProfileImageStamp::FindDefaults();

Services::Complete("NullProfiles::Setup");
?>