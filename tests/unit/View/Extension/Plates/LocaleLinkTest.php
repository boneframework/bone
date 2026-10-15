<?php declare(strict_types=1);

namespace Tests\View\Extension\Plates;

use Bone\I18n\View\Extension\LocaleLink;
use Codeception\Test\Unit;

class LocaleLinkTest extends Unit
{
    public function testLink(): void
    {
        $locale = \Locale::getDefault();
        $viewHelper = new LocaleLink(true);
        $this->assertEquals('/' . $locale, $viewHelper->locale());
    }
}
