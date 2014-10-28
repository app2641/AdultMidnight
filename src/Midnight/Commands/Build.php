<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

use Midnight\Crawler\EntryManager,
    Midnight\Crawler\ContentsBuilder;

class Build extends AbstractCommand implements CommandInterface
{

    /**
     * @var array
     **/
    private $params;


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
            $this->params = $params;
            $this->_validateParameters();

            // エントリデータを初期化する
            $this->_initEntryData();

            // デモページの構築
            $this->_buildDemoPage();

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
        if (! isset($this->params[1])) {
            throw new \Exception('ビルドするページ名を指定してください');
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
     * デモページの構築
     *
     * @return void
     **/
    private function _buildDemoPage ()
    {
        $builder = new ContentsBuilder();
        $builder->setEntryData($this->entry_data);
        $builder->buildContents($this->params[1]);
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
