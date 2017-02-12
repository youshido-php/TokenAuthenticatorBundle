<?php

namespace Youshido\TokenAuthenticationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Youshido\TokenAuthenticationBundle\Model\AccessTokenInterface;

class TokenDeleteCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('token:delete')
            ->addArgument('userId', InputArgument::REQUIRED)
            ->addArgument('token', InputArgument::OPTIONAL)
            ->setDescription('Delete all user tokens');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getArgument('userId');

        $om       = $this->getContainer()->get('universal_object_manager');
        $tokens   = $om->getTokenRepository()->findBy(['modelId' => $userId]);
        $helper   = $this->getHelper('question');
        $question = new ConfirmationQuestion('Confirm deleting all user tokens?(y/N)', false);

        if ($helper->ask($input, $output, $question)) {
            foreach ($tokens as $token) {
                $om->remove($token);
            }
            $om->flush();

            $output->writeln(sprintf("User with id %s tokens were deleted (%s).", $userId, count($tokens)));

        } else {
            $output->writeln("Nothing has happened.");
        }

    }
}
