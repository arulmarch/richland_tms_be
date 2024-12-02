<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PODController;
use App\Http\Controllers\OwnedController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ManifestController;
use App\Http\Controllers\MechanicController;
use App\Http\Controllers\RingCodeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterUomController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\ClientRateController;
use App\Http\Controllers\MasterRoleController;
use App\Http\Controllers\MasterUserController;
use App\Http\Controllers\MasterAreasController;
use App\Http\Controllers\SelfBillingController;
use App\Http\Controllers\ServiceTaskController;
use App\Http\Controllers\TransporterController;
use App\Http\Controllers\TypeTaxableController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\AccidentTypeController;
use App\Http\Controllers\MasterClientController;
use App\Http\Controllers\MasterVendorController;
use App\Http\Controllers\SalesInvoiceController;
use App\Http\Controllers\ServiceOrderController;
use App\Http\Controllers\DedicatedRateController;
use App\Http\Controllers\MasterCompanyController;
use App\Http\Controllers\MessageBrokerController;
use App\Http\Controllers\RoutePlanningController;
use App\Http\Controllers\TruckAccidentController;
use App\Http\Controllers\TruckingOrderController;
use App\Http\Controllers\MasterCustomerController;
use App\Http\Controllers\TrackingDriverController;
use App\Http\Controllers\TransportOrderController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\TransporterRateController;
use App\Http\Controllers\ComponentEntriesController;
use App\Http\Controllers\HistoryChangeLoadController;
use App\Http\Controllers\MobileAppsVersionController;
use App\Http\Controllers\TrafficMonitoringController;
use App\Http\Controllers\ServiceTaskEntriesController;
use App\Http\Controllers\ListOrderManagementController;
use App\Http\Controllers\MasterCostComponentController;
use App\Http\Controllers\DetailOrderManagementController;
use App\Http\Controllers\GetCartrack\CartrackVehiclesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user_management/login', [UserManagementController::class, 'Login']);
Route::post('/user_management/login-web', [UserManagementController::class, 'LoginWeb']);
Route::post('/user_management/register', [UserManagementController::class, 'Register']);
Route::post('/user_management/reset_password', [UserManagementController::class, 'ResetPassword']);

Route::post('/mobile_apps_version/check_version', [MobileAppsVersionController::class, 'CheckVersion']);
Route::get('/mobile_apps_version/donwload_mobile_apps', [MobileAppsVersionController::class, 'DownloadMobileApps']);
Route::get('/mobile_apps_version/qr_code_mobile_apps', [MobileAppsVersionController::class, 'QRCodeMobileApps']);

// Route::group(['middleware' => ['apikey.auth']], function() {
//     Route::get('/tracking_driver/get_location_driver', [TrackingDriverController::class, 'GetLocationDriver']);
// });

Route::group(['middleware' => ['jwt.auth']], function() {

    //=========================mobile apps=========================
    Route::post('/user_management/get_data_driver_by_id', [UserManagementController::class, 'GetDataDriverById']);
    Route::post('/user_management/logout', [UserManagementController::class, 'Logout']);
    Route::post('/user_management/change_password', [UserManagementController::class, 'ChangePassword']);

    Route::post('/list_order/progress_order', [ListOrderManagementController::class, 'ListProgressOrder']);
    Route::post('/list_order/all_order', [ListOrderManagementController::class, 'ListAllOrder']);
    Route::post('/list_order/order_origin', [ListOrderManagementController::class, 'ListOrderOrigin']);
    Route::post('/list_order/order_dest', [ListOrderManagementController::class, 'ListOrderDest']);

    Route::post('/detail_order/detail', [DetailOrderManagementController::class, 'DetailOrder']);
    // Route::post('/detail_order/detail_origin', [DetailOrderManagementController::class, 'DetailOrderOrigin');
    // Route::post('/detail_order/detail_dest', [DetailOrderManagementController::class, 'DetailOrderDest');
    Route::post('/detail_order/update_order', [DetailOrderManagementController::class, 'UpdateOrder']);

    Route::post('/manifest/get_data_odometer', [ManifestController::class, 'GetDataOdometer']);
    Route::post('/manifest/update_odometer', [ManifestController::class, 'UpdateOdometer']);

    Route::post('/tracking_driver/update_location', [TrackingDriverController::class, 'UpdateLocationDriver']);
    //=========================mobile apps=========================

    ////=========================GPS CARTRACK=========================
    Route::get('cartrack/vahicle_tracking',[CartrackVehiclesController::class, 'getVehicleTracking']);
    ////=========================END GPS CARTRACK=========================



    ////=========================Web=========================
    Route::get('/master_areas/get_data', [MasterAreasController::class, 'GetData']);
    Route::post('/master_areas/create', [MasterAreasController::class, 'Create']);
    Route::post('/master_areas/update', [MasterAreasController::class, 'Update']);
    Route::post('/master_areas/delete', [MasterAreasController::class, 'Delete']);
    Route::post('/master_areas/get_data_by_id', [MasterAreasController::class, 'GetDataById']);
    Route::post('/master_areas/search_data', [MasterAreasController::class, 'SearchData']);

    Route::post('/message_broker/send_notif_tracking', [MessageBrokerController::class, 'SendNotifTracking']);
    Route::post('/message_broker/send_notif_single_token', [MessageBrokerController::class, 'SendNotifSingleToken']);
    Route::post('/message_broker/get_data', [MessageBrokerController::class, 'GetData']);

    Route::post('/mobile_apps_version/create', [MobileAppsVersionController::class, 'Create']);
    Route::post('/mobile_apps_version/update', [MobileAppsVersionController::class, 'Update']);
    Route::post('/mobile_apps_version/delete', [MobileAppsVersionController::class, 'Delete']);
    Route::get('/mobile_apps_version/get_data', [MobileAppsVersionController::class, 'GetData']);
    Route::post('/mobile_apps_version/get_data_by_id', [MobileAppsVersionController::class, 'GetDataById']);

    Route::post('/banner/create', [BannerController::class, 'Create']);
    Route::post('/banner/update', [BannerController::class, 'Update']);
    Route::post('/banner/delete', [BannerController::class, 'Delete']);
    Route::get('/banner/get_data', [BannerController::class, 'GetData']);
    Route::post('/banner/get_data_by_id', [BannerController::class, 'GetDataById']);

    Route::post('/detail_order/detail_origin', [DetailOrderManagementController::class, 'DetailOrderOrigin']);
    Route::post('/detail_order/detail_dest', [DetailOrderManagementController::class, 'DetailOrderDest']);

    Route::post('/master_user/change_image', [MasterUserController::class, 'ChangeImage']);
    Route::post('/master_user/create', [MasterUserController::class, 'Create']);
    Route::post('/master_user/update', [MasterUserController::class, 'Update']);
    Route::post('/master_user/delete', [MasterUserController::class, 'Delete']);
    Route::post('/master_user/get_data', [MasterUserController::class, 'GetData']);
    Route::post('/master_user/get_data_by_id', [MasterUserController::class, 'GetDataById']);
    Route::post('/master_user/activated', [MasterUserController::class, 'Activated']);
    Route::post('/master_user/change_password', [MasterUserController::class, 'ChangePassword']);

    Route::post('/company/create', [MasterCompanyController::class, 'Create']);
    Route::post('/company/update', [MasterCompanyController::class, 'Update']);
    Route::post('/company/delete', [MasterCompanyController::class, 'Delete']);
    Route::get('/company/get_data', [MasterCompanyController::class, 'GetData']);
    Route::post('/company/get_data_by_id', [MasterCompanyController::class, 'GetDataById']);
    Route::post('/company/search_data', [MasterCompanyController::class, 'SearchData']);

    Route::post('/role/create', [MasterRoleController::class, 'Create']);
    Route::post('/role/update', [MasterRoleController::class, 'Update']);
    Route::post('/role/delete', [MasterRoleController::class, 'Delete']);
    Route::get('/role/get_data', [MasterRoleController::class, 'GetData']);
    Route::post('/role/search_data', [MasterRoleController::class, 'SearchData']);
    Route::post('/role/get_data_by_id', [MasterRoleController::class, 'GetDataById']);
    Route::get('/role/get_data_menu', [MasterRoleController::class, 'GetMenu']);
    Route::post('/role/insert_menu', [MasterRoleController::class, 'InsertMenu']);

    Route::post('/setting/create', [SettingController::class, 'Create']);
    Route::post('/setting/update', [SettingController::class, 'Update']);
    Route::post('/setting/delete', [SettingController::class, 'Delete']);
    Route::get('/setting/get_data', [SettingController::class, 'GetData']);
    Route::post('/setting/get_data_by_id', [SettingController::class, 'GetDataById']);
    Route::post('/setting/get_data_by_code', [SettingController::class, 'GetDataByCode']);

    Route::post('/master_uom/create', [MasterUomController::class, 'Create']);
    Route::post('/master_uom/update', [MasterUomController::class, 'Update']);
    Route::post('/master_uom/delete', [MasterUomController::class, 'Delete']);
    Route::get('/master_uom/get_data', [MasterUomController::class, 'GetData']);
    Route::post('/master_uom/get_data_by_id', [MasterUomController::class, 'GetDataById']);
    Route::post('/master_uom/get_data_by_code', [MasterUomController::class, 'GetDataByCode']);
    Route::get('/master_uom/search_data', [MasterUomController::class, 'SearchData']);

    Route::post('/master_cost_component/create', [MasterCostComponentController::class, 'Create']);
    Route::post('/master_cost_component/update', [MasterCostComponentController::class, 'Update']);
    Route::post('/master_cost_component/delete', [MasterCostComponentController::class, 'Delete']);
    Route::get('/master_cost_component/get_data', [MasterCostComponentController::class, 'GetData']);
    Route::post('/master_cost_component/get_data_by_id', [MasterCostComponentController::class, 'GetDataById']);
    Route::get('/master_cost_component/search', [MasterCostComponentController::class, 'SearchData']);

    Route::post('/master_customer/create', [MasterCustomerController::class, 'Create']);
    Route::post('/master_customer/update', [MasterCustomerController::class, 'Update']);
    Route::post('/master_customer/delete', [MasterCustomerController::class, 'Delete']);
    Route::get('/master_customer/get_data', [MasterCustomerController::class, 'GetData']);
    Route::post('/master_customer/get_data_by_id', [MasterCustomerController::class, 'GetDataById']);
    Route::post('/master_customer/search', [MasterCustomerController::class, 'Search']);

    Route::post('/vehicle/create', [VehicleController::class, 'Create']);
    Route::post('/vehicle/update', [VehicleController::class, 'Update']);
    Route::post('/vehicle/delete', [VehicleController::class, 'Delete']);
    Route::get('/vehicle/get_data', [VehicleController::class, 'GetData']);
    Route::post('/vehicle/get_data_by_id', [VehicleController::class, 'GetDataById']);
    Route::get('/vehicle/export', [VehicleController::class, 'Export']);
    Route::get('/vehicle/search', [VehicleController::class, 'SearchData']);

    Route::post('/vehicle_type/create', [VehicleTypeController::class, 'Create']);
    Route::post('/vehicle_type/update', [VehicleTypeController::class, 'Update']);
    Route::post('/vehicle_type/delete', [VehicleTypeController::class, 'Delete']);
    Route::get('/vehicle_type/get_data', [VehicleTypeController::class, 'GetData']);
    Route::post('/vehicle_type/get_data_by_id', [VehicleTypeController::class, 'GetDataById']);
    Route::get('/vehicle_type/search', [VehicleTypeController::class, 'Search']);

    Route::post('/driver/create', [DriverController::class, 'Create']);
    Route::post('/driver/update', [DriverController::class, 'Update']);
    Route::post('/driver/delete', [DriverController::class, 'Delete']);
    Route::get('/driver/get_data', [DriverController::class, 'GetData']);
    Route::post('/driver/get_data_by_id', [DriverController::class, 'GetDataById']);
    Route::get('/driver/search', [DriverController::class, 'Search']);

    Route::post('/transporter_rate/create', [TransporterRateController::class, 'Create']);
    Route::post('/transporter_rate/update', [TransporterRateController::class, 'Update']);
    Route::post('/transporter_rate/delete', [TransporterRateController::class, 'Delete']);
    Route::get('/transporter_rate/get_data', [TransporterRateController::class, 'GetData']);
    Route::post('/transporter_rate/get_data_by_id', [TransporterRateController::class, 'GetDataById']);

    Route::post('/transporter/create', [TransporterController::class, 'Create']);
    Route::post('/transporter/update', [TransporterController::class, 'Update']);
    Route::post('/transporter/delete', [TransporterController::class, 'Delete']);
    Route::get('/transporter/get_data', [TransporterController::class, 'GetData']);
    Route::post('/transporter/get_data_by_id', [TransporterController::class, 'GetDataById']);
    Route::get('/transporter/search', [TransporterController::class, 'Search']);

    Route::post('/master_client/create', [MasterClientController::class, 'Create']);
    Route::post('/master_client/update', [MasterClientController::class, 'Update']);
    Route::post('/master_client/delete', [MasterClientController::class, 'Delete']);
    Route::get('/master_client/get_data', [MasterClientController::class, 'GetData']);
    Route::post('/master_client/get_data_by_id', [MasterClientController::class, 'GetDataById']);
    Route::get('/master_client/search', [MasterClientController::class, 'SearchData']);

    Route::post('/master_vendor/create', [MasterVendorController::class, 'Create']);
    Route::post('/master_vendor/update', [MasterVendorController::class, 'Update']);
    Route::post('/master_vendor/delete', [MasterVendorController::class, 'Delete']);
    Route::get('/master_vendor/get_data', [MasterVendorController::class, 'GetData']);
    Route::post('/master_vendor/get_data_by_id', [MasterVendorController::class, 'GetDataById']);
    Route::get('/master_vendor/search', [MasterVendorController::class, 'SearchData']);

    Route::post('/mechanic/create', [MechanicController::class, 'Create']);
    Route::post('/mechanic/update', [MechanicController::class, 'Update']);
    Route::post('/mechanic/delete', [MechanicController::class, 'Delete']);
    Route::get('/mechanic/get_data', [MechanicController::class, 'GetData']);
    Route::post('/mechanic/get_data_by_id', [MechanicController::class, 'GetDataById']);
    Route::get('/mechanic/search', [MechanicController::class, 'SearchData']);

    Route::post('/client_rate/create', [ClientRateController::class, 'Create']);
    Route::post('/client_rate/update', [ClientRateController::class, 'Update']);
    Route::post('/client_rate/delete', [ClientRateController::class, 'Delete']);
    Route::get('/client_rate/get_data', [ClientRateController::class, 'GetData']);
    Route::post('/client_rate/get_data_by_id', [ClientRateController::class, 'GetDataById']);

    Route::post('/truck_accident/create', [TruckAccidentController::class, 'Create']);
    Route::post('/truck_accident/update', [TruckAccidentController::class, 'Update']);
    Route::post('/truck_accident/delete', [TruckAccidentController::class, 'Delete']);
    Route::get('/truck_accident/get_data', [TruckAccidentController::class, 'GetData']);
    Route::post('/truck_accident/get_data_by_id', [TruckAccidentController::class, 'GetDataById']);

    Route::post('/service_task/create', [ServiceTaskController::class, 'Create']);
    Route::post('/service_task/update', [ServiceTaskController::class, 'Update']);
    Route::post('/service_task/delete', [ServiceTaskController::class, 'Delete']);
    Route::get('/service_task/get_data', [ServiceTaskController::class, 'GetData']);
    Route::post('/service_task/get_data_by_id', [ServiceTaskController::class, 'GetDataById']);
    Route::get('/service_task/search', [ServiceTaskController::class, 'SearchData']);

    Route::post('/service_task_entries/create', [ServiceTaskEntriesController::class, 'Create']);
    Route::post('/service_task_entries/update', [ServiceTaskEntriesController::class, 'Update']);
    Route::post('/service_task_entries/delete', [ServiceTaskEntriesController::class, 'Delete']);
    Route::get('/service_task_entries/get_data', [ServiceTaskEntriesController::class, 'GetData']);
    Route::post('/service_task_entries/get_data_by_id', [ServiceTaskEntriesController::class, 'GetDataById']);

    Route::post('/service_order/create', [ServiceOrderController::class, 'Create']);
    Route::post('/service_order/update', [ServiceOrderController::class, 'Update']);
    Route::post('/service_order/delete', [ServiceOrderController::class, 'Delete']);
    Route::get('/service_order/get_data', [ServiceOrderController::class, 'GetData']);
    Route::post('/service_order/get_data_by_id', [ServiceOrderController::class, 'GetDataById']);
    Route::get('/service_order/search_status', [ServiceOrderController::class, 'SearchStatus']);
    Route::get('/service_order/search_type', [ServiceOrderController::class, 'SearchType']);
    Route::post('/service_order/get_total_amount', [ServiceOrderController::class, 'GetTotalAmount']);

    Route::post('/accident_type/create', [AccidentTypeController::class, 'Create']);
    Route::post('/accident_type/update', [AccidentTypeController::class, 'Update']);
    Route::post('/accident_type/delete', [AccidentTypeController::class, 'Delete']);
    Route::get('/accident_type/get_data', [AccidentTypeController::class, 'GetData']);
    Route::post('/accident_type/get_data_by_id', [AccidentTypeController::class, 'GetDataById']);
    Route::get('/accident_type/search', [AccidentTypeController::class, 'SearchData']);

    Route::get('/dashboard/get_asset_data', [DashboardController::class, 'getAssetData']);
    Route::post('/dashboard/get_shipment_activity', [DashboardController::class, 'getShipmentActivity']);
    Route::post('/dashboard/get_traffic_monitoring', [DashboardController::class, 'getTrafficMonitoringStatus']);
    Route::post('/dashboard/get_trucking_order', [DashboardController::class, 'getTruckingOrder']);
    Route::post('/dashboard/get_summary_data', [DashboardController::class, 'getSummaryData']);
    Route::get('/dashboard/get_data_unit_service', [DashboardController::class, 'getDataUnitService']);

    Route::post('/owned/create', [OwnedController::class, 'Create']);
    Route::post('/owned/update', [OwnedController::class, 'Update']);
    Route::post('/owned/delete', [OwnedController::class, 'Delete']);
    Route::get('/owned/get_data', [OwnedController::class, 'GetData']);
    Route::post('/owned/get_data_by_id', [OwnedController::class, 'GetDataById']);

    Route::get('/type_taxable/search', [TypeTaxableController::class, 'SearchData']);

    Route::get('/trucking_order/get_data', [TruckingOrderController::class, 'GetData']);
    Route::post('/trucking_order/get_data_by_id', [TruckingOrderController::class, 'GetDataById']);
    Route::post('/trucking_order/create', [TruckingOrderController::class, 'Create']);
    Route::post('/trucking_order/update', [TruckingOrderController::class, 'Update']);
    Route::post('/trucking_order/delete', [TruckingOrderController::class, 'Delete']);
    Route::get('/trucking_order/search_data', [TruckingOrderController::class, 'SearchData']);

    Route::get('/transport_order/get_data', [TransportOrderController::class, 'GetData']);
    Route::post('/transport_order/get_data_by_id', [TransportOrderController::class, 'GetDataById']);
    Route::post('/transport_order/create', [TransportOrderController::class, 'Create']);
    Route::post('/transport_order/update', [TransportOrderController::class, 'Update']);
    Route::post('/transport_order/delete', [TransportOrderController::class, 'Delete']);

    Route::get('/route_planning/get_data_transport_order', [RoutePlanningController::class, 'GetDataTransportOrder']);
    Route::get('/route_planning/get_data_manifest', [RoutePlanningController::class, 'GetDataManifest']);
    Route::get('/route_planning/search_manifest', [RoutePlanningController::class, 'SearchManifest']);
    Route::post('/route_planning/add_transport_order', [RoutePlanningController::class, 'RouteAddTransportOrder']);
    Route::post('/route_planning/unroute_transport_order', [RoutePlanningController::class, 'UnRouteTransportOrder']);
    Route::post('/route_planning/create_manifest', [RoutePlanningController::class, 'CreateManifest']);
    Route::post('/route_planning/get_data_manifest_by_id', [RoutePlanningController::class, 'GetDataManifestById']);
    Route::get('/route_planning/search_data_transport_mode', [RoutePlanningController::class, 'SearchDataTransportMode']);
    Route::post('/route_planning/update_manifest', [RoutePlanningController::class, 'UpdateManifest']);
    Route::post('/route_planning/confirm_manifest', [RoutePlanningController::class, 'RouteConfirmManifest']);
    Route::post('/route_planning/unconfirm_manifest', [RoutePlanningController::class, 'RouteUnConfirmManifest']);
    Route::post('/route_planning/update_vehicle_rate', [RoutePlanningController::class, 'UpdateVehicleRate']);
    Route::post('/route_planning/delete', [RoutePlanningController::class, 'Delete']);
    Route::post('/route_planning/upload_file', [RoutePlanningController::class, 'UploadFile']);

    Route::get('/ring_code/search_data', [RingCodeController::class, 'SearchData']);

    Route::get('/component_entries/get_data', [ComponentEntriesController::class, 'GetData']);
    Route::post('/component_entries/create', [ComponentEntriesController::class, 'Create']);
    Route::post('/component_entries/delete', [ComponentEntriesController::class, 'Delete']);

    Route::get('/traffic_monitoring/get_data_by_manifest', [TrafficMonitoringController::class, 'GetDataByManifest']);
    Route::post('/traffic_monitoring/get_data_by_id', [TrafficMonitoringController::class, 'GetDataById']);
    Route::post('/traffic_monitoring/update_time_window', [TrafficMonitoringController::class, 'UpdateTimeWindow']);
    Route::get('/traffic_monitoring/get_traffic_monitoring', [TrafficMonitoringController::class, 'GetTrafficMonitoring']);

    Route::get('/pod/get_data', [PODController::class, 'GetData']);
    Route::get('/pod/get_pod_code', [PODController::class, 'GetPODCode']);
    Route::post('/pod/get_data_by_id', [PODController::class, 'GetDataById']);
    Route::post('/pod/update', [PODController::class, 'Update']);
    Route::post('/pod/cancel_pod', [PODController::class, 'CancelPod']);

    Route::get('/self_billing/get_data', [SelfBillingController::class, 'GetData']);
    Route::get('/self_billing/get_manifest', [SelfBillingController::class, 'GetManifest']);
    Route::post('/self_billing/create', [SelfBillingController::class, 'Create']);
    Route::post('/self_billing/update', [SelfBillingController::class, 'Update']);
    Route::post('/self_billing/delete', [SelfBillingController::class, 'Delete']);
    Route::post('/self_billing/get_data_by_id', [SelfBillingController::class, 'GetDataById']);
    Route::post('/self_billing/upload_file', [SelfBillingController::class, 'UploadFile']);

    Route::get('/sales_invoice/get_data', [SalesInvoiceController::class, 'GetData']);
    Route::get('/sales_invoice/get_manifest', [SalesInvoiceController::class, 'GetManifest']);
    Route::post('/sales_invoice/create', [SalesInvoiceController::class, 'Create']);
    Route::post('/sales_invoice/update', [SalesInvoiceController::class, 'Update']);
    Route::post('/sales_invoice/delete', [SalesInvoiceController::class, 'Delete']);
    Route::post('/sales_invoice/get_data_by_id', [SalesInvoiceController::class, 'GetDataById']);
    Route::post('/sales_invoice/upload_file', [SalesInvoiceController::class, 'UploadFile']);

    Route::post('/tracking_driver/get_location_driver', [TrackingDriverController::class, 'GetLocationDriver']);

    Route::get('/history_change_load/get_data', [HistoryChangeLoadController::class, 'GetData']);
    Route::post('/history_change_load/create', [HistoryChangeLoadController::class, 'Create']);

    Route::post('/dedicated_rate/create', [DedicatedRateController::class, 'Create']);
    Route::post('/dedicated_rate/update', [DedicatedRateController::class, 'Update']);
    Route::post('/dedicated_rate/delete', [DedicatedRateController::class, 'Delete']);
    Route::get('/dedicated_rate/get_data', [DedicatedRateController::class, 'GetData']);
    Route::post('/dedicated_rate/get_data_by_id', [DedicatedRateController::class, 'GetDataById']);
////=========================Web=========================

//=========================procedure scrypt=========================
Route::post('/procedure/close_traffic_monitoring', [ProcedureController::class, 'CloseTrafficMonitoring']);
//=========================procedure scrypt=========================


});
