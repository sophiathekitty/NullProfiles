<?php
require_once("../../../../includes/main.php");
$data = [];
$data['pronouns'] = PronounSets::Pronouns();
OutputJson($data);
?>