<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

use Midnight\Crawler\Crawler,
    Midnight\Crawler\PluginManager;

use Garnet\Container,
    Midnight\Factory\CraelerPluginTestDataFactory;

class GenerateEntryData extends AbstractCommand implements CommandInterface
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
            // テスト用ページをクロールしてエントリデータを取得する
            $this->_crawlTestPages();

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
        $crawler = new Crawler();
        $crawler->setDryRun(true);

        $manager   = new PluginManager();
        $container = new Container(new CrawlerPluginTestDataFactory);

        foreach ($manager->getEnablePluginNames() as $plugin_name) {
            $plugin = $manager->getPlugin($plugin_name);
            $plugin->setTestData($container->get($plugin_name));
            $crawler->setPlugin($plugin);
        }
    }


    /**
     * ヘルプメッセージの表示
     *
     * @return String
     **/
    public static function help ()
    {
        return 'テスト用のエントリデータを再構築する';
    }
}
