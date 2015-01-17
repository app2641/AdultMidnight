<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

use Garnet\Container,
    Midnight\Factory\CrawlerPluginTestDataFactory;

use Midnight\Crawler\PluginManager;
use Midnight\Crawler\Plugin\TestData\AbstractTestData;

class UpdateTestData extends AbstractCommand implements CommandInterface
{

    /**
     * @var array
     **/
    private $params;


    /**
     * コマンドの実行
     *
     * @param  array $params  パラメータ配列
     * @return void
     **/
    public function execute (array $params)
    {
        try {
            $this->params = $params;
            if (! isset($params[1])) {
                throw new \Exception('プラグイン名を指定してください');
            }

            // 対象となるTestDataPluginを取得する
            $plugin = $this->_getTestDataPlugin();

            // テストデータを更新する
            $this->_updateTestData($plugin);

        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
    }


    /**
     * テストデータプラグインを取得して配列で返す
     *
     * @return AbstractTestData
     **/
    private function _getTestDataPlugin ()
    {
        $container = new Container(new CrawlerPluginTestDataFactory);
        return $container->get($this->params[1]);
    }


    /**
     * テストデータを取得して更新する
     *
     * @param  AbstractTestData $plugin
     * @return void
     **/
    private function _updateTestData (AbstractTestData $plugin)
    {
        // エラー用のデータ(error.html, error2.html)を除外するため-2する
        $count = count($plugin->getHtmlPaths()) - 2;

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->load($plugin->getRssPath());

        $items = $dom->getElementsByTagName('item');
        if ($items->length === 0) {
            $items = $dom->getElementsByTagName('entry');
        }

        foreach ($items as $key => $item) {
            $test_data = $this->_downloadTestData($item);
            $test_data_path = $plugin->getHtmlPaths()[$key];

            file_put_contents(ROOT.'/data/fixtures/html/'.$test_data_path, $test_data);
        }
    }


    /**
     * テストデータをダウンロードする
     *
     * @param  DOMElement $item
     * @return string
     **/
    private function _downloadTestData ($item)
    {
        $url = $item->getElementsByTagName('link')->item(0)->nodeValue;
        if ($url === '') {
            $url = $item->getElementsByTagName('link')->item(0)->getAttribute('href');
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }


    /**
     * ヘルプメッセージの表示
     *
     * @return string
     **/
    public static function help ()
    {
        return 'プラグイン名を指定してテストデータの更新を行う';
    }
}
