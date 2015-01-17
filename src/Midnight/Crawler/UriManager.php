<?php


namespace Midnight\Crawler;

use Midnight\Utility\CrawlerException;

class UriManager
{

    /**
     * @var string
     **/
    private $raw_url;


    /**
     * 分解されたurlデータ
     *
     * @var array
     **/
    private $parse_data;


    /**
     * ベースのurlを取得する
     *
     * @return string
     **/
    private function _getBaseUrl ()
    {
        return $this->parse_data['scheme'].'://'.$this->parse_data['host'];
    }


    /**
     * xvideos.comのURLかどうかを判別する
     *
     * @param  string $url
     * @return boolean
     **/
    public function isXvideosUrl ($url)
    {
        $info = parse_url($url);

        if (! isset($info['host'])) return false;
        if (! in_array($info['host'], array('jp.xvideos.com', 'xvideos.com')))  {
            return false;
        }

        return true;
    }


    /**
     * ホスト名によってメソッドをスイッチする
     * スイッチ先のメソッドでurlを解決し、返り値とする
     *
     * @param  string $url
     * @return string
     **/
    public function resolve ($url)
    {
        $this->raw_url    = $url;
        $this->parse_data = parse_url($url);
        if (! isset($this->parse_data['host'])) return false;

        switch ($this->parse_data['host']) {
            case 'flashservice.xvideos.com':
            case 'www.xvideos.com':
                $url = $this->_resolveXvideosUrl();
                break;

            case 'asg.to':
                $url = $this->_resolveAsgToUrl();
                break;

            case 'video.fc2.com':
                $url = $this->_resolveFc2Url();
                break;

            case 'www.google.com':
                $url = $this->_resolveGoogleUrl();
                break;

            case 'hqq.tv':
                $url = false;
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
        $url = 'http://jp.xvideos.com';
        $path = $this->parse_data['path'];

        // embed用かどうか
        if (preg_match('/embedframe/', $path)) {
            $url .= str_replace('embedframe/', 'video', $path);
            $url .= '/';
        } else {
            $url .= $path;
        }

        return $url;
    }


    /**
     * asg.to (アゲサゲ) の動画urlを解決する
     *
     * @return string
     **/
    private function _resolveAsgToUrl ()
    {
        // ベースとなるurl(トップページ)の構築
        $base_url = $this->_getBaseUrl();
        if (! isset($this->parse_data['query'])) return $base_url;

        // mcdという値を抽出する
        preg_match('/mcd=([0-9a-zA-Z]+)/', $this->parse_data['query'], $matches);
        if (! isset($matches[1])) return $base_url;

        return $base_url.'/contentsPage.html?mcd='.$matches[1];
    }


    /**
     * FC2動画のurlを解決する
     *
     * @return string
     **/
    private function _resolveFc2Url ()
    {
        // ja/a/content あるいは content を url に含む場合、
        // それが既に動画のurlとなっている為、そのまま返す
        // pattern: http://video.fc2.com/(ja/a/|)content
        $pattern = $this->parse_data['scheme'].':\/\/'.$this->parse_data['host'].'(\/ja|\/a)+\/content';
        if (preg_match('/^'.$pattern.'/', $this->raw_url)) {
            return $this->raw_url;
        }

        $base_url = $this->_getBaseUrl();
        if (! isset($this->parse_data['query'])) return $base_url;

        // iという値を抽出してurlを構築する
        // e.g. http://video.fc2.com/flv2.swf?i=20130827LybYzuyu&d=2185
        preg_match('/i=([a-zA-Z0-9]*).*/', $this->parse_data['query'], $matches);
        if (! isset($matches[1])) return $base_url;

        return $base_url.'/content/'.$matches[1];
    }


    /**
     * Googleのurlを解決する
     * 主にEroEroプラグインで使用する
     * クエリに動画urlが含まれてそれを抽出する
     *
     * e.g.
     * https://www.google.com/webhp?hl=all#safe=off&hl=all&q=http:%2F%2Fwww.pornhub.com%2Fview_video.php%3Fviewkey%3D1947857414
     *
     * @return string
     */
    private function _resolveGoogleUrl ()
    {
        preg_match('/(http.*)/', $this->parse_data['fragment'], $matches);
        if (! isset($matches[1])) {
            throw new CrawlException($this->raw_url.' urlをパース出来ませんでした');
        }

        $url = str_replace('%2F', '/', $matches[1]);
        $url = str_replace('%3F', '?', $url);
        $url = str_replace('%3D', '=', $url);

        return $url;
    }
}

