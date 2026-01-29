<?php

namespace BoneTest\Mvc\View\Extension\Plates;

use Bone\View\Extension\Plates\AlertBox;
use Codeception\Test\Unit;

class AlertBoxTest extends Unit
{
    public function testAlertBox(): void
    {
        $viewHelper = new AlertBox();
        $this->assertEquals('<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>hello</div>', $viewHelper->alertBox(['hello', 'info']));
    }
}
