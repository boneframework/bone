<?php

use Bone\I18n\View\Extension\LocaleLink;
use Bone\I18n\Service\TranslatorFactory;
use Codeception\Test\Unit;

class LocaleLinkTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;


    public function testLink()
    {
        $locale = Locale::getDefault();
        $viewHelper = new LocaleLink(true);
        $this->assertEquals('/' . $locale, $viewHelper->locale());
    }
}
