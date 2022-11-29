<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

final class FileUpdater extends AbstractUpdater
{    
    protected $filesystem;
    protected $configuration;
    
    public function run(): Response
    {
        $this->filesystem = new Filesystem();
        $this->createCfg();
        $response = new Response(json_encode(1));

        try {
            $this->checkCrc();
            $this->update();
        } catch (\Exception $e) {
            if ($e->getCode() === 512) {
                $this->rollback();
            }
            
            return new Response(json_encode([
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]), 200, ['Content-Type' => 'application/json']); 
        }
        
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    protected function checkCrc()
    {
        $files = json_decode(file_get_contents($this->configuration['new'].'/crc.json'), true);
        foreach ($files as $file => $crc) {
            if ($crc === '') {
                continue;
            }
                        
            $md5 = md5(file_get_contents($this->dir . $file));
            if ($md5 !== $crc) {
                throw new \Exception('File modified: '. $file, 256);
            }
        }
    }
    
    protected function createCfg()
    {
        $handler = array_diff(scandir(__DIR__ . '/../tmp/download/'), ['.', '..']);
        $dir = end($handler);
        
        if (empty($dir)) {
            throw new \Exception('Update error, package not exist', 256);
        }
        
        $this->configuration = [
            'src' => __DIR__ . '/../',
            'new' => __DIR__ . '/../tmp/download/'.$dir,
            'backup' => __DIR__ . '/../tmp/backup/',
        ];
    }
    
    protected function rollback()
    {
       $this->filesystem->mirror($this->configuration['backup'], $this->configuration['src']);
    }
    
    protected function update()
    {
        try {
            $this->filesystem->mirror($this->configuration['src'], $this->configuration['backup']);
            $this->filesystem->mirror($this->configuration['new'], $this->configuration['src']);
        } catch(\Exception $e) {
            throw new \Exception('Update Error: ' . $e->getMessage(), 512);
        }
    }
}