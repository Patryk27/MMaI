<?php

namespace App\App\Http\Requests\Backend\Pages;

use App\Languages\LanguagesFacade;
use App\Languages\Models\Language;
use App\Languages\Queries\GetLanguageByIdQuery;
use Illuminate\Foundation\Http\FormRequest;

class UpsertRequest extends FormRequest
{

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'page.type' => 'required',
            'pageVariants.*.language_id' => 'numeric',
            'pageVariants.*.status' => 'string',
            'pageVariants.*.route' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9\-\/]*$/'],
            'pageVariants.*.title' => 'string',
        ];
    }

    /**
     * Returns translated attributes for the error messages.
     *
     * Generates human-readable versions of attributes in this fashion: [
     *   'pageVariants.1.title' => 'Title (English)',
     * ]
     *
     * @return array
     */
    public function attributes(): array
    {
        $pageVariants = $this->input('pageVariants');

        // It may be the case that someone malformed the input and what we've
        // received is not an array - in this case: bail out.
        if (!is_array($pageVariants)) {
            return [];
        }

        // We need the languages' repository to translate language ids into
        // language models.
        $languagesFacade = $this->container->make(LanguagesFacade::class);

        $result = [];

        foreach ($pageVariants as $pageVariantIdx => $pageVariant) {
            $languageId = array_get($pageVariant, 'language_id');

            if (isset($languageId)) {
                $language = $languagesFacade->queryOne(
                    new GetLanguageByIdQuery($languageId)
                );

                $result += $this->getAttributesForLanguage($pageVariantIdx, $language);
            }
        }

        return $result;
    }

    /**
     * Returns translated attributes for given page variant and language.
     *
     * Example result: [
     *   'pageVariants.0.title' => 'Title (Polish)',
     * ]
     *
     * @param int $pageVariantIdx
     * @param Language $language
     * @return array
     */
    private function getAttributesForLanguage(int $pageVariantIdx, Language $language): array
    {
        $result = [];

        foreach (['status', 'route', 'title'] as $field) {
            $translatedField = __('base/models/page-variant.fields.' . $field);

            $key = sprintf('pageVariants.%d.%s', $pageVariantIdx, $field);
            $value = sprintf('%s (%s)', $translatedField, $language->english_name);

            $result[$key] = $value;
        }

        return $result;
    }

}