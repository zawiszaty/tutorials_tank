<?php

namespace App\Application\Command\User\SendEmail;

use App\Application\Command\CommandHandlerInterface;

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
     * SendEmailHandler constructor.
     *
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param SendEmailCommand $command
     */
    public function __invoke(SendEmailCommand $command)
    {
        $message = (new \Swift_Message('NoReply'))
            ->setFrom('send@example.com')
            ->setTo($command->getEmail())
            ->setBody(
              '
              http://localhost:8080/user/token/' . $command->getToken()
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;

        $this->mailer->send($message);
    }
}
