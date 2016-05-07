<?php

namespace Midnight\Crawler\Plugin\TestData;

class IchizenTestData extends AbstractTestData
{
    /**
     * @var string
     **/
    protected $rss_name = 'ichizen.xml';

    /**
     * @var array
     **/
    protected $html_paths = array(
        'ichizen/3847.html',
        'ichizen/3839.html',

        // タイトルクエリの不正、画像クエリの不正
        'ichizen/error.html',

        // 画像srcの不正
        'ichizen/error2.html'
    );
}
