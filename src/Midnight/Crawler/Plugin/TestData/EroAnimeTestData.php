<?php

namespace Midnight\Crawler\Plugin\TestData;

class EroAnimeTestData extends AbstractTestData
{
    /**
     * @var string
     **/
    protected $rss_name = 'eroanime.xml';

    /**
     * @var array
     **/
    protected $html_paths = array(
        'eroanime/boin.html',
        'eroanime/598.html',

        // タイトルクエリの不正、画像クエリの不正
        'eroanime/error.html',

        // 画像srcの不正
        'eroanime/error2.html'
    );
}
