<?php

return array(

   'fileDir' => dirname($_SERVER['DOCUMENT_ROOT']) . '/data/uploads/',

   'languageDir' => dirname($_SERVER['DOCUMENT_ROOT']) . '/module/Application/language/',

   'surveyFormDir' => dirname($_SERVER['DOCUMENT_ROOT']) . '/module/Administration/src/Administration/Form/SurveyForm/',

    'cookie' => array(
        'rememberMeName' => 'MyUnhcrRememberMe',
        'rememberMeKey' => 'L8UC9qhgfI0K632LLxpeC8Clj0jF4XBA',
        'rememberMeSeconds' => 2592000,
    ),
);
