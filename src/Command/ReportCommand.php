<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\ImageProcessor;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Doctrine\ORM\EntityManagerInterface;

class ReportCommand extends ContainerAwareCommand
{

    private $image;
    private $entity;

    public function __construct(ImageProcessor $image, EntityManagerInterface $entity)
    {
        $this->image = $image;
        $this->entity = $entity;
        parent::__construct();
    }


    // public function __construct()
    // {
    //     //$this->image = $image;
    //     parent::__construct();
    // }

    protected function configure()
    {
        $this->setName('ok');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //echo get_class($this->image)."\n";
        echo get_class($this->entity);
    }
}

?>
