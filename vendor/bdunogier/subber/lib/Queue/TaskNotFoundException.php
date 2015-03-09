<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Queue;

use Exception;

class TaskNotFoundException extends Exception
{
    public function __construct( $keyName, $keyValue )
    {
        parent::__construct( "No Task found with $keyName = $keyValue" );
    }
}
