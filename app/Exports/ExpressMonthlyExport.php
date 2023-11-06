<?php

namespace App\Exports;

use App\activation_form;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class ExpressMonthlyExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
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
            return $data = activation_form::select(\DB::raw('(@count:=@count+1) AS serial'), 'activation_forms.activation_selected_no', 'activation_forms.activation_sr_no', 'plans.plan_name', 'activation_forms.sim_type', 'activation_forms.activation_date')
            ->LeftJoin(
                'plans',
                'plans.id',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'users.id',
                'activation_forms.saler_id'
            )
                ->where('activation_forms.channel_type', 'ExpressDial')
                ->where('activation_forms.status', '1.02')

            -> whereMonth('activation_forms.created_at', Carbon::now()->submonth())
            -> whereYear('activation_forms.created_at', Carbon::now()->year)

                // ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->get();
        } else {


            // $mytime = \Carbon\Carbon::now();
            // date('Y-m-d H:i:s');
            // z
            \DB::statement(\DB::raw('set @count=0'));
            // return $query->select(\DB::raw('*, (@count:=@count+1) AS serial'));
            return $data = activation_form::select(\DB::raw('(@count:=@count+1) AS serial'), 'activation_forms.activation_selected_no', 'activation_forms.activation_sr_no', 'plans.plan_name', 'activation_forms.sim_type', 'activation_forms.activation_date')
            ->LeftJoin(
                'plans',
                'plans.id',
                'activation_forms.select_plan'
            )
            ->LeftJoin(
                'users',
                'users.id',
                'activation_forms.saler_id'
            )
                ->where('activation_forms.channel_type', 'ExpressDial')
                ->where('activation_forms.status','1.02')
                ->whereMonth('activation_forms.created_at', Carbon::now()->month)
                ->whereYear('activation_forms.created_at', Carbon::now()->year)
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
            'Activation Date'
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
