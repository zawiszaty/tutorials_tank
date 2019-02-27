<?php

namespace App\Application\Command\Message\View;

/**
 * Class MessageViewCommand.
 */
class MessageViewCommand
{
    /**
     * @var array[]
     */
    private $messages;

    /**
     * MessageViewCommand constructor.
     *
     * @param array[] $messages
     */
    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    /**
     * @return array[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
