<?php
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
Route::get('/', function () {
    return view('welcome');
});
//form dashboard URL
Route::get('/forms', 'FormController@all_forms');
Route::get('/request_accept/{id}', 'FormController@request_accept');


Route::get('/ajax/get-supervisor-end-user', 'FunctionController@getEndUsers')->name('getEndUsers');
Route::post('/search-enduser', 'EndUserController@postSearch')->name('postSearch');
Route::get('/searchresult-enduser', 'EndUserController@searchResult')->name('searchResult');

Route::post('/search-daily-activity', 'FunctionController@dailyactivitySearch')->name('dailyactivitySearch');

Route::post('/search-message', 'MessageController@postSearchMessage')->name('postSearchMessage');
//ATTENDANCES ROUTE
Route::post('/search-attendance', 'AttendanceController@postSearchAttendance')->name('postSearchAttendance');
Route::post('/search-attendancetype2', 'AttendanceType2Controller@postSearchAttendanceType2')->name('postSearchAttendanceType2');

//btal sales order
Route::post('/search-sales_order', 'SalesOrderController@postSearchSalesOrder')->name('postSearchSalesOrder');
Route::post('/search-sales_order_bydate', 'SalesOrderController@postSearchSalesOderdate')->name('postSearchSalesOderdate');

//Sales Order for BENGAL
Route::post('/search-cart', 'CartController@postSearchCart')->name('postSearchCart');
Route::post('/search-cart_dealer', 'CartController@postSearchCartbyDealer')->name('postSearchCartbyDealer');
Route::post('/search-cart_subagent', 'CartController@postSearchCartbySubAgent')->name('postSearchCartbySubAgent');


//farm visit layer for BENGAL
Route::post('/search-farm_visit_layer', 'FarmVisitLayerController@postSearchFarmVisitLayer')->name('postSearchFarmVisitLayer');


// layer performance
Route::post('/search-layer_performance', 'LayerPerformanceController@postSearchLayerPerformance')->name('postSearchLayerPerformance');
Route::post('/search-layer_performance_feedmill', 'LayerPerformanceController@postSearchLayerPerformancefeedmill')->name('postSearchLayerPerformancefeedmill');
Route::post('/search-layer_performance_bybreed', 'LayerPerformanceController@postSearchLayerPerformancebreed')->name('postSearchLayerPerformancebreed');
Route::post('/search-layer_performance_bydate', 'LayerPerformanceController@postSearchLayerPerformancedate')->name('postSearchLayerPerformancedate');

//layer life cycle
Route::post('/search-layer_life_cycle', 'LayerLifeCycleController@postSearchLayerLifeCycles')->name('postSearchLayerLifeCycles');
Route::post('/search-layer_life_cycle_bydate', 'LayerLifeCycleController@postSearchLayerLifeCycledate')->name('postSearchLayerLifeCycledate');
Route::post('/search-layer_life_cycle_farm', 'LayerLifeCycleController@postSearchLayerLifeCyclefarm')->name('postSearchLayerLifeCyclefarm');

//fcr after sale
Route::post('/search-fcr_after_sale', 'FcrAfterSaleController@postSearchFcrAfterSale')->name('postSearchFcrAfterSale');
Route::post('/search-fcr_after_sale_feedmill', 'FcrAfterSaleController@postSearchFcrAfterSalefeedmill')->name('postSearchFcrAfterSalefeedmill');
Route::post('/search-fcr_after_sale_bybreed', 'FcrAfterSaleController@postSearchFcrAfterSalebreed')->name('postSearchFcrAfterSalebreed');
Route::post('/search-fcr_after_sale_bydate', 'FcrAfterSaleController@postSearchFcrAfterSaledate')->name('postSearchFcrAfterSaledate');

//broiler life cycle
Route::post('/search-broiler_life_cycle', 'BroilerLifeCycleController@postSearchBroilerLifeCycles')->name('postSearchBroilerLifeCycles');
Route::post('/search-broiler_life_cycle_bydate', 'BroilerLifeCycleController@postSearchBroilerLifeCycledate')->name('postSearchBroilerLifeCycledate');
Route::post('/search-broiler_life_cycle_farm', 'BroilerLifeCycleController@postSearchBroilerLifeCyclefarm')->name('postSearchBroilerLifeCyclefarm');


//expense
Route::post('/search-advance_expense', 'AdvanceExpenseController@postSearchExpense')->name('postSearchExpense');
Route::post('/search-advance_expense-date', 'AdvanceExpenseController@postSearchExpenseforDate')->name('postSearchExpenseforDate');

//expense for sunny feed
Route::post('/search-advance_expenses_type2', 'AdvanceExpenseType2Controller@postSearchExpenseType2')->name('postSearchExpenseType2');


Route::post('/search-dealer_hit', 'DealerHitController@postSearchDealerHits')->name('postSearchDealerHits');
Route::post('/search-outlet_hit', 'OutletHitController@postSearchOutletHits')->name('postSearchOutletHits');
Route::post('/search-outlet', 'OutletController@postSearchOutlets')->name('postSearchOutlets');
Route::post('/search-subagent', 'SubAgentController@postSearchSubAgents')->name('postSearchSubAgents');
Route::post('/search-farm', 'FarmController@postSearchFarms')->name('postSearchFarms');
Route::post('/search-tour_plan', 'ActualTourPlanType2Controller@postSearchTourPlan')->name('postSearchTourPlan');
Route::post('/search-advance_tour_plan', 'AdvanceTourPlanController@postSearchAdvanceTourPlan')->name('postSearchAdvanceTourPlan');
Route::post('/search-activity_suggestion_location', 'ActivitySuggestionLocationController@postSearchLocation')->name('postSearchLocation');
Route::post('/search-advance_location', 'AdvanceActivitySuggestionLocationController@postSearchAdvanceLocation')->name('postSearchAdvanceLocation');

//fcr before sale
Route::post('/search-fcr_before_sale', 'FcrBeforeSaleController@postSearchFcrBeforeSale')->name('postSearchFcrBeforeSale');
Route::post('/search-fcr_before_sale_feedmill', 'FcrBeforeSaleController@postSearchFcrBeforeSalefeedmill')->name('postSearchFcrBeforeSalefeedmill');
Route::post('/search-fcr_before_sale_bybreed', 'FcrBeforeSaleController@postSearchFcrBeforeSalebreed')->name('postSearchFcrBeforeSalebreed');
Route::post('/search-fcr_before_sale_bydate', 'FcrBeforeSaleController@postSearchFcrBeforeSaledate')->name('postSearchFcrBeforeSaledate');

Route::get('/location', 'FunctionController@location')->name('location');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/daily', 'DailyController@index')->name('daily');
Route::group(['middleware' => ['role:admin', 'auth']], function () {
    Route::resource('users', 'UsersController');
});

Route::post('/search-userlist', 'UserListController@searchuserlist')->name('searchuserlist');
Route::resource('userlists', 'UserListController');
Route::resource('sales_orders', 'SalesOrderController');
Route::resource('devices', 'DeviceController');
Route::resource('device_admin', 'DeviceAdminController');
Route::resource('device_user', 'DeviceUserController');
Route::resource('version_notifications', 'VersionNotificationController');
Route::resource('clients', 'ClientController');
Route::resource('endusers', 'EndUserController');
Route::resource('invitations', 'InvitationController');
Route::resource('unused_invitations', 'UnusedInvitationController');
Route::resource('used_invitations', 'UsedInvitationController');
Route::resource('payments', 'PaymentController');
Route::resource('receipts', 'ReceiptController');
Route::resource('locations', 'LocationController');
Route::resource('messages', 'MessageController');
Route::resource('statuses', 'StatusController');
Route::resource('onlinestatuses', 'OnlineStatusController');
Route::resource('offlinestatuses', 'OfflineStatusController');
Route::resource('activities', 'ActivityController');
Route::resource('supervisors', 'SupervisorController');
Route::resource('agents', 'AgentController');
Route::resource('subagents', 'SubAgentController');
Route::resource('visit_details', 'VisitDetailController');
Route::resource('client_setups', 'ClientSetupController');
Route::resource('tasks', 'TaskController');
Route::resource('task_messages', 'TaskMessageController');
Route::resource('TaskStatus', 'TaskStatusController');
Route::resource('attendances', 'AttendanceController');
Route::resource('reports', 'ReportController');
Route::resource('advance_expenses', 'AdvanceExpenseController');
Route::resource('role_supervisors', 'RoleSupervisorController');
Route::resource('dealers', 'DealerController');
Route::resource('dealer_hits', 'DealerHitController');
Route::resource('outlet_hits', 'OutletHitController');
Route::resource('advance_dealer_hits', 'AdvanceDealerHitController');
Route::resource('dealer_endusers', 'DealerEnduserController');
Route::resource('fcr_before_sales', 'FcrBeforeSaleController');
Route::resource('fcr_after_sales', 'FcrAfterSaleController');
Route::resource('conveyances', 'ConveyanceController');
Route::resource('layer_performances', 'LayerPerformanceController');
Route::resource('layer_farms', 'LayerFarmController');
Route::resource('broiler_farms', 'BroilerFarmController');
Route::resource('layer_life_cycles', 'LayerLifeCycleController');
Route::resource('activity_suggestions', 'ActivitySuggestionController');
Route::resource('activity_suggestion_locations', 'ActivitySuggestionLocationController');
Route::resource('fcr_reports', 'FcrController');
Route::resource('monthly_sales_targets', 'MonthlySalesTargetController');
Route::resource('logs', 'LogController');
Route::resource('app_features', 'AppFeatureController');
Route::resource('farms', 'FarmController');
Route::resource('feeds', 'FeedController');
Route::resource('feed_mills', 'FeedMillController');
Route::resource('breeds', 'BreedController');
Route::resource('company_props', 'CompanyPropertyController');
Route::resource('push_notifications', 'PushNotificationController');
Route::resource('calendar_events', 'CalendarEventController');
Route::resource('time_tables', 'TimeTableController');
Route::resource('enduser_designations', 'EnduserDesignationController');
Route::resource('enduser_salaries', 'EnduserSalaryController');
Route::resource('report_images', 'ReportImageController');
Route::resource('problem_submissions', 'ProblemSubmissionController');
Route::resource('comments', 'CommentController');
Route::resource('packages', 'PackageController');
Route::resource('user_leave_applications', 'UserLeaveApplicationController');
Route::resource('client_leaves', 'ClientLeaveController');
Route::resource('aci_dealers', 'AciDealerController');
Route::resource('aci_farms', 'AciFarmController');
Route::resource('feedbacks', 'FeedbackController');
Route::resource('dealer_visits', 'DealerVisitController');
Route::resource('vaccinations', 'VaccinationController');
Route::resource('hierarchys', 'HierarchyController');

Route::resource('advance_subagents', 'AdvanceSubAgentController');
Route::resource('advance_farms', 'AdvanceFarmController');
Route::resource('features', 'FeatureController');
Route::resource('product_types', 'ProductTypeController');

Route::resource('outlets', 'OutletController');
Route::resource('divisions', 'MacroDivisionController');
Route::resource('districts', 'MacroDistrictController');
Route::resource('companies', 'CompanyController');
Route::resource('company_visits', 'CompanyVisitController');

Route::resource('zones', 'ZoneController');
Route::resource('regions', 'RegionController');
Route::resource('divisions', 'DivisionController');
Route::resource('districts', 'DistrictController');
Route::resource('upozilas', 'UpozilaController');
Route::resource('advance_dealers', 'AdvanceDealerController');
Route::resource('farm_visit_layers', 'FarmVisitLayerController');
Route::resource('farm_visit_broilers', 'FarmVisitBroilerController');
Route::resource('farm_visit_fishs', 'FarmVisitFishController');

Route::resource('actual_tour_plans', 'ActualTourPlanController');
Route::resource('advance_tour_plans', 'AdvanceTourPlanController');
Route::resource('tour_plans', 'ActualTourPlanType2Controller');
Route::resource('advance_tour_plans_type2', 'AdvanceTourPlanType2Controller');

Route::resource('notification_updates', 'NotificationUpdateController');
Route::resource('remainders', 'RemainderController');
Route::resource('advance_expenses_type2', 'AdvanceExpenseType2Controller');


Route::resource('advance_tasks', 'AdvanceTaskController');
Route::resource('phone_changes', 'PhoneChangeController');
Route::resource('carts', 'CartController');
Route::resource('products', 'ProductController');
Route::resource('payment_types', 'PaymentTypeController');
Route::resource('advance_payments', 'AdvancePaymentController');

Route::resource('daily_activities', 'DailyActivityController');
Route::resource('visiting_agents', 'VisitedAgentController');
Route::resource('visiting_farmers', 'VisitedFarmerController');
Route::resource('visiting_sub_agents', 'VisitedSubAgentController');

Route::resource('daily_reports', 'DailyReportController');
Route::resource('broiler_life_cycles', 'BroilerLifeCycleController');
Route::resource('dealer_sales_details', 'DealerSalesInfoController');



Route::resource('client_server_keys', 'ClientServerKeyController');
Route::resource('photos', 'PhotoController');
Route::resource('archives', 'ArchivesController');

Route::post('employees/update/{id}','EmployeeController@update');

Route::resource('attendances', 'AttendanceController');
Route::resource('attendancetype2', 'AttendanceType2Controller');
Route::get('myprofile/{id}','UserController@myProfile');
Route::put('myprofile/update/{id}','UserController@myprofileupdate');
Route::resource('role','UserRoleController');
Route::resource('time_tables', 'TimeTableController');
Route::resource('shifts', 'ShiftController');
Route::resource('employee_shifts', 'EmployeeShiftController');
Route::get('calendar', 'EventController@index');
Route::resource('calendars', 'CalendarController');
Route::get('date', 'EventController@getFridays');
Route::resource('employee_designations', 'EmployeeDesignationController');
Route::resource('designations', 'DesignationController');
Route::resource('advance_locations', 'AdvanceActivitySuggestionLocationController');
