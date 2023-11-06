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

class CustomActivationDownload implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $start_date;
    protected $end_date;
    protected $channel_partner;
    protected $number_category;
    function __construct($start_date,$end_date,$channel_partner,$number_category)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->channel_partner = $channel_partner;
        $this->number_category = $number_category;
    }
    public function collection()
    {
        $idsArr = explode(',', $this->number_category);
        // $idsArr2 = explode(',', $this->channel_partner);
        $channel  = $this->channel_partner;
        //
        \DB::statement(\DB::raw('set @count=0'));
        // return $query->select(\DB::raw('*, (@count:=@count+1) AS serial'));
        return $data = activation_form::select(\DB::raw('(@count:=@count+1) AS serial'), 'activation_forms.customer_name', 'activation_forms.activation_selected_no', 'activation_forms.activation_sr_no', 'plans.plan_name', 'activation_forms.emirate_location', 'activation_forms.sim_type', 'activation_forms.activation_date', 'numberdetails.type')
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
        // ->whereIn('numberdetails.type', $idsArr)
            ->when($channel, function ($query) use ($channel) {
                if ($channel == 'All') {
                    return $query->whereIn('activation_forms.channel_type', ['TTF', 'ExpressDial']);
                } else {
                    return $query->where('activation_forms.channel_type', $channel);
                    // return $query->whereIn('partner.id', $deals_in_daily);
                }
            })
        // ->whereIn('activation_forms.channel_type', $idsArr2)
        ->whereBetween('activation_forms.created_at', [$this->start_date,$this->end_date])
        ->get();
    }
    public function headings(): array
    {
        return [
            'S#',
            'Customer Number',
            'Account Number',
            'Sub Request Number',
            'Package',
            'Region',
            'Type',
            'Activation Date', 'Number Type'
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class=>function (AfterSheet $event) {
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
