<?php

namespace App\Exports;

use App\Models\lead_sale;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;


class HomeWifiTracker implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        $time = Carbon::now()->format('d');
        

            \DB::statement(\DB::raw('set @count=0'));

            return $data = lead_sale::select(
                \DB::raw('(@count:=@count+1) AS serial'),
                \DB::raw("CONCAT('Vocus Electronic Trading LLC') as partner_name"), //title
                \DB::raw("CONCAT('TE151') as dealer_id"), //title
                'lead_sales.du_lead_no as du_lead_no',
                'users.name as agent_name',
                'lead_sales.work_order_num as work_order_num',
                'lead_sales.reff_id as reff_id',
                'lead_sales.customer_name',
                'lead_sales.customer_number',
                'lead_sales.emirate',
                'lead_sales.lead_date',
                'home_wifi_plans.du_wifi_name as order_type',
                // \DB::raw("CONCAT('Home WiFi 5g Plus') as order_type"), //title
                \DB::raw("CONCAT('NEW') as order_new"), //title
                \DB::raw("CONCAT('0%') as discount"), //title
                'lead_sales.order_status as status',
                'lead_sales.contract_id',
                'lead_sales.delivery_date as delivery_date',
                'lead_sales.activation_date as activation_date',
                'lead_sales.emirate_id as emirate_id',
                'lead_sales.nationality as nationality',
                'lead_sales.fourjee_id',
                'lead_sales.fourjee_account',
                'lead_sales.contract_id'
                // 'lead_sales.contract_id'

                // \DB::raw("CONCAT(' ') as order_status"), //title
                // \DB::raw("CONCAT(' ') as rejected_reason"), //title
                // \DB::raw("CONCAT(' ') as delivered_on"), //title
                // \DB::raw("CONCAT(' ') as disconnection_date"), //title
                // \DB::raw("CONCAT(' ') as active_date"), //title
            )
            ->Join(
                'home_wifi_plans','home_wifi_plans.id','lead_sales.plans'
            )
                ->Join(
                    'users',
                    'users.id',
                    'lead_sales.saler_id'
                )
                ->where('lead_sales.lead_type', 'HomeWifi')
                // ->where('lead_sales.status','1.08')
            
            // ->whereDate('lead_sales.updated_at', Carbon::yesterday())
            ->whereMonth('lead_sales.updated_at', Carbon::now()->month)
            ->whereYear('lead_sales.created_at', Carbon::now()->year)
                ->get();

            // $mytime = \Carbon\Carbon::now();
            // date('Y-m-d H:i:s');
            // z
            // \DB::statement(\DB::raw('set @count=0'));
            // // return $query->select(\DB::raw('*, (@count:=@count+1) AS serial'));
            // return $data = activation_form::select(\DB::raw('(@count:=@count+1) AS serial'), 'activation_forms.activation_selected_no', 'activation_forms.activation_sr_no', 'plans.plan_name', 'activation_forms.sim_type', 'activation_forms.activation_date')
            // ->LeftJoin(
            //     'plans',
            //     'plans.id',
            //     'activation_forms.select_plan'
            // )
            // ->LeftJoin(
            //     'users',
            //     'users.id',
            //     'activation_forms.saler_id'
            // )
            // ->where('activation_forms.channel_type', 'MWH')
            // ->where('activation_forms.status', '1.02')
            // ->whereMonth('activation_forms.created_at', Carbon::now()->month)
            // ->whereYear('activation_forms.created_at', Carbon::now()->year)
            // ->get();
        // }
    }
    public function headings(): array
    {
        return [
            'S#',
            'Partner Name',
            'Dealer ID',
            'Order Particulars #',
            'Sales Agent Name',
            '4G Service Mobile Number',
            'Request Number',
            'Customer Name',
            'Mobile Number',
            'Emirate Name',
            'Submittion Date',
            'Order Type',
            'Order Category',
            'Discount',
            'Order Status',
            'Rejected Reason',
            'Emirate ID #',
            'Nationality #',
            '4G Account #',
            // 'Activation Date',
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                // $event->cells('A1:D1', function ($cells) {
                //     $cells->setBackground('#008686');
                //     $cells->setAlignment('center');
                // });
                $event->sheet->getStyle('A1:R1')->getFill()->applyFromArray(['fillType' => 'solid', 'rotation' => 0, 'color' => ['rgb' => '000000'],]);
                $event->sheet->getStyle('A1:R1')->applyFromArray(array(
                    'font' => array(
                        'type'  => 'solid',
                        'color' => array('rgb' => 'FFFFFF')
                    )
                ));
                // $event->sheet->getDelegate()->getStyle('A1:D1')->setBackground('#008686');
                $event->sheet->getDelegate()->getStyle('A1:A3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('B1:B3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                // $event->sheet->getDelegate()->getStyle('B1:B3000')->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);
                $event->sheet->getDelegate()->getStyle('C1:C3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('D1:D3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('E1:E3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('F1:F3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('G1:G3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('H1:H3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('I1:I3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('J1:J3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('K1:K3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('L1:L3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('M1:M3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('N1:N3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('O1:O3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('P1:P3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('Q1:Q3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('R1:R3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                // $event->sheet->getDelegate()->getStyle('S1:S3000')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
    public function title(): string
    {
        return 'Home Wifi Tracker Sheet';
    }

}
