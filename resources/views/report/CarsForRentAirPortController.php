<?php

namespace App\Http\Controllers;

use App\Models\all_airport;
use App\Models\be_airport;
use App\Models\be_airport_dtl;
use App\Models\be_brand;
use App\Models\be_make;
use App\Models\be_model;
use App\Models\be_type;
use App\Models\cdw_charges;
use App\Models\config_setup;
use App\Models\country_phone_code;
use App\Models\vehicle;
use App\Models\partner;
use App\Models\emirates_be;
use App\Models\site_settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
// OR with multi
use Artesaos\SEOTools\Facades\JsonLdMulti;

// OR
use Artesaos\SEOTools\Facades\SEOTools;

class CarsForRentAirPortController extends Controller
{




	public function airport_listing(request $request){
        //return $request->slug;
		  $expp = explode('/',$request->slug);
            $error_count = count($expp);
            if ($error_count > 1) {
                abort(404);
            }
		  $model_id_selected = 0;
		  if(!empty($expp[0]))$country_name = $expp[0]; else $country_name = '';
		  if(!empty($expp[1]))$city_name = $expp[1]; else $city_name = '';
		  if(!empty($expp[2]))$airport_name = $expp[2]; else $airport_name = '';

		  if(!empty($expp[3])){
		  	$car_name = $expp[3];

			/*if(count($exp_car)==1){

			}elseif(count($exp_car)>1){
				$exp_car[''];
			}*/
		  }
		  else $car_name = '';

		  if(count($expp)==1){
		    $model_id_selected = $request->slug;
		   }


		$mn = be_make::select('make_name')->where('make_name', $car_name)->count();
        $tn = be_type::select('type_name')->where('type_name', $car_name)->get()->count();
        $mon = be_model::select('model_name')->where('model_name', $car_name)->count();
        $bn = be_brand::select('brand_name')->where('brand_name', $car_name)->count();
        $slug = $car_name;
        //return $model_id_selected;
        if (strpos($slug, '-') !== false) {
            // echo 'true';
            $mine = explode('-', $slug);
            $count = count($mine);
            if ($count > 2) {

                $first = $mine['0'];
                $second = $mine['1'];
                $third = $mine['2'];
            } else {
                $first = $mine['0'];
                $second = $mine['1'];
                $third = '';
            }
        } else {
            $first = '';
            $second = '';
            $third = '';
        }
        $make_brand_model = vehicle::select("be_make.make_id", "be_brand.brand_id")
        ->LeftJoin(
            'be_model',
            'be_model.model_id',
            '=',
            'vehicles.model_id_be'
        )
        ->LeftJoin(
            'be_brand',
            'be_brand.brand_id',
            '=',
            'be_model.brand_id'
        )
        ->LeftJoin(
            'be_type',
            'be_type.type_id',
            '=',
            'be_brand.type_id'
        )
        ->LeftJoin(
            'be_make',
            'be_make.make_id',
            '=',
            'be_brand.make_id_new'
        )
        ->LeftJoin(
            'partner',
            'partner.partner_id',
            '=',
            'vehicles.vendor_id'
        )
        ->where([
            [
                'partner.active', '=', '1'
            ],
            [
                'be_make.status', '=', '1.04'
            ],
            [
                'vehicles.status', '=', '8.02'
            ],
            [
                'be_make.make_name', '=', $first
            ],
            [
                'be_brand.brand_name', '=', $second
            ],
            [
                'be_model.model_name', '=', $third
            ]
        ])
        ->first();
		
        $make_brand = vehicle::select("be_make.make_id", "be_brand.brand_id")
        ->LeftJoin(
            'be_model',
            'be_model.model_id',
            '=',
            'vehicles.model_id_be'
        )
        ->LeftJoin(
            'be_brand',
            'be_brand.brand_id',
            '=',
            'be_model.brand_id'
        )
        ->LeftJoin(
            'be_type',
            'be_type.type_id',
            '=',
            'be_brand.type_id'
        )
        ->LeftJoin(
            'be_make',
            'be_make.make_id',
            '=',
            'be_brand.make_id_new'
        )
        ->LeftJoin(
            'partner',
            'partner.partner_id',
            '=',
            'vehicles.vendor_id'
        )
        ->where([
            [
                'partner.active', '=', '1'
            ],
            [
                'be_make.status', '=', '1.04'
            ],
            [
                'vehicles.status', '=', '8.02'
            ],
            [
                'be_make.make_name', '=', $first
            ],
            [
                'be_brand.brand_name', '=', $second
            ]
        ])
        ->first();
		
		
		$make_brand_model = vehicle::select("be_make.make_id", "be_brand.brand_id",'be_model.model_id')
        ->LeftJoin(
            'be_model',
            'be_model.model_id',
            '=',
            'vehicles.model_id_be'
        )
        ->LeftJoin(
            'be_brand',
            'be_brand.brand_id',
            '=',
            'be_model.brand_id'
        )
        ->LeftJoin(
            'be_type',
            'be_type.type_id',
            '=',
            'be_brand.type_id'
        )
        ->LeftJoin(
            'be_make',
            'be_make.make_id',
            '=',
            'be_brand.make_id_new'
        )
        ->LeftJoin(
            'partner',
            'partner.partner_id',
            '=',
            'vehicles.vendor_id'
        )
        ->where([
            [
                'partner.active', '=', '1'
            ],
            [
                'be_make.status', '=', '1.04'
            ],
            [
                'vehicles.status', '=', '8.02'
            ],
            [
                'be_make.make_name', '=', $first
            ],
            [
                'be_brand.brand_name', '=', $second
            ],
			[
                'be_model.model_name', '=', $third
            ]
        ])
        ->first();
		

        $make_type = vehicle::select("be_make.make_id", "be_type.type_id")
        ->LeftJoin(
            'be_model',
            'be_model.model_id',
            '=',
            'vehicles.model_id_be'
        )
        ->LeftJoin(
            'be_brand',
            'be_brand.brand_id',
            '=',
            'be_model.brand_id'
        )
        ->LeftJoin(
            'be_type',
            'be_type.type_id',
            '=',
            'be_brand.type_id'
        )
        ->LeftJoin(
            'be_make',
            'be_make.make_id',
            '=',
            'be_brand.make_id_new'
        )
        ->LeftJoin(
            'partner',
            'partner.partner_id',
            '=',
            'vehicles.vendor_id'
        )
        ->where([
            [
                'partner.active', '=', '1'
            ],
            [
                'be_make.status', '=', '1.04'
            ],
            [
                'vehicles.status', '=', '8.02'
            ],
            [
                'be_make.make_name', '=', $first
            ],
            [
                'be_type.type_name', '=', $second
            ]
        ])->first();
        //
        $make_model = vehicle::select("be_make.make_id", "be_model.model_id")
            ->LeftJoin(
                'be_model',
                'be_model.model_id',
                '=',
                'vehicles.model_id_be'
            )
            ->LeftJoin(
                'be_brand',
                'be_brand.brand_id',
                '=',
                'be_model.brand_id'
            )
            ->LeftJoin(
                'be_type',
                'be_type.type_id',
                '=',
                'be_brand.type_id'
            )
            ->LeftJoin(
                'be_make',
                'be_make.make_id',
                '=',
                'be_brand.make_id_new'
            )
            ->LeftJoin(
                'partner',
                'partner.partner_id',
                '=',
                'vehicles.vendor_id'
            )
            ->where([
                [
                    'partner.active', '=', '1'
                ],
                [
                    'be_make.status', '=', '1.04'
                ],
                [
                    'vehicles.status', '=', '8.02'
                ],
                [
                    'be_make.make_name', '=', $first
                ],
                [
                    'be_model.model_name', '=', $second
                ]
            ])->first();
        $brand_model = vehicle::select("be_brand.brand_id", "be_model.model_id")
        ->LeftJoin(
            'be_model',
            'be_model.model_id',
            '=',
            'vehicles.model_id_be'
        )
        ->LeftJoin(
            'be_brand',
            'be_brand.brand_id',
            '=',
            'be_model.brand_id'
        )
        ->LeftJoin(
            'be_type',
            'be_type.type_id',
            '=',
            'be_brand.type_id'
        )
        ->LeftJoin(
            'be_make',
            'be_make.make_id',
            '=',
            'be_brand.make_id_new'
        )
        ->LeftJoin(
            'partner',
            'partner.partner_id',
            '=',
            'vehicles.vendor_id'
        )
        ->where([
            [
                'partner.active', '=', '1'
            ],
            [
                'be_make.status', '=', '1.04'
            ],
            [
                'vehicles.status', '=', '8.02'
            ],
            [
                'be_brand.brand_name', '=', $first
            ],
            [
                'be_model.model_name', '=', $second
            ]
        ])->first();
        //
        $brand_type = vehicle::select("be_brand.brand_id", "be_type.type_id")
            ->LeftJoin(
                'be_model',
                'be_model.model_id',
                '=',
                'vehicles.model_id_be'
            )
            ->LeftJoin(
                'be_brand',
                'be_brand.brand_id',
                '=',
                'be_model.brand_id'
            )
            ->LeftJoin(
                'be_type',
                'be_type.type_id',
                '=',
                'be_brand.type_id'
            )
            ->LeftJoin(
                'be_make',
                'be_make.make_id',
                '=',
                'be_brand.make_id_new'
            )
            ->LeftJoin(
                'partner',
                'partner.partner_id',
                '=',
                'vehicles.vendor_id'
            )
            ->where([
                [
                    'partner.active', '=', '1'
                ],
                [
                    'be_make.status', '=', '1.04'
                ],
                [
                    'vehicles.status', '=', '8.02'
                ],
                [
                    'be_brand.brand_name', '=', $first
                ],
                [
                    'be_type.type_name', '=', $second
                ]
            ])->first();
        // return $request;

		//$request->session()->put('vehicle_name', '');
        $total_days = request()->session()->get('total_days');
		$page_carname = 'no';


		$DealsData = \App\Helpers\AppHelper::instance()->GetCompaniesDealsDailWeeklyMonthly($total_days);

		$deals_in_daily = $DealsData[0];
		$deals_in_weekly = $DealsData[1];
		$deals_in_monthly = $DealsData[2];

        //print_r($deals_in_daily);
        //return $company_list_monthly_contracts =  $result->partner_id;
        //$company_list_monthly_contracts = explode(',',$company_list_monthly_contracts);
        // $data
		
       // $country_name = $country_name = str_replace('-', ' ', $country_name);
	   
        //    $sql = "SELECT a.brand_name
        // --    "
        // $sql = ""

            $data = vehicle::select("be_make.make_name", "be_type.type_name", 'be_brand.brand_name', "be_model.model_name", "be_model.running_power", "partner.company_name", "be_airport_dtl.daily_rate", "be_airport_dtl.weekly_rate", "be_airport_dtl.monthly_rate", "be_brand.brand_id", "vehicles.model_id_be", "be_make.make_id", "domain_dtl.crm_logo", "be_model.img_name","active as activ",'partner.partner_id as vendor_id', "be_model.passanger",
            'be_model.luggage',
            'be_model.luggage_ar',
            'be_model.luggage_tr',
            'be_vehicles_specs_dtl.specs_value','be_type_sub.cat_name',
            'vehicles.status',
            'partner.id as partner_id', 'be_airport.airport_id')
            ->LeftJoin(
                'be_model',
                'be_model.model_id',
                '=',
                'vehicles.model_id_be'
            )
            ->LeftJoin(
                'be_brand',
                'be_brand.brand_id',
                '=',
                'be_model.brand_id'
            )
            ->LeftJoin(
                'be_type',
                'be_type.type_id',
                '=',
                'be_brand.type_id'
            )
            ->LeftJoin(
                'be_make',
                'be_make.make_id',
                '=',
                'be_brand.make_id_new'
            )
            ->LeftJoin(
                'partner',
                'partner.partner_id',
                '=',
                'vehicles.vendor_id'
            )
            ->LeftJoin(
                'colors',
                'colors.color_id',
                '=',
                'vehicles.color_id',
            )
            ->LeftJoin(
                'setups',
                'setups.setup_id',
                '=',
                'be_model.transmission',
            )

			 ->LeftJoin(
            'domain_dtl',
            'domain_dtl.vendor_id',
            '=',
            'partner.id',
        )

            ->LeftJoin(
                'be_vehicles_specs',
                'be_vehicles_specs.model_id',
                '=',
                'be_model.model_id', 'and','partner.id' , '=', 'be_vehicles_specs.vendor_id'
            )

			 ->LeftJoin(
                'be_vehicles_specs_dtl',

                'be_vehicles_specs_dtl.vsp_id',
                '=',
                'be_vehicles_specs.vsp_id','and','be_vehicles_specs_dtl.specs_id' , '=', '1'
            )
			->LeftJoin(
                'be_type_sub',

                'be_type_sub.cat_id',
                '=',
                'vehicles.cat_id'
            )

			/*->Join(
                'be_airport_dtl',

                'be_airport_dtl.model_id',
                '=',
                'vehicles.model_id_be' 


            )*/
			 ->Join(
                    'be_airport_dtl',
                    function ($join){
                        $join->on('be_airport_dtl.model_id', '=', 'vehicles.model_id_be');
                        $join->on('be_airport_dtl.vendor_id', '=', 'partner.id');
                    }
                )
			
			->Join(
                'be_airport',

                'be_airport.id',
                '=',
                'be_airport_dtl.promo_id'
            )

			->Join(
                'all_airport',

                'all_airport.id',
                '=',
                'be_airport.airport_id'
            )


            // ->groupBy(1,2,3,4)
            ->groupBy('be_make.make_name', "be_type.type_name",
                'be_brand.brand_name',
                "be_model.model_name"
            )

            ->where("partner.active",'1')
			 ->where("be_airport_dtl.p_status",'1')



			 	->when($country_name, function ($query) use ($total_days,$country_name,$city_name,$airport_name,$model_id_selected,$mn,$tn,$mon,$bn,$car_name,$make_brand,$make_type,$make_model,$brand_model,$brand_type,$make_brand_model) {
                    if ($model_id_selected > 0) {
                        return $query->where('be_airport_dtl.model_id', $model_id_selected)->where('vehicles.status', '8.02');;
                    }
					elseif(!empty($country_name) && $airport_name == ''){
						return $query->where('all_airport.url_name', $country_name)->where('vehicles.status', '8.02');;
					}
					else if(!empty($airport_name)){
						
                    if($tn){
                            return $query->where('all_airport.url_country', $country_name)->where('all_airport.url_name', $airport_name)->where('vehicles.status', '8.02')->where('be_type.type_name', $car_name);
                    }
                    if ($mn > 0) {
                        return $query->where('all_airport.url_name', $airport_name)->where('be_make.make_name', $car_name)->where('vehicles.status', '8.02');
                    } else if ($tn > 0) {
                        return $query->where('all_airport.url_name', $airport_name)->where('be_type.type_name', $car_name)->where('vehicles.status', '8.02');
                    } else if ($mon > 0) {
                        return $query->where('all_airport.url_name', $airport_name)->where('be_model.model_name', $car_name)->where('vehicles.status', '8.02');
                    } else if ($bn > 0) {
                        return $query->where('all_airport.url_name', $airport_name)->where('be_brand.brand_name', $car_name)->where('vehicles.status', '8.02');
                    }
					 else if ($make_brand_model) {
                        $l = explode("-",
                            $car_name
                        );
                        $make = $l['0'];
                        $brand = $l['1'];
                        $model = $l['2'];
                        return $query->where('all_airport.url_name', $airport_name)->where([["be_make.make_name", "=", $make], ["be_brand.brand_name", "=", $brand], ["be_model.model_name", "=", $model]])->where('vehicles.status', '!=', '8.07');
                    }
					
					 else if ($make_brand) {
                        $l = explode("-", $car_name);
                        $make = $l['0'];
                        $brand = $l['1'];
                        return $query->where('all_airport.url_name', $airport_name)
                        ->where([["be_make.make_name", "=", $make], ["be_brand.brand_name", "=", $brand]])
                        ->where('vehicles.status', '8.02');
                    } else if ($make_type) {
                        $l = explode("-", $car_name);
                        $make = $l['0'];
                        $brand = $l['1'];
                        return $query->where('all_airport.url_name', $airport_name)
                        ->where([["be_make.make_name", "=", $make], ["be_type.type_name", "=", $brand]])
                        ->where('vehicles.status', '8.02');
                    } else if ($make_model) {
                        $l = explode("-", $car_name);
                        // return $l['0'] . $l['1'] . $l['2'];
                        $make = $l['0'];
                        $brand = $l['1'];
                        return $query->where('all_airport.url_name', $airport_name)
                        ->where([["be_make.make_name", "=", $make], ["be_model.model_name", "=", $brand]])
                        ->where('vehicles.status', '8.02');
                    } else if ($brand_model) {
                        $l = explode("-", $car_name);
                        // return $l['0'] . $l['1'] . $l['2'];
                        $make = $l['0'];
                        $brand = $l['1'];

                        return $query->where('all_airport.url_name', $airport_name)
                        ->where([["be_brand.brand_name", "=", $make], ["be_model.model_name", "=", $brand]])
                        ->where('vehicles.status', '8.02');
                    } else if ($brand_type) {
                        $l = explode("-", $car_name);
                        // return $l['0'] . $l['1'] . $l['2'];
                        $make = $l['0'];
                        $brand = $l['1'];
                        return $query->where('all_airport.url_name', $airport_name)
                        ->where([["be_brand.brand_name", "=", $make], ["be_type.type_name", "=", $brand]])
                        ->where('vehicles.status', '8.02');
                    } else if ($mn == 0 && $tn == 0 && $mon == 0 &&  $bn == 0 && !empty($car_name)) {

                        $l = explode("-",
                            $car_name
                        );
                        $make = $l['0'];
                        $brand = $l['1'];
                        $model = $l['2'];
                        return $query->where('all_airport.url_name', $airport_name)->where([["be_make.make_name", "=", $make], ["be_brand.brand_name", "=", $brand], ["be_model.model_name", "=", $model]])->where('vehicles.status', '!=', '8.07');
                    }
                         else{
                            return $query->where('all_airport.url_country', $country_name)->where('all_airport.url_name', $airport_name)->where('vehicles.status', '8.02');
                         }
					}
					else
					if($model_id_selected>0){
						return $query->where('be_airport_dtl.model_id', $model_id_selected)->where('vehicles.status', '8.02');;
					}

					if(!empty($airport_name)){
						return $query->where('all_airport.url_name', $airport_name)->where('vehicles.status', '8.02');;
					}
					else if(!empty($city_name)){
						return $query->where('all_airport.url_city', $city_name)->where('vehicles.status', '8.02');;
					}
					// else




                })

            ->orderBy('be_airport_dtl.monthly_rate', 'asc')


        ->paginate();
        $seo = \App\Models\make_seo_main::select('make_seo_mains.*')
        ->where('type_id', 501)
            // ->where('make_id', $data['0']->make_id)
            // ->where('brand_id', $data['0']->brand_id)
        ->first();



        $meta_title = "Caryaati Rent a Car Dubai - Marketplace";
        // return $request->slug;
        $seo = \App\Helpers\SeoController::instance()->GetTitleDtl($request->slug, 'Airport');
        if ($seo) {
            $meta_title = $seo->meta_title;
            $meta_desc = $seo->meta_desc;
        } else {
            $meta_title = $meta_title;
            $meta_desc = $meta_title;
        }


            $s = $request->slug;


        //$data = array_merge($data,$page_carname);
        // $seo = site_settings::findorfail(1);
        // return $seo->meta_desc;
        if($seo){

        SEOTools::setTitle($seo->meta_title);
        SEOTools::setDescription($seo->meta_desc);
        SEOTools::opengraph()->setUrl(str_replace(' ', '-', strtolower($seo->url)));
        SEOTools::setCanonical(str_replace(' ', '-', strtolower($seo->url)));        // SEOTools::opengraph()->addProperty('type', 'articles');
        SEOTools::twitter()->setSite('@caryaati');
        // SEOTools::jsonLd()->addImage('https://codecasts.com.br/img/logo.jpg');
            //return $data;
        }
        // return $country_name;
		$airport_page = 'yes';
        $VehicleCount = $data->count();
        if ($request->ajax()) {
            return view('cars-for-rent.include.mycars_airport', compact('data', 'total_days','VehicleCount','airport_page'));
        }
        //
        return view('cars-for-rent.cars_airport',compact('data','s','total_days','VehicleCount', 'seo','country_name','city_name','airport_name','model_id_selected'));
    }


	public function similar_vehicle_airport(request $request){

		
		$expp = explode('-',$request->model_id_be);
		$model_id_be = $expp[0];
		$airport_id = $expp[1];
		
		 $total_days = $request->days;
		$DealsData = \App\Helpers\AppHelper::instance()->GetCompaniesDealsDailWeeklyMonthly($total_days);

		$deals_in_daily = $DealsData[0];
		$deals_in_weekly = $DealsData[1];
		$deals_in_monthly = $DealsData[2];

        $data = vehicle::select("be_make.make_name", "be_type.type_name", 'be_brand.brand_name', "be_model.model_name","vehicles.vendor_id", "be_model.running_power", "partner.company_name", "be_airport_dtl.daily_rate", "be_airport_dtl.weekly_rate", "be_airport_dtl.monthly_rate", "be_brand.brand_id", "vehicles.model_id_be", "be_make.make_id", "domain_dtl.crm_logo", "be_model.img_name", "active as activ","partner.protection_id","partner.id as partner_id", "be_model.passanger",'be_model.luggage', 'be_model.luggage_ar', 'be_model.luggage_tr', 'be_vehicles_specs_dtl.specs_value','be_airport.pickup_charges','be_airport.monitoring_charges','be_airport.delivery_charges','all_airport.name','be_airport.service_avail','be_airport.id as  airport_id')
        ->LeftJoin(
            'be_model',
            'be_model.model_id',
            '=',
            'vehicles.model_id_be'
        )
        ->LeftJoin(
            'be_brand',
            'be_brand.brand_id',
            '=',
            'be_model.brand_id'
        )
        ->LeftJoin(
            'be_type',
            'be_type.type_id',
            '=',
            'be_brand.type_id'
        )
        ->LeftJoin(
            'be_make',
            'be_make.make_id',
            '=',
            'be_brand.make_id_new'
        )
        ->LeftJoin(
            'partner',
            'partner.partner_id',
            '=',
            'vehicles.vendor_id'
        )
        ->LeftJoin(
            'colors',
            'colors.color_id',
            '=',
            'vehicles.color_id',
        )
        ->LeftJoin(
            'setups',
            'setups.setup_id',
            '=',
            'be_model.transmission',
        )
        ->LeftJoin(
            'domain_dtl',
            'domain_dtl.vendor_id',
            '=',
            'partner.id',
        )
		 ->LeftJoin(
                'be_vehicles_specs',
                'be_vehicles_specs.model_id',
                '=',
                'be_model.model_id', 'and','partner.id' , '=', 'be_vehicles_specs.vendor_id'
            )

			 ->LeftJoin(
                'be_vehicles_specs_dtl',

                'be_vehicles_specs_dtl.vsp_id',
                '=',
                'be_vehicles_specs.vsp_id', 'and', 'be_vehicles_specs_dtl.specs_id' , '=', '1'
            )
			
			/*->Join(
                'be_airport_dtl',

                'be_airport_dtl.model_id',
                '=',
                'vehicles.model_id_be' , 'and', 'partner.id','=', 'be_airport_dtl.vendor_id'

            )*/
			
			->Join(
                    'be_airport_dtl',
                    function ($join){
                        $join->on('be_airport_dtl.model_id', '=', 'vehicles.model_id_be');
                        $join->on('be_airport_dtl.vendor_id', '=', 'partner.id');
                    }
                )
			
			
			->Join(
                'be_airport',

                'be_airport.id',
                '=',
                'be_airport_dtl.promo_id' 
            )
			->Join(
                'all_airport',

                'all_airport.id',
                '=',
                'be_airport.airport_id'
				
            )

        // ->groupBy(1,2,3,4)
        ->groupBy(
            'vehicles.vendor_id',
        )
        ->where('vehicles.status', '8.02')
        ->where("partner.active", '1')
        ->where("vehicles.model_id_be", $model_id_be)
		->where("be_airport.airport_id", $airport_id)


		->when($total_days, function ($query) use ($total_days,$deals_in_daily,$deals_in_weekly,$deals_in_monthly) {

					if($total_days>=30){
						return $query->whereIn('partner.id', $deals_in_monthly);
					}
					else if($total_days<30 && $total_days>=7){
						return $query->whereIn('partner.id', $deals_in_weekly);
					}
					else if($total_days<7){
						return $query->whereIn('partner.id', $deals_in_daily);
					}

                })

        // ->orderBy('vehicles.monthly_rate', 'desc')
        ->get();

        $current_rate = $request->current_rate;
        $country_phone_code = country_phone_code::all();
        return view('cars-for-rent.ajax.similar_airport',compact('data','total_days', 'current_rate', 'country_phone_code'));
    }



	 public function CarSliderAiport(Request $request)
    {
        // return $request->vendor;
		
        if ($request->days >= 30) {
            $rt = 'monthly_rate';
        } else if ($request->days >= 7) {
            $rt = 'weekly_rate';
        } else if ($request->days < 7) {
            $rt = 'daily_rate';
        } else {
            $rt = 'monthly_rate';
        }

		$total_days = $request->days;
		$DealsData = \App\Helpers\AppHelper::instance()->GetCompaniesDealsDailWeeklyMonthly($total_days);

		$deals_in_daily = $DealsData[0];
		$deals_in_weekly = $DealsData[1];
		$deals_in_monthly = $DealsData[2];

				$data = vehicle::select("be_make.make_name", "be_type.type_name", 'be_brand.brand_name', "be_model.model_name", "be_model.running_power", "partner.company_name", "be_airport_dtl.daily_rate", "be_airport_dtl.weekly_rate", "be_airport_dtl.monthly_rate", "be_brand.brand_id", "vehicles.model_id_be", "be_make.make_id", "domain_dtl.crm_logo", "be_model.img_name", "active as activ", 'partner.partner_id as vendor_id', "be_model.passanger",'be_model.luggage', 'be_model.luggage_ar', 'be_model.luggage_tr', 'partner.id as partner_id2')
           ->LeftJoin(
               'be_model',
               'be_model.model_id',
               '=',
               'vehicles.model_id_be'
           )
           ->LeftJoin(
               'be_brand',
               'be_brand.brand_id',
               '=',
               'be_model.brand_id'
           )
           ->LeftJoin(
               'be_type',
               'be_type.type_id',
               '=',
               'be_brand.type_id'
           )
               ->LeftJoin(
                   'be_make',
                   'be_make.make_id',
                   '=',
                   'be_brand.make_id_new'
               )
               ->LeftJoin(
                   'partner',
                   'partner.partner_id',
                   '=',
                   'vehicles.vendor_id'
               )
               ->LeftJoin(
                   'colors',
                   'colors.color_id',
                   '=',
                   'vehicles.color_id',
               )
               ->LeftJoin(
                   'setups',
                   'setups.setup_id',
                   '=',
                   'be_model.transmission',
               )
               ->LeftJoin(
                   'domain_dtl',
                   'domain_dtl.vendor_id',
                   '=',
                   'partner.id',
               )
			   ->Join(
                'be_airport_dtl',

                'be_airport_dtl.model_id',
                '=',
                'vehicles.model_id_be'


            )
			->Join(
                'be_airport',

                'be_airport.id',
                '=',
                'be_airport_dtl.promo_id'
            )


               // ->groupBy(1,2,3,4)
               ->groupBy(
                   // "be_type.type_name",
                   'be_brand.brand_name',
                   // "be_model.model_name",
                //    "be_make.make_name"
               )
               ->where('vehicles.status', '8.02')
               ->where("partner.active", '1')
               //->where("be_type.type_name", $request->car_types)
               ->where('vehicles.model_id_be', '!=', $request->model_id_be)
              ->where('vehicles.vendor_id', '!=', $request->vendor_id)
			  ->when($total_days, function ($query) use ($total_days,$deals_in_daily,$deals_in_weekly,$deals_in_monthly) {

					if($total_days>=30){
						return $query->whereIn('partner.id', $deals_in_monthly);
					}
					else if($total_days<30 && $total_days>=7){
						return $query->whereIn('partner.id', $deals_in_weekly);
					}
					else if($total_days<7){
						return $query->whereIn('partner.id', $deals_in_daily);
					}

                })
               ->orderBy('vehicles.' . $rt, 'DESC')

               // ->orderBy('vehicles.$', 'desc')
               ->take(7)
               ->get();


		 //return $request->car_types;
        $total_days = $request->days;
		//return $data;
        return view('cars-for-rent.ajax.new-card-small-airport', compact('data', 'total_days'));
        // ->paginate();
    }
    //


	public function sidebar_filters(Request $request){

    $total_days = $request->total_days;
    $DealsData = \App\Helpers\AppHelper::instance()->GetCompaniesDealsDailWeeklyMonthly($total_days);

    $deals_in_daily = $DealsData[0];
    $deals_in_weekly = $DealsData[1];
    $deals_in_monthly = $DealsData[2];

    $seach_valuess = explode(',',$request->values);
    $checkBoxid = $request->checkBoxid;

        $expp = explode('/',$request->slug);
        if(!empty($expp[0]))$country_name = $expp[0]; else $country_name = '';
        if(!empty($expp[1]))$city_name = $expp[1]; else $city_name = '';
        if(!empty($expp[2]))$airport_name = $expp[2]; else $airport_name = '';
        if(!empty($expp[3]))$model_id_selected = $expp[3]; else $model_id_selected = 0;
        if(count($expp)==1){
        $model_id_selected = $request->slug;
        }


        if ($total_days >= 30) {
        $rt = 'monthly_rate';
    } else if ($total_days >= 7) {
        $rt = 'weekly_rate';
    } else {
        $rt = 'daily_rate';
    }
    //return $seach_valuess[0];
        $data = vehicle::select("be_make.make_name", "be_type.type_name", 'be_brand.brand_name', "be_model.model_name", "be_model.running_power", "partner.company_name", "be_airport_dtl.daily_rate", "be_airport_dtl.weekly_rate", "be_airport_dtl.monthly_rate", "be_brand.brand_id", "vehicles.model_id_be", "be_make.make_id", "domain_dtl.crm_logo", "be_model.img_name","active as activ",'partner.partner_id as vendor_id', "be_model.passanger",'be_model.luggage', 'be_model.luggage_ar', 'be_model.luggage_tr', 'be_vehicles_specs_dtl.specs_value','be_type_sub.cat_name','partner.id as partner_id','vehicles.status','be_airport.airport_id')
        ->LeftJoin(
            'be_model',
            'be_model.model_id',
            '=',
            'vehicles.model_id_be'
        )
        ->LeftJoin(
            'be_brand',
            'be_brand.brand_id',
            '=',
            'be_model.brand_id'
        )
        ->LeftJoin(
            'be_type',
            'be_type.type_id',
            '=',
            'be_brand.type_id'
        )
        ->LeftJoin(
            'be_make',
            'be_make.make_id',
            '=',
            'be_brand.make_id_new'
        )
        ->LeftJoin(
            'partner',
            'partner.partner_id',
            '=',
            'vehicles.vendor_id'
        )
        ->LeftJoin(
            'colors',
            'colors.color_id',
            '=',
            'vehicles.color_id',
        )
        ->LeftJoin(
            'setups',
            'setups.setup_id',
            '=',
            'be_model.transmission',
        )

            ->LeftJoin(
        'domain_dtl',
        'domain_dtl.vendor_id',
        '=',
        'partner.id',
    )

        ->LeftJoin(
            'be_vehicles_specs',
            'be_vehicles_specs.model_id',
            '=',
            'be_model.model_id', 'and','partner.id' , '=', 'be_vehicles_specs.vendor_id'
        )

            ->LeftJoin(
            'be_vehicles_specs_dtl',

            'be_vehicles_specs_dtl.vsp_id',
            '=',
            'be_vehicles_specs.vsp_id','and','be_vehicles_specs_dtl.specs_id' , '=', '1'
        )
        ->LeftJoin(
            'be_type_sub',

            'be_type_sub.cat_id',
            '=',
            'vehicles.cat_id'
        )

        /*->Join(
            'be_airport_dtl',

            'be_airport_dtl.model_id',
            '=',
            'vehicles.model_id_be'


        )*/
            ->Join(
                'be_airport_dtl',
                function ($join){
                    $join->on('be_airport_dtl.model_id', '=', 'vehicles.model_id_be');
                    $join->on('be_airport_dtl.vendor_id', '=', 'partner.id');
                }
            )
        
        ->Join(
            'be_airport',

            'be_airport.id',
            '=',
            'be_airport_dtl.promo_id'
        )

        ->Join(
            'all_airport',

            'all_airport.id',
            '=',
            'be_airport.airport_id'
        )

    ->when($total_days, function ($query) use ($total_days,$checkBoxid,$seach_valuess){

                if($checkBoxid=='min_add_driver'){
                    return $query->LeftJoin(
                                    'config_setup',
                                    'config_setup.vendor_id',
                                    '=',
                                    'partner.id'
                                )
                                ->where("config_setup.label_id", '86')
                                ->orderBy('config_setup.val','asc' );

                }

            })




        // ->groupBy(1,2,3,4)
        ->groupBy('be_make.make_name', "be_type.type_name",
            'be_brand.brand_name',
            "be_model.model_name"
        )
        ->where('vehicles.status', '8.02')
        ->where("partner.active",'1')
            ->where("be_airport_dtl.p_status",'1')



            ->when($total_days, function ($query) use ($total_days,$country_name,$city_name,$airport_name,$model_id_selected) {

                if($model_id_selected>0){
                    return $query->where('be_airport_dtl.model_id', $model_id_selected);
                }
                else
                if(!empty($airport_name)){
                    return $query->where('all_airport.url_name', $airport_name);
                }
                else if(!empty($city_name)){
                    return $query->where('all_airport.url_city', $city_name);
                }
                else if(!empty($country_name)){
                    //return $query->where('all_airport.url_country', $country_name);
                    return $query->where('all_airport.url_name', $country_name);
                }



            })
            ->when($total_days, function ($query) use ($total_days,$checkBoxid,$seach_valuess,$rt) {

                if(count($seach_valuess) > 0 && !empty($seach_valuess[0]) ){


                    if ($checkBoxid=='engincapacity_1') {
                        return $query->whereIn('be_vehicles_specs_dtl.specs_value', $seach_valuess)
                        ->where('be_vehicles_specs_dtl.specs_id', '1');
                    }
                    else if ($checkBoxid=='category_cars') {
                        return $query->whereIn('be_type_sub.cat_id', $seach_valuess);
                    }
                    else if ($checkBoxid=='passenger_lists') {
                        return $query->whereIn('be_model.passanger', $seach_valuess);
                    }
                    else if ($checkBoxid=='passenger_lists') {
                        return $query->whereIn('be_model.passanger', $seach_valuess);
                    }
                    else if ($checkBoxid=='min_air_dc') {
                        return $query->orderBy('be_airport.delivery_charges','asc' );

                    }
                    else if ($checkBoxid=='min_air_pickup') {
                        return $query->orderBy('be_airport.pickup_charges','asc' );

                    }
                    //sorting
                    elseif ($checkBoxid=='input-sort') {

                            if ($seach_valuess[0]=='hight_to_low') {
                            return $query->orderBy('be_airport_dtl.' . $rt,'desc' );

                        }
                        else if ($seach_valuess[0]=='latest_model') {
                            return $query->orderBy('be_model.model_name','desc' );

                        }
                        else if ($seach_valuess[0]=='older_model') {
                            return $query->orderBy('be_model.model_name','asc' );

                        }
                    }//end elase
                    else if($checkBoxid=='price_range'){


                            $type = $seach_valuess[0];
                            $min = $seach_valuess[1];
                            $max = $seach_valuess[2];

                            $cureency_rate = request()->session()->get('cureency_rate');
                            if ($cureency_rate != 0.00) {
                                $min = round($min / $cureency_rate, 2);
                                $max = round($max / $cureency_rate, 2);
                            }
                            else{
                                $min = $a['1'];
                                $max = $a['2'];
                            }
                            if($type=='D'){
                                return $query->whereBetween('be_airport_dtl.daily_rate', array($min, $max));
                            }
                            else if($type=='W'){
                                return $query->whereBetween('be_airport_dtl.weekly_rate', array($min, $max));
                            }
                            else if($type=='M'){
                                return $query->whereBetween('be_airport_dtl.monthly_rate', array($min, $max));
                            }//type


                    }



                }//values


            })



        ->orderBy('be_airport_dtl.' . $rt,'asc' )
        // ->orderBy('.monthly_rate', 'asc')

    ->paginate();



    $VehicleCount = $data->count();
        $airport_page = 'yes';
        $total_days = $request->total_days;
        return view('cars-for-rent.include.mycars_airport', compact('data', 'total_days','VehicleCount','airport_page'));





    }



	public function price_rang_max_mini(Request $request){
        // regular_promo
        // return $request;



		$DealsData = \App\Helpers\AppHelper::instance()->GetCompaniesDealsDailWeeklyMonthly(0);

		$deals_in_daily = $DealsData[0];
		$deals_in_weekly = $DealsData[1];
		$deals_in_monthly = $DealsData[2];

		$rates_type = $request->rate_type;

        if ($request->rate_type == 'D') { //daily
            $rang = "be_airport_dtl.daily_rate";
        } else
		if ($request->rate_type == 'W') { //daily
            $rang = "be_airport_dtl.weekly_rate";
        } else if ($request->rate_type == 'M') { //daily
            $rang = "be_airport_dtl.monthly_rate";
        } else {
            $rang = "be_airport_dtl.daily_rate";
        }

		//return $rang;
        $max = be_airport_dtl::select('be_airport_dtl.*')
        ->LeftJoin(
            'partner','partner.id','be_airport_dtl.vendor_id'
        )
		->LeftJoin(
            'vehicles','vehicles.model_id_be','be_airport_dtl.model_id' ,
			'and','vehicles.vendor_id','=','partner.partner_id'
        )
        ->where('partner.active','1')->where('vehicles.status','8.02')
		->where('be_airport_dtl.p_status','1')

		->when($rates_type, function ($query) use ($rates_type,$deals_in_daily,$deals_in_weekly,$deals_in_monthly) {

					if($rates_type == 'D'){
						return $query->whereIn('partner.id', $deals_in_daily);
					}
					else if($rates_type == 'W'){
						return $query->whereIn('partner.id', $deals_in_weekly);
					}
					else if($rates_type == 'M'){
						return $query->whereIn('partner.id', $deals_in_monthly);
					}

                })

		->max($rang);


        $min = be_airport_dtl::select('be_airport_dtl.*')
        ->LeftJoin(
            'partner','partner.id','be_airport_dtl.vendor_id'
        )
       ->LeftJoin(
            'vehicles','vehicles.model_id_be','be_airport_dtl.model_id' ,
			'and','vehicles.vendor_id','=','partner.partner_id'
        )
        ->where('partner.active','1')->where('vehicles.status','8.02')
		->where('be_airport_dtl.p_status','1')

		->when($rates_type, function ($query) use ($rates_type,$deals_in_daily,$deals_in_weekly,$deals_in_monthly) {

					if($rates_type == 'D'){
						return $query->whereIn('partner.id', $deals_in_daily);
					}
					else if($rates_type == 'W'){
						return $query->whereIn('partner.id', $deals_in_weekly);
					}
					else if($rates_type == 'M'){
						return $query->whereIn('partner.id', $deals_in_monthly);
					}

                })


		->min($rang);


		//$geo_city = $request->geo_city;
       // $request->session()->put('geo_city', $request->geo_city);
      	$cureency_rate = request()->session()->get('cureency_rate');
        $cureency_symbol = request()->session()->get('cureency_symbol');
        if ($cureency_rate != 0.00) {
            $min = round($min * $cureency_rate, 2);
			$max = round($max * $cureency_rate, 2);

        }



        return $min . '##'. $max.'##'.$cureency_symbol;

    }


	public static function GetCountryListBigDropDown($deals_in_daily,$deals_in_weekly,$deals_in_monthly,$cont_id){

	$total_days = request()->session()->get('total_days');

	 return $k = vehicle::select('all_airport.country')
                    ->LeftJoin(
                    'be_model',
                    'be_model.model_id',
                    '=',
                    'vehicles.model_id_be'
                    )
                    ->LeftJoin(
                    'be_brand',
                    'be_brand.brand_id',
                    '=',
                    'be_model.brand_id'
                    )
                    ->LeftJoin(
                    'be_type',
                    'be_type.type_id',
                    '=',
                    'be_brand.type_id'
                    )
                    ->LeftJoin(
                    'be_make',
                    'be_make.make_id',
                    '=',
                    'be_brand.make_id_new'
                    )
                    ->LeftJoin(
                    'partner',
                    'partner.partner_id',
                    '=',
                    'vehicles.vendor_id'
                    )

                    /*->Join(
                        'be_airport_dtl',

                        'be_airport_dtl.model_id',
                        '=',
                        'vehicles.model_id_be'


                    )*/
					 ->Join(
                    'be_airport_dtl',
						function ($join){
							$join->on('be_airport_dtl.model_id', '=', 'vehicles.model_id_be');
							$join->on('be_airport_dtl.vendor_id', '=', 'partner.id');
						}
               		 )
					
                    ->Join(
                        'be_airport',

                        'be_airport.id',
                        '=',
                        'be_airport_dtl.promo_id'
                    )
                    ->Join(
                        'all_airport',

                        'all_airport.id',
                        '=',
                        'be_airport.airport_id'
                    )
                    ->Join(
                        'all_airport_continents',

                        'all_airport_continents.cont_id',
                        '=',
                        'all_airport.cont_id'
                    )
					
					

                    /*->where([
                    [
                    '', '=', '1'
                    ],

                    [
                    '', '=', '8.02'
                    ]

                   ])*/

                  ->where("partner.active", '1')
				   ->where("vehicles.status", '8.02')
				 
				  ->where("all_airport.cont_id", $cont_id)
                   ->when($total_days, function ($query) use ($total_days,$deals_in_daily,$deals_in_weekly,$deals_in_monthly) {

					if($total_days>=30){
						return $query->whereIn('partner.id', $deals_in_monthly);
					}
					else if($total_days<30 && $total_days>=7){
						return $query->whereIn('partner.id', $deals_in_weekly);
					}
					else if($total_days<7){
						return $query->whereIn('partner.id', $deals_in_daily);
					}

                })



                    ->distinct()
                    ->orderBy('all_airport_continents.name')->get();




	}


	public static function GetCityListBigDropDown($deals_in_daily,$deals_in_weekly,$deals_in_monthly,$cont_id,$country){

	$total_days = request()->session()->get('total_days');

	 return $k = vehicle::select('all_airport.city')
                    ->LeftJoin(
                    'be_model',
                    'be_model.model_id',
                    '=',
                    'vehicles.model_id_be'
                    )
                    ->LeftJoin(
                    'be_brand',
                    'be_brand.brand_id',
                    '=',
                    'be_model.brand_id'
                    )
                    ->LeftJoin(
                    'be_type',
                    'be_type.type_id',
                    '=',
                    'be_brand.type_id'
                    )
                    ->LeftJoin(
                    'be_make',
                    'be_make.make_id',
                    '=',
                    'be_brand.make_id_new'
                    )
                    ->LeftJoin(
                    'partner',
                    'partner.partner_id',
                    '=',
                    'vehicles.vendor_id'
                    )

                    /*->Join(
                        'be_airport_dtl',

                        'be_airport_dtl.model_id',
                        '=',
                        'vehicles.model_id_be'


                    )*/
					->Join(
                    'be_airport_dtl',
						function ($join){
							$join->on('be_airport_dtl.model_id', '=', 'vehicles.model_id_be');
							$join->on('be_airport_dtl.vendor_id', '=', 'partner.id');
						}
               		 )
					
                    ->Join(
                        'be_airport',

                        'be_airport.id',
                        '=',
                        'be_airport_dtl.promo_id'
                    )
                    ->Join(
                        'all_airport',

                        'all_airport.id',
                        '=',
                        'be_airport.airport_id'
                    )
                    ->Join(
                        'all_airport_continents',

                        'all_airport_continents.cont_id',
                        '=',
                        'all_airport.cont_id'
                    )

                    ->where([
                    [
                    'partner.active', '=', '1'
                    ],

                    [
                    'vehicles.status', '=', '8.02'
                    ]

                   ])

                  ->where("all_airport.cont_id", $cont_id)
				  ->where("all_airport.country", $country)

                   ->when($total_days, function ($query) use ($total_days,$deals_in_daily,$deals_in_weekly,$deals_in_monthly) {

					if($total_days>=30){
						return $query->whereIn('partner.id', $deals_in_monthly);
					}
					else if($total_days<30 && $total_days>=7){
						return $query->whereIn('partner.id', $deals_in_weekly);
					}
					else if($total_days<7){
						return $query->whereIn('partner.id', $deals_in_daily);
					}

                })



                    ->distinct()
                    ->orderBy('all_airport.city')->get();




	}


	public static function GetAirportNameListBigDropDown($deals_in_daily,$deals_in_weekly,$deals_in_monthly,$cont_id,$country,$city){

	$total_days = request()->session()->get('total_days');

	 return $k = vehicle::select('all_airport.name','all_airport.id','all_airport.url_country','all_airport.url_city','all_airport.url_name')
                    ->LeftJoin(
                    'be_model',
                    'be_model.model_id',
                    '=',
                    'vehicles.model_id_be'
                    )
                    ->LeftJoin(
                    'be_brand',
                    'be_brand.brand_id',
                    '=',
                    'be_model.brand_id'
                    )
                    ->LeftJoin(
                    'be_type',
                    'be_type.type_id',
                    '=',
                    'be_brand.type_id'
                    )
                    ->LeftJoin(
                    'be_make',
                    'be_make.make_id',
                    '=',
                    'be_brand.make_id_new'
                    )
                    ->LeftJoin(
                    'partner',
                    'partner.partner_id',
                    '=',
                    'vehicles.vendor_id'
                    )

                    ->Join(
                        'be_airport_dtl',

                        'be_airport_dtl.model_id',
                        '=',
                        'vehicles.model_id_be'


                    )
                    ->Join(
                        'be_airport',

                        'be_airport.id',
                        '=',
                        'be_airport_dtl.promo_id'
                    )
                    ->Join(
                        'all_airport',

                        'all_airport.id',
                        '=',
                        'be_airport.airport_id'
                    )
                    ->Join(
                        'all_airport_continents',

                        'all_airport_continents.cont_id',
                        '=',
                        'all_airport.cont_id'
                    )

                    ->where([
                    [
                    'partner.active', '=', '1'
                    ],

                    [
                    'vehicles.status', '=', '8.02'
                    ]

                   ])

                  ->where("all_airport.cont_id", $cont_id)
				  ->where("all_airport.country", $country)
				   ->where("all_airport.city", $city)
                   ->when($total_days, function ($query) use ($total_days,$deals_in_daily,$deals_in_weekly,$deals_in_monthly) {

					if($total_days>=30){
						return $query->whereIn('partner.id', $deals_in_monthly);
					}
					else if($total_days<30 && $total_days>=7){
						return $query->whereIn('partner.id', $deals_in_weekly);
					}
					else if($total_days<7){
						return $query->whereIn('partner.id', $deals_in_daily);
					}

                })



                    ->distinct()
                    ->orderBy('all_airport.name')->get();




	}


	public function search_aiport_list(Request $request){
       // if ($request->has('AiportSearching')) {
             $AirportsSearch = $request->airport_search_123;
			if(strlen($AirportsSearch)>=3){

            // ->get();
            //
            $total_days = request()->session()->get('total_days');
			$DealsData = \App\Helpers\AppHelper::instance()->GetCompaniesDealsDailWeeklyMonthly($total_days);

		$deals_in_daily = $DealsData[0];
		$deals_in_weekly = $DealsData[1];
		$deals_in_monthly = $DealsData[2];

	    $data = vehicle::select('all_airport.name','all_airport.country','all_airport.city','all_airport.id' ,'all_airport.url_country','all_airport.url_city','all_airport.url_name')
                    ->LeftJoin(
                    'be_model',
                    'be_model.model_id',
                    '=',
                    'vehicles.model_id_be'
                    )
                    ->LeftJoin(
                    'be_brand',
                    'be_brand.brand_id',
                    '=',
                    'be_model.brand_id'
                    )
                    ->LeftJoin(
                    'be_type',
                    'be_type.type_id',
                    '=',
                    'be_brand.type_id'
                    )
                    ->LeftJoin(
                    'be_make',
                    'be_make.make_id',
                    '=',
                    'be_brand.make_id_new'
                    )
                    ->LeftJoin(
                    'partner',
                    'partner.partner_id',
                    '=',
                    'vehicles.vendor_id'
                    )

                    ->Join(
                        'be_airport_dtl',

                        'be_airport_dtl.model_id',
                        '=',
                        'vehicles.model_id_be'


                    )
                    ->Join(
                        'be_airport',

                        'be_airport.id',
                        '=',
                        'be_airport_dtl.promo_id'
                    )
                    ->Join(
                        'all_airport',

                        'all_airport.id',
                        '=',
                        'be_airport.airport_id'
                    )
                    ->Join(
                        'all_airport_continents',

                        'all_airport_continents.cont_id',
                        '=',
                        'all_airport.cont_id'
                    )

                    ->where([
                    [
                    'partner.active', '=', '1'
                    ],

                    [
                    'vehicles.status', '=', '8.02'
                    ],
                    [
                    DB::raw("CONCAT(all_airport.country,' ', all_airport.city, ' ', all_airport.name)"), 'LIKE', "%" . $AirportsSearch . "%"
                ]
                   ])


                   ->when($total_days, function ($query) use ($total_days,$deals_in_daily,$deals_in_weekly,$deals_in_monthly) {

					if($total_days>=30){
						return $query->whereIn('partner.id', $deals_in_monthly);
					}
					else if($total_days<30 && $total_days>=7){
						return $query->whereIn('partner.id', $deals_in_weekly);
					}
					else if($total_days<7){
						return $query->whereIn('partner.id', $deals_in_daily);
					}

                })



              ->distinct()
              ->orderBy('all_airport.name')->take(10)->get();


            // ->orderBy('be_type.type_name', 'desc')
            // ->union($a)
            // ->union($b)
            // ->union($c)
            // ->union($d)
        // ->get();
		//return $data;
            foreach($data as $i => $items){
				//echo $items->name."<br>";
				$airport_url = $items->url_country.'/'.$items->url_city.'/'.$items->url_name;?>

			 <li style=" padding: 0px 13px; font-size:12px">
                 <i class="fa fa-plane"></i> &nbsp;<a href="javascript:;" onclick="setAiportName('<?php echo $items->city ?>','<?php echo $items->country ?>','<?php echo $items->id ?>','<?php echo $items->name ?>','<?php echo $airport_url ?>')">
				 <?php echo $items->name.','.$items->city.','.$items->country; ?></a>
                  </li>
			<?php }

       }//if AiportSearching
       // return response()->json($data);
    }



	 public function quick_search_airport(Request $request){
        // return $request;
        // $request->session()->put('model_id_be', $request->model_id_be);
        // $request->session()->put('package_name', $request->package_name);
        // $request->session()->put('vendor_id', $request->vendor_id);
        //$slug = str_replace(' ','-',$request->all_vehicles2);
        $request->session()->put('start', $request->start);
        $request->session()->put('start_time', $request->start_time);
        $request->session()->put('end', $request->end);
        $request->session()->put('end_time', $request->end_time);
        $request->session()->put('total_days', $request->total_days);
        $request->session()->put('location', $request->search_location);
        $request->session()->put('arrival_terminal', $request->arrival_terminal);
        $request->session()->put('delivery_charges', $request->delivery_charges);
        $request->session()->put('additional_driver', $request->additional_driver);
        $request->session()->put('flight_number', $request->flight_number);
        $request->session()->put('arrival_terminal', $request->arrival_terminal);
        $request->session()->put('geo_city', $request->geo_city);
        $request->session()->put('airport_id', 254);
        return 1;
        // $request->session()->put('package', $request->package);
        // $request->session()->put('package_price', $request->package_price);
    }



    public function search_vehicle_airports(Request $request)
    {
        if ($request->has('q')) {
            $AirportsSearch = $request->q;
            // return $request;


            $e = vehicle::select(
                DB::raw("CONCAT(be_make.make_name,' ', be_brand.brand_name, ' ', be_model.model_name, ' ', be_type.type_name ) as source"),
                DB::raw("CONCAT(be_make.make_name,'-', be_brand.brand_name, '-', be_model.model_name ) as source2")
            )
            ->LeftJoin(
                'be_model',
                'be_model.model_id',
                '=',
                'vehicles.model_id_be'
            )
                ->LeftJoin(
                    'be_brand',
                    'be_brand.brand_id',
                    '=',
                    'be_model.brand_id'
                )
                ->LeftJoin(
                    'be_type',
                    'be_type.type_id',
                    '=',
                    'be_brand.type_id'
                )
                ->LeftJoin(
                    'be_make',
                    'be_make.make_id',
                    '=',
                    'be_brand.make_id_new'
                )
                ->LeftJoin(
                    'partner',
                    'partner.partner_id',
                    '=',
                    'vehicles.vendor_id'
                )
                ->Join(
                    'be_airport_dtl',

                    'be_airport_dtl.model_id',
                    '=',
                    'vehicles.model_id_be'
                )
                ->Join(
                    'be_airport',

                    'be_airport.id',
                    '=',
                    'be_airport_dtl.promo_id'
                )
                ->Join(
                    'all_airport',

                    'all_airport.id',
                    '=',
                    'be_airport.airport_id'
                )
                ->Join(
                    'all_airport_continents',

                    'all_airport_continents.cont_id',
                    '=',
                    'all_airport.cont_id'
                )
                ->where([
                    [
                        'partner.active', '=', '1'
                    ],
                    [
                        'be_make.status', '=', '1.04'
                    ],
                    [
                        'vehicles.status', '=', '8.02'
                    ],
                    [
                        'all_airport.name', '=', $request->airport_name
                    ],
                    [
                        DB::raw("CONCAT(be_make.make_name,' ', be_brand.brand_name, ' ', be_model.model_name, ' ', be_type.type_name)"), 'LIKE', "%" . $AirportsSearch . "%"
                    ]
                ])
                ->distinct();
            $make_type = vehicle::select(
                DB::raw("CONCAT(be_make.make_name,' ',be_type.type_name ) as source"),
                DB::raw("CONCAT(be_make.make_name,'-',be_type.type_name ) as source2")
            )
            ->LeftJoin(
                'be_model',
                'be_model.model_id',
                '=',
                'vehicles.model_id_be'
            )
                ->LeftJoin(
                    'be_brand',
                    'be_brand.brand_id',
                    '=',
                    'be_model.brand_id'
                )
                ->LeftJoin(
                    'be_type',
                    'be_type.type_id',
                    '=',
                    'be_brand.type_id'
                )
                ->LeftJoin(
                    'be_make',
                    'be_make.make_id',
                    '=',
                    'be_brand.make_id_new'
                )
                ->LeftJoin(
                    'partner',
                    'partner.partner_id',
                    '=',
                    'vehicles.vendor_id'
                )
                ->Join(
                    'be_airport_dtl',

                    'be_airport_dtl.model_id',
                    '=',
                    'vehicles.model_id_be'
                )
                ->Join(
                    'be_airport',

                    'be_airport.id',
                    '=',
                    'be_airport_dtl.promo_id'
                )
                ->Join(
                    'all_airport',

                    'all_airport.id',
                    '=',
                    'be_airport.airport_id'
                )
                ->Join(
                    'all_airport_continents',

                    'all_airport_continents.cont_id',
                    '=',
                    'all_airport.cont_id'
                )
                ->where([
                    [
                        'partner.active', '=', '1'
                    ],
                    [
                        'be_make.status', '=', '1.04'
                    ],
                    [
                        'vehicles.status', '=', '8.02'
                    ],
                    [
                        'all_airport.name', '=', $request->airport_name
                    ],
                    [
                        DB::raw("CONCAT(be_make.make_name,' ', be_type.type_name)"), 'LIKE', "%" . $AirportsSearch . "%"
                    ]
                ])
                ->distinct();
            $make_brand = vehicle::select(
                DB::raw("CONCAT(be_make.make_name,' ',be_brand.brand_name ) as source"),
                DB::raw("CONCAT(be_make.make_name,'-',be_brand.brand_name ) as source2")
            )
            ->LeftJoin(
                'be_model',
                'be_model.model_id',
                '=',
                'vehicles.model_id_be'
            )
                ->LeftJoin(
                    'be_brand',
                    'be_brand.brand_id',
                    '=',
                    'be_model.brand_id'
                )
                ->LeftJoin(
                    'be_type',
                    'be_type.type_id',
                    '=',
                    'be_brand.type_id'
                )
                ->LeftJoin(
                    'be_make',
                    'be_make.make_id',
                    '=',
                    'be_brand.make_id_new'
                )
                ->LeftJoin(
                    'partner',
                    'partner.partner_id',
                    '=',
                    'vehicles.vendor_id'
                )
                ->Join(
                    'be_airport_dtl',

                    'be_airport_dtl.model_id',
                    '=',
                    'vehicles.model_id_be'
                )
                ->Join(
                    'be_airport',

                    'be_airport.id',
                    '=',
                    'be_airport_dtl.promo_id'
                )
                ->Join(
                    'all_airport',

                    'all_airport.id',
                    '=',
                    'be_airport.airport_id'
                )
                ->Join(
                    'all_airport_continents',

                    'all_airport_continents.cont_id',
                    '=',
                    'all_airport.cont_id'
                )
                ->where([
                    [
                        'partner.active', '=', '1'
                    ],
                    [
                        'be_make.status', '=', '1.04'
                    ],
                    [
                        'vehicles.status', '=', '8.02'
                    ],
                    [
                        'all_airport.name', '=', $request->airport_name
                    ],
                    [
                        DB::raw("CONCAT(be_make.make_name,' ', be_brand.brand_name)"), 'LIKE', "%" . $AirportsSearch . "%"
                    ]
                ])
                ->distinct();
            $make_model = vehicle::select(
                DB::raw("CONCAT(be_make.make_name,' ',be_brand.brand_name ) as source"),
                DB::raw("CONCAT(be_make.make_name,'-',be_model.model_name ) as source2")
            )
            ->LeftJoin(
                'be_model',
                'be_model.model_id',
                '=',
                'vehicles.model_id_be'
            )
                ->LeftJoin(
                    'be_brand',
                    'be_brand.brand_id',
                    '=',
                    'be_model.brand_id'
                )
                ->LeftJoin(
                    'be_type',
                    'be_type.type_id',
                    '=',
                    'be_brand.type_id'
                )
                ->LeftJoin(
                    'be_make',
                    'be_make.make_id',
                    '=',
                    'be_brand.make_id_new'
                )
                ->LeftJoin(
                    'partner',
                    'partner.partner_id',
                    '=',
                    'vehicles.vendor_id'
                )
                ->Join(
                    'be_airport_dtl',

                    'be_airport_dtl.model_id',
                    '=',
                    'vehicles.model_id_be'
                )
                ->Join(
                    'be_airport',

                    'be_airport.id',
                    '=',
                    'be_airport_dtl.promo_id'
                )
                ->Join(
                    'all_airport',

                    'all_airport.id',
                    '=',
                    'be_airport.airport_id'
                )
                ->Join(
                    'all_airport_continents',

                    'all_airport_continents.cont_id',
                    '=',
                    'all_airport.cont_id'
                )
                ->where([
                    [
                        'partner.active', '=', '1'
                    ],
                    [
                        'be_make.status', '=', '1.04'
                    ],
                    [
                        'vehicles.status', '=', '8.02'
                    ],
                    [
                        'all_airport.name', '=', $request->airport_name
                    ],
                    [
                        DB::raw("CONCAT(be_make.make_name,' ', be_model.model_name)"), 'LIKE', "%" . $AirportsSearch . "%"
                    ]
                ])
                ->distinct();
            $brand_model = vehicle::select(
                DB::raw("CONCAT(be_brand.brand_name,' ',be_model.model_name ) as source"),
                DB::raw("CONCAT(be_brand.brand_name,'-',be_model.model_name ) as source2")
            )
            ->LeftJoin(
                'be_model',
                'be_model.model_id',
                '=',
                'vehicles.model_id_be'
            )
                ->LeftJoin(
                    'be_brand',
                    'be_brand.brand_id',
                    '=',
                    'be_model.brand_id'
                )
                ->LeftJoin(
                    'be_type',
                    'be_type.type_id',
                    '=',
                    'be_brand.type_id'
                )
                ->LeftJoin(
                    'be_make',
                    'be_make.make_id',
                    '=',
                    'be_brand.make_id_new'
                )
                ->LeftJoin(
                    'partner',
                    'partner.partner_id',
                    '=',
                    'vehicles.vendor_id'
                )
                ->Join(
                    'be_airport_dtl',

                    'be_airport_dtl.model_id',
                    '=',
                    'vehicles.model_id_be'
                )
                ->Join(
                    'be_airport',

                    'be_airport.id',
                    '=',
                    'be_airport_dtl.promo_id'
                )
                ->Join(
                    'all_airport',

                    'all_airport.id',
                    '=',
                    'be_airport.airport_id'
                )
                ->Join(
                    'all_airport_continents',

                    'all_airport_continents.cont_id',
                    '=',
                    'all_airport.cont_id'
                )
                ->where([
                    [
                        'partner.active', '=', '1'
                    ],
                    [
                        'be_make.status', '=', '1.04'
                    ],
                    [
                        'vehicles.status', '=', '8.02'
                    ],
                    [
                        'all_airport.name', '=', $request->airport_name
                    ],
                    [
                        DB::raw("CONCAT(be_brand.brand_name,' ', be_model.model_name)"), 'LIKE', "%" . $AirportsSearch . "%"
                    ]
                ])
                ->distinct();
            $brand_type = vehicle::select(
                DB::raw("CONCAT(be_brand.brand_name,' ',be_type.type_name ) as source"),
                DB::raw("CONCAT(be_brand.brand_name,'-',be_type.type_name ) as source2")
            )
            ->LeftJoin(
                'be_model',
                'be_model.model_id',
                '=',
                'vehicles.model_id_be'
            )
                ->LeftJoin(
                    'be_brand',
                    'be_brand.brand_id',
                    '=',
                    'be_model.brand_id'
                )
                ->LeftJoin(
                    'be_type',
                    'be_type.type_id',
                    '=',
                    'be_brand.type_id'
                )
                ->LeftJoin(
                    'be_make',
                    'be_make.make_id',
                    '=',
                    'be_brand.make_id_new'
                )
                ->LeftJoin(
                    'partner',
                    'partner.partner_id',
                    '=',
                    'vehicles.vendor_id'
                )
                ->Join(
                    'be_airport_dtl',

                    'be_airport_dtl.model_id',
                    '=',
                    'vehicles.model_id_be'
                )
                ->Join(
                    'be_airport',

                    'be_airport.id',
                    '=',
                    'be_airport_dtl.promo_id'
                )
                ->Join(
                    'all_airport',

                    'all_airport.id',
                    '=',
                    'be_airport.airport_id'
                )
                ->Join(
                    'all_airport_continents',

                    'all_airport_continents.cont_id',
                    '=',
                    'all_airport.cont_id'
                )
                ->where([
                    [
                        'partner.active', '=', '1'
                    ],
                    [
                        'be_make.status', '=', '1.04'
                    ],
                    [
                        'vehicles.status', '=', '8.02'
                    ],
                    [
                        'all_airport.name', '=', $request->airport_name
                    ],
                    [
                        DB::raw("CONCAT(be_brand.brand_name,' ', be_type.type_name)"), 'LIKE', "%" . $AirportsSearch . "%"
                    ]
                ])
                ->distinct();
                $make = vehicle::select(
                DB::raw("CONCAT(be_make.make_name) as source"),
                DB::raw("CONCAT(be_make.make_name) as source2")
            )
            ->LeftJoin(
                'be_model',
                'be_model.model_id',
                '=',
                'vehicles.model_id_be'
            )
                ->LeftJoin(
                    'be_brand',
                    'be_brand.brand_id',
                    '=',
                    'be_model.brand_id'
                )
                ->LeftJoin(
                    'be_type',
                    'be_type.type_id',
                    '=',
                    'be_brand.type_id'
                )
                ->LeftJoin(
                    'be_make',
                    'be_make.make_id',
                    '=',
                    'be_brand.make_id_new'
                )
                ->LeftJoin(
                    'partner',
                    'partner.partner_id',
                    '=',
                    'vehicles.vendor_id'
                )
                ->Join(
                    'be_airport_dtl',

                    'be_airport_dtl.model_id',
                    '=',
                    'vehicles.model_id_be'
                )
                ->Join(
                    'be_airport',

                    'be_airport.id',
                    '=',
                    'be_airport_dtl.promo_id'
                )
                ->Join(
                    'all_airport',

                    'all_airport.id',
                    '=',
                    'be_airport.airport_id'
                )
                ->Join(
                    'all_airport_continents',

                    'all_airport_continents.cont_id',
                    '=',
                    'all_airport.cont_id'
                )
                ->where([
                    [
                        'partner.active', '=', '1'
                    ],
                    [
                        'be_make.status', '=', '1.04'
                    ],
                    [
                        'vehicles.status', '=', '8.02'
                    ],
                    [
                        'all_airport.name', '=', $request->airport_name
                    ],
                    [
                        'be_make.make_name', 'LIKE', "%" . $AirportsSearch . "%"
                    ]
                ])
                ->distinct();
            $brand = vehicle::select(
                DB::raw("CONCAT(be_brand.brand_name) as source"),
                DB::raw("CONCAT(be_brand.brand_name) as source2")
            )
            ->LeftJoin(
                'be_model',
                'be_model.model_id',
                '=',
                'vehicles.model_id_be'
            )
                ->LeftJoin(
                    'be_brand',
                    'be_brand.brand_id',
                    '=',
                    'be_model.brand_id'
                )
                ->LeftJoin(
                    'be_type',
                    'be_type.type_id',
                    '=',
                    'be_brand.type_id'
                )
                ->LeftJoin(
                    'be_make',
                    'be_make.make_id',
                    '=',
                    'be_brand.make_id_new'
                )
                ->LeftJoin(
                    'partner',
                    'partner.partner_id',
                    '=',
                    'vehicles.vendor_id'
                )
                ->Join(
                    'be_airport_dtl',

                    'be_airport_dtl.model_id',
                    '=',
                    'vehicles.model_id_be'
                )
                ->Join(
                    'be_airport',

                    'be_airport.id',
                    '=',
                    'be_airport_dtl.promo_id'
                )
                ->Join(
                    'all_airport',

                    'all_airport.id',
                    '=',
                    'be_airport.airport_id'
                )
                ->Join(
                    'all_airport_continents',

                    'all_airport_continents.cont_id',
                    '=',
                    'all_airport.cont_id'
                )
                ->where([
                    [
                        'partner.active', '=', '1'
                    ],
                    [
                        'be_make.status', '=', '1.04'
                    ],
                    [
                        'vehicles.status', '=', '8.02'
                    ],
                    [
                        'all_airport.name', '=', $request->airport_name
                    ],
                    [
                        'be_brand.brand_name', 'LIKE', "%" . $AirportsSearch . "%"
                    ]
                ])
                ->distinct();
            $model = vehicle::select(
                DB::raw("CONCAT(be_model.model_name) as source"),
                DB::raw("CONCAT(be_model.model_name) as source2")
            )
            ->LeftJoin(
                'be_model',
                'be_model.model_id',
                '=',
                'vehicles.model_id_be'
            )
                ->LeftJoin(
                    'be_brand',
                    'be_brand.brand_id',
                    '=',
                    'be_model.brand_id'
                )
                ->LeftJoin(
                    'be_type',
                    'be_type.type_id',
                    '=',
                    'be_brand.type_id'
                )
                ->LeftJoin(
                    'be_make',
                    'be_make.make_id',
                    '=',
                    'be_brand.make_id_new'
                )
                ->LeftJoin(
                    'partner',
                    'partner.partner_id',
                    '=',
                    'vehicles.vendor_id'
                )
                ->Join(
                    'be_airport_dtl',

                    'be_airport_dtl.model_id',
                    '=',
                    'vehicles.model_id_be'
                )
                ->Join(
                    'be_airport',

                    'be_airport.id',
                    '=',
                    'be_airport_dtl.promo_id'
                )
                ->Join(
                    'all_airport',

                    'all_airport.id',
                    '=',
                    'be_airport.airport_id'
                )
                ->Join(
                    'all_airport_continents',

                    'all_airport_continents.cont_id',
                    '=',
                    'all_airport.cont_id'
                )
                ->where([
                    [
                        'partner.active', '=', '1'
                    ],
                    [
                        'be_make.status', '=', '1.04'
                    ],
                    [
                        'vehicles.status', '=', '8.02'
                    ],
                    [
                        'all_airport.name', '=', $request->airport_name
                    ],
                    [
                    'be_model.model_name', 'LIKE', "%" . $AirportsSearch . "%"
                    ]
                ])
                ->distinct();
            $type = vehicle::select(
                DB::raw("CONCAT(be_type.type_name) as source"),
                DB::raw("CONCAT(be_type.type_name) as source2")
            )
            ->LeftJoin(
                'be_model',
                'be_model.model_id',
                '=',
                'vehicles.model_id_be'
            )
                ->LeftJoin(
                    'be_brand',
                    'be_brand.brand_id',
                    '=',
                    'be_model.brand_id'
                )
                ->LeftJoin(
                    'be_type',
                    'be_type.type_id',
                    '=',
                    'be_brand.type_id'
                )
                ->LeftJoin(
                    'be_make',
                    'be_make.make_id',
                    '=',
                    'be_brand.make_id_new'
                )
                ->LeftJoin(
                    'partner',
                    'partner.partner_id',
                    '=',
                    'vehicles.vendor_id'
                )
                ->Join(
                    'be_airport_dtl',

                    'be_airport_dtl.model_id',
                    '=',
                    'vehicles.model_id_be'
                )
                ->Join(
                    'be_airport',

                    'be_airport.id',
                    '=',
                    'be_airport_dtl.promo_id'
                )
                ->Join(
                    'all_airport',

                    'all_airport.id',
                    '=',
                    'be_airport.airport_id'
                )
                ->Join(
                    'all_airport_continents',

                    'all_airport_continents.cont_id',
                    '=',
                    'all_airport.cont_id'
                )
                ->where([
                    [
                        'partner.active', '=', '1'
                    ],
                    [
                        'be_make.status', '=', '1.04'
                    ],
                    [
                        'vehicles.status', '=', '8.02'
                    ],
                    [
                        'all_airport.name', '=', $request->airport_name
                    ],
                    [
                        'be_type.type_name', 'LIKE', "%" . $AirportsSearch . "%"
                    ]
                ])
                ->distinct()
                ->union($e)
                ->union($make)
                ->union($brand)
                ->union($model)
                ->union($make_type)
                ->union($make_brand)
                ->union($make_model)
                ->union($brand_model)
                ->union($brand_type)
                ->get();
            $data = $type;
        }
        return response()->json($data);
    }

	// public function search_vehicle_airports(Request $request){
    //     if ($request->has('q')) {
    //         $AirportsSearch = $request->q;
    //         // return $request;


    //         $e = vehicle::select(DB::raw("CONCAT(be_make.make_name,' ', be_brand.brand_name, ' ', be_model.model_name, ' ', be_type.type_name ) as source"),
	// 		DB::raw("CONCAT(be_make.make_name,'-', be_brand.brand_name, '-', be_model.model_name ) as source2"))
    //         ->LeftJoin(
    //             'be_model',
    //             'be_model.model_id',
    //             '=',
    //             'vehicles.model_id_be'
    //         )
    //         ->LeftJoin(
    //             'be_brand',
    //             'be_brand.brand_id',
    //             '=',
    //             'be_model.brand_id'
    //         )
    //         ->LeftJoin(
    //             'be_type',
    //             'be_type.type_id',
    //             '=',
    //             'be_brand.type_id'
    //         )
    //         ->LeftJoin(
    //             'be_make',
    //             'be_make.make_id',
    //             '=',
    //             'be_brand.make_id_new'
    //         )
    //         ->LeftJoin(
    //             'partner',
    //             'partner.partner_id',
    //             '=',
    //             'vehicles.vendor_id'
    //         )
	// 		->Join(
	// 			'be_airport_dtl',

	// 			'be_airport_dtl.model_id',
	// 			'=',
	// 			'vehicles.model_id_be'
	// 		)
	// 		->Join(
	// 			'be_airport',

	// 			'be_airport.id',
	// 			'=',
	// 			'be_airport_dtl.promo_id'
	// 		)
	// 		->Join(
	// 			'all_airport',

	// 			'all_airport.id',
	// 			'=',
	// 			'be_airport.airport_id'
	// 		)
	// 		->Join(
	// 			'all_airport_continents',

	// 			'all_airport_continents.cont_id',
	// 			'=',
	// 			'all_airport.cont_id'
	// 		)


    //         ->where([
    //             [
    //                 'partner.active', '=', '1'
    //             ],
    //             [
    //                 'be_make.status', '=', '1.04'
    //             ],
    //             [
    //                 'vehicles.status', '=', '8.02'
    //             ],
    //             [
    //                 'all_airport.name', '=', $request->airport_name
    //             ],
    //             [
    //                 DB::raw("CONCAT(be_make.make_name,' ', be_brand.brand_name, ' ', be_model.model_name, ' ', be_type.type_name)"), 'LIKE', "%" . $AirportsSearch . "%"
    //             ]
    //         ])
    //         ->distinct()

    //         ->get();
    //         $data = $e;
    //     }
    //     return response()->json($data);
    // }


	public static function get_airport_details($airport_id){



            $e = vehicle::select('all_airport.name', 'all_airport.country','all_airport.city','be_airport.pickup_charges','be_airport.monitoring_charges','be_airport.delivery_charges','be_airport.special_notes')
            ->LeftJoin(
                'be_model',
                'be_model.model_id',
                '=',
                'vehicles.model_id_be'
            )
            ->LeftJoin(
                'be_brand',
                'be_brand.brand_id',
                '=',
                'be_model.brand_id'
            )
            ->LeftJoin(
                'be_type',
                'be_type.type_id',
                '=',
                'be_brand.type_id'
            )
            ->LeftJoin(
                'be_make',
                'be_make.make_id',
                '=',
                'be_brand.make_id_new'
            )
            ->LeftJoin(
                'partner',
                'partner.partner_id',
                '=',
                'vehicles.vendor_id'
            )
			->Join(
				'be_airport_dtl',

				'be_airport_dtl.model_id',
				'=',
				'vehicles.model_id_be'


			)
			->Join(
				'be_airport',

				'be_airport.id',
				'=',
				'be_airport_dtl.promo_id'
			)
			->Join(
				'all_airport',

				'all_airport.id',
				'=',
				'be_airport.airport_id'
			)
			->Join(
				'all_airport_continents',

				'all_airport_continents.cont_id',
				'=',
				'all_airport.cont_id'
			)


            ->where([
                [
                    'partner.active', '=', '1'
                ],
                [
                    'be_make.status', '=', '1.04'
                ],
                [
                    'vehicles.status', '=', '8.02'
                ],
                [
                  'be_airport.id', '=', $airport_id
                ]
            ])
            ->distinct()

            ->get();
            $data = $e;
       //	$data2 = array();

		//$data2[] = $data[0];

        return $data;
    }
	
	
	 public static function count_vehicle($model_id_be, $vendor_id,$airport_id){
        // return $model_id_be . $vendor_id;
        // return $vendor_id;
        // return  "30";
        return $vcount = vehicle::select("vehicles.id")
		 ->Join(
                'partner','partner.partner_id','vehicles.vendor_id'
            )
		->Join(
                    'be_airport_dtl',
                    function ($join){
                        $join->on('be_airport_dtl.model_id', '=', 'vehicles.model_id_be');
                        $join->on('be_airport_dtl.vendor_id', '=', 'partner.id');
                    }
                )
			
			
			->Join(
                'be_airport',

                'be_airport.id',
                '=',
                'be_airport_dtl.promo_id' 
            )
				
			
        ->where('vehicles.model_id_be',$model_id_be)
        ->where('vehicles.vendor_id', $vendor_id)
        ->where('vehicles.status', '8.02')
		 ->where("be_airport.airport_id", $airport_id)
        ->count();
    }
	
	
	public static function supplier_count($model_id_be,$airport_id){
        
 		$total_days = request()->session()->get('total_days');
		
		$DealsData = \App\Helpers\AppHelper::instance()->GetCompaniesDealsDailWeeklyMonthly($total_days);

		$deals_in_daily = $DealsData[0];
		$deals_in_weekly = $DealsData[1];
		$deals_in_monthly = $DealsData[2];
		
        return $user_info = DB::table('vehicles')
            ->select('vehicles.model_id_be','vehicles.vendor_id', DB::raw('count(*) as total'))
            ->Join(
                'partner','partner.partner_id','vehicles.vendor_id'
            )
			->Join(
                    'be_airport_dtl',
                    function ($join){
                        $join->on('be_airport_dtl.model_id', '=', 'vehicles.model_id_be');
                        $join->on('be_airport_dtl.vendor_id', '=', 'partner.id');
                    }
                )
			
			
			->Join(
                'be_airport',

                'be_airport.id',
                '=',
                'be_airport_dtl.promo_id' 
            )
			
            ->where('vehicles.status','8.02')
            ->where('partner.active','1')
            ->where('vehicles.model_id_be',$model_id_be)
			 ->where("be_airport.airport_id", $airport_id)
			->when($total_days, function ($query) use ($total_days,$deals_in_daily,$deals_in_weekly,$deals_in_monthly) {

					if($total_days>=30){
						return $query->whereIn('partner.id', $deals_in_monthly);
					}
					else if($total_days<30 && $total_days>=7){
						return $query->whereIn('partner.id', $deals_in_weekly);
					}
					else if($total_days<7){
						return $query->whereIn('partner.id', $deals_in_daily);
					}

                })
			
            ->groupBy('vehicles.vendor_id')
            ->get()->count();

    }
	
	
	public static function check_expected_date($model_id,$airport_id){
        // return $model_id;
        //         SELECT DATE(c.`dropoff_date`)return_date,a.`model_id_be` FROM vehicles a
        // INNER JOIN be_model b ON b.model_id = a.model_id_be
        // LEFT OUTER JOIN sale_order_1 c ON c.`product_dtl_id` = a.`rowid`
        // WHERE c.`status` = 4.02 AND a.`status` = 8.03 AND a.`model_id_be` = 42 ORDER BY c.`dropoff_date` DESC LIMIT 1
            $checked = vehicle::select('partner.id')
             ->LeftJoin(
                'partner','partner.partner_id','vehicles.vendor_id'
            )
			 ->Join(
                    'be_airport_dtl',
                    function ($join){
                        $join->on('be_airport_dtl.model_id', '=', 'vehicles.model_id_be');
                        $join->on('be_airport_dtl.vendor_id', '=', 'partner.id');
                    }
                )
			
			
			->Join(
                'be_airport',

                'be_airport.id',
                '=',
                'be_airport_dtl.promo_id' 
            )
			
			
            ->where('partner.active',1)
            ->where('model_id_be',$model_id)
			->where("be_airport.airport_id", $airport_id)
			->where('vehicles.status','8.02')->first();
			
			
			
			
            if(!$checked){


            $data = vehicle::select('sale_order_1.dropoff_date','vehicles.model_id_be','vehicles.vendor_id')
            ->Join(
                'be_model','be_model.model_id','vehicles.model_id_be'
            )
            ->LeftJoin(
                'sale_order_1','sale_order_1.product_dtl_id','vehicles.rowid'
            )
            ->LeftJoin(
                    'partner',
                    'partner.partner_id',
                    'vehicles.vendor_id'
             )
			 ->Join(
                    'be_airport_dtl',
                    function ($join){
                        $join->on('be_airport_dtl.model_id', '=', 'vehicles.model_id_be');
                        $join->on('be_airport_dtl.vendor_id', '=', 'partner.id');
                    }
                )
			
			
			->Join(
                'be_airport',

                'be_airport.id',
                '=',
                'be_airport_dtl.promo_id' 
            )
				
			 
			 
            ->where('partner.active',1)
            ->where('sale_order_1.status','4.02')->where('vehicles.status','8.03')->where('vehicles.model_id_be',$model_id)
			->where("be_airport.airport_id", $airport_id)
			->OrderBy('sale_order_1.dropoff_date','desc')->limit(1)->first();
            if($data){
                return $data->dropoff_date;
            }
            else{
                // return "No Date Found";
                return '1';
            }
        }
        else{
            return "0";
        }
    }
	
	public static function comm_fixed($vendor_id){
         $r = partner::select('comm_fixed','mobile2')->where('id',$vendor_id)->first();
         if($r->comm_fixed == '0.00'){
             return '971543363900';
         }
         else{
             return $r->mobile2;
         }
    }
	

}
