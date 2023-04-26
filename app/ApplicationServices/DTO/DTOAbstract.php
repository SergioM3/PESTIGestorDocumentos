<?php

namespace App\ApplicationServices\DTO;

use Illuminate\Contracts\Support\Arrayable;

abstract class DTOAbstract implements Arrayable
{
    public function all(): array
    {
        return get_object_vars($this);
    }

    public function toArray(): array
    {
        return $this->all();
    }

    /**
     * Function to return an array of all the public parameters of the DTO.
     *
     * @return array # Array of Public parameters
     */
    public static function getPublicParams(): array
    {
        $reflectionClass = new \ReflectionClass(static::class);
        $publicProperties = $reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC);
        $params = [];

        foreach ($publicProperties as $property) {
            $params[] = $property->getName();
        }

        return $params;
    }

    public function toJSONResponse()
    {
        return response()->json($this);
    }
}
