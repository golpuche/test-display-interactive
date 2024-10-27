<?php

declare(strict_types=1);

namespace App\Infrastructure\Messenger\Bus;

use App\Application\Query\QueryBusInterface;
use App\Application\Query\QueryInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerQueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function ask(QueryInterface $query): mixed
    {
        try {
            return $this->handle($query);
        } catch (HandlerFailedException $e) {
            /** @var array{string, \Throwable} $exceptions */
            $exceptions = $e->getWrappedExceptions();

            /* @var \Throwable */
            throw array_shift($exceptions);
        }
    }
}
