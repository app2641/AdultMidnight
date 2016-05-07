<?php

namespace Midnight\Crawler\Plugin\TestData;

class HentaiAnimeTestData extends AbstractTestData
{
    /**
     * @var string
     **/
    protected $rss_name = 'hentai-anime.xml';

    /**
     * @var array
     **/
    protected $html_paths = array(
        'hentai-anime/blog-entry-317.html',
        'hentai-anime/blog-entry-466.html',
        'hentai-anime/blog-entry-464.html',
        'hentai-anime/error.html',
        'hentai-anime/error2.html'
    );
}
