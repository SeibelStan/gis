<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include('lib.php');

$userKey = @$_REQUEST['key'];

if(!checkKey($userKey)) {
    include('deny.php');
    die();
}

$sample = 'https://catalog.api.2gis.ru/2.0/catalog/marker/search?page=1&page_size=10000&rubric_id=15791&region_id=38&viewpoint1=30.268267393112183%2C59.97150536930754&viewpoint2=30.276839733123783%2C59.970246305908745&locale=ru_RU&key=rutnpt3272';

if(@$_REQUEST['url']) {
    header('Content-type: text/csv');
    $url = $_REQUEST['url'];
    $matches = [];
    preg_match('/viewpoint1=(.+?)&viewpoint2=(.+?)&/', $url, $matches);
    $viewpoint1 = $matches[1];
    $viewpoint2 = $matches[2];
    $data = file_get_contents($url);
    $items = json_decode($data)->result->items;

    $urls = [];
    foreach($items as $i => $item) {
        if($i >= max($_REQUEST['lim'], 1)) {
            break;
        }
        $urls[] = 'https://catalog.api.2gis.ru/2.0/catalog/branch/get?id=' . $item->id .'&see_also_size=5&viewpoint1=' . $viewpoint1 . '&viewpoint2=' . $viewpoint2 . '&locale=ru_RU&fields=items.adm_div%2Citems.region_id%2Citems.reviews%2Citems.point%2Citems.urls%2Citems.name_ex%2Citems.org%2Citems.group%2Citems.see_also%2Citems.dates%2Citems.external_content%2Citems.flags%2Citems.ads.options%2Citems.email_for_sending.allowed%2Csearch_attributes&key=rutnpt3272';
    }

    $items = $_REQUEST['method'] == 'a' ? curlGetAlong($urls) : curlGetSeq($urls);

    $fullItems = [];
    foreach($items as $i => $item) {
        $fullItems[] = json_decode($item)->result->items[0];
    }

    $fullItems = arrayMultiSort($fullItems, ['name' => 'asc']);

    $table = '';
    foreach($fullItems as $i => $item) {
        if(!isset($item->contact_groups)) {
            continue;
        }
        if(!$item->contact_groups[0]->contacts) {
            continue;
        }
        if($i) {
            if($fullItems[$i - 1]->name == $item->name) {
                continue;
            }
        }

        $table .= $item->name . ";";
        $table .= $item->address_name . ' ' . @$item->address_comment . ";";
        $contacts = [];
        foreach($item->contact_groups as $group) {
            foreach($group->contacts as $contact) {
                $contacts[] = $contact->text;
            }
        }
        $table .= implode(',', $contacts);
        $table .= "\n";
    }
    echo $table;
    die();
}

include('view.php');