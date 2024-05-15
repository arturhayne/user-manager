<?php

declare(strict_types=1);

namespace UserManager\Domain;

use Doctrine\Common\Collections\Collection;
use UserManager\Domain\Exception\InvalidFieldsException;

class UserValuesValidationService
{
    public function __construct(private Validator $validator)
    {
    }

    public function validate(array $fields, Collection $rules): void
    {
        $errors = $this->validator->validate($fields, $rules->toArray());
        if (!empty($errors)) {
            throw new InvalidFieldsException($errors);
        }
    }
}
