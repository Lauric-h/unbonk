<?php

namespace App\Infrastructure\Shared\Bus\Middleware;

use App\Infrastructure\Shared\Bus\UserAwareInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class UserStampMiddleWare implements MiddlewareInterface
{
    public function __construct(private Security $security)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        if ($message instanceof UserAwareInterface) {
            $user = $this->security->getUser();

            if (!$user) {
                throw new AccessDeniedException();
            }

            $message->setUserId($user->getUser()->id);
        }

        return $stack->next()->handle($envelope, $stack);
    }
}