<?php
namespace Martinshaw\StreetGroupInterviewTechTest\Exceptions;

use Exception;

class PathOutsideOfPermittedRootException extends Exception
{
    public function __construct()
    {
        parent::__construct('The requested CSV file path to be loaded is outside of the permitted data directory');
    }
}