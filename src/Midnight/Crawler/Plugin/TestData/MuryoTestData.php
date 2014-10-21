<?php


namespace Midnight\Crawler\Plugin\TestData;

class MuryoTestData extends AbstractTestData
{

    /**
     * @var string
     **/
    protected $rss_name = 'muryo.xml';


    /**
     * @var array
     **/
    protected $html_paths = array(
        'muryo/71340.html',
        'muryo/71370.html',
        'muryo/71376.html',
        'muryo/error.html',
        'muryo/error2.html'
    );
}
