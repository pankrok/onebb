<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Box;
use App\Entity\Group;
use App\Entity\User;
use App\Entity\Skin;

class BoxFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userStats = new Box();
        $customBox = new Box();
        $adminGroup = new Group();
        $userGroup = new Group();
        $admin = new User();
        $skin = new Skin();
        
        $adminGroup
            ->setHtmlCode('<strong style="color: red;"><i class="fas fa-circle-notch fa-spin"></i> {{username}}</strong>')
            ->setGroupName('<strong style="color: red;"><i class="fas fa-circle-notch fa-spin"></i> Admin</strong>')
            ->setGroupLevel(0)
        ;
        
        $userGroup
            ->setHtmlCode('<strong style="color: var(--black);"><i  class="fa fa-plus-circle"></i> {{username}}</strong>')
            ->setGroupName('<strong><i class="fa fa-plus-circle"></i> User</strong>')
            ->setGroupLevel(0)
        ;
        
        $admin
            ->setUsername('admin')
            ->setPassword('admin')
            ->setEmail('admin@foo.com')
            ->setBanned(0)
            ->setVerified(1)
            ->setUserGroup($adminGroup)
            ->setAcpEnable(1)
            ->setMcpEnable(1)
            ->setAvatar(null)
        ;
        
        $userStats
            ->setName('User statistics')
            ->setEngine('UserStats')
            ->setHtml(null)
        ;
        
        $customBox
            ->setName('Custom box')
            ->setEngine('CustomBox')
            ->setHtml('<p>This is custom box example</p>')
        ;
        
        $skin
            ->setName('standard')
            ->setVersion('1.0')
            ->setAuthor('PanKrok')
            ->setActive(1)     
            ->setUpdatedAt(new \DateTimeImmutable('NOW'))
            ->setCreatedAt(new \DateTimeImmutable('NOW'))            
        ;
        
        $manager->persist($adminGroup);
        $manager->persist($userGroup);
        $manager->persist($admin);
        $manager->persist($skin);        
        $manager->persist($userStats);
        $manager->persist($customBox);

        $manager->flush();
        
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $manager->flush();
        
    }
}
