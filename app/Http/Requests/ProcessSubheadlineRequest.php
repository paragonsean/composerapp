<?php

namespace App\Http\Requests;

use App\Rules\DocumentsLimitGateRule;
use App\Rules\ValidateTemplateIdRule;
use App\Rules\TemplatesGateRule;
use App\Rules\WordsLimitGateRule;
use Illuminate\Foundation\Http\FormRequest;

class ProcessSubheadlineRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:128', new WordsLimitGateRule($this->user()), new DocumentsLimitGateRule($this->user()), new TemplatesGateRule($this->user())],
            'product' => ['required', 'string', 'max:128'],
            'audience' => ['required', 'string', 'max:256'],
            'description' => ['required', 'string', 'max:1024'],
            'tone' => ['required', 'in:' . implode(',', array_keys(config('completions.tones')))],
            'template_id' => ['required', new ValidateTemplateIdRule()],
            'creativity' => ['required', 'numeric', 'between:0,1'],
            'variations' => ['required', 'integer', 'in:' . implode(',', config('completions.variations'))],
            'language' => ['required', 'in:' . implode(',', config('completions.languages'))]
        ];
    }
}
