<?php

namespace Midnight\Crawler\Plugin\TestData;

class AdultAdultTestData extends AbstractTestData
{
    /**
     * @var string
     **/
    protected $rss_name = 'adult-adult.xml';

    /**
     * @var array
     **/
    protected $html_paths = array(
        'adult-adult/blog-entry-19719.html',
        'adult-adult/blog-entry-19726.html',

        // タイトルクエリの不正、画像クエリの不正
        'adult-adult/error.html',

        // 画像srcの不正
        'adult-adult/error2.html'
    );
}
