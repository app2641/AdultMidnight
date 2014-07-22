<?php


namespace Midnight\Crawler;

class ContentsBuilder
{

    /**
     * メインページあるいはデモページを生成する際に、
     * コンテンツのエントリ情報となるデータ配列 

     * @var array
     */
    private $entry_data = array();


    /**
     * @param  array $entry_data 
     * @return array
     */
    public function setEntryData($entry_data)
    {
        $this->entry_data = $entry_data;
    }


    /**
     * レイアウトファイルを取得する
     *
     * @param  string $file_name 読み込むレイアウトファイル名
     * @return string
     */
    private function _getLayout($file_name)
    {
        return file_get_contents(ROOT.'/data/template/'.$file_name.'.html');
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
        $layout = $this->_buildMainLayout();
        $entries = $this->_buildEntryLayout();

        $layout = str_replace('${contents}', $entries, $layout);
        file_put_contents(ROOT.'/public_html/'.$page_name.'.html', $layout);
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
            $entry = str_replace('${image_src}', $data->eyecatch, $entry);

            // 動画リンク部のレイアウト調整
            $star_el = '';
            foreach ($data->movies as $movie) {
                $star_el .= sprintf($movie_layout, $movie);
            }
            $entry = str_replace('${movie_stars}', $star_el, $entry);

            // keyが四で割って三余る場合、div閉じタグを仕込む
            if ($key % 4 == 3) $entry .= '</div>';

            $entries .= $entry;
        }
        // 最後のdiv閉じタグを仕込む
        if ($key % 4 != 3) $entries .= '</div>';

        return $entries;
    }
}

