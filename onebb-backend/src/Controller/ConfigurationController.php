<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

class ConfigurationController extends AbstractController
{
    /**
     * Require ROLE_CONFIG_GET only for this action.
     *
     * @IsGranted("ROLE_CONFIG_GET")
     */
    #[Route('/api/configuration', methods: 'GET')]
    public function getConfig(): Response
    {
        $cfg = Yaml::parseFile($this->getParameter('kernel.project_dir').'/config/onebb.yaml');

        $cfg['parameters']['mailer.dns'] = $this->decodeMailerDns($cfg['parameters']['mailer.dns']);
        $response = new Response();
        $response->setContent(json_encode($cfg));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Require ROLE_CONFIG_PUT only for this action.
     *
     * @IsGranted("ROLE_CONFIG_PUT")
     */
    #[Route('/api/configuration', methods: 'POST')]
    public function putConfig(Request $request): Response
    {
        $cfg = Yaml::parseFile($this->getParameter('kernel.project_dir').'/config/onebb.yaml')['parameters'];
        $data = $request->toArray();
        $data['mailer.dns'] = $this->encodeMailerDns(
            $data['mailer.username'],
            $data['mailer.password'],
            $data['mailer.server'],
            $data['mailer.port']
        );

        unset($data['version']);
        $array = [
            'parameters' => [
                'maintaince' => $data['maintaince'],
                'default_group' =>  $data['default_group'],
                'board_name' =>  $data['board_name'],
                'base_url' => $data['base_url'] ?? $cfg['base_url'],
                'acp_url' => $data['acp_url'] ?? $cfg['acp_url'],
                'version' => $cfg['version'],
                'mailer.dns' => $data['mailer.dns'] ?? $cfg['mailer.dns'],
                'mail.validation' => $data['mail.validation'] ?? $cfg['mail.validation'],
                'mail.address' => $data['mail.address'] ?? $cfg['mail.address'],
                'mail.username' => $data['mail.username'] ?? $cfg['mail.username'],
            ],
        ];

        $yaml = Yaml::dump($array);

        file_put_contents($this->getParameter('kernel.project_dir').'/config/onebb.yaml', $yaml);

        $response = new Response();
        $response->setContent(json_encode(true));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
    /**
     * Require ROLE_CONFIG_PUT only for this action.
     *
     * @IsGranted("ROLE_CONFIG_PUT")
     */
    #[Route('/api/admin/cache', methods: 'PUT')]
    public function clearCache(): Response
    {
        
        $phpBinaryFinder = new PhpExecutableFinder(); 
               
        $process = new Process([
            'php', 'bin/console', 'cache:clear'
         ]);
        $process->setWorkingDirectory(__DIR__ . '/../..');
        $process->start();
        $process->wait();      
        
        $log = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"),"<br/>", $process->getOutput());
        
        $response = new Response();
        $response->setContent(json_encode($log));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }

    protected function decodeMailerDns(string $dns): array
    {
        if ($dns === 'sendmail://default') {
            return [
            'username' => null,
            'password' => null,
            'server' => null,
            'port' => null,
        ];
        }
        
        $str = substr($dns, 7);
        list($user, $server) = explode('@', $str);
        list($username, $password) = explode(':', $user);
        list($server, $port) = explode(':', $server);
        $username = str_replace('%40', '@', $username);

        return [
            'username' => $username,
            'password' => $password,
            'server' => $server,
            'port' => $port,
        ];
    }

    protected function encodeMailerDns(string $username, string $password, string $server, string $port): string
    {
        if ($username === null || $password === null || $server === null || $port === null) {
            return 'sendmail://default';
        }
        
        $username = str_replace('@', '%40', $username);
        $dns = 'smtp://%s:%s@%s:%s';

        return sprintf($dns, $username, $password, $server, $port);
    }
    
}
