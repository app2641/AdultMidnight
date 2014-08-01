<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

use Midnight\Crawler\EntryManager,
    Midnight\Crawler\ImageManager,
    Midnight\Crawler\ContentsBuilder;
use Midnight\Aws\S3;

class BuildDemo extends AbstractCommand implements CommandInterface
{

    /**
     * エントリデータ
     *
     * @var array
     **/
    private $entry_data;


    /**
     * コマンドの実行
     *
     * @param Array $params  パラメータ配列
     * @return void
     **/
    public function execute (Array $params)
    {
        try {
            // エントリデータを初期化する
            $this->_initEntryData();

            // 画像をダウンロードする
            //$this->_downloadEyeCatchImages();

            // デモページの構築
            $this->_buildDemoPage();

        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
    }


    /**
     * エントリデータの初期化
     *
     * @return void
     **/
    private function _initEntryData ()
    {
        $entry_data = file_get_contents(ROOT.'/data/fixtures/entry_data.json');
        $entry_data = json_decode($entry_data);

        $manager = new EntryManager();
        $this->entry_data = $manager->format($entry_data);
    }


    /**
     * アイキャッチ画像をダウンロードする
     *
     * @return void
     **/
    private function _downloadEyeCatchImages ()
    {
        $manager = new ImageManager();
        $manager->setS3(new S3());

        foreach ($this->entry_data as $key => $data) {
            $manager->execute($data->eyecatch, $data->title);
            $this->entry_data[$key]->image_src = $manager->getDownloadPath();
        }
    }


    /**
     * デモページの構築
     *
     * @return void
     **/
    private function _buildDemoPage ()
    {
        $builder = new ContentsBuilder();
        $builder->setEntryData($this->entry_data);
        $builder->buildContents('demo');
    }


    /**
     * ヘルプメッセージの表示
     *
     * @return String
     **/
    public static function help ()
    {
        return 'デモページを構築する';
    }
}
