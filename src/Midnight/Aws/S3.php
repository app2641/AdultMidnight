<?php

namespace Midnight\Aws;

use Aws\S3\S3Client;
use Aws\Common\Enum\Region;
use Guzzle\Http\EntityBody;

class S3 extends AbstractAws
{
    /**
     * @var S3Client
     **/
    protected $client;

    /**
     * @var string
     **/
    private $bucket;

    /**
     * コンストラクタ
     *
     * @return void
     **/
    public function __construct ()
    {
        parent::__construct();

        $this->client = S3Client::factory($this->getConfig());
        $this->setBucket($this->ini['bucket']);
    }

    /**
     * @param  string $bucket
     * @return void
     */
    public function setBucket ($bucket)
    {
        $this->bucket = $bucket;
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
                'Key' => $to_path,
                'Body' => EntityBody::factory(fopen($from_path, 'r'))
            ));

        } catch (\Exception $e) {
            throw $e;
        }

        return true;
    }

    /**
     * S3の指定パスのファイルをダウンロードする
     *
     * @param  string $path
     * @return string
     **/
    public function download ($path)
    {
        try {
            $result = $this->client->getObject(array(
                'Bucket' => $this->bucket,
                'Key' => $path
            ));

            $response = '';
            $result['Body']->rewind();

            while ($data = $result['Body']->read(1024)) {
                $response .= $data;
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return $response;
    }

    /**
     * 指定パスのファイルがS3にあるかどうかを判別する
     *
     * @param  string $path
     * @return boolean
     **/
    public function doesObjectExist ($path)
    {
        return $this->client->doesObjectExist($this->bucket, $path);
    }
}
