<?php

namespace App\ApplicationServices\DTO;

use Illuminate\Validation\Validator;
use App\ApplicationServices\DTO\DTOAbstract;
use App\ApplicationServices\DTO\DTOInterface;

class DocumentSubmitDTO extends DTOAbstract implements DTOInterface
{
    public function __construct(
        public readonly int $user_id,
        public readonly int $document_type_id,
        public readonly ?string $publish_date = null,
        public readonly ?array $document_metadata = null,
        public readonly ?string $temp_document_folder,
        public readonly ?string $document_filename = null
    ) {
        $this->validate();
    }

    public function rules(): array
    {
        return [];
        /** To be Changed */
        return [
            'user_id' => 'required|int'
        ];
    }

    public function messages(): array
    {
        return [];
        /** To be Changed */
        return [
            'user_id' => 'user_id must exist and be a number'
        ];
    }

    public function validator(): Validator
    {
        return validator($this->toArray(), $this->rules(), $this->messages());
    }

    public function validate(): array
    {
        return $this->validator()->validate();
    }
}
