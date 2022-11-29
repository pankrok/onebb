<?php

ini_set("display_errors", 1);
error_reporting(E_ALL);

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Console\Output\BufferedOutput;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\InputStream;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpClient\HttpClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;


require_once dirname(__DIR__).'/vendor/autoload.php';
define('ENV', (include (dirname(__DIR__) . '/.env.local.php')));
define('STDIN',fopen("php://stdin","r"));
const OBB_SERVER = 'https://symfony.s89.eu';

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    
    private $services = [];
    
            
    public function registerBundles(): array
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle(),
            new Nelmio\CorsBundle\NelmioCorsBundle(),
        ];
    }

    protected function configureContainer(ContainerConfigurator $c): void
    {
        $c->import(__DIR__.'/../config/onebb.yaml');
    
        
        $c->extension('framework', [
            'secret' => ENV['APP_SECRET'],
        ]);
        
        $c->extension('nelmio_cors', [
            'defaults' => [
                'origin_regex' => true,
                'allow_credentials' => true,
                'allow_origin'=> ['*'],
                'allow_methods' => ['GET', 'OPTIONS', 'POST', 'PUT', 'PATCH', 'DELETE'],
                'allow_headers' => ['Content-Type', 'Authorization', 'X-ONEBB-ADMIN'],
                'expose_headers' => ['Link'],
                'max_age' => 3600,
            ],
            'paths' => [
                '^/' => null
            ]
        ]);
        
         $c->extension('lexik_jwt_authentication', [
            'secret_key' => '%kernel.project_dir%/config/jwt/private.pem',
            'public_key' => '%kernel.project_dir%/config/jwt/public.pem',
            'pass_phrase' => ENV['JWT_PASSPHRASE'],
            'token_ttl' => 3600 ,
        ]);
        
        $c->extension('doctrine', [
            'dbal' => [
                'url' => ENV['DATABASE_URL'],
            ],
            'orm' => [
                'auto_generate_proxy_classes' => true,
                'naming_strategy' => 'doctrine.orm.naming_strategy.underscore_number_aware',
                'auto_mapping' => true,
                'mappings' => [
                    'App' => [
                        'is_bundle' => false,
                        'type' => 'annotation',
                        'dir' => '%kernel.project_dir%/src/Entity',
                        'prefix' => 'App\Entity',
                        'alias' => 'App',
                    ],
                ]
            ]
        ]);
        
        $c->extension('security', [
            'enable_authenticator_manager' => true,
            'password_hashers' => [
                'Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface' => 'auto',
                'App\Entity\User' => [
                    'algorithm' => 'auto'
                ]
            ],
            'providers' => [
                'app_user_provider' => [
                    'entity' => [
                        'class' => 'App\Entity\User',
                        'property' => 'username',
                    ]
                ],
            ],
            'firewalls' => [
                'dev' => [
                    'pattern' => '^/(_(profiler|wdt)|css|images|js)/',
                    'security' => false
                ],
                'updater' => [
                    'pattern' => '^/update',
                    'stateless' => true,
                    'guard' => [
                        'authenticators' => ['lexik_jwt_authentication.jwt_token_authenticator']
                    ]
                ],
                'main' => [
                    'lazy' => true,
                    'provider' => 'app_user_provider'
                ],
            ],
            'access_control' => [
                [
                    'path' => '/update',
                    'roles' => 'ROLE_ADMIN'
                ]
            ],
        ]);
        
        $c->services()
            ->load('App\\Repository\\', __DIR__ . '/../src/Repository')
            ->autowire()
            ->autoconfigure()
        ;
      
    }
    
    
    public function getLogDir(): string
    {
        return __DIR__ . '/../../var/log/';
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->add('check', '/update/check')->controller([$this, 'check']);
        $routes->add('run', '/update/run')->controller([$this, 'run']);
        $routes->add('api_login_check', '/update/login');
    }


    public function check(): Response
    {
        $cache = new FilesystemAdapter();   
        $response = $cache->get('update', function (ItemInterface $item) {
            $item->expiresAfter(5);
            $client = HttpClient::create([
                'headers' => [
                    'User-Agent' => 'OnebbUpdater/1.00 [en] (Symfony)',
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);
            try {
                $response = $client->request(
                    'GET',
                    'https://symfony.s89.eu/api/releases?version=' . $this->container->getParameter('version')
                );
                $statusCode = $response->getStatusCode();
                if ($statusCode !== 200) {
                    throw new \Exception('Master server error');
                }
                
                $contentType = $response->getHeaders()['content-type'][0];
                if ($contentType !== 'application/json; charset=utf-8') {
                    throw new \Exception('Master server response error');
                }
                
                $content = $response->toArray();
                $id = $content[0]['id'];
                $response = $client->request(
                    'GET',
                    'https://symfony.s89.eu/api/releases?id[gt]=' . $id
                );
                $statusCode = $response->getStatusCode();
                if ($statusCode !== 200) {
                    throw new \Exception('Master server error');
                }
                
                $contentType = $response->getHeaders()['content-type'][0];
                if ($contentType !== 'application/json; charset=utf-8') {
                    throw new \Exception('Master server response error');
                }
                
                $content = $response->getContent();
                file_put_contents(__DIR__.'/updater.json', $content);
                $response = new Response($content);
                
            } catch (\Exception $e) {
                $response = new Response(json_encode($content), 529);
            }
            return $response;
        });
        
        $response->headers->set('Content-Type', 'application/json');
                
        return $response;
    }
    
    public function run(Request $request): Response
    {      
        spl_autoload_register([$this, 'updateAutoload']);        
        $this->createServices();
        foreach ($this->services as $service) {
            try {
                $response = $service->run();
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        if (!isset($response)) {
           $response = new Response();
        }
        return $response;
    }
        
    private function updateAutoload($className)
    {
        if (str_contains($className, 'Updater')) {
            $path = __DIR__ . '/src/';
            if (file_exists($path.$className.'.php'))
                include $path.$className.'.php';
        }
    }
    
    private function createServices()
    {
        $services = [
            DownloadUpdater::class,
            FileUpdater::class,
        ];
        
        foreach ($services as $service) 
        {
            $this->services[] = new $service();
        }
    }
}