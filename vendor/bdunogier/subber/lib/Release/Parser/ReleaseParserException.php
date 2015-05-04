<?php

/**
 * This file is part of the eZ Publish Kernel package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Release\Parser;

class ReleaseParserException extends \InvalidArgumentException
{
    public function __construct($releaseName, $whatsWrong)
    {
        parent::__construct("Unable to parse '$releaseName': $whatsWrong");
    }
}
