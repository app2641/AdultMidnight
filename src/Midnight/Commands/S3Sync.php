<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

use Midnight\Aws\S3;

class S3Sync extends AbstractCommand implements CommandInterface
{

    /**
     * @var array
     */
    private $params;


    /**
     * @var array
     */
    private $exclude_files = array();


    /**
     * @var S3
     */
    private $s3;


    /**
     * コマンドの実行
     *
     * @param  array $params  パラメータ配列
     * @return void
     **/
    public function execute (array $params)
    {
        try {
            // アップロード対象のディレクトリを取得する
            $this->params = $params;
            $target_directory = $this->_getTargetDirectory();

            // 除外ファイルの初期化
            $this->_initExcludeFiles();

            // 同期処理
            $this->s3 = new S3();
            $this->_sync($target_directory);

        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
    }


    /**
     * 同期対象のディレクトリパスを取得する
     *
     * @return string
     */
    private function _getTargetDirectory ()
    {
        if (isset($this->params[1])) {
            $target = ROOT.'/'.$this->params[1];
            $target = preg_replace('/\/$/', '', $target);

            if (! preg_match('/public_html\/*/', $target)) {
                throw new \Exception('同期はpublic_htmlディレクトリ以下が対象です');
            }

        } else {
            $target = ROOT.'/public_html';
        }

        if (! is_dir($target)) {
            throw new \Exception('ディレクトリが存在しません');
        }

        return $target.'/';
    }


    /**
     * data/config/sync_exclude.txtから除外ファイルを配列化させる
     *
     * @return void
     */
    private function _initExcludeFiles ()
    {
        $files = file_get_contents(ROOT.'/data/config/sync_exclude.txt');
        $files = explode(PHP_EOL, $files);

        $this->exclude_files = $files;
    }


    /**
     * S3に対象ディレクトリを同期する
     *
     * @param  string $target_directory
     * @return void
     */
    private function _sync ($target_directory)
    {
        foreach (glob($target_directory.'*') as $path) {
            $file = str_replace(ROOT.'/public_html/', '', $path);
            if (in_array($file, $this->exclude_files)) {
                continue;
            }

            if (is_dir($path)) {
                $path .= '/';
                $this->_sync($path);
            } else {
                $this->s3->upload($path, $file);
            }
        }
    }


    /**
     * ヘルプメッセージの表示
     *
     * @return String
     **/
    public static function help ()
    {
        return '指定ディレクトリをS3に同期する。未指定の場合はpublic_html以下を対象とする。';
    }
}
