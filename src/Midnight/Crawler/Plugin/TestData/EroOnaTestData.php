<?php

namespace Midnight\Crawler\Plugin\TestData;

class EroOnaTestData extends AbstractTestData
{
    /**
     * @var string
     **/
    protected $rss_name = 'eroona.xml';

    /**
     * @var array
     **/
    protected $html_paths = array(
        'eroona/1606.html',
        'eroona/1604.html',
        'eroona/1589.html',

        // タイトルクエリの不正、画像クエリの不正
        'eroona/error.html',

        // 画像srcの不正
        'eroona/error2.html'
    );
}
