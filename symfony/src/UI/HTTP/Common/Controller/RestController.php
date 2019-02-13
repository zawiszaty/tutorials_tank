<?php

declare(strict_types=1);

namespace App\UI\HTTP\Common\Controller;

use Broadway\EventHandling\EventBus;
use Broadway\EventStore\Dbal\DBALEventStore;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class RestController.
 */
class RestController extends Controller
{
    /**
     * @var CommandBus
     */
    protected $queryBus;

    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * @var EventBus
     */
    protected $eventBus;

    /**
     * @var DBALEventStore
     */
    protected $eventStore;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * RestController constructor.
     */
    public function __construct(
        CommandBus $queryBus,
        CommandBus $commandBus,
        EventBus $eventBus,
        DBALEventStore $eventStore,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;
        $this->eventStore = $eventStore;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return array
     */
    protected function getErrorMessages(FormInterface $form)
    {
        $errors = [];

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
}
