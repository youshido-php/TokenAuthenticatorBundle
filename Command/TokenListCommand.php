<?php

namespace Youshido\TokenAuthenticationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Youshido\TokenAuthenticationBundle\Model\AccessTokenInterface;

class TokenListCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('token:list')
            ->addArgument('userId', InputArgument::REQUIRED)
            ->setDescription('Show list of tokens for a specific user');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getArgument('userId');

        $tokens = $this->getContainer()->get('universal_object_manager')->getTokenRepository()->findBy(['modelId' => $userId]);
        $output->writeln(sprintf("User with id %s has %d tokens.", $userId, count($tokens)));
        foreach($tokens as $token) {
            /** @var AccessTokenInterface $token */
            $output->writeln(sprintf("%s – %s", $token->getCreatedAt()->format('D m, Y H:i:a'), $token->getValue()));
        }

    }
}
