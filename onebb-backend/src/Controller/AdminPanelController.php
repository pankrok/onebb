<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\Yaml\Yaml;
use Doctrine\DBAL\Connection;
use Doctrine\Common\Collections\Criteria;
use App\Service\PluginService;

class AdminPanelController extends AbstractController
{
    
    #[Route('%acp_url%{page<.+>}', name: 'app_admin_panel')]
    public function index(PluginService $pluginService): Response
    {
        $pluginService->checkPluginDir();
        return $this->render('admin_panel/index.html.twig', [
            'controller_name' => 'AdminPanelController',
        ]);
    }
    
    #[Route('api/plugin/control', name: 'app_plugin_control')]
    /**
     * Require ROLE_ADMIN only for this action
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminPlugin(Request $request, PluginService $pluginService): Response
    {
        $payload = $request->toArray();
        if ($payload['temp'] === '' || $payload['temp'] === null) {
            $payload['temp'] = 'getAdminTemplate';
        }   

        if ($payload['script'] === '' || $payload['script'] === null) {
            $payload['script'] = 'getAdminScript';
        }  
            
        $response = new Response();
        $response->setContent(json_encode([
            'template' => $pluginService->action($payload['plugin'], $payload['temp']),
            'script' => $pluginService->action($payload['plugin'],  $payload['script'])
        ]));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
    
    #[Route('api/admin/stats', name: 'app_admin_stats')]
    /**
     * Require ROLE_ADMIN only for this action
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function boardStats(CacheInterface $cache, Connection $connection): Response
    {
        
        
        $stats = $cache->get('app.statistics', function ($item) use ($connection) {           
            $entityManager = $this->getDoctrine()->getManager();
            $qb = $entityManager->createQueryBuilder();

            $day = (24 * 60 * 60); // hours * min * sec
            $days = 29;
            $results = $connection->fetchAllAssociative("select version()");

            $mysql_version =  $results[0]['version()'];
            $mariadb_version = '';
            if (strpos($mysql_version, 'Maria') !== false) {
                $mariadb_version = $mysql_version;
                $mysql_version = '';
            }
                
            for ($i = 0; $i < 30; $i++) {
                $date = new \DateTimeImmutable(date("Y-m-d", time() - (($days-$i) *  $day)).' 23:59:59');
                $perDate = new \DateTimeImmutable(date("Y-m-d", time() - (($days-$i) * $day)));
                
                $plot = $this->getDoctrine()->getRepository(\App\Entity\Plot::class);
                $post = $this->getDoctrine()->getRepository(\App\Entity\Post::class);
                $user = $this->getDoctrine()->getRepository(\App\Entity\User::class);
                
                $criteria = Criteria::create()
                    ->where(Criteria::expr()->lt("created_at", $date))
                ;
                
                $criteriaPerDat = Criteria::create()
                    ->where(Criteria::expr()->lt("created_at", $date))
                    ->andWhere(Criteria::expr()->gt("created_at", $perDate))
                ;

                $stats['plots'][$i] = $plot->matching($criteria)->count([]);
                $stats['posts'][$i] = $post->matching($criteria)->count([]);
                $stats['users'][$i] = $user->matching($criteria)->count([]);
                $stats['plots_per_day'][$i] = $plot->matching($criteriaPerDat)->count([]);
                $stats['posts_per_day'][$i] = $post->matching($criteriaPerDat)->count([]);
                $stats['users_per_day'][$i]  = $user->matching($criteriaPerDat)->count([]);
            }
            
            $stats['current_plots'] = end($stats['plots']);
            $stats['current_posts'] = end($stats['posts']);
            $stats['current_users'] = end($stats['users']);
            $stats['plots'] = json_encode($stats['plots']);
            $stats['posts'] = json_encode($stats['posts']);
            $stats['users'] = json_encode($stats['users']);
            $stats['plots_per_day'] = json_encode($stats['plots_per_day']);
            $stats['posts_per_day'] = json_encode($stats['posts_per_day']);
            $stats['users_per_day'] = json_encode($stats['users_per_day']);

            $version = Yaml::parseFile($this->getParameter('kernel.project_dir').'/config/onebb.yaml')['parameters']['version'];
        
            $stats['version'] = $version;
            $stats['php_version'] = phpversion();
            $stats['mysql_version'] = $mysql_version;
            
            $stats['time'] = time();
            
            $item->expiresAfter(3600);
            
            return $stats;
        });       
        
        $response = new Response();
        $response->setContent(json_encode($stats));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
}
