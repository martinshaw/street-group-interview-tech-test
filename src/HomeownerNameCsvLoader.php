<?php

namespace Martinshaw\StreetGroupInterviewTechTest;

use Martinshaw\StreetGroupInterviewTechTest\Exceptions\{
    FileNotFoundException,
    PathOutsideOfPermittedRootException
};

class HomeownerNameCsvLoader
{
    /**
     * @var HomeownerName[]
     */
    protected $successfulNameRows = [];

    /**
     * @var int[]
     */
    protected $failedNameRows = [];

    /**
     * @throws FileNotFoundException
     * @throws PathOutsideOfPermittedRootException
     * @return string
     */
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

    /**
     * @return string[][]
     */
    private function equalizeArrayLengths(array $arrayA, array $arrayB): array
    {
        $arrayACount = count($arrayA);
        $arrayBCount = count($arrayB);
        if ($arrayACount > $arrayBCount) {
            $arrayB = array_pad($arrayB, $arrayACount, "");
        } elseif ($arrayBCount > $arrayACount) {
            $arrayA = array_pad($arrayA, $arrayBCount, "");
        }
        return [$arrayA, $arrayB];
    }

    /**
     * @return HomeownerName[]
     */
    private function parseNameStringToObjects(string $nameString): array
    {
        $titles = ['Mr', 'Mrs', 'Ms', 'Miss', 'Dr', 'Doctor', 'Prof', 'Professor', 'Rev', 'Reverend', 'Capt', 'Cpt', 'Captain', 'Sgt', 'Sergeant', 'Lt', 'Lieutenant', 'Sir', 'Lady', 'Lord', 'Dame', 'Madam', 'Mister'];
        $conjoiner = ['And', 'and\/or', 'and',  '&'];

        $regex = '/^(?P<titleA>' . implode('|', $titles) . ')[\.]?\s*(?P<firstNameA>[A-Za-z-]{2,})?\s*((?P<initialA>[A-Z])?[\.]?\s+)?(?P<lastNameA>[A-Za-z-]+)?(?:\s+(?P<conjoiner>' . implode('|', $conjoiner) . ')\s+(?P<titleB>' . implode('|', $titles) . ')[\.]?\s*(?P<firstNameB>[A-Za-z-]{2,})?\s*(?P<initialB>[A-Z])?[\.]?\s+(?P<lastNameB>[A-Za-z-]+))?$/';

        if (preg_match($regex, $nameString, $matches)) {
            $titleA = empty($matches['titleA']) ? null : $matches['titleA'];
            $firstNameA = empty($matches['firstNameA']) ? null : $matches['firstNameA'];
            $initialA = empty($matches['initialA']) ? null : $matches['initialA'];
            $lastNameA = empty($matches['lastNameA']) ? null : $matches['lastNameA'];
            $conjoiner = empty($matches['conjoiner']) ? null : $matches['conjoiner'];
            $titleB = empty($matches['titleB']) ? null : $matches['titleB'];
            $firstNameB = empty($matches['firstNameB']) ? null : $matches['firstNameB'];
            $initialB = empty($matches['initialB']) ? null : $matches['initialB'];
            $lastNameB = empty($matches['lastNameB']) ? null : $matches['lastNameB'];

            $lastNameA = empty($matches['lastNameA']) ? $matches['lastNameB'] : $matches['lastNameA'];

            if (empty($conjoiner) === false && empty($firstNameA)) {
                $firstNameA = $firstNameB;
                $firstNameB = "";
            }

            if ($conjoiner) {
                return [
                    new HomeownerName($titleA, $firstNameA, $initialA, $lastNameA),
                    new HomeownerName($titleB, $firstNameB, $initialB, $lastNameB),
                ];
            }

            return [new HomeownerName($titleA, $firstNameA, $initialA, $lastNameA)];
        }

        return [];
    }

    /**
     * @return HomeownerName[]
     */
    private function loadRowColumnsFromFile(string $filePath): array
    {
        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);
        $data = [];
        while ($row = fgetcsv($file)) {
            [$header, $row] = $this->equalizeArrayLengths($header, $row);
            $data[] = array_combine($header, $row);
        }
        fclose($file);

        $data = array_reduce($data, function ($carry, $row, $index) {
            // if (empty($row['homeowner']) || $row === false) {
            //     $this->failedNameRows[] = $index + 1;
            //     return $carry;
            // }

            // $parsedNames = $this->parseNameStringToObjects($row['homeowner']);

            // if (empty($parsedNames)) {
            //     $this->failedNameRows[] = $index + 1;
            //     return $carry;
            // }

            // return array_merge($carry, $parsedNames);

            return $carry;
        }, []);

        $this->successfulNameRows = $data;

        return $data;
    }

    /**
     * @throws FileNotFoundException
     * @throws PathOutsideOfPermittedRootException
     * @return HomeownerName[]
     */
    public function load(string $filePath): array
    {
        $filePath = $this->validateFilePath($filePath);

        $nameStrings = $this->loadRowColumnsFromFile($filePath);

        // TODO: Remove need for multiple loops by adding process of name string to initial loop in getRowColumnsFromFile

        // $names = call_user_func_array('array_merge', array_map(function ($nameString) {
        //     return $this->parseNameStringToObjects($nameString);
        // }, $nameStrings));

        return $nameStrings;
    }
}
