<?php

namespace Midnight\Crawler\Plugin\TestData;

class MatomeTestData extends AbstractTestData
{
    /**
     * @var string
     **/
    protected $rss_name = 'matome.xml';

    /**
     * @var array
     **/
    protected $html_paths = array(
        'matome/blog-entry-3641.html',
        'matome/error.html',
        'matome/error2.html'
    );
}
