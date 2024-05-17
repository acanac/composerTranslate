<?php

namespace Acanac\ComposerTranslate;

class Translator
{
    private $apiUrl = 'https://translate.googleapis.com/translate_a/single';

    public function translate($text, $targetLanguage, $sourceLanguage, $chunkSize)
    {
        $translatedText = '';
        $chunks = str_split($text, $chunkSize);
    
        foreach ($chunks as $chunk) {
            $translatedChunk = $this->translateChunk($chunk, $targetLanguage, $sourceLanguage);
            $translatedText .= $translatedChunk;
        }
    
        return $translatedText;
    }
    
    private function translateChunk($text, $targetLanguage, $sourceLanguage)
    {
        $queryParams = [
            'client' => 'gtx',
            'sl' => $sourceLanguage,
            'tl' => $targetLanguage,
            'dt' => 't',
            'q' => $text,
        ];
    
        // Construire les paramètres de l'URL
        $queryString = http_build_query($queryParams);
    
        // Construire l'URL complète
        $url = $this->apiUrl . '?' . $queryString;
    
        $response = file_get_contents($url);
        if ($response === false) {
            throw new \Exception("Error connecting to the translation service.");
        }
    
        $json = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Error decoding the translation response.");
        }
    
        // Vérifier que le JSON contient bien les traductions attendues
        if (!isset($json[0]) || !is_array($json[0])) {
            throw new \Exception("Unexpected translation response format.");
        }

        // Concaténer toutes les parties de la traduction pour former le texte complet
        $translatedText = '';
        foreach ($json[0] as $translationPart) {
            if (isset($translationPart[0])) {
                $translatedText .= $translationPart[0];
            }
        }
    
        return $translatedText;
    }
}
