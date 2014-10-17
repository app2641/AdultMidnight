<?php


namespace Midnight\Crawler\Plugin\TestData;

class AdultVideoTestData extends AbstractTestData
{

    /**
     * @var string
     **/
    protected $rss_name = 'adult-video.xml';


    /**
     * @var array
     **/
    protected $html_paths = array(
        'adult-video/blog-entry-13160.html',
        'adult-video/blog-entry-13159.html'
    );
}

