<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

use Midnight\Crawler\Crawler,
    Midnight\Crawler\PluginManager,
    Midnight\Crawler\ImageManager;

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

            $plugins = $this->_getTragetPlugins();
            $this->_crawl($plugins);

            $this->_downloadEyeCatchImages();

        } catch (\Exception $e) {
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
<<<<<<< HEAD
            $crawl_data[] = $crawler->crawl();
=======
            $crawl_data = $crawler->crawl();

            $this->crawl_data = array_merge($this->crawl_data, $crawl_data);
>>>>>>> 190597b... 基本構成の実装 #5
        }
    }


    /**
     * アイキャッチ画像をダウンロードする
     *
     * @return void
     **/
    private function _downloadEyeCatchImages ()
    {
        $manager = new ImageManager();

        //var_dump($this->crawl_data);
        //exit();
    }
}
