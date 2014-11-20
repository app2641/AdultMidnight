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
        'bikyaku/blog-entry-2186.html',
        'bikyaku/blog-entry-2184.html',
        'bikyaku/error.html',
        'bikyaku/error2.html'
    );
}
