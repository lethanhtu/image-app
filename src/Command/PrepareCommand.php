<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class PrepareCommand extends Command
{
    protected $publicDir;

    public function __construct($publicDir)
    {
        $this->imageDir = $publicDir;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:prepare_dir')
        ->addOption('log', 'l', InputOption::VALUE_OPTIONAL, 'The status of image directory', false)
        ->setDescription('Create image directory')
        ->setHelp('This command create directory for saving uploaded images');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $showLog = $input->getOption('log') !== false;

        if($showLog) {
            $output->writeln('List created directory:');
            $isCreated = false;

        }

        if($this->prepareDir($this->imageDir)) {
            $output->writeln($this->imageDir);
            $isCreated = true;
        }

        if($this->prepareDir($this->getSubDirPath('detail')) && $showLog) {
            $output->writeln($this->getSubDirPath('detail'));
            $isCreated = true;
        }

        if($this->prepareDir($this->getSubDirPath('thumbnail')) && $showLog) {
            $output->writeln($this->getSubDirPath('thumbnail'));
            $isCreated = true;
        }

        if($this->prepareDir($this->getSubDirPath('original')) && $showLog) {
            $output->writeln($this->getSubDirPath('original'));
            $isCreated = true;
        }

        if($showLog && !$isCreated) {
            $output->writeln('There is not directory is created');
        }



    }

    public function prepareDir($dir)
    {
        $created = false;
        if (!file_exists($dir)) {
            mkdir($dir, 0755);
            $created = true;
        } elseif (!is_writable($dir) || !is_readable($dir)) {
            chmod($dir, 0755);
        }

        return $created;
    }


    public function getSubDirPath($subDir)
    {
        return $this->imageDir.DIRECTORY_SEPARATOR.$subDir;
    }
}
