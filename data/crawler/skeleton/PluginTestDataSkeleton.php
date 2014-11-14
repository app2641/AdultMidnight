<?php


namespace Midnight\Crawler\Plugin\TestData;

class ${name}TestData extends AbstractTestData
{

    /**
     * @var string
     **/
    protected $rss_name = '${group}.xml';


    /**
     * @var array
     **/
    protected $html_paths = array(
        '${group}/.html',

        // タイトルクエリの不正、画像クエリの不正
        '${group}/error.html',

        // 画像srcの不正
        '${group}/error2.html'
    );
}

