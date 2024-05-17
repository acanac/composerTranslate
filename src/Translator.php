<?php

namespace Acanac\ComposerTranslate;

class Translator
{
    private $apiUrl = 'https://translate.googleapis.com/translate_a/single';

    public function translate($text, $targetLanguage, $sourceLanguage)
    {
        $queryParams = [
            'client' => 'gtx',
            'sl' => $sourceLanguage,
            'tl' => $targetLanguage,
            'dt' => 't',
            'q' => $text,
        ];

        // Construire l'URL sans encoder les espaces
        $url = $this->apiUrl . '?' . http_build_query($queryParams, '', '&', PHP_QUERY_RFC3986);

        $response = file_get_contents($url);
        if ($response === false) {
            throw new \Exception("Error connecting to the translation service.");
        }

        $json = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Error decoding the translation response.");
        }

        $translatedText = $json[0][0][0];

        return $translatedText;
    }
}
