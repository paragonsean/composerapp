<?php

namespace App\Http\Requests;

use App\Rules\ImagesLimitGateRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:128', new ImagesLimitGateRule($this->user())],
            'description' => ['required', 'string', 'max:2048'],
            'resolution' => ['required', 'string', 'in:' . implode(',', array_keys(config('images.resolutions')))],
            'style' => ['nullable', 'string', 'in:' . implode(',', array_keys(config('images.styles')))],
            'medium' => ['nullable', 'string', 'in:' . implode(',', array_keys(config('images.mediums')))],
            'filter' => ['nullable', 'string', 'in:' . implode(',', array_keys(config('images.filters')))],
            'variations' => ['sometimes', 'required', 'integer', 'in:' . implode(',', config('completions.variations'))]
        ];
    }
}
