<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class UserAvatarController extends AbstractController
{
    public const IMG_DIR = '/public/upload/img/';

    public function __invoke(User $data): User
    {
        $avatar = $data->getAvatar();
        $avatar = $this->compress($avatar);
        $data->setAvatar($avatar);

        return $data;
    }

    // FIXME quality should be store in onebb config file!
    private function compress($source, $quality = 90): string
    {
        $source = substr($source, 11);
        $info = getimagesize($source);
        $img_dir = $this->getParameter('kernel.project_dir').self::IMG_DIR;
        $filename = md5(time().$source);
        if ('image/jpeg' == $info['mime']) {
            $image = imagecreatefromjpeg($source);
            $filename .= '.jpeg';
        } elseif ('image/gif' == $info['mime']) {
            $image = imagecreatefromgif($source);
            $filename .= '.gif';
        } elseif ('image/png' == $info['mime']) {
            $image = imagecreatefrompng($source);
            $filename .= '.png';
        } else {
            throw new \Exception('File upload faild, unknow mime type.');
        }

        imagejpeg($image, $img_dir.$filename, $quality);

        return $filename;
    }
}
