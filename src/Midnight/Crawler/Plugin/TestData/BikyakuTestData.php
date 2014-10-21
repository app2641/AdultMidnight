<?php


namespace Midnight\Crawler\Plugin\TestData;

class BikyakuTestData extends AbstractTestData
{

    /**
     * @var string
     **/
    protected $rss_name = 'bikyaku.xml';


    /**
     * @var array
     **/
    protected $html_paths = array(
        'bikyaku/blog-entry-1957.html',
        'bikyaku/blog-entry-1954.html',
        'bikyaku/error.html',
        'bikyaku/error2.html'
    );
}
