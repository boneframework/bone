<?php declare(strict_types=1);

namespace Tests;

use Bone\Contracts\Service\TranslatorInterface;
use Codeception\Test\Unit;

class FormTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testForm()
    {
        $translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $form = new \Bone\I18n\Form('testform', $translator);
        $form->init();
        $this->assertInstanceOf(TranslatorInterface::class, $form->getTranslator());
    }
}


