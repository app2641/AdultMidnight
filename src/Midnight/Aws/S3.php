<?php


namespace Midnight\Aws;

use Aws\S3\S3Client;
use Aws\Common\Enum\Region;
use Guzzle\Http\EntityBody;

class S3
{

    /**
     * @var S3Client
     **/
    private $client;


    /**
     * @var string
     **/
    private $bucket;


    /**
     * aws.iniへのパス
     *
     * @var string
     **/
    private $aws_ini_path = 'data/config/aws.ini';


    /**
     * コンストラクタ
     *
     * @return void
     **/
    public function __construct ()
    {
        $ini_path = ROOT.'/'.$this->aws_ini_path;
        $ini = parse_ini_file($ini_path);

        $this->client = S3Client::factory(
            array(
                'key' => $ini['key'],
                'secret' => $ini['secret'],
                'region' => Region::AP_NORTHEAST_1
            )
        );

        $this->bucket = $ini['bucket'];
    }


    /**
     * S3の指定パスへファイルをアップロードする
     *
     * @param  string $from_path
     * @param  string $to_path
     * @return void
     **/
    public function upload ($from_path, $to_path)
    {
        try {
            $this->client->putObject(array(
                'Bucket' => $this->bucket,
                'Body' => $body
            ));

        } catch (\Exception $e) {
            throw $e;
        }

        return true;
    }
}

