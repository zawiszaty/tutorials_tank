<?php

namespace App\Application\Command\User\SendEmail;

use App\Application\Command\CommandHandlerInterface;
use App\Infrastructure\User\Query\Projections\UserView;
use App\Infrastructure\User\Query\Repository\MysqlUserReadModelRepository;
use Ramsey\Uuid\Uuid;
use Swift_TransportException;
use Twig_Environment;

/**
 * Class SendEmailHandler.
 */
class SendEmailHandler implements CommandHandlerInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Twig_Environment
     */
    private $template;

    private $message;

    /**
     * @var MysqlUserReadModelRepository
     */
    private $userRepository;

    /**
     * SendEmailHandler constructor.
     */
    public function __construct(\Swift_Mailer $mailer, Twig_Environment $template, MysqlUserReadModelRepository $userRepository)
    {
        $this->mailer = $mailer;
        $this->template = $template;
        $this->userRepository = $userRepository;
    }

    /**
     * @param SendEmailCommand $command
     *
     * @throws Swift_TransportException
     * @throws \Assert\AssertionFailedException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(SendEmailCommand $command)
    {
        /** @var UserView $user */
        $user = $this->userRepository->get(UserView::class, $command->getUser());
        switch ($command->getType()) {
            case 'REGISTRATION':
                $this->message = (new \Swift_Message('NoReply'))
                    ->setFrom('test@wp.pl')
                    ->setTo($command->getEmail())
                    ->setBody(
                        $this->template->render(
                            'emails/registration.html.twig',
                            ['token' => $user->getConfirmationToken()]
                        ),
                        'text/html'
                    );

                break;
            case 'CHANGE_EMAIL':
                $token = Uuid::uuid4() . '-' . Uuid::uuid4();
                $user->setConfirmationToken($token);
                $this->userRepository->apply();
                $this->message = (new \Swift_Message('NoReply'))
                    ->setFrom('test@wp.pl')
                    ->setTo($command->getEmail())
                    ->setBody(
                        $this->template->render(
                            'emails/changeEmail.html.twig',
                            ['token' => $token, 'oldMail' => $command->getEmail()]
                        ),
                        'text/html'
                    );

                break;
            default:
                throw new Swift_TransportException('Mail template dont find');
                break;
        }
        $this->mailer->send($this->message);
    }
}
