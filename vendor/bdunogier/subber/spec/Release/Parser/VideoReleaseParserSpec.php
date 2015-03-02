<?php

namespace spec\BD\Subber\Release\Parser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VideoReleaseParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BD\Subber\Release\Parser\VideoReleaseParser');
    }
}
