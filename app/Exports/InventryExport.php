<?php

namespace App\Exports;

use App\Inventry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class InventryExport implements FromCollection ,WithHeadings ,ShouldAutoSize ,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $request;

    public function __construct(array $request) 
    {
        $this->request = $request;
    }
    public function collection()
    {
        $Inventry = Inventry::query();
        if(!empty($this->request))
        {
            if($this->request['job_num'] && $this->request['job_num'] != '')
            {
                $Inventry->where('job_num','like','%'.$this->request['job_num'].'%');
            }
            if($this->request['category'] && $this->request['category'] != '')
            {
                $Inventry->where('category','like','%'.$this->request['category'].'%');
            }
            if($this->request['manufacturer'] && $this->request['manufacturer'] != '')
            {
                $Inventry->where('manufacturer','like','%'.$this->request['manufacturer'].'%');
            }
            if($this->request['model_name'] && $this->request['model_name'] != '')
            {
                $Inventry->where('model_name','like','%'.$this->request['model_name'].'%');
            }
            if($this->request['serial_number'] && $request['serial_number'] != '')
            {
                $Inventry->where('serial_number','like','%'.$request['serial_number'].'%');
            }
        }
        $Inventry = $Inventry->get();
        return $Inventry;
    }
    public function headings(): array
    {
        return [
            '#',
            'Date',
            'Job Number',
            'SKU',
            'Category',
            'Manufacturer',
            'Model Name',
            'Serial Number',
            'Processor',
            'Memory',
            'Hard Disk Model',
            'Hard Disk Serial',
            'Hard Disk Size',
            'CD Drive',
            'Network Card',
            'Raid Controller',
            'Information',
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(20);
            },
        ];
    }
}
