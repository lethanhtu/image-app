<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class ReportCommand extends ContainerAwareCommand
{
    protected $publicDir;

    public function __construct($publicDir)
    {
        $this->imageDir = $publicDir.DIRECTORY_SEPARATOR.'images';
    }

    protected function configure()
    {
        $this->setName('app:prepare_dir')
        ->setDescription('Create image directory')
        ->setHelp('This command create directory for saving uploaded images');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        prepare($this->imageDir);
        prepare($this->getSubDirPath('gallery'));
        prepare($this->getSubDirPath('original'));
        prepare($this->getSubDirPath('thumbnail'));
    }

    protected function prepareDir($dir)
    {
        if(!file_exist($dir)){
            mkdir($dir, 0755);
        } elseif(is_writable($dir)) {
            chmod($dir, 0755);
        }
    }


    protected function getSubDirPath($subDir)
    {
        return $this->imageDir.DIRECTORY_SEPARATOR.$subDir;
    }
}
