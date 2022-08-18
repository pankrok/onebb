<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\SkinRepository;
use App\Entity\Skin;

class SkinController extends AbstractController
{
    private const SKIN_DIR = DIRECTORY_SEPARATOR  . 'public' . DIRECTORY_SEPARATOR . 'skins';
    protected $skinRepository;
    protected $entityManager;
    
    public function __construct(SkinRepository $skinRepository, ManagerRegistry $doctrine)
    {
        $this->skinRepository = $skinRepository;
        $this->entityManager = $doctrine->getManager();
    }
    
    /**
     * Require ROLE_CONFIG_GET only for this action.
     *
     * @IsGranted("ROLE_SKING_GET")
     */
    #[Route('/api/admin-skins', methods: 'GET')]
    public function getSkin(): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        
        $response->setContent(json_encode(
            $this->checkSkins(),
        ));

        return $response;
    }

    /**
     * Require ROLE_SKIN_EDIT only for this action.
     *
     * @IsGranted("ROLE_SKIN_EDIT")
     */
    #[Route('/api/admin-skins/{id}', methods: 'PUT')]
    public function putSkin(int $id): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        
        $newSkin = $this->skinRepository->find($id);
        $oldSkin = $this->skinRepository->findOneBy(['active' => 1]);
        
        $newSkin->setActive(1);
        $oldSkin->setActive(0);
        
        $this->entityManager->persist($newSkin);
        $this->entityManager->persist($oldSkin);
        
        $return = $this->entityManager->flush();
        
        $this->changeSkinTpl($newSkin->getName());
        
        $response->setContent(json_encode($return));

        return $response;
    }
    
    private function checkSkins(): array
    {
        $return = [];        
        $skinDir = $this->getParameter('kernel.project_dir') . SELF::SKIN_DIR;
        $skins = scandir($skinDir);
        $skins = array_diff($skins, ['.', '..', '.gitignore']);         
        foreach ($skins as $skin) {
            $skinEntity = $this->skinRepository->findOneBy(['name' => $skin]);
            if ($skinEntity === null) {
                $file = $skinDir . DIRECTORY_SEPARATOR . $skin . DIRECTORY_SEPARATOR . 'skin.json' ;
                if (file_exists($file)) {
                    
                    $newSkin = json_decode(file_get_contents($file) , true);
                    $skinEntity = new Skin();
                    $skinEntity
                        ->setName($newSkin['name'])
                        ->setVersion($newSkin['version'])
                        ->setAuthor($newSkin['author'])
                        ->setActive(0)
                        ->setCreatedAt(new \DateTimeImmutable('NOW'))
                        ->setUpdatedAt(new \DateTimeImmutable('NOW'))
                    ;
                    
                    $this->entityManager->persist($skinEntity);
                    $this->entityManager->flush();
                }                
            }
            $return[$skinEntity->getId()] = [
                'name' => $skinEntity->getName(),
                'version' => $skinEntity->getVersion(),
                'author' => $skinEntity->getAuthor(),
                'active' => $skinEntity->getActive(),
            ];
        }
        return $return;
    }
    
    private function changeSkinTpl(string $newSkin): void
    {
        $indexDir = $this->getParameter('kernel.project_dir') . SELF::SKIN_DIR . DIRECTORY_SEPARATOR . $newSkin . DIRECTORY_SEPARATOR . 'index.html';
        $indexTpl = $this->getParameter('kernel.project_dir') . DIRECTORY_SEPARATOR . '/templates/boards/index.html.twig';
        
        try {
            $content = file_get_contents($indexDir);
            if ($content === false) {
                throw new \Exception('Skin not found!');
            }
            file_put_contents($indexTpl, $content);
        } catch (\Exception $e) {
            die(json_encode($e));
        }
        
    }
}
