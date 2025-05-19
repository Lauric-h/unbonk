<?php

namespace App\Application\User\RegisterUser;

use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class RegisterUserCommand implements CommandInterface
{
    public function __construct(
        #[Assert\NotBlank]
        public ?string $username = null,
        #[Assert\NotBlank]
        public ?string $email = null,
        #[Assert\NotBlank]
        public ?string $password = null,
    ) {
    }
}
