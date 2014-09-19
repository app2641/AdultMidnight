<?php


namespace Midnight\Aws;

use Aws\AutoScaling\AutoScalingClient;
use Aws\Common\Enum\Region;
use Guzzle\Http\EntityBody;

class AutoScaling extends AbstractAws
{

    /**
     * AutoScalingClient
     */
    protected $client;


    /**
     * @return void
     */
    public function __construct ()
    {
        parent::__construct();
        $this->client = AutoScalingClient::factory($this->getConfig());
    }


    /**
     * LaunchConfigurationをすべて取得する
     *
     * @return array
     */
    public function getLaunchConfigurations ()
    {
        try {
            $configs = $this->client->describeLaunchConfigurations();
        
        } catch (\Exception $e) {
            throw $e;
        }

        return $configs;
    }


    /**
     * 指定名のLaunchConfigurationを削除する
     *
     * @param  string $name
     * @return void
     **/
    public function deleteLaunchConfiguration ($name)
    {
        try {
            $this->client->deleteLaunchConfiguration(array(
                'LaunchConfigurationName' => $name
            ));
        
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * 新しいLaunchConfigurationを生成する
     *
     * @param  array $options
     * @return void
     **/
    public function createLaunchConfiguration ($options)
    {
        try {
            $this->client->createLaunchConfiguration($options);
        
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * 新規のAutoScalingGroupを生成する
     *
     * @param  array $options
     * @return void
     **/
    public function createAutoScalingGroup ($options)
    {
        try {
            $this->client->createAutoScalingGroup($options);
        
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * AutoScalingGroupを更新する
     *
     * @param  array $options
     * @return void
     **/
    public function updateAutoScalingGroup ($options)
    {
        try {
            $this->client->updateAutoScalingGroup($options);
        
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * ScheduledActionを更新する
     *
     * @param  array $options
     * @return void
     **/
    public function UpdateScheduledAction ($options)
    {
        try {
            $this->client->putScheduledUpdateGroupAction($options);
        
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

