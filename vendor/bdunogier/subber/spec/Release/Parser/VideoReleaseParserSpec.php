<?php

namespace spec\BD\Subber\Release\Parser;

use PhpSpec\ObjectBehavior;

class VideoReleaseParserSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('BD\Subber\Release\Parser\VideoReleaseParser');
    }
}
