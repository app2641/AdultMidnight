<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

use Midnight\Crawler\Crawler,
    Midnight\Crawler\PluginManager,
    Midnight\Crawler\ImageManager,
    Midnight\Crawler\EntryManager;
use Midnight\Aws\S3;

use Garnet\Container,
    Midnight\Factory\CrawlerPluginTestDataFactory;

class GenerateEntryData extends AbstractCommand implements CommandInterface
{
    /**
     * エントリデータ
     *
     * @var array
     **/
    private $crawl_data = array();

    /**
     * コマンドの実行
     *
     * @param Array $params  パラメータ配列
     * @return void
     **/
    public function execute (Array $params)
    {
        try {
            // テスト用ページをクロールしてエントリデータを取得する
            $this->_crawlTestPages();

            // 引数にフラグがある場合、画像のダウンロードも行う
            if (isset($params[1]) && $params[1] === 'true') {
                $this->_downloadEyeCatchImages();
            }

            // Jsonファイルに保存する
            $this->_saveJsonFile();

        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
    }

    /**
     * テスト用ページをクロールする
     *
     * @return void
     **/
    private function _crawlTestPages ()
    {
        $crawler   = new Crawler();
        $manager   = new PluginManager();
        $container = new Container(new CrawlerPluginTestDataFactory);

        foreach ($manager->getEnablePluginNames() as $plugin_name) {
            $plugin = $manager->getPlugin($plugin_name);
            $plugin->setTestData($container->get($plugin_name));
            $crawler->setPlugin($plugin);
            $entry_data = $crawler->crawl();
            $this->crawl_data = array_merge($this->crawl_data, $entry_data);
        }

        $entry_manager    = new EntryManager();
        $this->crawl_data = $entry_manager->format($this->crawl_data);
    }

    /**
     * アイキャッチ画像のダウンロードを行う
     *
     * @return void
     **/
    private function _downloadEyeCatchImages ()
    {
        $manager = new ImageManager();
        $manager->setS3(new S3());

        foreach ($this->crawl_data as $key => $data) {
            $manager->execute($data->eyecatch, $data->title);
            $this->crawl_data[$key]->image_src = $manager->getDownloadPath();
        }
    }

    /**
     * Jsonファイルに保存
     *
     * @return void
     **/
    private function _saveJsonFile ()
    {
        $json = json_encode($this->crawl_data);
        $file_path = ROOT.'/data/fixtures/entry_data.json';
        file_put_contents($file_path, $json);
    }

    /**
     * ヘルプメッセージの表示
     *
     * @return String
     **/
    public static function help ()
    {
        return 'テスト用のエントリデータを再構築する'.PHP_EOL.
            '引数trueを指定でアイキャッチのダウンロードも行う';
    }
}
