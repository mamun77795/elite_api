<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//elite API
Route::get('/total-sales', 'DealerUserControllerApi@test_api');
Route::get('/account-summary', 'DealerUserControllerApi@account_summary');
//account statement insert api through elite erp
Route::get('/account-statement', 'DealerUserControllerApi@account_statement');
Route::get('/invoice', 'DealerUserControllerApi@invoice');
Route::get('/add-dealer', 'DealerUserControllerApi@add_dealer');
 //SMS Gateway
Route::post('/token', 'DealerUserControllerApi@token');
Route::post('/new-token', 'DealerUserControllerApi@newToken');
//credit note insert api through elite erp
Route::get('/credit-note', 'DealerUserControllerApi@credit_note');
// eTracker USER API
//Route::post('/login', 'LoginControllerApi@login');


Route::post('/account-statement-date-history', 'DealerUserControllerApi@account_statement_date_history');

Route::post('/credit-note-date-history', 'DealerUserControllerApi@credit_note_date_history');
Route::post('/registration', 'DealerUserControllerApi@registration');
Route::post('/login', 'DealerUserControllerApi@login');
Route::post('/forgot-password', 'DealerUserControllerApi@forget_password');
//old update password api
Route::post('/update-password', 'DealerUserControllerApi@update_password');
//new update password api
Route::post('/update-password-by-phone', 'DealerUserControllerApi@new_update_password');
Route::post('/update-token', 'DealerUserControllerApi@update_token');
Route::post('/get-profile', 'DealerUserControllerApi@get_profile');
Route::post('/update-profile', 'DealerUserControllerApi@update_profile');
Route::post('/get-initial-info-profile', 'DealerUserControllerApi@get_initial_info_profile');
Route::post('/get-district', 'DealerUserControllerApi@get_district');
Route::post('/get-thana', 'DealerUserControllerApi@get_thana');
Route::post('/dealer-dashboard', 'DealerUserControllerApi@dealer_dashboard');
Route::post('/painter-dashboard', 'DealerUserControllerApi@painter_dashboard');
Route::get('/painter-total-point-histories', 'DealerUserControllerApi@painterTotalPointHistories');
Route::post('/notification', 'DealerUserControllerApi@notification');
Route::post('/promotional-offers', 'DealerUserControllerApi@promotional_offers');
Route::post('/claim-token', 'DealerUserControllerApi@get_dealer_claim_points');
Route::post('/get-redeem-info', 'DealerUserControllerApi@get_redeem_info');
//unused api
Route::post('/get-redeem-historys', 'DealerUserControllerApi@get_redeem_history');
Route::post('/get-claim-historyss', 'DealerUserControllerApi@get_claim_history');
//redeem history api
Route::post('/get-redeem-history', 'DealerUserControllerApi@get_redeem_details');
//scan history api
Route::post('/get-claim-history', 'DealerUserControllerApi@get_claim_details');
Route::post('/get-claim-details-this-month', 'DealerUserControllerApi@get_claim_details_this_month');
Route::post('/get-claim-details-this-year', 'DealerUserControllerApi@get_claim_details_this_year');
Route::post('/purchase-info', 'DealerUserControllerApi@purchase_info');
Route::post('/get-purchase-details', 'DealerUserControllerApi@get_purchase_details');
Route::post('/get-purchase-historyss', 'DealerUserControllerApi@get_purchase_history');
Route::post('/points', 'DealerUserControllerApi@points');
Route::post('/get-transaction-history', 'DealerUserControllerApi@get_transaction_history');
//unused api
Route::post('/volume-transfer-initial-info', 'DealerUserControllerApi@volume_transfer_initil_info');
Route::post('/get-product-list', 'DealerUserControllerApi@get_product_list');
Route::post('/purchase-initial-info', 'DealerUserControllerApi@purchase_initil_info');
Route::post('/get-dpu-history', 'DealerUserControllerApi@get_dpu_information');
Route::post('/scan-point', 'DealerUserControllerApi@scan_point');
Route::post('/submit-point', 'DealerUserControllerApi@submit_point');
Route::post('/phone-available', 'DealerUserControllerApi@phone_available');
Route::post('/redeem-point', 'DealerUserControllerApi@redeem_point');
Route::post('/volume-transfer', 'DealerUserControllerApi@volume_transfer');
Route::post('/get-volume-transfer-details', 'DealerUserControllerApi@get_volume_transfer_details');
Route::post('/place-order', 'DealerUserControllerApi@place_order');
Route::post('/add-photo', 'DealerUserControllerApi@add_photo');
Route::post('/get-place-order-historyss', 'DealerUserControllerApi@get_place_order_history');
Route::post('/get-volume-transfer-historyss', 'DealerUserControllerApi@get_volume_transfer_history');
Route::post('/get-claim-history-by-date-range', 'DealerUserControllerApi@get_claim_history_by_date_range');
Route::post('/get-volume-transfer-history-by-date-range', 'DealerUserControllerApi@get_volume_transfer_history_by_date_range');
Route::post('/get-place-order-history-by-date-range', 'DealerUserControllerApi@get_place_order_history_by_date_range');
Route::post('/get-volume-transfer-history', 'DealerUserControllerApi@get_volume_transfer_list');
Route::post('/get-redeem-history-by-date-range', 'DealerUserControllerApi@get_redeem_history_by_date_range');
Route::post('/get-place-order-details', 'DealerUserControllerApi@get_place_order_details');
Route::post('/get-place-order-history', 'DealerUserControllerApi@get_place_order_list');
Route::post('/purchase', 'DealerUserControllerApi@purchase');
Route::post('/get-purchase-history', 'DealerUserControllerApi@get_purchase_list');
Route::post('/get-dpu-details', 'DealerUserControllerApi@get_dpu_details');
Route::post('/get-dpu-history-by-date-range', 'DealerUserControllerApi@get_dpu_history_by_date_range');
Route::post('/get-dpu-historyss', 'DealerUserControllerApi@get_dpu_history');
Route::post('/date-history-for-dealer', 'DealerUserControllerApi@date_history_for_dealer');
Route::post('/update-dpu', 'DealerUserControllerApi@update_dpu');
Route::post('/delete-dpu', 'DealerUserControllerApi@delete_dpu');
Route::post('/get-purchase-history-by-date-range', 'DealerUserControllerApi@get_purchase_history_by_date_range');
Route::post('/get-invoice-history-by-date-range', 'DealerUserControllerApi@get_invoice_history_by_date_range');
Route::post('/get-credit-note-history-by-date-range', 'DealerUserControllerApi@get_credit_note_history_by_date_range');
Route::post('/get-order-history-by-date-range', 'DealerUserControllerApi@get_order_history_by_date_range');
Route::post('/get-account-statement-history-by-date-range', 'DealerUserControllerApi@get_account_statement_history_by_date_range');
Route::post('/get-my-accounts', 'DealerUserControllerApi@get_my_accounts');
Route::post('/get-invoice-details-for-five-order', 'DealerUserControllerApi@get_invoice_details_for_five_order');
Route::post('/get-account-statement', 'DealerUserControllerApi@get_account_statement');
Route::post('/get-credit-note', 'DealerUserControllerApi@get_credit_note');
Route::post('/get-invoice', 'DealerUserControllerApi@get_invoice');
Route::post('/get-order-details', 'DealerUserControllerApi@get_order_details');
Route::post('/get-invoice-details', 'DealerUserControllerApi@get_invoice_details');
Route::post('/get-order-history', 'DealerUserControllerApi@get_order_history');
Route::post('/get-cash-back', 'DealerUserControllerApi@get_cash_back');
Route::post('/get-shopboy-incentive', 'DealerUserControllerApi@get_shopboy_incentive');
Route::post('/get-product-scheme', 'DealerUserControllerApi@get_product_scheme');
Route::post('/get-additional-commission', 'DealerUserControllerApi@get_additional_commission');
Route::post('/get-foreign-tour', 'DealerUserControllerApi@get_foreign_tour');
Route::post('/get-toc', 'DealerUserControllerApi@get_toc');
Route::post('/get-exclusivity', 'DealerUserControllerApi@get_exclusivity');
Route::post('/initial-info-for-product', 'DealerUserControllerApi@initial_info_for_product');
Route::post('/get-pack-size', 'DealerUserControllerApi@get_pack_size');
Route::post('/get-shade-name', 'DealerUserControllerApi@get_shade_name');
Route::post('/get-claim-details-last-year', 'DealerUserControllerApi@get_claim_details_last_year');
Route::post('/get-purchase-point-this-month', 'DealerUserControllerApi@get_purchase_point_this_month');
Route::post('/get-purchase-point-this-year', 'DealerUserControllerApi@get_purchase_point_this_year');
Route::post('/get-bonus-point-this-year', 'DealerUserControllerApi@get_bonus_point_this_year');
Route::post('/get-bonus-point-last-year', 'DealerUserControllerApi@get_bonus_point_last_year');
Route::post('/get-purchase-point-last-year', 'DealerUserControllerApi@get_purchase_point_last_year');
Route::post('/get-redeem-point-this-year', 'DealerUserControllerApi@get_redeem_point_this_year');
Route::post('/get-division-depo-list', 'DealerUserControllerApi@get_division_depo_list');
Route::post('/get-dealer-list', 'DealerUserControllerApi@get_dealer_list');
Route::post('/get-transaction-history-by-date-range', 'DealerUserControllerApi@get_transaction_history_by_date_range');
//unused api list
Route::post('/duplicate_token_info', 'DealerUserControllerApi@duplicate_token_info');
Route::post('/dealer_type_checking', 'DealerUserControllerApi@dealer_type_checking');
Route::post('/dealer_negitive_stock_checkings', 'DealerUserControllerApi@dealer_negitive_stock_checkings');
//unused api list end

//ponner mojud list api
Route::post('/dealer-product-stock-checking', 'DealerUserControllerApi@dealer_product_stock_checking');
//ponner mojud details api
Route::post('/product-stock-details', 'DealerUserControllerApi@product_stock_details');
Route::post('/login-with-imei', 'DealerUserControllerApi@login_two');
Route::post('/user-login', 'DealerUserControllerApi@user_login');
Route::post('/update-imei', 'DealerUserControllerApi@update_imei');

Route::post('/point-update', 'DealerUserControllerApi@point_update');
Route::get('/all-painter-info', 'DealerUserControllerApi@all_painter_info');
Route::get('/test', 'TestController@test');
Route::post('/update-volume-transfer', 'DealerUserControllerApi@updateVolumeTransfer');
Route::post('/update-bonus-point', 'DealerUserControllerApi@updateBonusPoint');
Route::post('/painter-info', 'DealerUserControllerApi@painter_info');
Route::post('/sales-person-insert', 'DealerUserControllerApi@sales_person_insert');
Route::post('/transfer-point', 'PointTransferApi@sendPoint');
Route::post('/approve-transfer', 'PointTransferApi@approval')->name('pointtransfer.approval');
Route::post('/cancel-transfer', 'PointTransferApi@cancel')->name('pointtransfer.cancel');
Route::post('/transfered-point', 'PointTransferApi@getPointTransfer')->name('pointtransfer.getPointTransfer');
