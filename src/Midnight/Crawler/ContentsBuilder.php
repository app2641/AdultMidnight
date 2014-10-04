<?php


namespace Midnight\Crawler;

use Midnight\Aws\S3;
use Midnight\Utility\Logger;

class ContentsBuilder
{

    /**
     * メインページあるいはデモページを生成する際に、
     * コンテンツのエントリ情報となるデータ配列 

     * @var array
     */
    private $entry_data = array();


    /**
     * @var S3
     **/
    private $S3;


    /**
     * 生成するページのtitleやdescriptionの値を記載した設定ファイル
     *
     * @var string
     **/
    private $site_ini_path = 'data/config/site.ini';


    /**
     * @param  array $entry_data 
     * @return array
     */
    public function setEntryData($entry_data)
    {
        $this->entry_data = $entry_data;
    }


    /**
     * @param  S3 $S3
     * @return void
     **/
    public function setS3 (\Midnight\Aws\S3 $S3)
    {
        $this->S3 = $S3;
    }


    /**
     * レイアウトファイルを取得する
     *
     * @param  string $file_name 読み込むレイアウトファイル名
     * @return string
     */
    private function _getLayout($file_name)
    {
        $layout_path = ROOT.'/data/template/'.$file_name.'.html';
        if (! file_exists($layout_path)) {
            throw new \Exception('レイアウトファイルが存在しません');
        }

        return file_get_contents($layout_path);
    }


    /**
     * 引数に与えたコンテンツを生成する
     * main, demo の場合は$this->entray_dataを使用して生成を行う
     *
     * @param  string $page_name ページ名(e.g. demo, index, who)
     * @return void
     */
    public function buildContents($page_name)
    {
        switch ($page_name) {
            case 'index':
            case 'demo':
                $this->_buildMainPage($page_name);
                break;
            default:
                $this->_buildSubPage($page_name);
                break;
        }
    }


    /**
     * メインページまたはデモページを構築する
     *
     * @param  string $page_name ページ名
     * @return void
     */
    private function _buildMainPage($page_name)
    {
        $layout    = $this->_buildMainLayout();
        $entries   = $this->_buildEntryLayout();
        $side_menu = $this->_buildSideMenuLayout();
        $footer    = $this->_buildFooterLayout();

        $layout = str_replace('${contents}', $entries, $layout);
        $layout = str_replace('${sidemenu}', $side_menu, $layout);
        $layout = str_replace('${footer}', $footer, $layout);
        $layout = $this->_setMetaData($page_name, $layout);

        $path = ROOT.'/public_html/'.$page_name.'.html';
        file_put_contents($path, $layout);

        // 過去ページのページャを更新する
        $this->_buildPastPager();

        // S3へコンテンツをアップロードする
        if ($page_name == 'demo') return false;
        $this->_uploadConents($path, $page_name.'.html');
    }


    /**
     * 昨日、一昨日のコンテンツにページャを指定する
     *
     * @return void
     **/
    private function _buildPastPager ()
    {
        if (IS_EC2 === false) return false;

        $yesterday = date('Ymd', time() - (60 * 60 * 24));
        $before_yesterday = date('Ymd', time() - (60 * 60 * 48));       

        // 既に昨日用のコンテンツを生成している場合は処理を行わない
        if ($this->S3->doesObjectExist('contents/'.$yesterday.'.html') === true) {
            return false;
        }


        // 現在indexとなっているページを昨日のコンテンツに繰り下げる
        try {
            $contents = $this->S3->download('index.html');
            $pattern = '/<li class="next-page next"><a href="[^<]*<\/a><\/li>/';
            $previous = '<li class="previous preview-page"><a href="/">&lt; Previous</a></li>';
            $next = '<li class="next-page next"><a href="/contents/'.
                $before_yesterday.'.html">Next &gt;</a></li>';
            $contents = preg_replace($pattern, $previous.$next, $contents);

            $path = '/tmp/'.$yesterday.'.html';
            file_put_contents($path, $contents);
            $this->S3->upload($path, 'contents/'.$yesterday.'.html');

        } catch (\Exception $e) {
            Logger::addLog($e->getFile().' on line '.$e->getLine());
            Logger::addLog($e->getMessage());
            Logger::addLog('現在のindex.htmlを昨日のコンテンツにするの失敗した'.PHP_EOL);
        }


        // 一昨日のページャを更新する
        try {
            $contents = $this->S3->download('contents/'.$before_yesterday.'.html');
            $html = $response->body;

            $pattern  = '<li class="previous preview-page"><a href="/">&lt; Previous</a></li>';
            $previous = '<li class="previous preview-page"><a href="/contents/'.
                $yesterday.'.html">&lt; Preview</a></li>';
            $contents = str_replace($pattern, $previous, $contents);

            $path = '/tmp/'.$before_yesterday.'.html';
            file_put_contents($path, $contents);
            $this->S3->upload($path, 'contents/'.$before_yesterday.'.html');

        } catch (\Exception $e) {
            Logger::addLog($e->getFile().' on line '.$e->getLine());
            Logger::addLog($e->getMessage());
            Logger::addLog('一昨日のページャがindex.html指してたのを更新するのに失敗した'.PHP_EOL);
        }
    }


    /**
     * 管理人についてや注意点のページを構築する
     *
     * @param  string $page_name
     * @return void
     **/
    private function _buildSubPage ($page_name)
    {
        $layout    = $this->_getLayout('layout');
        $contents  = $this->_buildSubContentsLayout($page_name);
        $side_menu = $this->_buildSideMenuLayout();
        $footer    = $this->_buildFooterLayout();

        $layout = str_replace('${contents}', $contents, $layout);
        $layout = str_replace('${sidemenu}', $side_menu, $layout);
        $layout = str_replace('${pager}', '', $layout);
        $layout = str_replace('${footer}', $footer, $layout);
        $layout = $this->_setMetaData($page_name, $layout);

        $path = ROOT.'/public_html/information/'.$page_name.'.html';
        file_put_contents($path, $layout);

        // S3へコンテンツをアップロードする
        $this->_uploadConents($path, 'information/'.$page_name.'.html');
    }


    /**
     * 指定パスのファイルをS3の指定パスへアップロードする
     *
     * @param  string $from_path
     * @param  string $to_path
     * @return void
     **/
    private function _uploadConents ($from_path, $to_path)
    {
        if (IS_EC2 === false) return false;

        try {
            $this->S3->upload($from_path, $to_path);
        
        } catch (\Exception $e) {
            Logger::addLog($from_path.' -> '.$to_path);
            Logger::addLog('S3へのアップロードに失敗した'.PHP_EOL);
        }
    }


    /**
     * ヘッダやページャなどメインのレイアウトを構築する
     *
     * @return string
     */
    private function _buildMainLayout()
    {
        $layout = $this->_getLayout('layout');
        $toppage_pager = $this->_getLayout('toppage_pager_layout');

        $past_contents = sprintf('/contents/%s.html', date('Ymd', time() - (60 * 60 * 24)));
        $toppage_pager = str_replace('${nextpage}', $past_contents, $toppage_pager);
        $layout = str_replace('${pager}', $toppage_pager, $layout);

        return $layout;
    }


    /**
     * エントリのレイアウトを構築する
     *
     * @return string
     */
    private function _buildEntryLayout()
    {
        $entry_layout = $this->_getLayout('entry_layout');
        $movie_layout = $this->_getLayout('movie_link_layout');
        $entries = '';

        foreach ($this->entry_data as $key => $data) {
            $entry = $entry_layout;

            // keyが四の倍数の場合、div.rowを仕込む
            if ($key % 4 == 0) $entry = '<div class="row">'.$entry;

            $entry = str_replace('${title}', $data->title, $entry);
            $entry = str_replace('${image_src}', $data->image_src, $entry);
            $entry = str_replace('${url}', $data->url, $entry);

            // 動画リンク部のレイアウト調整
            $star_el = '';
            foreach ($data->movies as $movie) {
                $star_el .= str_replace('${movie_link}', $movie, $movie_layout);
            }
            $entry = str_replace('${movie_stars}', $star_el, $entry);

            // keyが四で割って三余る場合、div閉じタグを仕込む
            if ($key % 4 == 3) $entry .= '</div>';

            $entries .= $entry;
        }
        // 最後のdiv閉じタグを仕込む
        if (! isset($key)) return $entries;
        if ($key % 4 != 3) $entries .= '</div>';

        return $entries;
    }


    /**
     * サブページのレイアウトを構築する
     *
     * @param  string $page_name  whoやsiteが入る。構築するレイアウト名
     * @return string
     **/
    private function _buildSubContentsLayout ($page_name)
    {
        $contents = $this->_getLayout($page_name.'_layout');
        return $contents;
    }


    /**
     * サイドメニューのレイアウトを構築する
     *
     * @return string
     **/
    private function _buildSideMenuLayout ()
    {
        $side_menu = $this->_getLayout('side_menu_layout');
        return $side_menu;
    }


    /**
     * フッターのレイアウトを構築する
     *
     * @return string
     **/
    private function _buildFooterLayout ()
    {
        $footer = $this->_getLayout('footer_layout');
        return $footer;
    }


    /**
     * titleやdescriptionなどのメタデータを仕込む
     *
     * @param  string $page_name 生成するページ名
     * @param  string $layout  レイアウトデータ
     * @return string
     **/
    private function _setMetaData ($page_name, $layout)
    {
        $ini = parse_ini_file(ROOT.'/'.$this->site_ini_path, true)[$page_name];

        $layout = str_replace('${title}', $ini['title'], $layout);
        $layout = str_replace('${description}', $ini['description'], $layout);

        return $layout;
    }
}

