<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

use Midnight\Aws\AutoScaling;

class UpdateAutoScaling extends AbstractCommand implements CommandInterface
{

    /**
     * @var array
     */
    private $params;


    /**
     * @var string
     */
    private $ami_id;


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
            $this->_validateParams();
            $this->_validateAmiId();

            $this->_updateAutoScalingGroup();

        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
    }


    /**
     * パラメータをバリデートする
     *
     * @return void
     */
    private function _validateParams ()
    {
        if (! isset($this->params[1])) {
            throw new \Exception('AMIを指定してください');
        }
    }


    /**
     * AMI-IDが実在するIDかを判別する
     *
     * @return void
     */
    private function _validateAmiId ()
    {
        $ami_id = $this->params[1];
        if (! preg_match('/ami-[a-z0-9]{8}/', $ami_id)) {
            throw new \Exception('AMI-IDを指定してください');
        }

        $this->ami_id = $ami_id;
    }


    /**
     * AutoScalingGroupの設定を更新する
     *
     * @return void
     */
    private function _updateAutoScalingGroup ()
    {
        $as = new AutoScaling();
        $configs = $as->getLaunchConfigurations();
    }


    /**
     * ヘルプメッセージの表示
     *
     * @return string
     **/
    public static function help ()
    {
        return 'AMI-IDを渡してAutoScalingGroupの更新を行う';
    }
}
