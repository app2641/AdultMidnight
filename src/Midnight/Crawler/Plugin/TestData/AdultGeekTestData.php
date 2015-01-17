<?php


namespace Midnight\Crawler\Plugin\TestData;

class AdultGeekTestData extends AbstractTestData
{

    /**
     * @var string
     **/
    protected $rss_name = 'adult-geek.xml';


    /**
     * @var array
     **/
    protected $html_paths = array(
        'adult-geek/post_5459.html',
        'adult-geek/post_6523.html',

        // タイトルクエリの不正、画像クエリの不正
        'adult-geek/error.html',

        // 画像src属性の不正
        'adult-geek/error2.html'
    );
}

