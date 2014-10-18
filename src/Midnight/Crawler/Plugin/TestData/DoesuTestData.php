<?php


namespace Midnight\Crawler\Plugin\TestData;

class DoesuTestData extends AbstractTestData
{

    /**
     * @var string
     **/
    protected $rss_name = 'doesu.xml';


    /**
     * @var array
     **/
    protected $html_paths = array(
        'doesu/8992.html',
        'doesu/error.html',
        'doesu/error2.html'
    );
}
