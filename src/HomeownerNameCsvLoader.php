<?php

namespace Martinshaw\StreetGroupInterviewTechTest;

use Martinshaw\StreetGroupInterviewTechTest\Exceptions\{
    FileNotFoundException,
    PathOutsideOfPermittedRootException
};

class HomeownerNameCsvLoader
{
    private function validateFilePath(string $filePath): string
    {
        $permittedRoot = realpath(__DIR__ . '/../data');
        $resolvedPath = realpath(dirname($filePath)) === false ? false : realpath(dirname($filePath)) . DIRECTORY_SEPARATOR . basename($filePath);

        if (strpos($resolvedPath, $permittedRoot) !== 0 || $resolvedPath === false) {
            throw new PathOutsideOfPermittedRootException();
        }

        if (!file_exists($resolvedPath)) {
            throw new FileNotFoundException();
        }

        return $resolvedPath;
    }

    public function load(string $filePath): array
    {
        $filePath = $this->validateFilePath($filePath);

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);
        $data = [];
        while ($row = fgetcsv($file)) {
            $data[] = array_combine($header, $row);
        }
        fclose($file);
        return $data;
    }
}
