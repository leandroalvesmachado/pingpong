<?php

class Csv
{
    private string $filePath;
    private string $delimiter;

    public function __construct(string $filePath, string $delimiter = ',')
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new RuntimeException("Arquivo CSV não encontrado ou não pode ser lido: {$filePath}");
        }

        $this->filePath = $filePath;
        $this->delimiter = $delimiter;
    }

    public function read(): array
    {
        $data = [];
        if (($handle = fopen($this->filePath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, $this->delimiter, '"', '\\')) !== false) {
                $data = array_merge($data, $row);
            }
            fclose($handle);
        }

        return $data;
    }
}