<?php


use Midnight\Aws\Ses;

class SesTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Ses
     **/
    private $ses;


    public function setUp ()
    {
        $this->ses = new Ses();
    }


    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    タイトルを指定してください
     * @group ses-not-set-title
     * @group ses
     */
    public function タイトルの指定がない場合 ()
    {
        $this->ses->send();
    }


    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    メール本文を指定してください
     * @group ses-not-set-body
     * @group ses
     */
    public function メール本文の指定がない場合 ()
    {
        $this->ses->setTitle('Title');
        $this->ses->send();
    }


    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    宛先の指定が正しくありません
     * @group ses-valid-to-address
     * @group ses
     */
    public function メールの宛先指定が正しくない場合 ()
    {
        $this->ses->setTitle('Title');
        $this->ses->setBody('Body');
        $this->ses->setTo('foo@foo.com');
        $this->ses->send();
    }
}

