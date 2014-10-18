<?php


namespace Midnight\Crawler\Plugin\TestData;

class DownloadTestData extends AbstractTestData
{

    /**
     * @var string
     **/
    protected $rss_name = 'download.xml';


    /**
     * @var array
     **/
    protected $html_paths = array(
        'download/60188.html',
        'download/error.html',
        'download/error2.html'
    );
}
