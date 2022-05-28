<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DatatableExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $data;
    protected $headings;
    protected $mapping;

    public function __construct($data, $headings, \Closure $mapping)
    {
        $this->data = $data;
        $this->headings = $headings;
        $this->mapping = $mapping;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function map($row): array
    {
        return call_user_func_array($this->mapping, [$row]);
    }
}
