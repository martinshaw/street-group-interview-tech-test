<?php
namespace Martinshaw\StreetGroupInterviewTechTest\Exceptions;

use Exception;

class FileNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('The requested CSV file to be loaded does not exist');
    }
}