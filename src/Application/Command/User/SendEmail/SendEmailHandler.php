<?php

namespace App\Application\Command\User\SendEmail;

use App\Application\Command\CommandHandlerInterface;
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

    /**
     * SendEmailHandler constructor.
     *
     * @param \Swift_Mailer    $mailer
     * @param Twig_Environment $template
     */
    public function __construct(\Swift_Mailer $mailer, Twig_Environment $template)
    {
        $this->mailer = $mailer;
        $this->template = $template;
    }

    /**
     * @param SendEmailCommand $command
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(SendEmailCommand $command)
    {
        $message = (new \Swift_Message('NoReply'))
            ->setFrom('test@wp.pl')
            ->setTo($command->getEmail())
            ->setBody(
                $this->template->render(
                    'emails/registration.html.twig',
                    ['token' => $command->getToken()]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
