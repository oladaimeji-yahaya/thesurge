<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | This file is where you may define all of the routes that are handled
  | by your application. Just tell Laravel the URIs it should respond
  | to using a Closure or controller method. Build something great!
  |
 */

Route::get('/ref/{ref}', 'RefController@findRef')->name('reflink');

Route::group([], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index');
    Route::get('/support', 'HomeController@contact')->name('support');
//    Route::get('/support', 'HomeController@support')->name('support')->middleware('auth');
//    Route::get('/faq', 'HomeController@faq')->name('faq');
    //Route::get('/affiliate', 'HomeController@affiliate')->name('affiliate');
    //Route::post('/affiliate', 'HomeController@sendAffiliate');
//    Route::get('/about', 'HomeController@about')->name('about');
//    Route::get('/guide', 'HomeController@guide')->name('guide');
    //Route::get('/team', 'HomeController@team')->name('team');
    Route::get('/contact-us', 'HomeController@contact')->name('contact');
    Route::post('/contact-us', 'HomeController@sendMail');
//    Route::get('/packages', 'HomeController@packages')->name('packages');
    //Route::get('/testimonials', 'HomeController@testimonies')->name('testimonials');
    Route::get('/subscribe/{email?}', 'HomeController@subscribe')->name('subscribe');
    Route::get('/terms-and-conditions', 'HomeController@tandc')->name('tandc');
    //Route::get('/privacy', 'HomeController@privacy')->name('privacy');
});

Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'namespace' => 'Dashboard', 'middleware' => ['auth', 'dashboard']], function () {
    Route::group(['middleware' => 'auth.active'], function () {
        Route::get('/', 'IndexController@index')->name('index');

        Route::get('/investments', 'PaymentController@showInvestments')->name('investments');
        Route::get('/checkouts', 'PaymentController@showWithdrawals')->name('withdrawals');

        Route::get('/notifications', 'NotificationController@index')->name('notifications');
        Route::get('notifications/{action}', ['as' => 'notification.action', 'uses' => 'NotificationController@react']);

        Route::get('/news', 'NotificationController@news')->name('allnews');
        Route::get('/news/{slug}', 'NotificationController@showNews')->name('viewnews');

        Route::get('/referrals', 'ReferralsController@index')->name('referrals');
        Route::get('/super-affiliate', 'ReferralsController@affiliate')->name('affiliate');

        Route::get('/bonuses', 'BonusesController@index')->name('bonuses');
        Route::get('/profile', 'ProfileController@index')->name('profile');

        Route::post('/profile', 'ProfileController@save')->name('profile');
        Route::post('/password', 'ProfileController@changePassword')->name('password');
        Route::post('/identity', 'ProfileController@uploadIdentityPhoto')->name('identity');
        Route::post('/photo', 'ProfileController@uploadProfilePhoto')->name('photo');

        Route::post('/invest', 'PaymentController@createInvestment')->name('invest');
        Route::get('/invest', 'PaymentController@showInvestmentPage')->name('showInvestmentPage');
        Route::post('/withdraw', 'PaymentController@createWithdrawal')->name('withdraw');
        Route::get('/mark-paid', 'PaymentController@markInvestmentAsPaid')->name('paidInvestment');
        Route::get('/cancelinvestment', 'PaymentController@cancelInvestmentRequest')->name('cancelInvestment');
        Route::get('/cancelwithdrawal', 'PaymentController@cancelWithdrawalRequest')->name('cancelWithdrawal');

        Route::post('/receipts', 'IndexController@submitReceipts')->name('receipts');
        Route::get('/receipts', 'IndexController@viewReceipts')->name('receipts');

        Route::get('/confirm-receipts', 'IndexController@confirmPayment')->name('confirm.receipts');
        Route::get('/confirm-noreceipts', 'IndexController@approveWithoutReceipt')->name('confirm.noreceipts');
        Route::get('/decline', 'IndexController@declinePayment')->name('decline');

        Route::get('/tools', 'IndexController@tools')->name('tools');
    });
    Route::get('/suspended', 'IndexController@suspended')->name('suspended');
});


//Require coinpayment route file
require base_path('app/Library/Coinpayment/Routes/coinpayment.php');

Route::group(['prefix' => setting('ADMIN_PREFIX', 'managemnt'), 'as' => 'admin.', 'middleware' => 'admin'], function () {

    //Require coinpayment route file
    require base_path('app/Library/Coinpayment/Routes/coinpayment-secure.php');

    Route::group(['namespace' => 'Admin'], function () {
        Route::get('/', 'DashboardController@index')->name('dashboard.index');

        Route::group(['prefix' => 'withdrawals', 'as' => 'withdrawals.'], function () {
            Route::get('/', 'WithdrawalsController@index')->name('index');
            Route::get('/{reference}/edit', 'WithdrawalsController@edit')->name('edit');
            Route::post('/{reference}/edit', 'WithdrawalsController@update');
            Route::post('/managelist', 'WithdrawalsController@manageList')->name('manage_list');
        });
        Route::group(['prefix' => 'investments', 'as' => 'investments.'], function () {
            Route::get('/', 'InvestmentsController@index')->name('index');
            Route::get('/{reference}/edit', 'InvestmentsController@edit')->name('edit');
            Route::post('/{reference}/edit', 'InvestmentsController@update');
            Route::post('/managelist', 'InvestmentsController@manageList')->name('manage_list');
        });
        Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
            Route::get('/', 'UsersController@index')->name('index');
            Route::get('/deposit', 'UsersController@deposit')->name('deposit');
            Route::get('/reinvest', 'UsersController@reinvest')->name('reinvest');
            Route::get('/withdraw', 'UsersController@withdraw')->name('withdraw');
            Route::get('/add-bonus', 'UsersController@addBonus')->name('add_bonus');
            Route::post('/managelist', 'UsersController@manageList')->name('manage_list');
            Route::get('/{user}', 'UsersController@view')->name('view');
            Route::get('/{user}/login', 'UsersController@loginAs')->name('login_as');
            Route::get('/{user}/affiliates', 'UsersController@viewAffiliates')->name('view_affiliates');
            Route::get('/{user}/referrals', 'UsersController@viewReferrals')->name('view_referrals');
            Route::get('/{user}/pay-bonus', 'UsersController@payBonuses')->name('pay_bonus');
        });
        Route::group(['prefix' => 'affiliate', 'as' => 'affiliate.'], function () {
            Route::get('/', 'AffiliateController@index')->name('index');
            Route::post('/managelist', 'AffiliateController@manageList')->name('manage_list');
            Route::group(['prefix' => 'requests', 'as' => 'requests.'], function () {
                Route::get('/', 'AffiliateController@requestList')->name('index');
                Route::post('/managelist', 'AffiliateController@manageRequestList')->name('manage_list');
            });
        });
        Route::group(['prefix' => 'admins', 'as' => 'admins.'], function () {
            Route::get('/', 'AdminsController@index')->name('index');
            Route::post('/managelist', 'AdminsController@manageList')->name('manage_list');
        });
        Route::group(['prefix' => 'plans', 'as' => 'plans.'], function () {
            Route::get('/', 'PlansController@index')->name('index');
            Route::post('/update', 'PlansController@update')->name('update');
        });
        Route::group(['prefix' => 'exchanges', 'as' => 'exchanges.'], function () {
            Route::get('/', 'ExchangesController@index')->name('index');
            Route::post('/update', 'ExchangesController@update')->name('update');
            Route::post('/qr', 'ExchangesController@qr')->name('qr');
            Route::post('/deleteqr', 'ExchangesController@deleteQR')->name('deleteqr');
            Route::post('/uploadqr', 'ExchangesController@uploadQR')->name('uploadqr');
            Route::post('/managelist', 'ExchangesController@manageList')->name('manage_list');
        });
        Route::group(['prefix' => 'faker', 'as' => 'faker.'], function () {
            Route::group(['prefix' => 'payouts', 'as' => 'payouts.'], function () {
                Route::get('/', 'FakerController@payouts')->name('index');
                Route::post('/create', 'FakerController@createPayouts')->name('create');
                Route::post('/update', 'FakerController@updatePayouts')->name('update');
                Route::post('/delete', 'FakerController@deletePayouts')->name('delete');
            });
            Route::group(['prefix' => 'team', 'as' => 'team.'], function () {
                Route::get('/', 'FakerController@team')->name('index');
                Route::post('/create', 'FakerController@createTeam')->name('create');
                Route::post('/update', 'FakerController@updateTeam')->name('update');
                Route::post('/delete', 'FakerController@deleteTeamMember')->name('delete');
            });
            Route::group(['prefix' => 'testimonials', 'as' => 'testimonials.'], function () {
                Route::get('/', 'FakerController@testimonials')->name('index');
                Route::post('/create', 'FakerController@createTestimonials')->name('create');
                Route::post('/update', 'FakerController@updateTestimonials')->name('update');
                Route::post('/delete', 'FakerController@deleteTestimonials')->name('delete');
            });
            Route::group(['prefix' => 'faqs', 'as' => 'faqs.'], function () {
                Route::get('/', 'FakerController@faqs')->name('index');
                Route::post('/create', 'FakerController@createFaqs')->name('create');
                Route::post('/update', 'FakerController@updateFaqs')->name('update');
                Route::post('/delete', 'FakerController@deleteFaqs')->name('delete');
            });
        });
        Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
            Route::get('/', 'SettingsController@index')->name('index');
//        Route::post('/qr', 'SettingsController@uploadQR')->name('uploadQR');
//        Route::get('/delqr', 'SettingsController@deleteQR')->name('deleteQR');
            Route::post('/update', 'SettingsController@update')->name('update');
        });
        Route::group(['prefix' => 'update', 'as' => 'update.'], function () {
            Route::get('/rates', 'DashboardController@updateRates')->name('rates');
            Route::get('/info', 'DashboardController@updateInfo')->name('info');
        });
    });
});


Auth::routes();
