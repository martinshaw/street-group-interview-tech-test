<?php

namespace Martinshaw\StreetGroupInterviewTechTest;

class HomeownerNameCsvLoader
{
    public function load(string $filePath): array
    {
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
