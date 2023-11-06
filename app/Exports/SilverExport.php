<?php

namespace App\Exports;

use App\activation_form;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterSheet;

class SilverExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        $time = Carbon::now()->format('d');
        if ($time == '1') {
            // $var_time = Carbon::now()->submonth())->get()
            \DB::statement(\DB::raw('set @count=0'));
            // return $query->select(\DB::raw('*, (@count:=@count+1) AS serial'));
            return $data = activation_form::select(\DB::raw('(@count:=@count+1) AS serial'), 'activation_forms.activation_selected_no', 'activation_forms.activation_sr_no', 'plans.plan_name', 'activation_forms.sim_type', 'activation_forms.activation_date', 'numberdetails.type')
            ->LeftJoin(
                'plans',
                'plans.id',
                'activation_forms.select_plan'
            )
                ->LeftJoin(
                    'numberdetails',
                    'numberdetails.number',
                    'activation_forms.activation_selected_no'
                )
                ->LeftJoin(
                    'users',
                    'users.id',
                    'activation_forms.saler_id'
                )
                ->where('numberdetails.type', 'Silver')
                ->where('activation_forms.status','1.02')
                ->whereIn('activation_forms.channel_type', ['ExpressDial'])
                ->whereBetween('activation_forms.created_at', [Carbon::parse('2022-01-29')->format('Y-m-d H:i:s'), Carbon::now()->today()])
                // ->whereBetween('activation_forms.created_at', [Carbon::parse('30-01-2022')->format('Y-m-d H:i:s'), Carbon::now()->today()])
                // ->whereMonth('activation_forms.created_at', Carbon::now()->submonth())

                // ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->get();
        } else {


            // $mytime = \Carbon\Carbon::now();
            // date('Y-m-d H:i:s');
            // z
            \DB::statement(\DB::raw('set @count=0'));
            // return $query->select(\DB::raw('*, (@count:=@count+1) AS serial'));
            return $data = activation_form::select(\DB::raw('(@count:=@count+1) AS serial'), 'activation_forms.activation_selected_no', 'activation_forms.activation_sr_no', 'plans.plan_name', 'activation_forms.sim_type', 'activation_forms.activation_date', 'numberdetails.type')
            ->LeftJoin(
                'plans',
                'plans.id',
                'activation_forms.select_plan'
            )
                ->LeftJoin(
                    'numberdetails',
                    'numberdetails.number',
                    'activation_forms.activation_selected_no'
                )
                ->LeftJoin(
                    'users',
                    'users.id',
                    'activation_forms.saler_id'
                )
                ->where('numberdetails.type', 'silver')
                ->whereIn('activation_forms.status', ['1.02','1.11'])
                ->whereIn('activation_forms.channel_type', ['ExpressDial'])
                ->whereBetween('activation_forms.created_at', ['2022-01-29 00:00:00', Carbon::now()->today()])
                // ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->get();
        }
    }
    public function headings(): array
    {
        return [
            'S#',
            // 'Customer Number',
            'Account Number',
            'Sub Request Number',
            'Package',
            // 'Region',
            'Type',
            'Activation Date', 'Number Type'
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:A3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('B1:B3000')->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);
                $event->sheet->getDelegate()->getStyle('C1:C3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('D1:D3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('G1:G3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('F1:F3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('H1:H3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
