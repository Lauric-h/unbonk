<?php

namespace App\UI\Http\Web\User\Form\Login;

use Symfony\Component\Validator\Constraints as Assert;

final class LoginModel
{
    public function __construct(
        #[Assert\NotBlank]
        public ?string $email = null,
        #[Assert\NotBlank]
        public ?string $password = null,
    ) {
    }
}