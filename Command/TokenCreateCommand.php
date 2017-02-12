<?php

namespace Youshido\TokenAuthenticationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TokenCreateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('token:create')
            ->addArgument('userId', InputArgument::REQUIRED)
            ->setDescription('Creates a token for a specified user');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getArgument('userId');
        
        $token = $this->getContainer()->get('access_token_helper')->generateToken($userId);
        $output->writeln("Token has been generated: " . $token->getValue());
    }
}
