<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Bundle\FrameworkBundle\Console\Application;

if (defined('STDIN') === false) {
    define('STDIN',fopen("php://stdin","r"));
}

class InstallController extends AbstractController
{
    
    #[Route('/wizard', name: 'install_home')]
    public function index(KernelInterface $kernel): RedirectResponse
    {             
        if (file_exists('../install')) {
            return $this->redirectToRoute('home');
        }
        
        $log = '';
        
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $output = new BufferedOutput();
        
        $options = array('command' => 'lexik:jwt:generate-keypair', '--overwrite' => true);
        $application->run(new ArrayInput($options), $output);
        $log .= $output->fetch();
        
        $options = array('command' => 'cache:clear');
        $application->run(new ArrayInput($options), $output);
        $log .= $output->fetch();
        file_put_contents('../install', '1');                
        return $this->redirectToRoute('app_admin_panel', ['page' => 'auth/login']);
    }
    
  
}
