<?php

namespace Midnight\Aws;

use Aws\Ses\SesClient;
use Aws\Common\Enum\Region;
use Guzzle\Http\EntityBody;

class Ses extends AbstractAws
{
    /**
     * @var SesClient
     **/
    protected $client;

    /**
     * メールタイトル
     *
     * @var string
     **/
    private $title;

    /**
     * メール本文
     *
     * @var string
     **/
    private $body;

    /**
     * メール送信先
     *
     * @var array
     **/
    private $to = array('app2641+adult-midnight@gmail.com');

    /**
     * コンストラクタ
     *
     * @return void
     **/
    public function __construct ()
    {
        parent::__construct();
        $this->client = SesClient::factory($this->getConfig(Region::US_EAST_1));
    }

    /**
     * @param  string $body
     * @return void
     **/
    public function setBody ($body)
    {
        $this->body = $body;
    }

    /**
     * @param  string $title
     * @return void
     **/
    public function setTitle ($title)
    {
        $this->title = $title;
    }

    /**
     * @param  array $to
     * @return void
     **/
    public function setTo ($to)
    {
        $this->to = $to;
    }

    /**
     * メール送信処理
     *
     * @return void
     **/
    public function send ()
    {
        try {
            $this->_validateParameters();

            $this->client->sendEmail(array(
                'Source' => 'app2641+adult-midnight@gmail.com',
                'Destination' => array(
                    'ToAddresses' => $this->to
                ),
                'Message' => array(
                    'Subject' => array(
                        'Data' => $this->title,
                        'Charset' => 'ISO-2022-JP'
                    ),
                    'Body' => array(
                        'Text' => array(
                            'Data' => $this->body,
                            'Charset' => 'ISO-2022-JP'
                        )
                    )
                )
            ));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * パラメータのバリデート
     *
     * @return void
     **/
    private function _validateParameters ()
    {
        if (is_null($this->title)) {
            throw new \Exception('タイトルを指定してください');
        }

        if (is_null($this->body)) {
            throw new \Exception('メール本文を指定してください');
        }

        if (! is_array($this->to)) {
            throw new \Exception('宛先の指定が正しくありません');
        }
    }
}
