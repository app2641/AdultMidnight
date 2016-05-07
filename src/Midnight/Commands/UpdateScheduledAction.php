<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

use Midnight\Aws\AutoScaling;

class UpdateScheduledAction extends AbstractCommand implements CommandInterface
{
    /**
     * @var AutoScaling
     **/
    private $as;

    /**
     * コマンドの実行
     *
     * @param  array $params  パラメータ配列
     * @return void
     **/
    public function execute (array $params)
    {
        try {
            $this->as = new AutoScaling();
            $this->_updateScheduledAction();

        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
    }

    /**
     * ScheduledActionを更新する
     *
     * @return void
     **/
    private function _updateScheduledAction ()
    {
        try {
            $options = array(
                'AutoScalingGroupName' => 'ModernAdultMidnightCrawlerGroup',
                'ScheduledActionName' => 'ModernAdultMidnightCrawlerSchedule',
                'Recurrence' => '55 10,12 * * *',
                'MinSize' => 1,
                'MaxSize' => 1
            );

            $this->as->UpdateScheduledAction($options);

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
        return 'ModernAdultMidnightCrawlerGroupのScheduledActionを更新する';
    }
}
