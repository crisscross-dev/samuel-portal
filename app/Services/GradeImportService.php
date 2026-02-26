<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class GradeImportService
{
    /**
     * Parse a CSV file into an array of rows.
     * Expected columns: student_id, final_grade
     *
     * @param UploadedFile $file
     * @return array ['data' => [...rows], 'errors' => [...]]
     */
    public function parseCSV(UploadedFile $file): array
    {
        $data   = [];
        $errors = [];
        $handle = fopen($file->getRealPath(), 'r');

        if (!$handle) {
            return ['data' => [], 'errors' => ['Could not open the uploaded file.']];
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return ['data' => [], 'errors' => ['CSV file is empty or invalid.']];
        }

        // Normalize header names
        $header = array_map(fn ($h) => strtolower(trim($h)), $header);

        $studentIdCol  = $this->findColumn($header, ['student_id', 'studentid', 'id']);
        $finalGradeCol = $this->findColumn($header, ['final_grade', 'finalgrade', 'grade', 'score']);

        if ($studentIdCol === false) {
            fclose($handle);
            return ['data' => [], 'errors' => ['CSV must have a "student_id" column.']];
        }
        if ($finalGradeCol === false) {
            fclose($handle);
            return ['data' => [], 'errors' => ['CSV must have a "final_grade" or "grade" column.']];
        }

        $rowNum = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;

            if (count($row) <= max($studentIdCol, $finalGradeCol)) {
                $errors[] = "Row {$rowNum}: Insufficient columns.";
                continue;
            }

            $studentId  = trim($row[$studentIdCol]);
            $finalGrade = trim($row[$finalGradeCol]);

            if ($studentId === '' && $finalGrade === '') {
                continue; // skip empty rows
            }

            if ($studentId === '') {
                $errors[] = "Row {$rowNum}: Missing student_id.";
                continue;
            }

            if ($finalGrade !== '' && (!is_numeric($finalGrade) || $finalGrade < 0 || $finalGrade > 100)) {
                $errors[] = "Row {$rowNum} (Student {$studentId}): Invalid grade '{$finalGrade}'. Must be 0-100.";
                continue;
            }

            $data[] = [
                'student_id'  => $studentId,
                'final_grade' => $finalGrade !== '' ? (float) $finalGrade : null,
            ];
        }

        fclose($handle);

        return compact('data', 'errors');
    }

    /**
     * Find the column index from multiple possible names.
     */
    private function findColumn(array $header, array $possibleNames): int|false
    {
        foreach ($possibleNames as $name) {
            $idx = array_search($name, $header);
            if ($idx !== false) {
                return $idx;
            }
        }
        return false;
    }

    /**
     * Generate a sample CSV template for download.
     */
    public function generateTemplate(): string
    {
        $csv = "student_id,final_grade\n";
        $csv .= "2025-00001,85.50\n";
        $csv .= "2025-00002,78.00\n";
        return $csv;
    }
}
