<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

class GeneratePlugin extends AbstractCommand implements CommandInterface
{

    /**
     * @var array
     **/
    private $params;


    /**
     * @var string
     **/
    private $name;


    /**
     * @var string
     **/
    private $path;


    /**
     * コマンドの実行
     *
     * @param Array $params  パラメータ配列
     * @return void
     **/
    public function execute (Array $params)
    {
        try {
            $this->params = $params;
            $this->_validateParameters();

            $this->_copySkeleton();
            $this->log('generated plugin!', 'success');

        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
    }


    /**
     * パラメータのバリデート
     *
     * @return void
     **/
    private function _validateParameters ()
    {
        array_shift($this->params);

        if (! isset($this->params[0])) {
            throw new \Exception('プラグイン名を指定してください');
        }

        $this->name = ucfirst($this->params[0]);
        $this->path = ROOT.'/src/Midnight/Crawler/Plugin/'.$this->name.'.php';

        if (file_exists($this->path)) {
            throw new \Exception('同名のプラグインが既に存在しています');
        }
    }


    /**
     * スケルトンファイルをコピーする
     *
     * @return void
     **/
    private function _copySkeleton ()
    {
        $skeleton_path = ROOT.'/data/crawler/skeleton/PluginSkeleton.php';
        $skeleton = file_get_contents($skeleton_path);

        $skeleton = str_replace('${name}', $this->name, $skeleton);
        file_put_contents($this->path, $skeleton);
    }


    /**
     * ヘルプメッセージの表示
     *
     * @return String
     **/
    public static function help ()
    {
        return 'クローラプラグインのスケルトンを生成する';
    }
}
