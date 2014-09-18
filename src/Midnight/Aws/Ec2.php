<?php


namespace Midnight\Aws;

use Aws\Ec2\Ec2Client;
use Aws\Common\Enum\Region;
use Guzzle\Http\EntityBody;

class Ec2 extends AbstractAws
{
    
    /**
     * @var Ec2Client
     */
    protected $client;


    /**
     * コンストラクタ
     *
     * @return void
     **/
    public function __construct ()
    {
        parent::__construct();
        $this->client = Ec2Client::factory($this->getConfig());
    }
}

