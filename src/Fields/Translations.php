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

        $currentUrl = url()->current();
        list($path, $originalQuery) = $this->extractQueryString(url()->current());

        if ($originalQuery) {
            $query = $originalQuery.'&'.$this->currentLocale;
            $url = str_replace($originalQuery, $query, $currentUrl);
        } else {
            $url = $currentUrl.'?'.$this->currentLocale;
        }

        if (count($languages) > 1) {
            return view('admin.formElements.translations', [
                'languages' => config('ignicms.languages'),
                'locale' => $this->currentLocale,
                'url' => $url,
            ])->render();
        }

        return '';
    }

    /**
     * Extract the query string from the given path.
     *
     * @param  string $path
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
}
