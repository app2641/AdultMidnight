<?php


use Midnight\Crawler\ContentsBuilder;
use Midnight\Crawler\EntryManager;

class ContentsBuilderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ContentsBuilder
     */
    private $builder;


    /**
     * @var EntryManager
     */
    private $manager;


    public function setUp()
    {
        $this->builder = new ContentsBuilder();
        $this->manager = new EntryManager();
    }


    /**
     * @test
     * @group builder
     * @group builder-build-demo
     */
    public function デモページを構築する()
    {
        $demo_path = ROOT.'/public_html/demo.html';
        if (file_exists($demo_path)) {
            unlink($demo_path);
        }

        $data = file_get_contents(ROOT.'/data/fixtures/entry_data.json');
        $data = $this->manager->format(json_decode($data));

        $this->builder->setEntryData($data);
        $this->builder->buildContents('demo');

        $this->assertTrue(file_exists($demo_path));
    }


    /**
     * @test
     *
     * @group builder
     * @group builder-build-who
     **/
    public function whoページを構築する ()
    {
        $who_path = ROOT.'/public_html/information/who.html';
        if (file_exists($who_path)) {
            unlink($who_path);
        }

        $this->builder->buildContents('who');
        $this->assertTrue(file_exists($who_path));
    }
}

