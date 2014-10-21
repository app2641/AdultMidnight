<?php


namespace Midnight\Crawler\Plugin\TestData;

class RakuenTestData extends AbstractTestData
{

    /**
     * @var string
     **/
    protected $rss_name = 'rakuen.xml';


    /**
     * @var array
     **/
    protected $html_paths = array(
        'rakuen/6640.html',
        'rakuen/error.html',
        'rakuen/error2.html'
    );
}
