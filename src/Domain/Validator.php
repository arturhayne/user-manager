<?php

namespace UserManager\Domain;

use UserManager\Infrastructure\UserValuesRepository;

class Validator
{
    public function __construct(private UserValuesRepository $repository)
    {
    }

    public function validate(array $fields, array $populationFields)
    {
        $errors = [];
        $populationId = $populationFields[0]->getPopulationId();

        foreach ($populationFields as $rule) {
            $rule = $rule->toArray();
            $fieldName = $rule['name'];
            $value = $fields[$fieldName] ?? null;

            if (empty($value) && $rule['required']) {
                $errors[$fieldName]['required'] = _removeUnderscore(ucfirst($fieldName)) . ' field is required.';
            }

            if (!empty($value) && $rule['isUnique'] && !$rule['isUniqueAcrossPopulation'] && !$this->repository->isUnique($value, $fieldName, $populationId)) {
                $errors[$fieldName]['isUnique'] = _removeUnderscore(ucfirst($fieldName)) . ' is not unique.';
            }

            if (!empty($value) && $rule['isUniqueAcrossPopulation'] && !$this->repository->isUniqueAcrossPopulation($value, $fieldName)) {
                $errors[$fieldName]['isUniqueAcrossPopulation'] = _removeUnderscore(ucfirst($fieldName)) . ' is not unique across population.';
            }

            if (!empty($value) && $rule['type'] == 'email' && !$this->validateEmail($value)) {
                $errors[$fieldName]['email'] = _removeUnderscore(ucfirst($fieldName)) . ' is invalid email.';
            }

            if (!empty($value) && $rule['type'] == 'date' && !$this->validateDate($value)) {
                $errors[$fieldName]['date'] = _removeUnderscore(ucfirst($fieldName)) . ' is invalid date.';
            }

            if (!empty($value) && $rule['type'] == 'text' && !is_string($value)) {
                $errors[$fieldName]['text'] = _removeUnderscore(ucfirst($fieldName)) . ' is invalid text.';
            }
        }

        return $errors;
    }

    public function validateEmail($email): bool
    {
        if (!is_string($email)) {
            return false;
        }

        $pattern = '/^\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b$/';

        return preg_match($pattern, $email) === 1;
    }

    public function validateDate($date, string $format = 'Y-m-d'): bool
    {
        if (!is_string($date)) {
            return false;
        }

        $dateTime = \DateTime::createFromFormat($format, $date);

        return $dateTime && $dateTime->format($format) === $date;
    }
}

function _removeUnderscore($string)
{
    return str_replace('_', ' ', $string);
}
