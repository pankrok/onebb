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
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Box;
use App\Entity\Group;
use App\Entity\User;
use App\Entity\Skin;

require_once dirname(__DIR__).'/../vendor/autoload.php';
define('ENV', (include (dirname(__DIR__) . '/../.env.local.php')));
define('STDIN',fopen("php://stdin","r"));

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
        
    public function registerBundles(): array
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle(),
        ];
    }

    protected function configureContainer(ContainerConfigurator $c): void
    {
        // PHP equivalent of config/packages/framework.yaml
        $c->extension('framework', [
            'secret' => ENV['APP_SECRET']
        ]);
        
         $c->extension('lexik_jwt_authentication', [
            'secret_key' => '%kernel.project_dir%/config/jwt/private.pem',
            'public_key' => '%kernel.project_dir%/config/jwt/public.pem',
            'pass_phrase' => ENV['JWT_PASSPHRASE'],
            'token_ttl' => 3600 ,
        ]);
        
        $c->extension('twig', [
               'default_path' => '%kernel.project_dir%/public/install/templates',
               'form_themes' => ['bootstrap_5_layout.html.twig']
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
                'main' => [
                    'lazy' => true,
                    'provider' => 'app_user_provider'
                ]
            ]
        ]);
        
        $c->services()
            ->load('App\\Repository\\', __DIR__ . '/../../src/Repository')
            ->autowire()
            ->autoconfigure()
        ;
      
    }
    
    protected function render(string $template, array $vars = [])
    {
        return new Response(
            $this
                ->getContainer()
                ->get('twig')
                ->render($template, $vars)
            );
    }
    
    public function getLogDir(): string
    {
        return __DIR__ . '/../../var/log/';
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->add('start', '/')->controller([$this, 'start']);
        $routes->add('variables', '/step-1')->controller([$this, 'variables']);
        $routes->add('createdb', '/step-2')->controller([$this, 'createdb']);  
        $routes->add('admin', '/step-3')->controller([$this, 'admin']);    
        $routes->add('finish', '/finish')->controller([$this, 'finish']);            
    }


    public function start(): Response
    {
        return $this->render('step/start.html.twig');
    }
    
    public function variables(Request $request, KernelInterface $kernel): Response
    {
        $log = $message = null;
        if (isset($_GET['db'])) {
            $message = $_GET['db'];
        }
        $v = [
            'db' => '',
            'server' => '',
            'user' => '',
            'password' => '',
        ];
        if ($request->request->has('db')) {
           $v = $request->request->all();
           $env = file_get_contents(__DIR__ .'/env.dat');
           $env = sprintf($env,
                bin2hex(random_bytes(16)),
                'mysql',
                urlencode($v['user']),
                $v['password'],
                $v['server'],
                '3306',
                $v['db'],
                '%kernel.project_dir%/config/jwt/private.pem',
                '%kernel.project_dir%/config/jwt/public.pem',
                bin2hex(random_bytes(16)),
                $v['acp'],
                $v['base'],
            );
            if ($log = file_put_contents(__DIR__ . '/../../.env.local.php', $env)) {
                $application = new Application($kernel);
                $application->setAutoExit(false);
                $output = new BufferedOutput();
                
                $options = array('command' => 'cache:clear', '--no-warmup' => true);
                $application->run(new ArrayInput($options), $output);
                sleep(5);
                $message = 1;
                
                $log .= $output->fetch();
                file_put_contents($this->getLogDir() . 'install.log', $log . PHP_EOL, FILE_APPEND);
                
            } else {
                $message = 'env err';
            }
        }
        return $this->render('step/variable.html.twig', ['message' => $message, 'log' => $log, 'v' => $v]);
    }

    public function createdb(KernelInterface $kernel): Response
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $output = new BufferedOutput();
        
        $options = array('command' => 'cache:clear', '--no-warmup' => true);
        $application->run(new ArrayInput($options), $output);
        $log = $output->fetch();
        
        // Drop old database
        $options = array('command' => 'doctrine:database:drop', '--force' => true);
        $application->run(new ArrayInput($options), $output);
        $log .= $output->fetch();
        
        preg_match('/SQLSTATE\[HY000\]/im', $log, $matches);
        if(empty($matches) === false) {
            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
            $domain = $_SERVER['SERVER_NAME'];        
            header("Location: $protocol$domain/public/install/step-1?db=err");
        }            
        // Create new database
        $options = array('command' => 'doctrine:database:create');
        $application->run(new ArrayInput($options), $output);
        $log .= $output->fetch();
        
        $options = array('command' => 'doctrine:schema:update', '--force' => true);
        $application->run(new ArrayInput($options), $output);
        $log .= $output->fetch();
       
        $options = array('command' => 'lexik:jwt:generate-keypair', '--overwrite' => true);
        $application->run(new ArrayInput($options), $output);
           
        file_put_contents($this->getLogDir() . 'install.log', $log . PHP_EOL, FILE_APPEND);
        
        return $this->render('step/db.html.twig', ['log' => $log]);
    }

    public function admin(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
       $message = null;
       if ($request->request->has('password')) {
           $v = $request->request->all();
           
           if ($v['password'] === $v['vpassword']) {
           
               
                $userStats = new Box();
                $customBox = new Box();
                $userGroup = new Group();   
                $skin = new Skin();
                $adminGroup = new Group();
                $admin = new User();
                
                $userGroup
                    ->setHtmlCode('<strong style="color: var(--black);"><i  class="fa fa-plus-circle"></i> {{username}}</strong>')
                    ->setGroupName('<strong><i class="fa fa-plus-circle"></i> User</strong>')
                    ->setGroupLevel(0)
                    ->setUpdatedAt(new \DateTimeImmutable('NOW'))
                    ->setCreatedAt(new \DateTimeImmutable('NOW'))
                ;
                
               
                $userStats
                    ->setName('User statistics')
                    ->setEngine('UserStats')
                    ->setHtml(null)
                    ->setUpdatedAt(new \DateTimeImmutable('NOW'))
                    ->setCreatedAt(new \DateTimeImmutable('NOW'))
                ;
                
                $customBox
                    ->setName('Custom box')
                    ->setEngine('CustomBox')
                    ->setHtml('<p>This is custom box example</p>')
                    ->setUpdatedAt(new \DateTimeImmutable('NOW'))
                    ->setCreatedAt(new \DateTimeImmutable('NOW'))
                ;
                
                $skin
                    ->setName('standard')
                    ->setVersion('1.0')
                    ->setAuthor('PanKrok')
                    ->setActive(1)     
                    ->setUpdatedAt(new \DateTimeImmutable('NOW'))
                    ->setCreatedAt(new \DateTimeImmutable('NOW'))            
                ;

               $adminGroup
                    ->setHtmlCode('<strong style="color: red;"><i class="fas fa-circle-notch fa-spin"></i> {{username}}</strong>')
                    ->setGroupName('<strong style="color: red;"><i class="fas fa-circle-notch fa-spin"></i> Admin</strong>')
                    ->setGroupLevel(0)
                    ->setUpdatedAt(new \DateTimeImmutable('NOW'))
                    ->setCreatedAt(new \DateTimeImmutable('NOW'))
                ;
               
               $admin
                    ->setUsername($v['username'])
                    ->setPassword($passwordHasher->hashPassword($admin, $v['password']))
                    ->setEmail($v['email'])
                    ->setBanned(0)
                    ->setVerified(1)
                    ->setUserGroup($adminGroup)
                    ->setAcpEnable(1)
                    ->setMcpEnable(1)
                    ->setAvatar(null)
                    ->setSlug('admin')
                    ->setPlotsNo(0)
                    ->setPostsNo(0)
                    ->setCreatedAt(new \DateTimeImmutable('NOW'))
                ;
                
                $em->persist($userGroup);
                $em->persist($skin);        
                $em->persist($userStats);
                $em->persist($customBox);

                $em->persist($adminGroup);
                $em->persist($admin);  
                $em->flush();
                
                $admin->setRoles(['ROLE_ADMIN']);
                $em->persist($admin);

                $em->flush();
                $message = 1;
                
                $conn = $em->getConnection();
                $sql = 'CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB';
                $stmt = $conn->prepare($sql);
                $resultSet = $stmt->executeQuery();
                
           } else {
            $message = 'passwords not match!';
           }
        }
        file_put_contents($this->getLogDir() . 'install.log', $message . PHP_EOL, FILE_APPEND);
        
        return $this->render('step/admin.html.twig', ['message' => $message]);
    }
    
    public function finish(KernelInterface $kernel)
    {        
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $options = array('command' => 'cache:clear', '--no-warmup' => true);
        $application->run(new ArrayInput($options));
        
        file_put_contents($this->getLogDir() . 'install.log', 'Install complete :: ' . time() . PHP_EOL, FILE_APPEND);
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
        $domain = $_SERVER['SERVER_NAME'];        
        file_put_contents(__DIR__ . '/../../.install', '1');
        header("Location: $protocol$domain/wizard");
        die();
    }

}