<?php
require_once 'simple_html_dom.php';
function makeCSV($datas)
{
    foreach ($datas as $key => $data) {
        $x[] = implode(",", $data);
    }
    return implode("\n", $x);
}
function b2e($str)
{
    return str_replace(['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'], ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'], $str);
}
// EN
$html = file_get_contents('https://en.wikipedia.org/wiki/List_of_postal_codes_in_Bangladesh');
$html = str_get_html($html);
$division = '';
$divisions = [];
$datas = $datas1 = [];
foreach ($html->find('.mw-headline') as $key => $value) {
    if (preg_match("/Division/", $value->innertext)) {
        $division = str_replace(" Division", '', $value->innertext);
        $divisions[] = $division;
    }
}
foreach ($html->find('.wikitable') as $key => $value) {
    if (preg_match("/Thana/", $value->innertext)) {
        $division = $divisions[$key];
        foreach ($value->find('tr') as $value2) {
            if (!preg_match("/SubOffice/", $value2->innertext)) {
                $x['division'] = $division;
                $x['district'] = strip_tags(@$value2->find('td')[0]->innertext);
                $x['thana'] = strip_tags(@$value2->find('td')[1]->innertext);
                $x['suboffice'] = strip_tags(@$value2->find('td')[2]->innertext);
                $x['postcode'] = strip_tags(@$value2->find('td')[3]->innertext);
                $datas[$x['postcode']]['en'] = $x;
                $datas1[] = $x;
            }
        }
    }
}

// BN
$html = file_get_contents('https://bn.wikipedia.org/wiki/%E0%A6%AC%E0%A6%BE%E0%A6%82%E0%A6%B2%E0%A6%BE%E0%A6%A6%E0%A7%87%E0%A6%B6%E0%A7%87%E0%A6%B0_%E0%A6%AA%E0%A7%8B%E0%A6%B8%E0%A7%8D%E0%A6%9F_%E0%A6%95%E0%A7%8B%E0%A6%A1%E0%A7%87%E0%A6%B0_%E0%A6%A4%E0%A6%BE%E0%A6%B2%E0%A6%BF%E0%A6%95%E0%A6%BE');
$html = str_get_html($html);
$division = '';
$divisions = [];
$datas2 = [];
foreach ($html->find('.mw-headline') as $key => $value) {
    if (preg_match("/বিভাগ/", $value->innertext)) {
        $division = str_replace(" বিভাগ", '', $value->innertext);
        $divisions[] = $division;
    }
    if (preg_match('/জেলা/', $value->innertext)) {
        $divisions[] = $division;
    }
}

foreach ($html->find('.wikitable') as $key => $value) {
    if (preg_match("/থানা/", $value->innertext)) {
        $division = $divisions[$key];
        foreach ($value->find('tr') as $value2) {
            if (!preg_match("/উপঅফিস/", $value2->innertext)) {
                $x['division'] = $division;
                $x['district'] = strip_tags(@$value2->find('td')[0]->innertext);
                $x['thana'] = strip_tags(@$value2->find('td')[1]->innertext);
                $x['suboffice'] = strip_tags(@$value2->find('td')[2]->innertext);
                $x['postcode'] = strip_tags(@$value2->find('td')[3]->innertext);
                $datas[b2e($x['postcode'])]['bn'] = $x;
                $datas2[] = $x;
            }
        }
    }
}
file_put_contents('postcode_en.csv', makeCSV($datas1));
file_put_contents('postcode_bn.csv', makeCSV($datas2));
file_put_contents('postcode.json', json_encode($datas, JSON_UNESCAPED_UNICODE));
file_put_contents('postcode-pretty.json', json_encode($datas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
