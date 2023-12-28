<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WelcomeDataExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    private $data;
    private $count=0;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function headings(): array
    {
        return ["No","Sales Area","Region", "Date", "SA Name", "Application Id ","Contract No","Brand","Version","Installation Process","Remark From Field","Applicant's Response", "Phone No"];
    }

    public function collection()
    {
        return $this->data;
    }
    public function map($data): array
    {
        $this->count++;
        return [
            $this->count,
            $data->saleAreaName,
            $data->regionName,
            $data->created_at,
            $data->saName,
            $data->application_id,
            $data->contract_no,
            $data->brandName,
            $data->version,
            $data->installProcessName,
            $data->remarkFieldName,
            $data->applicantResponseName,
            $data->phone_no
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:M1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial');
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(11);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
                $event->sheet->getDelegate()->freezePane('A2');
            },
        ];
    }
}
