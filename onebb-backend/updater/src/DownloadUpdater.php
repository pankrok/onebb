<?php

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class DownloadUpdater extends AbstractUpdater
{
    const TMP_DIR = __DIR__ . '/../tmp/download/';
    
    public function run()
    {
        $data = json_decode(file_get_contents(__DIR__ . '/../updater.json') ,true);
        if (empty($data)) {
            return new Response();
        }
        
        $v = $data[0]['version'];
        $dest = self::TMP_DIR . $v;
        $url = OBB_SERVER . $data[0]['file'];
        $handler = explode('/', $data[0]['file']);
        $filename = end($handler);
        $filename = self::TMP_DIR . $filename;
        
        $filesystem = new Filesystem();
        $client = HttpClient::create([
            'headers' => [
                'User-Agent' => 'OnebbUpdater/1.00 [en] (Symfony)',
            ],
        ]);
        
        $response = $client->request('GET', $url);
        $content = $response->getContent();
        
        $filesystem->dumpFile($filename, $content);
        $filesystem->mkdir($dest);
        $zip = new \ZipArchive;
        $res = $zip->open($filename);
        if ($res === TRUE) {
          $zip->extractTo($dest);
          $zip->close();
        } else {
          throw new \Exception('File donwload or extract error', 256);
        }
        
        array_shift($data);
        file_put_contents(__DIR__ . '/../updater.json' ,json_encode($data));
        $filesystem->remove($filename);

        return new Response();
    }
}