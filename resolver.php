<?php

    require_once('config.php');

    if(!isset($_REQUEST['url']))
        exit;

    $url = $_REQUEST['url'];
    $resolver_url = "http://api.soundcloud.com/resolve.json?url=$url&client_id=" . SOUNDCITE_SOUNDCLOUD_KEY;

    /* Verifies that url being resolved is a soundcloud.com url */
    if( preg_match("#^https://soundcloud.com#", $url) ) {
        $response = file_get_contents($resolver_url);
        if(!empty($response)) {
            header('Content-Type: application/json');
            echo $response;
            exit;
        }
    }