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
     * @var S3
     **/
    private $S3;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->manager = new ImageManager();

        $this->S3 = $this->getMock('Midnight\Aws\S3', array('upload'));
        $this->S3->expects($this->any())
            ->method('upload')
            ->will($this->returnValue(true));
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
        $title = 'app2641.com';
        $this->manager->execute($url, $title);
    }


    /**
     * @test
     * @medium
     * @group image-execute
     * @group image
     */
    public function 正常な処理 ()
    {
        $url = 'http://app2641.com/resources/images/profile.png';
        $title = 'app2641.com';
        $this->manager->setS3($this->S3);
        $this->manager->execute($url, $title);

        $datetime = new \DateTime('now');
        $image_path = sprintf(
            ROOT.'/public_html/contents/%s/%s/%s/e2eed553b0ec153428ed8cd7f051029c.png',
            $datetime->format('Y'),
            $datetime->format('m'),
            $datetime->format('d')
        );
        $this->assertTrue(file_exists($image_path));

        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
}

