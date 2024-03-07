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
     * For security reasons, we should only allow files to be loaded from a specific directory on the server within this project or from the temporary uploads directory
     * 
     * @var string
     */
    protected $permittedRoot = __DIR__ . '/../data';

    /**
     * @throws FileNotFoundException
     * @throws PathOutsideOfPermittedRootException
     * @return string
     */
    private function validateFilePath(string $filePath): string
    {
        $temporaryUploadsRoot = ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
        $temporaryUploadsRootOnMac = '/private' . $temporaryUploadsRoot;

        $permittedRoot = realpath($this->permittedRoot);

        $resolvedPath = realpath(dirname($filePath)) === false ? false : realpath(dirname($filePath)) . DIRECTORY_SEPARATOR . basename($filePath);

        if (
            (
                strpos($resolvedPath, $permittedRoot) !== 0 &&
                strpos($resolvedPath, $temporaryUploadsRoot) !== 0 &&
                strpos($resolvedPath, $temporaryUploadsRootOnMac) !== 0
            ) ||
            $resolvedPath === false
        ) {
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
                $firstNameB = null;
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
        $this->successfulNameRows = [];
        $this->failedNameRows = [];

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);
        $data = [];
        while ($row = fgetcsv($file)) {
            [$header, $row] = $this->equalizeArrayLengths($header, $row);
            $data[] = array_combine($header, $row);
        }
        fclose($file);

        foreach ($data as $index => $row) {
            if (empty($row['homeowner']) || $row === false) {
                $this->failedNameRows[] = $index + 1;
                continue;
            }

            $parsedNames = $this->parseNameStringToObjects($row['homeowner']);

            if (empty($parsedNames)) {
                $this->failedNameRows[] = $index + 1;
                continue;
            }

            $this->successfulNameRows = array_merge($this->successfulNameRows, $parsedNames);
        }

        return $this->successfulNameRows;
    }

    /**
     * @throws FileNotFoundException
     * @throws PathOutsideOfPermittedRootException
     * @return HomeownerName[]
     */
    public function load(string $filePath): array
    {
        $filePath = $this->validateFilePath($filePath);

        return $this->loadRowColumnsFromFile($filePath);
    }

    /**
     * @return int[]
     */
    public function getFailedNameRows(): array
    {
        return $this->failedNameRows;
    }

    /**
     * @return HomeownerName[]
     */
    public function getSuccessfulNameRows(): array
    {
        return $this->successfulNameRows;
    }
}
