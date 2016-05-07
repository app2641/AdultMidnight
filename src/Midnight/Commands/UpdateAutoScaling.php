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
            $this->params = $params;
            $this->_validateParams();
            $this->_validateAmiId();

            $this->as = new AutoScaling();
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
        // 使用するLaunchConfigurationの取得
        $config_name = $this->_getLaunchConfiguration();

        // 使用するAutoScalingGroupの更新
        try {
            $options = array(
                'AutoScalingGroupName' => 'ModernAdultMidnightCrawlerGroup',
                'LaunchConfigurationName' => $config_name,
                'MinSize' => 0,
                'MaxSize' => 0,
                'AvailabilityZones' => array(Aws\Common\Enum\Region::AP_NORTHEAST_1.'a'),
                'VPCZoneIdentifier' => 'subnet-b2ed03c5'
            );
            $this->as->updateAutoScalingGroup($options);
        } catch (\Exception $e) {
            $this->as->createAutoScalingGroup($options);
        }
    }

    /**
     * AutoScalingに使用するLaunchConfigurationを生成して返す
     * 既にconfigが三つ存在していた場合は古いものを削除してから生成を行う
     *
     * @return string
     **/
    private function _getLaunchConfiguration ()
    {
        $configs   = $this->as->getLaunchConfigurations();
        $versions  = array();
        $base_name = 'ModernAdultMidnightCrawlerLaunchConfigVer';

        foreach ($configs['LaunchConfigurations'] as $config) {
            $name = $config['LaunchConfigurationName'];
            if (preg_match('/'.$base_name.'([0-9]+)/', $name, $matches)) {
                $versions[] = $matches[1];
            }
        }

        // 配列の昇順化
        asort($versions);

        // ModernAdultMidnightCrawlerLaunchConfigが三つ以上存在した場合は一番古いものを削除する
        if (count($versions) >= 3) {
            $config_name = $base_name.array_shift($versions);
            $this->as->deleteLaunchConfiguration($config_name);
        }

        // 生成したいLaunchConfiguration名を作る
        if (count($versions) == 0) {
            $config_name = $base_name.'1';
        } else {
            $ver = intval(array_pop($versions));
            $config_name = $base_name.strval($ver + 1);
        }

        // 新しいLaunchConfiguration生成
        $this->_createLaunchConfiguration($config_name);

        return $config_name;
    }

    /**
     * 新しいLaunchConfigurationを生成する
     *
     * @param  string $name
     * @return void
     **/
    private function _createLaunchConfiguration ($name)
    {
        $user_data = file_get_contents(ROOT.'/data/config/CrawlerCloudConfig.txt');
        $user_data = base64_encode($user_data);

        $options = array(
            'LaunchConfigurationName' => $name,
            'ImageId' => $this->ami_id,
            'UserData' => $user_data,
            'SecurityGroups' => array('sg-e8b77b8d'),
            'InstanceType' => Aws\Ec2\Enum\InstanceType::T2_MICRO
        );

        $this->as->createLaunchConfiguration($options);
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
