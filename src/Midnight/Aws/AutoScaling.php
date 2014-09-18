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
}

