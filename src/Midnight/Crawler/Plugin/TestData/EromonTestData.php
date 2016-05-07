<?php

namespace Midnight\Crawler\Plugin\TestData;

class EromonTestData extends AbstractTestData
{
    /**
     * @var string
     **/
    protected $rss_name = 'eromon.xml';

    /**
     * @var array
     **/
    protected $html_paths = array(
        'eromon/4370.html',
        'eromon/4368.html',
        'eromon/4367.html',
        'eromon/4366.html',

        // タイトルクエリの不正、画像クエリの不正
        'eromon/error.html',

        // 画像srcの不正
        'eromon/error2.html'
    );
}
