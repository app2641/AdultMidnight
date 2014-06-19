<?php


use Midnight\Crawler\ImageManager;
use Midnight\Aws\S3;

class ImageManagerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ImageManager
     **/
    private $manager;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->manager = new ImageManager();
    }


    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    S3クラスを指定してください
     * @group image-not-set-s3
     * @group image
     */
    public function S3がセットしてない場合 ()
    {
        $url = 'http://app2641.com';
        $this->manager->execute($url);
    }


    /**
     * @test
     * @group image-execute
     * @group image
     */
    public function 正常な処理 ()
    {
        $url = 'http://app2641.com/resources/images/profile.png';
        $this->manager->setS3(new S3());
        $this->manager->execute($url);
    }
}

