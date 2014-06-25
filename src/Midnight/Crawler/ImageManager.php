<?php


namespace Midnight\Crawler;

use Midnight\Aws\S3;

class ImageManager
{

    /**
     * @var string
     **/
    private $url;


    /**
     * @var S3
     **/
    private $S3;


    /**
     * @param  S3 $S3
     * @return void
     **/
    public function setS3 (\Midnight\Aws\S3 $S3)
    {
        $this->S3 = $S3;
    }


    /**
     * 画像を指定urlからダウンロードしてS3へアップロードする
     *
     * @param  string $url
     * @return void
     **/
    public function execute ($url)
    {
        try {
            $this->url = $url;
            $this->_validateParameters();

            $this->_download();
            $this->_convert();
            $this->_upload();
        
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * パラメータをバリデートする
     *
     * @return void
     **/
    private function _validateParameters ()
    {
        if (is_null($this->S3)) {
            throw new \Exception('S3クラスを指定してください');
        }
    }


    /**
     * 画像をダウンロードする
     *
     * @return void
     **/
    private function _download ()
    {
        
    }


    /**
     * 画像をリサイズする
     *
     * @return void
     **/
    private function _convert ()
    {
        
    }


    /**
     * 画像をS3へアップロードする
     *
     * @return void
     **/
    private function _upload ()
    {
        
    }
}

