<?php

use Acanac\ComposerTranslate\Translator;

require_once __DIR__ . '/../vendor/autoload.php';

$translator = new Translator();
echo $translatedText = $translator->translate('Hello', 'en', 'fr');