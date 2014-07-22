<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

class BuildDemo extends AbstractCommand implements CommandInterface
{

    /**
     * layout.htmlへのパス
     *
     * @var string
     **/
    private $layout_path = 'data/template/layout.html';


    /**
     * コマンドの実行
     *
     * @param Array $params  パラメータ配列
     * @return void
     **/
    public function execute (Array $params)
    {
        try {
            $layout = file_get_contents($this->layout_path);

        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
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
