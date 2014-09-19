<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

use Midnight\Aws\AutoScaling;

class TerminateCrawler extends AbstractCommand implements CommandInterface
{

    /**
     * コマンドの実行
     *
     * @param  array $params  パラメータ配列
     * @return void
     **/
    public function execute (array $params)
    {
        try {
            $options = array(
                'AutoScalingGroupName' => 'ModernAdultMidnightCrawlerGroup',
                'MinSize' => 0,
                'MaxSize' => 0,
                'DesiredCapacity' => 0
            );

            $as = new AutoScaling();
            $as->updateAutoScalingGroup($options);

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
        return '起動しているクローラサーバを破棄する';
    }
}
