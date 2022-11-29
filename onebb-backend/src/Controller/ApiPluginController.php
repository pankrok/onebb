<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Service\PluginService;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\OneMessenger;
use App\Entity\User;

class ApiPluginController extends AbstractController
{
   
    protected $client;
    protected $filesystem;
    protected $path;
    protected $tmp;
    
    public function __construct(HttpClientInterface $client, KernelInterface $appKernel)
    {
        $this->client = $client;
        $this->path = $appKernel->getProjectDir() . '/plugin/';
        $this->tmp = $appKernel->getProjectDir() . '/tmp/plugin/';
        $this->filesystem = new Filesystem();
    }
    
    
    #[Route('/api/plugins/download', name: 'plugins_download')]

    public function index(HttpClientInterface $client, Request $request, PluginService $pluginService): Response
    {     
        $success = false;
        $data = json_decode($request->getContent(), true)['path'];
        $handler = explode('/', $data);
        $filename = end($handler);
        array_pop($handler);
        $url = implode('/', $handler);        
        $storeResponse = $client->request('GET', $url . '/' . $filename);
        $content = $storeResponse->getContent();
        $this->filesystem->dumpFile($this->tmp . $filename, $content);
        $zip = new \ZipArchive;
        $res = $zip->open($this->tmp . $filename);
        if ($res === TRUE) {
            $zip->extractTo($this->tmp);
            $zip->close();

            $dir = array_diff(scandir($this->tmp), ['.', '..', $filename]);
            $dir = (end($dir));
            $this->filesystem->mirror($this->tmp . '/' . $dir, $this->path . '/' . $dir);
            $this->filesystem->remove([$this->tmp . '/' . $dir, $this->tmp . $filename]);
            $pluginService->checkPluginDir();
            $success = true;
        } 
        
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode([
            'success' => $success,
        ]));
        return $response;
    }
    
}
