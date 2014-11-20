<?php


namespace Midnight\Crawler\Plugin\TestData;

class YouskbeTestData extends AbstractTestData
{

    /**
     * @var string
     **/
    protected $rss_name = 'youskbe.xml';


    /**
     * @var array
     **/
    protected $html_paths = array(
        'youskbe/025386.html',
        'youskbe/error.html',
        'youskbe/error2.html'
    );
}
