<?php

class PronounSets extends clsModel {
    public $table_name = "PronounSets";
    public $fields = [
        [
            'Field'=>"id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>"auto_increment"
        ],[
            'Field'=>"they",
            'Type'=>"varchar(50)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"them",
            'Type'=>"varchar(50)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"their",
            'Type'=>"varchar(50)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"theirs",
            'Type'=>"varchar(50)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"themself",
            'Type'=>"varchar(50)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"modified",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"current_timestamp()",
            'Extra'=>"on update current_timestamp()"
        ]
    ];
    private static $instance = null;
    /**
     * @return PronounSets|clsModel
     */
    private static function GetInstance(){
        if(is_null(PronounSets::$instance)) PronounSets::$instance = new PronounSets();
        return PronounSets::$instance;
    }
    public static function PronounsId($id){
        if($id == 0) return PronounSets::$default;
        $instance = PronounSets::GetInstance();
        return $instance->LoadWhere(['id'=>$id]);
    }
    public static function Pronouns(){
        $instance = PronounSets::GetInstance();
        $pronouns = $instance->LoadAll();
        if(count($pronouns) == 0) {
            PronounSets::CreateDefaults();
            $pronouns = $instance->LoadAll();
        }
        $pro = [];
        $pro[] = PronounSets::$default;
        foreach($pronouns as $pronoun){
            $pro[] = $pronoun;
        }
        return $pro;
    }
    public static function SavePronouns($data){
        $instance = PronounSets::GetInstance();
        $data = $instance->CleanData($data);
        if(isset($data['id']) && !is_null(PronounSets::PronounsId($data['id']))) return $instance->Save($data,['id'=>$data['id']]);
        return $instance->Save($data);
    }
    public static function CreateDefaults(){
        PronounSets::CheckPronouns([
            'id'=>1,
            'they'=>'she',
            'them'=>'her',
            'their'=>'her',
            'theirs'=>'hers',
            'themself'=>'herself',
        ]);
        PronounSets::CheckPronouns([
            'id'=>2,
            'they'=>'he',
            'them'=>'him',
            'their'=>'his',
            'theirs'=>'his',
            'themself'=>'himself',
        ]);
        PronounSets::CheckPronouns([
            'id'=>3,
            'they'=>'ae',
            'them'=>'aer',
            'their'=>'aer',
            'theirs'=>'aers',
            'themself'=>'aerself',
        ]);
        PronounSets::CheckPronouns([
            'id'=>4,
            'they'=>'fae',
            'them'=>'faer',
            'their'=>'faer',
            'theirs'=>'faers',
            'themself'=>'faerself',
        ]);
        PronounSets::CheckPronouns([
            'id'=>5,
            'they'=>'per',
            'them'=>'per',
            'their'=>'pers',
            'theirs'=>'pers',
            'themself'=>'perself',
        ]);
        PronounSets::CheckPronouns([
            'id'=>6,
            'they'=>'ey',
            'them'=>'em',
            'their'=>'eir',
            'theirs'=>'eirs',
            'themself'=>'eirself',
        ]);
        PronounSets::CheckPronouns([
            'id'=>7,
            'they'=>'e',
            'them'=>'em',
            'their'=>'eir',
            'theirs'=>'eirs',
            'themself'=>'emself',
        ]);
        PronounSets::CheckPronouns([
            'id'=>8,
            'they'=>'ve',
            'them'=>'ver',
            'their'=>'vis',
            'theirs'=>'vis',
            'themself'=>'verself',
        ]);
        PronounSets::CheckPronouns([
            'id'=>9,
            'they'=>'xe',
            'them'=>'xem',
            'their'=>'xyr',
            'theirs'=>'xyrs',
            'themself'=>'xemself',
        ]);
        PronounSets::CheckPronouns([
            'id'=>10,
            'they'=>'ze',
            'them'=>'hir',
            'their'=>'hir',
            'theirs'=>'hirs',
            'themself'=>'hirself',
        ]);
        PronounSets::CheckPronouns([
            'id'=>11,
            'they'=>'zie',
            'them'=>'zim',
            'their'=>'zir',
            'theirs'=>'zis',
            'themself'=>'zieself',
        ]);
        PronounSets::CheckPronouns([
            'id'=>12,
            'they'=>'tey',
            'them'=>'ter',
            'their'=>'tem',
            'theirs'=>'ters',
            'themself'=>'terself',
        ]);
        PronounSets::CheckPronouns([
            'id'=>13,
            'they'=>'it',
            'them'=>'it',
            'their'=>'it',
            'theirs'=>'its',
            'themself'=>'itself',
        ]);
    }
    private static $default = [
        'id'=>"0",
        'they'=>'they',
        'them'=>'them',
        'their'=>'their',
        'theirs'=>'theirs',
        'themself'=>'themself',
    ];
    private static function CheckPronouns($data){
        $pronouns = PronounSets::PronounsId($data['id']);
        if(is_null($pronouns)){
            PronounSets::SavePronouns($data);
        }
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new PronounSets();
    PronounSets::CreateDefaults();
}
?>