<?php


namespace Midnight\Crawler\Plugin\TestData;

class ShikosenTestData extends AbstractTestData
{

    /**
     * @var string
     **/
    protected $rss_name = 'shikosen.xml';


    /**
     * @var array
     **/
    protected $html_paths = array(
        'shikosen/36152.html',
        'shikosen/toppage.html'
    );
}
