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
     * @var string
     **/
    private $title;


    /**
     * @var string
     **/
    private $download_path;


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
     * @param  string $title
     * @return void
     **/
    public function execute ($url, $title)
    {
        try {
            $this->url = $url;
            $this->title = $title;
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
        $url_info  = parse_url($this->url);
        $path_info = pathinfo($url_info['path']);
        $ext       = $path_info['extension'];
        $file_name = md5($this->title);

        $datetime = new \DateTime('now');
        $this->download_path = sprintf(
            ROOT.'/public_html/contents/%s/%s/%s/'.$file_name.'.'.$ext,
            $datetime->format('Y'),
            $datetime->format('m'),
            $datetime->format('d')
        );
        $this->_makeDirectory($this->download_path);

        // curlでアイキャッチ画像をダウンロードする
        try {
            $command = sprintf('curl %s -o %s', $this->url, $this->download_path);
            exec($command);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * 指定したパスへディレクトリを生成する
     * 再帰的な生成にも対応
     *
     * @param  string $path
     * @return void
     **/
    private function _makeDirectory ($path)
    {
        $dirname = pathinfo($path)['dirname'];
        if (! is_dir($dirname)) {
            $command = 'mkdir -p '.$dirname;
            exec($command);
        }
    }


    /**
     * 画像をリサイズする
     *
     * @return void
     **/
    private function _convert ()
    {
        try {
            $command = sprintf(
                'convert -resize 130 %s %s',
                $this->download_path,
                $this->download_path
            );
            exec($command);

        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * 画像をS3へアップロードする
     *
     * @return void
     **/
    private function _upload ()
    {
        $upload_path = str_replace(ROOT.'/public_html/', '', $this->download_path);
        $this->S3->upload($this->download_path, $upload_path);
    }
}

