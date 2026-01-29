<?php

use Bone\I18n\View\Extension\Translate;
use Bone\I18n\Service\TranslatorFactory;
use Bone\Contracts\Service\TranslatorInterface;
use Codeception\Test\Unit;

class TranslateTest extends Unit
{
    private TranslatorInterface $translator;

    public function _before(): void
    {
        $factory = new TranslatorFactory();
        $config = [
            'enabled' => false,
            'translations_dir' => 'tests/_data/translations',
            'type' => Gettext::class,
            'default_locale' => 'en_GB',
            'supported_locales' => ['en_PI', 'en_GB', 'nl_BE', 'fr_BE'],
            'date_format' => 'd/m/Y',
        ];
        Locale::setDefault($config['default_locale']);
        $translator = $factory->createTranslator($config);
        $this->translator = $translator;
    }


    public function testTranslate(): void
    {
        $translate = new Translate($this->translator);
        $greeting = $translate->translate('greeting');
        $this->assertEquals('Hello', $greeting);
        Locale::setDefault('nl_BE');
        $greeting = $translate->translate('greeting');
        $this->assertEquals('Hoi', $greeting);
    }
}
