<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of CustomCommand
 * symfony дээр cron job ажиллуулах
 * @author Satjan
 */
class CustomCommand extends Command {

    protected static $defaultName = 'app:send-notification';
    private $em;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->em = $entityManager;
        parent::__construct();
    }

    protected function configure() {
        $this->setDescription('Send notification.')
                ->setHelp('Send notification to user');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->sendNtf();
        return 0;
    }

    private function sendNtf() {
        echo 'success notification  - ' . date('Y-m-d H:i:s');
    }

}
