<?php

namespace Midnight\Crawler\Plugin\TestData;

class MuryoEroTestData extends AbstractTestData
{
    /**
     * @var string
     **/
    protected $rss_name = 'muryoero.xml';

    /**
     * @var array
     **/
    protected $html_paths = array(
        'muryoero/15535.html',

        // タイトルクエリの不正、画像クエリの不正
        'muryoero/error.html',

        // 画像srcの不正
        'muryoero/error2.html'
    );
}
