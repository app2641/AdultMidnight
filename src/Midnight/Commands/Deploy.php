<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

use Midnight\Aws\S3;

class Deploy extends AbstractCommand implements CommandInterface
{

    /**
     * zip化したアプリケーションの一時保存場所
     */
    private $zip_path;


    /**
     * コマンドの実行
     *
     * @param  array $params  パラメータ配列
     * @return void
     **/
    public function execute (array $params)
    {
        try {
            chdir(ROOT);

            // zip化コマンドの実行
            $result = $this->_zipApplication();

            // zip化したアプリケーションをS3に保存する
            $this->_uploadS3($result);

        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
    }


    /**
     * アプリケーションをzip化してtmpに保存する
     *
     * @return boolean
     */
    private function _zipApplication ()
    {
        $this->zip_path = '/tmp/ModernAdultMidnight.zip';
        if (file_exists($this->zip_path)) unlink($this->zip_path);

        $command = sprintf(
            'zip -r %s %s -x@%s',
            $this->zip_path,
            '*',
            ROOT.'/data/config/exclude.lst'
        );
        passthru($command, $return);

        return $return;
    }


    /**
     * zip化したアプリケーションをS3へ保存する
     *
     * @param  boolean $result zip化コマンドの実行成否
     * @return void
     */
    private function _uploadS3 ($result)
    {
        try {
            if ($result === 1) {
                throw new \Exception('アプリケーションのzip化に失敗しました');
            }

            $S3 = new S3();
            $S3->setBucket('app2641');
            $S3->upload($this->zip_path, 'ModernAdultMidnight.zip');
        
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * ヘルプメッセージの表示
     *
     * @return string
     **/
    public static function help ()
    {
        return 'アプリケーションをzip化してS3へ保存する';
    }
}
