<?php

namespace App\UI\Http\Web\User\Form\Register;

use Symfony\Component\Validator\Constraints as Assert;

final class RegisterModel
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
