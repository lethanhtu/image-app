<?php
namespace App\Service;

use Gumlet\ImageResize;
use Symfony\Component\DependencyInjection\ContainerInterface;
class ImageProcessor
{
    private $path;

    public function __construct($path = '')
    {
        $this->path = $path;
    }

    public function save($file)
    {
        $extension = $file->getClientOriginalExtension();
        $newFileName = hash('md5', microtime());
        $newName = $newFileName.'.'.$extension;

        $file->move($this->path.'/original', $newName);

        $imageResize = new ImageResize($this->path.'/original/'.$newName);
        $imageResize->crop(300,200);
        $imageResize->save($this->path.'/thumbnail/'.$newFileName.'.'.$extension);
    }
}
