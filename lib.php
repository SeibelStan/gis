<?php

function arrayMultiSort($array, $args = []) {
    usort(
        $array, function($a, $b) use ($args) {
            $res = 0;

            $a = (object)$a;
            $b = (object)$b;

            foreach($args as $k => $v) {
                if($a->$k == $b->$k) {
                    continue;
                }

                $res = ($a->$k < $b->$k) ? -1 : 1;
                if($v == 'desc') {
                    $res = -$res;
                }
                break;
            }

            return $res;
        }
    );

    return $array;
}

function checkKey($userKey) {
    $keys = explode("\n", trim(file_get_contents('keys.txt')));
    $allow = false;
    foreach($keys as $key) {
        if($key == $userKey) {
            $allow = true;
        }
    }
    return $allow;
}

function curlGetAlong($urls) {
    $mh = curl_multi_init();
    $channels = [];

    foreach($urls as $url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_multi_add_handle($mh, $ch);
        $channels[$url] = $ch;
    }

    $active = 0;
    do {
        curl_multi_exec($mh, $active);
    } while($active > 0);

    $result = [];
    foreach($channels as $channel) {
        $result[] = curl_multi_getcontent($channel);
        curl_multi_remove_handle($mh, $channel);
    }

    curl_multi_close($mh);
    return $result;
}

function curlGetSeq($urls) {
    $result = [];

    foreach($urls as $url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result[] = curl_exec($ch);
    }

    curl_close($ch);
    return $result;
}