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
     * ホスト名によってメソッドをスイッチする
     * スイッチ先のメソッドでurlを解決し、返り値とする
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

            case 'asg.to':
                $url = $this->_resolveAsgToUrl();
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


    /**
     * asg.to (アゲアゲ) の動画urlを解決する
     *
     * @return string
     **/
    private function _resolveAsgToUrl ()
    {
        // ベースとなるurl(トップページ)の構築
        $base_url = $this->parse_data['scheme'].'://'.$this->parse_data['host'];
        if (! isset($this->parse_data['query'])) return $base_url;

        // mcdという値を抽出する
        preg_match('/mcd=([0-9a-zA-Z]+)/', $this->parse_data['query'], $matches);
        if (! isset($matches[1])) return $base_url;

        return $base_url.'/contentsPage.html?mcd='.$matches[1];
    }
}

