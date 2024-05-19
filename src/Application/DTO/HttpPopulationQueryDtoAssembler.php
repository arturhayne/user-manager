<?php

declare(strict_types=1);

namespace UserManager\Application\DTO;

use UserManager\Domain\Population;

class HttpPopulationQueryDtoAssembler implements \JsonSerializable
{
    private $response;

    private function __construct(array $populations)
    {
        $this->response = $populations;
    }

    public static function create(array $populations)
    {
        return new self($populations);
    }

    public function jsonSerialize(): mixed
    {
        return array_map(function (Population $population) {
            $fields = array_map(function ($field) {
                return [
                    'type' => $field->getType(),
                    'required' => $field->isRequired(),
                    'isUnique' => $field->isUnique(),
                    'multi' => $field->isMulti(),
                    'sensitive' => $field->isSensitive(),
                    'name' => $field->getName(),
                    'displayName' => $field->getDisplayName(),
                    'isUniqueAcrossPopulation' => $field->isUniqueAcrossPopulation(),
                ];
            }, $population->getPopulationFields()->toArray());

            return [
                'name' => $population->getName(),
                'fields' => $fields,
            ];
        }, $this->response);
    }
}
