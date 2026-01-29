<?php

namespace BoneTest\Mvc\Controller;

use Barnacle\Container;
use Bone\Controller\Controller;
use Bone\Controller\Init;
use Bone\View\ViewEngine;
use Bone\Server\SessionAwareInterface;
use Bone\Server\SiteConfig;
use Bone\Server\Traits\HasSessionTrait;
use Bone\View\ViewEngineInterface;
use Bone\Contracts\Service\TranslatorInterface;
use Codeception\Test\Unit;
use Del\SessionManager;

class InitTest extends Unit
{
    public function testInit(): void
    {
        $controller = new class extends Controller implements SessionAwareInterface {
            use HasSessionTrait;
        };

        $container = new Container();
        $container[SiteConfig::class] = $this->getMockBuilder(SiteConfig::class)->disableOriginalConstructor()->getMock();
        $container[TranslatorInterface::class] = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $container[ViewEngineInterface::class] = $this->getMockBuilder(ViewEngine::class)->getMock();
        $container[SessionManager::class] = SessionManager::getInstance();
        $controller = Init::controller($controller, $container);
        $this->assertInstanceOf(SiteConfig::class, $controller->getSiteConfig());
        $this->assertInstanceOf(TranslatorInterface::class, $controller->getTranslator());
        $this->assertInstanceOf(ViewEngine::class, $controller->getView());
        $this->assertInstanceOf(SessionManager::class, $controller->getSession());
    }
}
