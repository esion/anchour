<?php

namespace Rizeway\Anchour\Step\Steps;

use Symfony\Component\Console\Output\OutputInterface;

use Rizeway\Anchour\Step\Step;

use jubianchi\Ftp\Ftp;
use jubianchi\Adapter\AdapterInterface;
use jubianchi\Output\Symfony\ConsoleOutputAdapter;
use Rizeway\Anchour\Step\Definition\Definition;

class StepFtp extends Step
{
    public function initialize()
    {
        if(false === $this->getAdapter()->extension_loaded('ftp'))
        {
            throw new \RuntimeException('FTP extension is not loaded');
        }
    }

    protected function setDefaultOptions()
    {
        $this->addOption('local_dir', Definition::TYPE_OPTIONAL);
        $this->addOption('remote_dir', Definition::TYPE_OPTIONAL);
    }

    protected function setDefaultConnections()
    {
        $this->addConnection('connection', Definition::TYPE_REQUIRED);
    }

    public function run(OutputInterface $output)
    {
        error_reporting(($level = error_reporting()) ^ E_WARNING);

        $connection = $this->connections['connection'];
        $connection->connect($output);
        $connection->setOutput(new ConsoleOutputAdapter($output));
        $connection->uploadDirectory(getcwd() . '/' . $this->options['local_dir'], $this->options['remote_dir']);

        error_reporting($level);
    }    
}