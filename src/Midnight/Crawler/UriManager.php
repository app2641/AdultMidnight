<?php


namespace Midnight\Crawler;

class UriManager
{

    /**
     * 分解されたurlデータ
     *
     * @var array
     **/
    private $parse_data;


    /**
     * urlを整形する
     *
     * @param  string $url
     * @return string
     **/
    public function resolve ($url)
    {
        $this->parse_data = parse_url($url);
        if (! isset($this->parse_data['host'])) return false;

        switch ($this->parse_data['host']) {
            case 'flashservice.xvideos.com':
            case 'www.xvideos.com':
                $url = $this->_resolveXvideosUrl();
                break;
        }

        return $url;
    }


    /**
     * xvideos.comのurlを解決する
     *
     * @return string
     **/
    private function _resolveXvideosUrl ()
    {
        $url  = 'http://jp.xvideos.com';
        $path = $this->parse_data['path'];

        // embed用かどうか
        if (preg_match('/embedframe/', $path)) {
            $url .= str_replace('embedframe/', 'video', $path);
        } else {
            $url .= $path;
        }

        return $url;
    }
}

