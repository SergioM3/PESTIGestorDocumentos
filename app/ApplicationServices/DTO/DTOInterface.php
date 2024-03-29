<?php

namespace App\ApplicationServices\DTO;

use Illuminate\Contracts\Validation\Validator;

interface DTOInterface
{
    public function rules(): array;
    public function messages(): array;
    public function validator(): Validator;
    public function validate(): array;
}
