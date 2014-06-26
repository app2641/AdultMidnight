<?php


use Midnight\Crawler\EntryManager;

class EntryManagerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var EntryManager
     **/
    private $manager;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->manager = new EntryManager();
    }
}

