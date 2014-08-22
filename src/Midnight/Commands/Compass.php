<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

class Compass extends AbstractCommand implements CommandInterface
{

    /**
     * @var array
     **/
    private $params;


    /**
     * sassファイル名
     *
     * @var string
     **/
    private $sass_name;


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

            $this->_compile();
            $this->log('compiled!', $this->sass_name.'.scss');

        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
    }


    /**
     * パラメータバリデート
     *
     * @return void
     **/
    private function _validateParameters ()
    {
        // 指定したsassファイルが存在するかどうか
        $this->sass_name = $this->params[1];
        $sass_path = ROOT.'/public_html/resources/sass/'.$this->sass_name.'.scss';
        if (! file_exists($sass_path)) {
            throw new \Exception('指定したsassファイルが存在しません');
        }
    }


    /**
     * compassを使ってsassをコンパイルする
     *
     * @return void
     **/
    private function _compile ()
    {
        try {
            $command = sprintf('compass compile %s.scss', $this->sass_name);

            chdir(ROOT.'/public_html/resources/sass');
            passthru($command);
        
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * ヘルプメッセージの表示
     *
     * @return String
     **/
    public static function help ()
    {
        return '指定したsassファイルをcompassでコンパイルする';
    }
}
