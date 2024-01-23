<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\BecomePartnerController;
use App\Http\Controllers\MapDataController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarsForRentController;
use App\Http\Controllers\CurrencyController;
use App\Models\country_phone_code;
use App\Models\partner;
use Illuminate\Support\Facades\Route;
use Cknow\Money\Money;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\BlogController;

use Illuminate\Http\Request;
// use PDF;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('phpinfo', function () {
    // return view('welcome');
    phpinfo();
    // ub
    // echo 'MyPath' . env('DOCUMENT_PATH') . env('DOCUMENT_PATH_ORIGIN');
});
Route::localized(function () {

// Route::group(['middleware' => 'language'], function () {


    //
    // Route::get('/ip', function (Request $request) {

    // });
    // Route::domain('{account}.caryaati.test')->group(function () {
    //     // Route::get('user/{id}', function ($account, $id) {
    //         //

    //     // });
    // });
    // Route::group(['domain' => '{account}.caryaati.'], function () {
    Route::domain('{account}.caryaati.com')->middleware('mymiddle')->group(function () {

	Route::get('test_riz', [FacebookController::class, 'test_riz'])->name('facebook.login2')->middleware('Localization');
        Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.home');
        // Route::get('/sitemap/homepage.xml', [SitemapController::class, 'home_page'])->name('sitemap.homepage');
        // Route::get('/sitemap/blog.xml', [SitemapController::class, 'blog'])->name('sitemap.blog');
        // Route::get('/sitemap/rent-a-car.xml', [SitemapController::class, 'rent_a_car'])->name('sitemap.rent-a-car');
        // Route::get('/sitemap/car-rental-near-me.xml', [SitemapController::class, 'car_rental_near_me'])->name('sitemap.car-rental-near-me');
        // Route::get('/sitemap/vendor-sitemap-{vendor_name}.xml', [SitemapController::class, 'vendor_rentals'])->name('sitemap.vendor_sitemap');
        // Route::get('/sitemap/country-sitemap-{country_name}.xml', [SitemapController::class, 'country_sitemap'])->name('sitemap.country');
        // Route::get('/sitemap/car-hire-by-city-sitemap-{country_name}.xml', [SitemapController::class, 'carhire_sitemap'])->name('sitemap.carhire');
        // Route::get('/sitemap/car-hire-by-city-area-sitemap/{country_name}/{city}/{area}/make.xml', [SitemapController::class, 'carhire_sitemap_make'])->name('sitemap.carhire.make');
        // Route::get('/sitemap/car-hire-by-city-area-sitemap/{country_name}/{city}/{area}/brand.xml', [SitemapController::class, 'carhire_sitemap_brand'])->name('sitemap.carhire.brand');
        // Route::get('/sitemap/car-hire-by-city-area-sitemap/{country_name}/{city}/{area}/type.xml', [SitemapController::class, 'carhire_sitemap_type'])->name('sitemap.carhire.type');
        // Route::get('/sitemap/car-hire-by-city-area-sitemap/{country_name}/{city}/{area}/model.xml', [SitemapController::class, 'carhire_sitemap_model'])->name('sitemap.carhire.model');
        // Route::get('/sitemap/car-hire-by-city-area-sitemap/{country_name}/{city}/{area}/all.xml', [SitemapController::class, 'carhire_sitemap_all'])->name('sitemap.carhire.all');
        // Route::get('/sitemap/car-hire-by-city-area-sitemap/{country_name}/{city}/{area}/make_brand.xml', [SitemapController::class, 'carhire_sitemap_make_brand'])->name('sitemap.carhire.make_brand');
        // Route::get('/sitemap/car-hire-by-city-area-sitemap/{country_name}/{city}/{area}/make_type.xml', [SitemapController::class, 'carhire_sitemap_make_type'])->name('sitemap.carhire.make_type');
        // Route::get('/sitemap/car-hire-by-city-area-sitemap/{country_name}/{city}/{area}/make_model.xml', [SitemapController::class, 'carhire_sitemap_make_model'])->name('sitemap.carhire.make_model');
        // Route::get('/sitemap/car-hire-by-city-area-sitemap/{country_name}/{city}/{area}/brand_model.xml', [SitemapController::class, 'carhire_sitemap_brand_model'])->name('sitemap.carhire.brand_model');
        // Route::get('/sitemap/car-hire-by-city-area-sitemap/{country_name}/{city}/{area}/brand_type.xml', [SitemapController::class, 'carhire_sitemap_brand_type'])->name('sitemap.carhire.brand_type');


        Route::get('facebook', [FacebookController::class, 'redirectToFacebook'])->name('facebook.login');
        Route::get('facebook/callback', [FacebookController::class, 'handleFacebookCallback'])->name('facebook.login.callback');
        Route::get('google', [FacebookController::class, 'redirectToGoogle'])->name('google.login');
        Route::get('google_callback', [FacebookController::class, 'handleGoogleCallback'])->name('google.login.callback');
        Route::post('google_new_login', [FacebookController::class, 'google_new_login'])->name('google_new_login')->middleware('guest');

        // return $account;

        Route::get('become-partner', [HomeController::class, 'become_partner'])->name('become-partner');
        Route::get('/', [HomeController::class, 'home', 'account'])->name('home');
        // Route::get('pop', function ($account) {
        //         return $account;
        // Route::get('/test3', function () {
        //     return view('email.booking-invoice');
        // });
        // // });
        // Route::get('/test2', function () {
        //     // return $country_phone_code = country_phone_code::all();
        //     $invoice = [
        //         'make' => '$data->make_name',
        //         'model' => '$data->model_name',
        //         'type' => '$data->type_name',
        //         'start' => 's',
        //         'end' => 's',
        //         'total_days' => 'ss',
        //         'deposit_charges' => 'ss',
        //         'customer_name' => '$cp->name',
        //         'dob' => '$cp->dob',
        //         'passport_no' => '$cp->doc_no',
        //         'customer_expiry' => '$cd->doc_expiry_date',
        //         'doc_no' => '$cd->doc_no',
        //         'doc_issue_by' => '$cd->doc_issue_by',
        //         'doc_expiry_date' => '$cd->doc_expiry_date',
        //         // 'doc_issue_by' => $cd->doc_issue_by,
        //         // 'doc_issue_by' => $cd->doc_issue_by,
        //         'rental_charges' => 'w',
        //         'payable' => 1,
        //         'vat_charges' => 1,
        //         'sub_total' => 1,
        //         'total_amount' => 1,
        //         'currency' => 1,
        //         'vendor_name' => 'PCR',
        //         'vendor_address' => 'Address Sorry',
        //     ];

        // $pdf = PDF::loadView('email.booking-invoice', compact('invoice'));

        // return $pdf->stream('itsolutionstuff.pdf');
        //         // // return
        //         // $details = [
        //         //     'hi' =>'hi',
        //         // ];
        //         // return $pdf = PDF::loadView('email.booking-invoice',$details);

        // });
        // AjaxSeries Start
        Route::get('/skill-auto-complete', 'App\Http\Controllers\AjaxController@dataAjax')->name('skill-auto-complete');
        Route::get('/dataAjax2', 'App\Http\Controllers\AjaxController@dataAjax2')->name('dataAjax2');
        Route::get('/dataAjax2Area', 'App\Http\Controllers\AreaController@dataAjax2')->name('dataAjax2.area');
        Route::get('/skill-auto-complete-vendor', 'App\Http\Controllers\AjaxController@dataAjaxVendor')->name('skill-auto-complete-vendor');
        Route::get('/priority', 'App\Http\Controllers\AjaxController@priority')->name('priority');
        Route::get('/priority1', 'App\Http\Controllers\AjaxController@priority1')->name('priority1');
        // AjaxSeries End
        Route::get('/getsdays', 'App\Http\Controllers\AjaxController@getsdays2')->name('getsdays');
        Route::get('/search_airport_location', 'App\Http\Controllers\AjaxController@AirportsSearch')->name('search_airport_location');
        Route::get('/search_airport_location_vendor', 'App\Http\Controllers\AjaxController@AirportsSearchVendor')->name('search_airport_location_vendor');
        Route::get('/subcrib_modal_view', 'App\Http\Controllers\AjaxController@subcrib_modal_view')->name('subcrib_modal_view');

        //modals Views
        Route::get('/whatsapp_modal_view', 'App\Http\Controllers\ModalController@whatsapp_modal_view')->name('whatsapp_modal_view');
        Route::get('/whatsapp_modal_view24', 'App\Http\Controllers\ModalController@whatsapp_modal_view24')->name('whatsapp_modal_view24');
        Route::get('/email_modal_view', 'App\Http\Controllers\ModalController@email_modal_view')->name('email_modal_view');
        Route::get('/call_modal_view', 'App\Http\Controllers\ModalController@call_modal_view')->name('call_modal_view');

        Route::post('/RequestEmail', [ModalController::class, 'RequestEmail'])->name('RequestEmail');
        Route::post('/WhatsAppAccess24', [ModalController::class, 'WhatsAppAccess24'])->name('WhatsAppAccess24');
        Route::get('airport_load', [AjaxController::class, 'airport_load'])->name('airport.load');
        Route::get('other_load', [AjaxController::class, 'other_load'])->name('other.url');
        Route::get('RelatedLoadUrl', [AjaxController::class, 'RelatedLoadUrl'])->name('RelatedLoadUrl');



        // Here your routes
        // route::get('')
        Route::post('/MapData', 'App\Http\Controllers\MapDataController@MapData')->name('map-data');
        Route::post('/NewMapType', 'App\Http\Controllers\MapDataController@NewMapType')->name('map-data-type');
        Route::get('/SideBarData', 'App\Http\Controllers\MapDataController@SideBarData')->name('sidebar-data');
        Route::post('/SideBarDataType', 'App\Http\Controllers\MapDataController@SideBarDataType')->name('sidebar-data-type');
        Route::post('/SideBarData2', 'App\Http\Controllers\MapDataController@SideBarData2')->name('sidebar-data2');
        Route::post('/infobox', 'App\Http\Controllers\MapDataController@infobox')->name('infobox');
        Route::post('/SideBarTypeData2', 'App\Http\Controllers\MapDataController@SideBarTypeData2')->name('sidebar-type-data2');
        Route::get('/NewMap2', 'App\Http\Controllers\HomeController@NewMap')->name('new-map2');
        Route::get('/car-rental-near-me', 'App\Http\Controllers\HomeController@NewMap2')->name('new-map');
        Route::get('/car-rental-near-me-{slug}', 'App\Http\Controllers\HomeController@NewMapSlug')->name('new-map-slug');
        // Route::get('/NewMap/{slug}', 'App\Http\Controllers\HomeController@NewMapSlug')->name('new-map-slug');
        Route::get('monthly-cars-for-rent', 'App\Http\Controllers\CarsForRentController@monthly_car_economy')->name('monthly.cars.economy');
        Route::get('{slug}-cars-for-rent', 'App\Http\Controllers\CarsForRentController@special_car_economy')->name('special.cars.economy');
        Route::get('full-size-cars-for-rent', 'App\Http\Controllers\CarsForRentController@special_car_fullsize')->name('special.cars.fullsize');
        Route::get('premium-bus-rental', 'App\Http\Controllers\CarsForRentController@special_premium_bus_rental')->name('special_premium_bus_rental');
        Route::get('premium-van-rental', 'App\Http\Controllers\CarsForRentController@special_premium_van_rental')->name('special_premium_van_rental');
        Route::get('luxury-bus-rental', 'App\Http\Controllers\CarsForRentController@special_luxury_bus_rental')->name('special_luxury_bus_rental');
		Route::post('load_card_subcat_ref', 'App\Http\Controllers\CarsForRentController@load_card_subcat_ref')->name('load_card_subcat_ref');
		Route::post('load_card_instantbooking', 'App\Http\Controllers\CarsForRentController@load_card_instantbooking')->name('load_card_instantbooking');
		
        Route::get('mid-size-cars-for-rent', 'App\Http\Controllers\CarsForRentController@special_car_midsize')->name('special.cars.midsize');
        Route::get('cars-for-rent/{slug}', 'App\Http\Controllers\CarsForRentController@rental_car')->name('cars-for-rent');
        Route::get('special-car/{slug}', 'App\Http\Controllers\CarsForRentController@special_car')->name('special.cars');

        Route::get('car-rentals/{slug}', 'App\Http\Controllers\CarsForRentController@rental_car_vendor')->name('car.rental');

        //airport route
        Route::get('/car-rental-airport/{slug}', 'App\Http\Controllers\CarsForRentAirPortController@airport_listing')
            ->where('slug', '.*')->name('airport_listing');
        //
        Route::post('/show-city', [AreaController::class, 'show_city'])->name('show.city');
        //
        Route::post('/show-area', [AreaController::class, 'show_area'])->name('show.area');
        Route::post('/show-airport', [AreaController::class, 'show_airport'])->name('show.airport');
        Route::post('/LoadFilter', [AreaController::class, 'LoadFilter'])->name('load.filter');
        Route::get('/sidebar_area_filter', [AreaController::class, 'sidebar_filters'])->name('sidebar.area.filter');
        Route::get('/rent-a-car/{slug}', 'App\Http\Controllers\AreaController@area_listing')
            ->where('slug', '.*')->name('my.area');
        Route::get('/rent-a-car-load/{slug}', 'App\Http\Controllers\AreaController@load_area_listing')
            ->where('slug', '.*')->name('my.area.load');
        Route::post('similar-vehicle-airport', 'App\Http\Controllers\CarsForRentAirPortController@similar_vehicle_airport')->name('similar-vehicle-airport');

        Route::post('car-slider-airport', 'App\Http\Controllers\CarsForRentAirPortController@CarSliderAiport')->name('car-slider-airport');

        Route::get('sidebar_filters', 'App\Http\Controllers\CarsForRentAirPortController@sidebar_filters')->name('sidebar_filters');
        Route::get('price_rang_max_mini', 'App\Http\Controllers\CarsForRentAirPortController@price_rang_max_mini', 'price_rang_max_mini')->name('price_rang_max_mini');

        Route::post('search_aiport_list', 'App\Http\Controllers\CarsForRentAirPortController@search_aiport_list')->name('search_aiport_list');
        Route::post('quick_search_airport', 'App\Http\Controllers\CarsForRentAirPortController@quick_search_airport')->name('quick_search_airport');
        Route::get('/search_vehicle_airports', 'App\Http\Controllers\CarsForRentAirPortController@search_vehicle_airports')->name('search_vehicle_airports');




        Route::get('vendor-single-car/{slug}', 'App\Http\Controllers\CarsForRentController@vendor_single_car')->name('vendor-single-car');
        Route::get('emirates/{slug}', 'App\Http\Controllers\CarsForRentController@emirates')->name('emirates');
        Route::get('airport/{slug}', 'App\Http\Controllers\CarsForRentController@airport')->name('airport');
        Route::post('similar-vehicle', 'App\Http\Controllers\CarsForRentController@similar_vehicle')->name('similar-vehicle');
        Route::post('similar-vehicle-area', 'App\Http\Controllers\CarsForRentController@similar_vehicle_area')->name('similar-vehicle-area');

        Route::get('/Booking', 'App\Http\Controllers\BookingController@booking')->name('booking')->middleware(['auth', 'BookingAuth']);
        // Route::get('/Payment', 'App\Http\Controllers\BookingController@booking')->name('booking')->middleware('auth');
        Route::post('/Booking-load', 'App\Http\Controllers\BookingController@booking_load')->name('booking_load');
        Route::post('/home_dates', 'App\Http\Controllers\BookingController@home_dates')->name('home_dates');
        Route::post('/UserUpdate', 'App\Http\Controllers\UserController@UserUpdate')->name('UserUpdate');
        Route::post('/ProfileUpdate', 'App\Http\Controllers\UserController@ProfileUpdate')->name('ProfileUpdate');

        Route::post('/UserUpdateNew', 'App\Http\Controllers\UserController@UserUpdateNew')->name('UserUpdateNew');
        Route::post('/AditionalDriverValiddation', 'App\Http\Controllers\UserController@AditionalDriverValiddation')->name('AditionalDriverValiddation');
        Route::post('/BookingValidations', 'App\Http\Controllers\BookingController@BookingValidations')->name('BookingValidations');

        Route::post('slider', 'App\Http\Controllers\CarsForRentController@slider_cars')->name('slider_cars');

        Route::post('slider', 'App\Http\Controllers\CarsForRentController@slider_cars')->name('slider_cars');

        // Route::post('/request-phone', 'App\Http\Controllers\ModalC@RequestPhone')->name('UserUpdate');
        //
        Route::get('rent-a-car-blogs', [BlogController::class, 'all_blog'])->name('caryaati.blog');
        Route::get('rent-a-car-blogs/{slug}', [BlogController::class, 'main_blog'])->name('main.blog');
        Route::get('rent-a-car-blogs-category/{slug}', [BlogController::class, 'main_blog_category'])->name('main.blog.category');
        Route::get('rent-a-car-blogs-tags/{slug}', [BlogController::class, 'main_blog_tags'])->name('main.blog.tags');
        Route::post('comment-submit', [BlogController::class, 'comment_submit'])->name('submit.comment');
        // Route::get('/', function () {
        //     // return "Home";
        // });
        // Route::get('/', 'App\Http\Controllers\HomeController@Home')->name('home');
        Route::get('/index', 'App\Http\Controllers\HomeController@Home');
        // Route::get('/', 'App\Http\Controllers\HomeController@Home')->name('home-2');
        Route::get('/login-user', 'App\Http\Controllers\UserController@login')->name('login-user');
        Route::get('/about-us', 'App\Http\Controllers\HomeController@about')->name('about-us');
        Route::get('/contact-us', 'App\Http\Controllers\HomeController@contact')->name('contact-us');
        Route::get('/faqs', 'App\Http\Controllers\HomeController@faq')->name('faqs');
        Route::get('/help', 'App\Http\Controllers\HomeController@help')->name('help');
        Route::get('/privacy-policy', 'App\Http\Controllers\HomeController@privacy')->name('privacy-policy');
        Route::get('/terms-and-conditions', 'App\Http\Controllers\HomeController@terms')->name('terms-condition');
        // Route::get('/terms-and-conditions', 'App\Http\Controllers\HomeController@terms')->name('terms-condition');
        // Route::get('/calendar-filter', 'App\Http\Controllers\CarsForRentController@calendar_filter')->name('become-partner');
        //
        // FILTER
        Route::post('ScreenShotDiv', [FilterController::class, 'ScreenShotDiv'])->name('ScreenShotDiv');
        Route::get('encrypted', [FilterController::class, 'encrypter_filter'])->name('encrypted-filter');
        Route::get('vendor-map', [FilterController::class, 'vendor_map'])->name('vendor.map.vehicle');
        Route::get('type-filter', [FilterController::class, 'type_filter'])->name('type-filter');
        Route::get('type-filter-vendor', [FilterController::class, 'type_filter_vendor'])->name('type-filter-vendor');
        Route::get('low-to-high', [FilterController::class, 'low_to_high'])->name('low-to-high');
        Route::get('high-to-low', [FilterController::class, 'high_to_low'])->name('high-to-low');
        Route::get('latest-model', [FilterController::class, 'latest_model'])->name('latest-model');
        Route::get('oldest-model', [FilterController::class, 'oldest_model'])->name('oldest-model');
        Route::get('low-to-high-vendor', [FilterController::class, 'low_to_high_vendor'])->name('low-to-high-vendor');
        Route::get('high-to-low-vendor', [FilterController::class, 'high_to_low_vendor'])->name('high-to-low-vendor');
        Route::get('latest-model-vendor', [FilterController::class, 'latest_model_vendor'])->name('latest-model-vendor');
        Route::get('oldest-model-vendor', [FilterController::class, 'oldest_model_vendor'])->name('oldest-model-vendor');
        Route::get('wishlist-vehicle/{slug}', 'App\Http\Controllers\WishListController@WishList')->name('wishlist.cars');
        // 
        Route::get('low-to-high-special/{slug}', [FilterController::class, 'low_to_high_special'])->name('low-to-high-special');
        Route::get('high-to-low-special', [FilterController::class, 'high_to_low_special'])->name('high-to-low-special');
        // 
        Route::get('latest-model-special', [FilterController::class, 'latest_model_special'])->name('latest-model-special');
        Route::get('oldest-model-special', [FilterController::class, 'oldest_model_special'])->name('oldest-model-special');
        Route::get('passenger_cars_special', [FilterController::class, 'passenger_cars_special'])->name('passenger_cars_special');
        Route::get('engin_capacity_special', [FilterController::class, 'engin_capacity_special'])->name('engin_capacity_special');
        Route::get('min_delivery_charges_cars_special', [FilterController::class, 'min_delivery_charges_cars_special'])->name('min_delivery_charges_cars_special');
        Route::get('driver_charges_min_car_special', [FilterController::class, 'driver_charges_min_car_special'])->name('driver_charges_min_car_special');



        //
        Route::get('low-to-high-map', [MapDataController::class, 'low_to_high'])->name('low-to-high-map');
        Route::get('high-to-low-map', [MapDataController::class, 'high_to_low'])->name('high-to-low-map');
        Route::get('latest-model-map', [MapDataController::class, 'latest_model'])->name('latest-model-map');
        Route::get('oldest-model-map', [MapDataController::class, 'oldest_model'])->name('oldest-model-map');
        //
        Route::get('be-make', [FilterController::class, 'be_make_advance'])->name('be.make.advance');
        Route::POST('be-type', [FilterController::class, 'be_type_advance'])->name('be.type.advance');
        Route::POST('be-brand', [FilterController::class, 'be_brand_advance'])->name('be.brand.advance');
        Route::POST('be-model', [FilterController::class, 'be_model_advance'])->name('be.model.advance');
        Route::get('passenger_cars', [FilterController::class, 'passenger_cars'])->name('passenger_cars');
        Route::get('passenger_cars_map', [MapDataController::class, 'passenger_cars_map'])->name('passenger_cars.map');
        Route::get('passenger_cars_vendor', [FilterController::class, 'passenger_cars_vendor'])->name('passenger_cars_vendor');
        Route::get('vendor_filter', [FilterController::class, 'vendor_filter'])->name('vendor_filter');

        Route::get('engin_capacity', [FilterController::class, 'engin_capacity'])->name('engin_capacity');
        Route::get('engin_capacity_map', [MapDataController::class, 'engin_capacity_map'])->name('engin_capacity.map');
        Route::get('category_cars', [FilterController::class, 'category_cars'])->name('category_cars');
        Route::get('CategoryMapCars', [MapDataController::class, 'CategoryMapCars'])->name('CategoryMapCars');

        Route::get('engin_capacity_vendor', [FilterController::class, 'engin_capacity_vendor'])->name('engin_capacity_vendor');
        Route::get('category_cars_vendor', [FilterController::class, 'category_cars_vendor'])->name('category_cars_vendor');

        // 
        Route::get('booked_vehicle', [FilterController::class, 'booked_vehicle'])->name('booked_vehicle');
        Route::get('booked_vehicle_airport', [FilterController::class, 'booked_vehicle_airport'])->name('booked_vehicle_airport');
        Route::get('booked_vehicle_vendor', [FilterController::class, 'booked_vehicle_vendor'])->name('booked_vehicle_vendor');
		Route::get('BookedVehicleUrl/{slug}', 'App\Http\Controllers\CarsForRentController@BookedVehicleUrl')->name('BookedVehicleUrl');

        // 
        Route::get('min_delivery_charges_cars', [FilterController::class, 'min_delivery_charges_cars'])->name('min_delivery_charges_cars');
        Route::get('driver_charges_min_car', [FilterController::class, 'driver_charges_min_car'])->name('driver_charges_min_car');
        Route::get('driver_charges_min_car_map', [MapDataController::class, 'driver_charges_min_car_map'])->name('driver_charges_min_car_map');
        Route::get('min_delivery_charges_cars_map', [MapDataController::class, 'min_delivery_charges_cars_map'])->name('min_delivery_charges_cars_map');
        Route::get('passenger-encrypt', [FilterController::class, 'passenger_encrypt'])->name('passenger-encrypted-filter');
        Route::get('driver-charges-encrypt', [FilterController::class, 'driver_charges_min_encrypt'])->name('driver-encrypted-filter');
        Route::get('delivery-encrypt', [FilterController::class, 'min_delivery_charges'])->name('delivery-encrypted-filter');
        Route::get('calendar-filter', [FilterController::class, 'calendar_filter'])->name('calendar-filter');
        Route::get('calendar-filter-area', [FilterController::class, 'calendar_filter_area'])->name('calendar-filter-area');
        Route::get('calendar-filter-airport', [FilterController::class, 'calendar_filter_airport'])->name('calendar-filter-airport');
        Route::get('calendar-filter-vendor', [FilterController::class, 'calendar_filter_vendor'])->name('calendar-filter-vendor');
        Route::get('calendar-filter-type', [FilterController::class, 'calendar_filter_type'])->name('calendar-filter-type');
        Route::get('range-filter', [FilterController::class, 'regular_promo'])->name('range-filter');
        Route::get('range-filter-filter', [FilterController::class, 'regular_promo_vendor'])->name('range-filter-vendor');
        Route::get('range', [FilterController::class, 'range'])->name('range');
        Route::get('range-maps', [MapDataController::class, 'range_maps'])->name('range.maps');
        Route::get('range-vendor', [FilterController::class, 'range_vendor'])->name('range-vendor');
        Route::post('car-slider', [CarsForRentController::class, 'CarSlider'])->name('car-slider');
        Route::post('quick_search', [CarsForRentController::class, 'quick_search'])->name('quick-search');
        Route::post('calendar_search', [CarsForRentController::class, 'calendar_search'])->name('calendar_search');
        Route::post('emirate_search', [CarsForRentController::class, 'emirate_search'])->name('emirate-search');
        Route::post('advance-search', [CarsForRentController::class, 'advance_search'])->name('advance-search');
        Route::post('postLogin', [UserController::class, 'postLogin'])->name('postLogin');
        Route::post('UserSignup', [UserController::class, 'UserSignup'])->name('UserSignup');
        Route::get('logout', [UserController::class, 'logout'])->name('logout-user');
        Route::post('pre-final-booking', [BookingController::class, 'pre_final_booking'])->name('pre-final-booking');
        Route::get('/Payment', [BookingController::class, 'payment'])->name('payment')->middleware('auth')->middleware(['auth', 'BookingAuth']);
        Route::post('/fnc', [BookingController::class, 'final_booking'])->name('final-booking');
        Route::get('/Confirmation', [BookingController::class, 'confirmation'])->name('confirmation')->middleware('auth')->middleware(['auth', 'BookingAuth']);
        Route::post('/RequestPhone', [ModalController::class, 'RequestPhone'])->name('RequestPhone');
        Route::post('/RequestWhatsApp', [ModalController::class, 'WhatsAppAccess'])->name('RequestWhatsApp');
        // Route::post('regular_promo', [CarsForRentController::class, 'regular_promo'])->name('regular_promo');
        Route::post('delivery_charges_fetch', [FilterController::class, 'delivery_charges_fetch'])->name('delivery.charges.fetch');
        Route::post('driver_charges', [FilterController::class, 'driver_charges'])->name('driver_charges');
        Route::post('airport_charges', [FilterController::class, 'airport_charges'])->name('airport.charges.fetch');
        Route::post('airport_type', [FilterController::class, 'airport_type'])->name('airport_type');
        Route::post('old-driver', [FilterController::class, 'old_driver_not_self'])->name('old_driver_not_self');
        Route::get('old_driver', [FilterController::class, 'old_driver_not_self_dashbord'])->name('old_driver_not_self_dashbord');

        // BOOKING SECTION
        Route::post('go-for-booking', [FilterController::class, 'go_for_booking'])->name('go-for-booking');
        Route::post('booking_data', [FilterController::class, 'booking_data'])->name('booking_data');
        Route::post('currency', [CurrencyController::class, 'checkingCurrency'])->name('currency');

        //
        Route::post('become-partner-store', [BecomePartnerController::class, 'store'])->name('become-partner.store');
        Route::get('test_query', [TestController::class, 'test_query'])->name('test_query');
        Route::get('seoexport', [TestController::class, 'seoexport'])->name('seoexport');
        // 
        // 
        Route::get('seo-import', [TestController::class, 'seo_import'])->name('seo.import');
        Route::post('seoupdatefile', [TestController::class, 'seoupdatefile'])->name('seo.upload.file');
        // 


        Route::get('ip', [TestController::class, 'test1'])->name('test1');
        Route::get('image-checker', [TestController::class, 'image_checker'])->name('image_checker');
        Route::get('image-checker-small', [TestController::class, 'image_checker_small'])->name('image_checker_small');
        Route::get('test', [TestController::class, 'test2'])->name('test2');
        Route::get('all-currency', [TestController::class, 'all_currency'])->name('all-currency');
        Route::get('import', [TestController::class, 'import'])->name('import');
        Route::get('ExportMake', [TestController::class, 'ExportMake'])->name('export.make');
        Route::get('ExportMake', [TestController::class, 'ExportMake'])->name('export.make');
        Route::get('ExportCarRentalNearMe', [TestController::class, 'ExportCarRentalNearMe'])->name('ExportCarRentalNearMe');
        Route::get('AirportExport', [TestController::class, 'AirportExport'])->name('AirportExport');
        Route::get('VendorExport', [TestController::class, 'VendorExport'])->name('VendorExport');
        Route::get('ExportCountryData', [TestController::class, 'ExportCountryData'])->name('export.country.data');
        // Route::get('importSeoPage', [TestController::class, 'importSeoPage'])->name('import');
        Route::get('/quick_signup_inq', 'App\Http\Controllers\ModalController@quick_signup_inq')->name('quick_signup_inq');
        Route::get('/QuickSignupModal', 'App\Http\Controllers\ModalController@QuickSignupModal')->name('QuickSignupModal');
        Route::post('QuickSocialLogin', [FacebookController::class, 'QuickSocialLogin'])->name('QuickSocialLogin')->middleware('guest');
        Route::post('QuickSignupBooking', [ModalController::class, 'QuickSignupBooking'])->name('QuickSignupBooking');
        // 
        Route::post('ImportExcel', [TestController::class, 'ImportExcel'])->name('import.excel');
        Route::post('SeoPageNameUpload', [TestController::class, 'SeoPageNameUpload'])->name('SeoPageNameUpload');
        Route::post('SeoVariationNameUpload', [TestController::class, 'SeoVariationNameUpload'])->name('SeoVariationNameUpload');
        Route::post('SeoPageTypeImport', [TestController::class, 'SeoPageTypeImport'])->name('SeoPageTypeImport');
        Route::post('driver_submit', [UserController::class, 'DriverUpdate'])->name('driver-submit');
        Route::post('detectlocation', [AjaxController::class, 'detectlocation'])->name('detectlocation');
        Route::post('clearcache', [AjaxController::class, 'clearcache'])->name('clear.cache');
        Route::post('contact_form_submit', [AjaxController::class, 'contact_form_submit'])->name('contact.form.submit');
        Route::post('subcribe_submit', [AjaxController::class, 'subcribe_submit'])->name('subcribe.form.submit');
        Route::get('MasterLogin/{id}', [AjaxController::class, 'MasterLogin'])->name('master.login');

        // Route::get('MasterLogin/{id}', 'MasterController@MasterLogin')->name('master.login');
        // 
        // Route::get('/home', 'HomeController@index')->name('home');
        Auth::routes();
        //
        Auth::routes(['verify' => true]);

        // Route::prefix('customer-dashboard')->group(function () {
        //     Route::get('index', [CustomerDashboardController::class, 'index'])->name('customer.index')->middleware('auth');
        //     Route::get('booking', [CustomerDashboardController::class, 'booking'])->name('customer.booking')->middleware('auth');
        //     Route::get('salik', [CustomerDashboardController::class, 'salik'])->name('customer.salik')->middleware('auth');
        //     Route::get('fines', [CustomerDashboardController::class, 'fines'])->name('customer.fine')->middleware('auth');
        //     Route::get('profile', [CustomerDashboardController::class, 'profile'])->name('customer.profile')->middleware('auth');
        //     Route::POST('booking-dtl', [CustomerDashboardController::class, 'booking_dtl'])->name('customer.booking-dtl')->middleware('auth');
        //     Route::POST('booking-status', [CustomerDashboardController::class, 'booking_status'])->name('customer.booking.status')->middleware('auth');
        //     Route::POST('ChangePassword', [CustomerDashboardController::class, 'ChangePassword'])->name('customer.ChangePassword')->middleware('auth');
        //     Route::POST('cancelReservation', [CustomerDashboardController::class, 'cancelReservation'])->name('cancelReservation')->middleware('auth');
        //     Route::POST('account_deactive', [CustomerDashboardController::class, 'account_deactive'])->name('account_deactive')->middleware('auth');
        // });
        Route::prefix('customer-dashboard')->group(function () {
            Route::get('index', [CustomerDashboardController::class, 'index'])->name('customer.index')->middleware('auth');
            Route::get('booking', [CustomerDashboardController::class, 'booking'])->name('customer.booking')->middleware('auth');
            Route::get('salik', [CustomerDashboardController::class, 'salik'])->name('customer.salik')->middleware('auth');
            Route::get('fines', [CustomerDashboardController::class, 'fines'])->name('customer.fine')->middleware('auth');
            Route::get('profile', [CustomerDashboardController::class, 'profile'])->name('customer.profile')->middleware('auth');
            Route::get('refferal', [CustomerDashboardController::class, 'refferal'])->name('customer.refferal')->middleware('auth');
            Route::get('reviews', [CustomerDashboardController::class, 'reviews'])->name('customer.reviews')->middleware('auth');
            Route::get('wallets', [CustomerDashboardController::class, 'wallets'])->name('customer.wallets')->middleware('auth');
            Route::get('rewards', [CustomerDashboardController::class, 'rewards'])->name('customer.rewards')->middleware('auth');
            Route::POST('booking-dtl', [CustomerDashboardController::class, 'booking_dtl'])->name('customer.booking-dtl')->middleware('auth');
            Route::POST('booking-status', [CustomerDashboardController::class, 'booking_status'])->name('customer.booking.status')->middleware('auth');
            Route::POST('ChangePassword', [CustomerDashboardController::class, 'ChangePassword'])->name('customer.ChangePassword')->middleware('auth');
            Route::GET('LoadTerms/{id}', [CustomerDashboardController::class, 'LoadTermsAndConditions'])->name('load.terms')->middleware('auth');
            Route::POST('cancelReservation', [CustomerDashboardController::class, 'cancelReservation'])->name('cancelReservation')->middleware('auth');
            Route::POST('account_deactive', [CustomerDashboardController::class, 'account_deactive'])->name('account_deactive')->middleware('auth');
        });
        // Route::post('customer', [CurrencyController::class, 'checkingCurrency'])->name('currency');
        //
    });
    // });

}, [
    'supported-locales' => [
        'en', 'ar', 'tr'
    ],
    'omit_url_prefix_for_locale' => 'en',
    'use_locale_middleware' => true,
]);