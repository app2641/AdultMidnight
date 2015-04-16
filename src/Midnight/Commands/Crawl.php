<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

use Midnight\Crawler\Crawler,
    Midnight\Crawler\PluginManager,
    Midnight\Crawler\EntryManager,
    Midnight\Crawler\ImageManager;
use Midnight\Crawler\ContentsBuilder;

use Midnight\Aws\S3;
use Midnight\Aws\Ses;

use Midnight\Utility\Logger;

class Crawl extends AbstractCommand implements CommandInterface
{

    /**
     * @var array
     **/
    private $params;


    /**
     * @var array
     **/
    private $crawl_data = array();


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
            Logger::init();

            $plugins = $this->_getTragetPlugins();
            $this->_crawl($plugins);
            $this->_downloadEyeCatchImages();
            $this->_build();

            $this->_sendLog();
            $this->log('build!', 'success');

        } catch (\Exception $e) {
            $this->_sendErrorLog($e);
            $this->errorLog($e->getMessage());
        }
    }



    /**
     * ヘルプメッセージの表示
     *
     * @return string
     **/
    public static function help ()
    {
        return 'サイトをクロールする。'.PHP_EOL.
            '引数にプラグイン名を与えて指定サイトのみのクロールが可能。';
    }


    /**
     * 今回使用するプラグインを配列で返す
     *
     * @return array
     **/
    private function _getTragetPlugins ()
    {
        $manager = new PluginManager();

        if (isset($this->params[1])) {
            // プラグインの指定がある場合
            $result = $manager->ifEnablePlugin($this->params[1]);
            if (! $result) {
                throw new \Exception('有効化されていないプラグインです');
            }
            $plugins = array($this->params[1]);

        } else {
            $plugins = $manager->getEnablePluginNames();
        }

        return $plugins;
    }


    /**
     * クロール処理を行う
     *
     * @param  array $plugins  読み込むプラグイン名の配列
     * @return array
     **/
    private function _crawl ($plugins)
    {
        $crawler   = new Crawler();
        $p_manager = new PluginManager();
        $crawl_data = array();

        foreach ($plugins as $plugin_name) {
            $plugin = $p_manager->getPlugin($plugin_name);

            $crawler->setPlugin($plugin);
            $crawl_data = $crawler->crawl();

            $this->crawl_data = array_merge($this->crawl_data, $crawl_data);
        }

        // エントリーデータを整理する
        $entry_manager = new EntryManager();
        $this->crawl_data = $entry_manager->format($this->crawl_data);
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

        foreach ($this->crawl_data as $key => $data) {
            if (count($data) == 0) continue;
            $manager->execute($data->eyecatch, $data->title);
            $this->crawl_data[$key]->image_src = $manager->getDownloadPath();
        }
    }


    /**
     * ページを構築する
     *
     * @return void
     **/
    private function _build ()
    {
        shuffle($this->crawl_data);

        $builder = new ContentsBuilder();
        $builder->setEntryData($this->crawl_data);
        $builder->setS3(new S3());
        $builder->buildContents('index');
    }


    /**
     * クロールのログをメール送信する
     *
     * @return void
     **/
    private function _sendLog ()
    {
        $log = Logger::getLog();
        if ($log === '') return false;

        $ses = new Ses();
        $ses->setTitle('Crawl Logger');
        $ses->setBody($log);
        $ses->send();
    }


    /**
     * クロール中に発生したエラーログをメール送信する
     *
     * @param  Exception $e
     * @return void
     **/
    private function _sendErrorLog ($e)
    {
        $ses = new Ses();
        $ses->setTitle('Crawl Error!');
        $ses->setBody(Logger::getStackTrace($e));
        $ses->send();
    }
}
