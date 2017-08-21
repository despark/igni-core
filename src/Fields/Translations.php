<?php

namespace Despark\Cms\Fields;

use Despark\Cms\Contracts\FieldContract;

class Translations implements FieldContract
{
    /**
     * @var string
     */
    private $currentLocale;

    public function __construct(string $currentLocale)
    {
        $this->currentLocale = $currentLocale;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        $languages = config('ignicms.languages');
        foreach ($languages as &$language) {
            if ($language['locale'] === config('app.fallback_locale')) {
                $language['name'] .= ' (default)';
            }
            $language['url'] = $this->generateLanguageUrl($language['locale']);
        }

        if (count($languages) > 1) {
            return view('ignicms::admin.formElements.translations', [
                'languages' => $languages,
                'locale' => $this->currentLocale,
            ])->render();
        }

        return '';
    }

    /**
     * Extract the query string from the given path.
     *
     * @param string $path
     *
     * @return array
     */
    protected function extractQueryString($path)
    {
        if (($queryPosition = strpos($path, '?')) !== false) {
            return [
                substr($path, 0, $queryPosition),
                substr($path, $queryPosition),
            ];
        }

        return [$path, ''];
    }

    protected function generateLanguageUrl($locale)
    {
        $currentUrl = url()->current();
        list($path, $originalQuery) = $this->extractQueryString(url()->current());

        if ($originalQuery) {
            $query = $originalQuery.'&locale='.$locale;
            $url = str_replace($originalQuery, $query, $currentUrl);
        } else {
            $url = $currentUrl.'?locale='.$locale;
        }

        return $url;
    }
}
