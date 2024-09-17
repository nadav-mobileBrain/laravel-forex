<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CronController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SignalController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebSettingController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\ManufactureController;
use App\Http\Controllers\BasicSettingController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Payment\PaytmController;
use App\Http\Controllers\Payment\SkillController;
use App\Http\Controllers\Payment\MollieController;
use App\Http\Controllers\Payment\PaypalController;
use App\Http\Controllers\Payment\StripeController;
use App\Http\Controllers\WithdrawMethodController;
use App\Http\Controllers\Auth\VerifyUserController;
use App\Http\Controllers\Payment\BlockIoController;
use App\Http\Controllers\Payment\DusuPayController;
use App\Http\Controllers\Payment\MonnifyController;
use App\Http\Controllers\Payment\MoyasarController;
use App\Http\Controllers\Payment\CashmaalController;
use App\Http\Controllers\Payment\CoinbaseController;
use App\Http\Controllers\Payment\CoingateController;
use App\Http\Controllers\Payment\PaystackController;
use App\Http\Controllers\Payment\RazorpayController;
use App\Http\Controllers\Payment\VoguePayController;
use App\Http\Controllers\Payment\ZarinPalController;
use App\Http\Controllers\Payment\BraintreeController;
use App\Http\Controllers\Payment\InstamojoController;
use App\Http\Controllers\Admin\ResetPasswordController;
use App\Http\Controllers\Payment\CoinPaymentController;
use App\Http\Controllers\Payment\FlutterwaveController;
use App\Http\Controllers\Payment\MercadoPagoController;
use App\Http\Controllers\Payment\SecurionpayController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Payment\AlipayGlobalController;
use App\Http\Controllers\Payment\AuthorizeNetController;
use App\Http\Controllers\Payment\PerfectMoneyController;
use App\Http\Controllers\Settings\Email\EmailController;
use App\Http\Controllers\Payment\BTCBlockChainController;
use App\Http\Controllers\Payment\WalletPaymentController;
use App\Http\Controllers\Settings\SMS\SMSGatewayController;
use App\Http\Controllers\Payment\SslCommerzPaymentController;
use App\Http\Controllers\Settings\Whatsapp\WhatsappController;

Route::get('/', [HomeController::class, 'getIndex'])->name('home');
Route::post('/submitContact', [HomeController::class, 'submitContact']);
Route::post('submit-subscribe', [HomeController::class, 'submitSubscribe'])->name('submit-subscribe');
Route::get('blog', [HomeController::class, 'getBlog'])->name('blog');
Route::get('terms-condition', [HomeController::class, 'getTermCondition'])->name('terms-condition');
Route::get('privacy-policy', [HomeController::class, 'getPrivacyPolicy'])->name('privacy-policy');
Route::get('blog-details/{slug}', [HomeController::class, 'detailsBlog'])->name('blog-details');
Route::get('category-blog/{slug}', [HomeController::class, 'categoryBlog'])->name('category-blog');
Route::get('/menu/{id}/{name}', [HomeController::class, 'getMenu']);
Route::get('about-us', [HomeController::class, 'getAbout'])->name('about-us');
Route::get('contact-us', [HomeController::class, 'getContact'])->name('contact-us');

Route::get('email-test', [HomeController::class, 'emailTest'])->name('email-test');
Route::get('cron-fire', [HomeController::class, 'submitCronJob'])->name('cron-fire');
Route::get('cron-subscribe-message', [HomeController::class, 'submitCronSubscribeMessage'])->name('cron-subscribe-message');

Route::get('cron-signal', [HomeController::class, 'submitCronJobSignal'])->name('cron-signal');
Route::get('cron-signal-telegram', [CronController::class, 'telegramCron'])->name('cron-signal-telegram');
Route::get('cron-signal-whatsapp', [CronController::class, 'whatsappCron'])->name('cron-signal-whatsapp');
Route::get('cron-signal-sms', [CronController::class, 'smsCron'])->name('cron-signal-sms');
Route::get('cron-signal-email', [CronController::class, 'emailCron'])->name('cron-signal-email');

Auth::routes();
Route::get('register/{id}', [RegisterController::class, 'refererRegister'])->name('auth.reference-register');

Route::get('user-dashboard', [UserController::class, 'getDashboard'])->name('user-dashboard');

Route::get('user-edit-profile', [UserController::class, 'editProfile'])->name('user-edit-profile');
Route::post('user-edit-profile', [UserController::class, 'updateProfile'])->name('user-update-profile');

Route::get('user-change-password', [UserController::class, 'getChangePass'])->name('user-change-password');
Route::post('user-change-password', [UserController::class, 'postChangePass'])->name('user-change-password');

Route::group(['prefix' => 'user'], function () {

    Route::get('email-verify', [VerifyUserController::class, 'emailVerify'])->name('email-verify');
    Route::post('verification-submit', [VerifyUserController::class, 'submitVerify'])->name('verification-submit');
    Route::post('email-resubmit', [VerifyUserController::class, 'emailResubmit'])->name('email-resubmit');

    Route::get('phone-verify', [VerifyUserController::class, 'phoneVerify'])->name('phone-verify');
    Route::post('phone-verification-submit', [VerifyUserController::class, 'submitPhoneVerify'])->name('phone-verification-submit');
    Route::post('phone-resubmit', [VerifyUserController::class, 'phoneResubmit'])->name('phone-resubmit');

    Route::get('new-signal', [UserController::class, 'newSignal'])->name('user-new-signal');
    Route::get('all-signal', [UserController::class, 'AllSignal'])->name('user-all-signal');
    Route::get('signal-view/{id}', [UserController::class, 'signalView'])->name('user-signal-view');

    Route::get('payment-method', [UserController::class, 'chosePayment'])->name('chose-payment-method');
    Route::post('submit-payment-method', [UserController::class, 'submitPaymentMethod'])->name('submit-payment-method');
    Route::post('manual-payment-submit', [UserController::class, 'manualPaymentSubmit'])->name('manual-payment-submit');

    Route::get('upgrade-plan', [UserController::class, 'getUpgradePlan'])->name('user-upgrade-plan');

    Route::post('upgrade-plan-submit', [UserController::class, 'updatePlanSubmit'])->name('upgrade-plan-submit');

    Route::get('plan-upgrade-payment', [UserController::class, 'planUpgradePayment'])->name('plan-upgrade-payment');

    Route::get('active-telegram', [UserController::class, 'activeTelegram'])->name('user-active-telegram');
    Route::post('active-telegram', [UserController::class, 'submitActiveTelegram'])->name('submit-active-telegram');

    Route::get('active-whatsapp', [UserController::class, 'activeWhatsapp'])->name('user-active-whatsapp');
    Route::post('active-whatsapp', [UserController::class, 'submitActiveWhatsapp'])->name('submit-user-whatsapp-number');
    Route::post('tone-whatsapp', [UserController::class, 'submitTokenWhatsapp'])->name('submit-user-whatsapp-token');

    Route::get('staff-request', [UserController::class, 'staffRequest'])->name('user-staff-request');
    Route::post('staff-request', [UserController::class, 'submitStaffRequest'])->name('submit-staff-request');

    Route::get('withdraw-now', [UserController::class, 'withdrawNow'])->name('user-withdraw-now');
    Route::get('withdraw-method/{id}', [UserController::class, 'withdrawMethod'])->name('user-withdraw-method');
    Route::get('check-withdraw/{av}/{amount}/{min}/{max}', [UserController::class, 'checkWithdraw'])->name('check-withdraw');
    Route::post('withdraw-confirm', [UserController::class, 'withdrawConfirm'])->name('user-withdraw-confirm');
    Route::get('withdraw-history', [UserController::class, 'withdrawHistory'])->name('user-withdraw-history');

    Route::get('submit-signal', [UserController::class, 'submitUserSignal'])->name('user-submit-signal');
    Route::post('submit-signal', [UserController::class, 'PostSubmitUserSignal'])->name('user-submit-signal');
    Route::get('submit-history', [UserController::class, 'submitHistory'])->name('submit-history');
    Route::get('submit-view/{id}', [UserController::class, 'submitView'])->name('signal-submit-view');

    Route::post('comment-submit', [UserController::class, 'commentSubmit'])->name('comment-submit');
    Route::post('rating-submit', [UserController::class, 'ratingSubmit'])->name('rating-submit');

    Route::get('transaction-log', [UserController::class, 'transactionLog'])->name('user-transaction-log');
    Route::get('referral-user', [UserController::class, 'referralUser'])->name('user-referral-user');
});

Route::post('paypal-submit', [PaypalController::class, 'paypalSubmit'])->name('paypal-submit');
Route::get('paypal-ipn', [PaypalController::class, 'paypalIpn'])->name('paypal-ipn');
Route::post('perfect-ipn', [PerfectMoneyController::class, 'perfectIPN'])->name('perfect-ipn');
Route::get('btc-ipn', [BTCBlockChainController::class, 'btcIPN'])->name('btc-ipn');
Route::post('stripe-submit', [StripeController::class, 'submitStripe'])->name('stripe-submit');
Route::get('stripe-ipn', [StripeController::class, 'stripeIPN'])->name('stripe-ipn');
Route::post('skrill-ipn', [SkillController::class, 'skrillIPN'])->name('skrill-ipn');
Route::post('coinpayment-ipn', [CoinPaymentController::class, 'coinPaymentIPN'])->name('coinpayment-ipn');
Route::post('commission-ipn', [WalletPaymentController::class, 'commissionPaymentIPN'])->name('commission-ipn');
Route::get('flutterwave-ipn', [FlutterwaveController::class, 'flutterwaveIPN'])->name('flutterwave-ipn');
Route::post('mollie-submit', [MollieController::class, 'preparePayment'])->name('mollie-submit');
Route::get('mollie-ipn', [MollieController::class, 'handleWebhookNotification'])->name('mollie-ipn');
Route::post('rozorpay-submit', [RazorpayController::class, 'processRozorPay'])->name('rozorpay-submit');
Route::post('paystack-submit', [PaystackController::class, 'processPaystack'])->name('paystack-submit');
Route::get('paystack-ipn', [PaystackController::class, 'paystackIPN'])->name('paystack-ipn');
Route::post('paytm-submit', [PaytmController::class, 'paymentProcess'])->name('paytm-submit');
Route::post('paytm-ipn', [PaytmController::class, 'paytmIPN'])->name('paytm-ipn');
Route::post('sslcommerz-submit', [SslCommerzPaymentController::class, 'process'])->name('sslcommerz-submit');
Route::post('sslcommerz-success', [SslCommerzPaymentController::class, 'success'])->name('sslcommerz-success');
Route::post('sslcommerz-fail', [SslCommerzPaymentController::class, 'fail'])->name('sslcommerz-fail');
Route::post('sslcommerz-cancel', [SslCommerzPaymentController::class, 'cancel'])->name('sslcommerz-cancel');
Route::post('sslcommerz-ipn', [SslCommerzPaymentController::class, 'ipn'])->name('sslcommerz-ipn');
Route::post('coingate-submit', [CoingateController::class, 'process'])->name('coingate-submit');
Route::post('coingate-ipn', [CoingateController::class, 'ipn'])->name('coingate-ipn');
Route::get('blockio-ipn', [BlockIoController::class, 'ipn'])->name('blockio-ipn');
Route::post('conbase-submit', [CoinbaseController::class, 'process'])->name('conbase-submit');
Route::get('coinbase-ipn', [CoinbaseController::class, 'ipn'])->name('coinbase-ipn');
Route::get('coinbase-fail', [CoinbaseController::class, 'fail'])->name('coinbase-fail');
Route::get('voguepay-ipn', [VoguePayController::class, 'ipn'])->name('voguepay-ipn');
Route::post('instamojo-submit', [InstamojoController::class, 'process'])->name('instamojo-submit');
Route::get('instamojo-ipn', [InstamojoController::class, 'ipn'])->name('instamojo-ipn');
Route::post('cashmaal-ipn', [CashmaalController::class, 'ipn'])->name('cashmaal-ipn');
Route::get('monnify-ipn', [MonnifyController::class, 'ipn'])->name('monnify-ipn');
Route::post('mercado-pago-submit', [MercadoPagoController::class, 'process'])->name('mercado-pago-submit');
Route::get('mercado-pago-success', [MercadoPagoController::class, 'success'])->name('mercado-pago-success');
Route::get('mercado-pago-ipn', [MercadoPagoController::class, 'ipn'])->name('mercado-pago-ipn');
Route::post('securionpay-submit', [SecurionpayController::class, 'process'])->name('securionpay-submit');
Route::post('authorizenet-submit', [AuthorizeNetController::class, 'process'])->name('authorizenet-submit');
Route::get('authorizenet-ipn', [AuthorizeNetController::class, 'ipn'])->name('authorizenet-ipn');
Route::post('alipayglobal-submit', [AlipayGlobalController::class, 'process'])->name('alipayglobal-submit');
Route::match(['get', 'post'], 'alipayglobal-ipn', [AlipayGlobalController::class, 'ipn'])->name('alipayglobal-ipn');

Route::post('zarinpal-submit', [ZarinPalController::class, 'process'])->name('zarinpal-submit');
Route::get('zarinpal-ipn', [ZarinPalController::class, 'ipn'])->name('zarinpal-ipn');
Route::get('moyasar-ipn', [MoyasarController::class, 'ipn'])->name('moyasar-ipn');
Route::post('dusupay-submit', [DusuPayController::class, 'process'])->name('dusupay-submit');
Route::post('dusupay-ipn', [DusuPayController::class, 'ipn'])->name('dusupay-ipn');
Route::post('braintree-ipn', [BraintreeController::class, 'ipn'])->name('braintree-ipn');

Route::get('admin', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin', [LoginController::class, 'login'])->name('admin.login.post');
Route::get('admin/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
Route::post('admin/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');
Route::get('admin/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('admin.password.reset');
Route::post('admin/password/reset', [ResetPasswordController::class, 'reset']);
Route::get('admin-logout', [LoginController::class, 'logout'])->name('admin.logout');

Route::get('admin-dashboard', [DashboardController::class, 'getDashboard'])->name('dashboard');

Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {

    Route::get('edit-profile', [BasicSettingController::class, 'editProfile'])->name('edit-profile');
    Route::post('edit-profile', [BasicSettingController::class, 'updateProfile'])->name('update-profile');

    Route::get('change-password', [BasicSettingController::class, 'getChangePass'])->name('admin-change-password');
    Route::post('change-password', [BasicSettingController::class, 'postChangePass'])->name('admin-change-password');

    Route::get('basic-setting', [BasicSettingController::class, 'getBasicSetting'])->name('basic-setting');
    Route::put('basic-general/{id}', [BasicSettingController::class, 'putBasicSetting'])->name('basic-update');

    Route::get('referral-setting', [BasicSettingController::class, 'referralSetting'])->name('referral-setting');
    Route::put('referral-setting/{id}', [BasicSettingController::class, 'referralSettingUpdate'])->name('referral-setting-update');

    Route::get('database-backup', [BasicSettingController::class, 'getDatabaseBackup'])->name('database-backup');
    Route::get('backup-submit', [BasicSettingController::class, 'submitDatabaseBackup'])->name('backup-submit');
    Route::get('database-backup-download/{id}', [BasicSettingController::class, 'downloadDatabaseBackup'])->name('database-backup-download');

    Route::get('google-recaptcha', [BasicSettingController::class, 'googleRecaptcha'])->name('google-recaptcha');
    Route::put('recaptcha-update/{id}', [BasicSettingController::class, 'updateRecaptcha'])->name('recaptcha-update');

    Route::get('manage-logo', [WebSettingController::class, 'manageLogo'])->name('manage-logo');
    Route::post('manage-logo', [WebSettingController::class, 'updateLogo'])->name('manage-logo');

    Route::get('email-template', [BasicSettingController::class, 'manageEmailTemplate'])->name('email-template');
    Route::post('email-template', [BasicSettingController::class, 'updateEmailTemplate'])->name('email-template');

    Route::get('email-setting', [BasicSettingController::class, 'getEmailSetting'])->name('email-setting');
    Route::put('email-update/{id}', [BasicSettingController::class, 'putEmailSetting'])->name('email-update');

    Route::get('email-drivers', [EmailController::class, 'index'])->name('email-drivers');
    Route::get('email-drivers/{id}/edit', [EmailController::class, 'edit'])->name('edit-email-drivers');
    Route::put('email-drivers/{id}/edit', [EmailController::class, 'update'])->name('update-email-drivers');
    Route::post('email-driver-test', [EmailController::class, 'test'])->name('email-driver-test');

    Route::get('whatsapp-drivers', [WhatsappController::class, 'index'])->name('whatsapp-drivers');
    Route::get('whatsapp-drivers/{id}/edit', [WhatsappController::class, 'edit'])->name('edit-whatsapp-drivers');
    Route::put('whatsapp-drivers/{id}/edit', [WhatsappController::class, 'update'])->name('update-whatsapp-drivers');
    Route::post('whatsapp-drivers-test', [WhatsappController::class, 'test'])->name('test-whatsapp-drivers');

    Route::get('telegram-config', [BasicSettingController::class, 'telegramConfig'])->name('telegram-config');
    Route::post('telegram-config', [BasicSettingController::class, 'updateTelegramConfig'])->name('update-template-config');

    Route::get('sms-template', [BasicSettingController::class, 'smsTemplate'])->name('sms-template');
    Route::post('sms-template', [BasicSettingController::class, 'submitSmsTemplate'])->name('sms-template');

    Route::get('currency-widget', [DashboardController::class, 'getCurrencyWidget'])->name('currency-widget');
    Route::post('currency-widget', [DashboardController::class, 'submitCurrencyWidget'])->name('currency-widget');

    Route::get('transaction-log', [DashboardController::class, 'getTransactionLog'])->name('transaction-log');

    Route::get('cron-job', [BasicSettingController::class, 'setCronJob'])->name('cron-job');

    Route::get('sms-gateway', [SMSGatewayController::class, 'index'])->name('sms-gateway');
    Route::get('sms-gateway/{id}/edit', [SMSGatewayController::class, 'edit'])->name('edit-sms-gateway');
    Route::put('sms-gateway/{id}/edit', [SMSGatewayController::class, 'update'])->name('update-sms-gateway');
    Route::post('sms-gateway-test', [SMSGatewayController::class, 'test'])->name('sms-gateway-test');

    Route::get('google-analytic', [BasicSettingController::class, 'getGoogleAnalytic'])->name('google-analytic');
    Route::post('google-analytic', [BasicSettingController::class, 'updateGoogleAnalytic'])->name('google-analytic');

    Route::get('live-chat', [BasicSettingController::class, 'getLiveChat'])->name('live-chat');
    Route::post('live-chat', [BasicSettingController::class, 'updateLiveChat'])->name('live-chat');

    Route::get('manage-terms', [WebSettingController::class, 'manageTermsCondition'])->name('manage-terms');
    Route::post('manage-terms', [WebSettingController::class, 'updateTermsCondition'])->name('manage-terms');

    Route::get('manage-privacy', [WebSettingController::class, 'managePrivacyPolicy'])->name('manage-privacy');
    Route::post('manage-privacy', [WebSettingController::class, 'updatePrivacyPolicy'])->name('manage-privacy');

    Route::get('user-create', [DashboardController::class, 'createUser'])->name('user-create');
    Route::post('user-create', [DashboardController::class, 'submitUser'])->name('user-create');
    Route::get('user-edit/{id}', [DashboardController::class, 'editUser'])->name('user-edit');
    Route::post('user-update', [DashboardController::class, 'updateUser'])->name('user-update');
    Route::delete('user-delete', [DashboardController::class, 'deleteUser'])->name('user-delete');
    Route::get('manage-user', [DashboardController::class, 'manageUser'])->name('manage-user');
    Route::post('user-block', [DashboardController::class, 'blockUser'])->name('user-block');
    Route::post('email-block', [DashboardController::class, 'blockEmail'])->name('email-block');
    Route::post('phone-block', [DashboardController::class, 'blockPhone'])->name('phone-block');

    Route::get('manage-asset', [ManufactureController::class, 'create'])->name('manage-asset');
    Route::post('manage-asset', [ManufactureController::class, 'store'])->name('manage-asset');
    Route::get('manage-asset/{product_id?}', [ManufactureController::class, 'edit'])->name('asset-edit');
    Route::put('manage-asset/{product_id?}', [ManufactureController::class, 'update'])->name('asset-edit');
    Route::delete('manage-asset/{product_id?}', [ManufactureController::class, 'delete'])->name('asset-delete');

    Route::get('manage-symbol', [ManufactureController::class, 'createSymbol'])->name('manage-symbol');
    Route::post('manage-symbol', [ManufactureController::class, 'storeSymbol'])->name('manage-symbol');
    Route::get('manage-symbol/{product_id?}', [ManufactureController::class, 'editSymbol'])->name('symbol-edit');
    Route::put('manage-symbol/{product_id?}', [ManufactureController::class, 'updateSymbol'])->name('symbol-edit');
    Route::delete('manage-symbol/{product_id?}', [ManufactureController::class, 'deleteSymbol'])->name('symbol-delete');

    Route::get('manage-type', [ManufactureController::class, 'createType'])->name('manage-type');
    Route::post('manage-type', [ManufactureController::class, 'storeType'])->name('manage-type');
    Route::get('manage-type/{product_id?}', [ManufactureController::class, 'editType'])->name('type-edit');
    Route::put('manage-type/{product_id?}', [ManufactureController::class, 'updateType'])->name('type-edit');
    Route::delete('manage-type/{product_id?}', [ManufactureController::class, 'deleteType'])->name('type-delete');

    Route::get('manage-frame', [ManufactureController::class, 'createFrame'])->name('manage-frame');
    Route::post('manage-frame', [ManufactureController::class, 'storeFrame'])->name('manage-frame');
    Route::get('manage-frame/{product_id?}', [ManufactureController::class, 'editFrame'])->name('frame-edit');
    Route::put('manage-frame/{product_id?}', [ManufactureController::class, 'updateFrame'])->name('frame-edit');
    Route::delete('manage-frame/{product_id?}', [ManufactureController::class, 'deleteFrame'])->name('frame-delete');

    Route::get('manage-status', [ManufactureController::class, 'createStatus'])->name('manage-status');
    Route::post('manage-status', [ManufactureController::class, 'storeStatus'])->name('manage-status');
    Route::get('manage-status/{product_id?}', [ManufactureController::class, 'editStatus'])->name('status-edit');
    Route::put('manage-status/{product_id?}', [ManufactureController::class, 'updateStatus'])->name('status-edit');
    Route::delete('manage-status/{product_id?}', [ManufactureController::class, 'deleteStatus'])->name('status-delete');

    Route::get('manage-footer', [WebSettingController::class, 'manageFooter'])->name('manage-footer');
    Route::put('manage-footer/{id}', [WebSettingController::class, 'updateFooter'])->name('manage-footer-update');

    Route::get('manage-social', [WebSettingController::class, 'manageSocial'])->name('manage-social');
    Route::post('manage-social', [WebSettingController::class, 'storeSocial'])->name('manage-social');
    Route::get('manage-social/{product_id?}', [WebSettingController::class, 'editSocial'])->name('social-edit');
    Route::put('manage-social/{product_id?}', [WebSettingController::class, 'updateSocial'])->name('social-edit');
    Route::delete('manage-social/{product_id?}', [WebSettingController::class, 'deleteSocial'])->name('social-delete');

    Route::get('menu-create', [WebSettingController::class, 'createMenu'])->name('menu-create');
    Route::post('menu-create', [WebSettingController::class, 'storeMenu'])->name('menu-create');
    Route::get('menu-control', [WebSettingController::class, 'manageMenu'])->name('menu-control');
    Route::get('menu-edit/{id}', [WebSettingController::class, 'editMenu'])->name('menu-edit');
    Route::post('menu-update/{id}', [WebSettingController::class, 'updateMenu'])->name('menu-update');
    Route::delete('menu-delete', [WebSettingController::class, 'deleteMenu'])->name('menu-delete');

    Route::get('manage-about', [WebSettingController::class, 'manageAbout'])->name('manage-about');
    Route::post('manage-about', [WebSettingController::class, 'updateAbout'])->name('manage-about');

    Route::get('manage-slider', [WebSettingController::class, 'manageSlider'])->name('manage-slider');
    Route::post('manage-slider', [WebSettingController::class, 'storeSlider'])->name('manage-slider');
    Route::get('slider-edit/{id}', [WebSettingController::class, 'editSlider'])->name('slider-edit');
    Route::put('slider-update/{id}', [WebSettingController::class, 'updateSlider'])->name('slider-update');
    Route::delete('slider-delete', [WebSettingController::class, 'deleteSlider'])->name('slider-delete');

    Route::get('testimonial-create', [WebSettingController::class, 'createTestimonial'])->name('testimonial-create');
    Route::post('testimonial-create', [WebSettingController::class, 'submitTestimonial'])->name('testimonial-create');
    Route::get('testimonial-all', [WebSettingController::class, 'allTestimonial'])->name('testimonial-all');
    Route::get('testimonial-edit/{id}', [WebSettingController::class, 'editTestimonial'])->name('testimonial-edit');
    Route::put('testimonial-edit/{id}', [WebSettingController::class, 'updateTestimonial'])->name('testimonial-update');
    Route::delete('testimonial-delete', [WebSettingController::class, 'deleteTestimonial'])->name('testimonial-delete');

    Route::get('member-create', [WebSettingController::class, 'createMember'])->name('member-create');
    Route::post('member-create', [WebSettingController::class, 'submitMember'])->name('member-create');
    Route::get('member-all', [WebSettingController::class, 'allMember'])->name('member-all');
    Route::get('member-edit/{id}', [WebSettingController::class, 'editMember'])->name('member-edit');
    Route::put('member-edit/{id}', [WebSettingController::class, 'updateMember'])->name('member-update');
    Route::delete('member-delete', [WebSettingController::class, 'deleteMember'])->name('member-delete');

    Route::get('manage-subscriber', [DashboardController::class, 'manageSubscriber'])->name('manage-subscriber');
    Route::delete('subscriber-delete', [DashboardController::class, 'deleteSubscriber'])->name('subscriber-delete');
    Route::get('subscriber-message', [DashboardController::class, 'getSubscriberMessage'])->name('subscriber-message');
    Route::post('subscriber-message', [DashboardController::class, 'submitSubscriberMessage'])->name('subscriber-message-submit');
    Route::get('subscriber-message-list', [DashboardController::class, 'subscriberMessageList'])->name('subscriber-message-list');
    Route::delete('subscriber-message-delete', [DashboardController::class, 'subscriberMessageDelete'])->name('subscriber-message-delete');

    Route::get('manage-breadcrumb', [WebSettingController::class, 'mangeBreadcrumb'])->name('manage-breadcrumb');
    Route::post('manage-breadcrumb', [WebSettingController::class, 'updateBreadcrumb'])->name('manage-breadcrumb');

    Route::get('speciality-create', [WebSettingController::class, 'createSpeciality'])->name('speciality-create');
    Route::post('speciality-create', [WebSettingController::class, 'storeSpeciality'])->name('speciality-create');
    Route::get('speciality-control', [WebSettingController::class, 'manageSpeciality'])->name('speciality-control');
    Route::get('speciality-edit/{id}', [WebSettingController::class, 'editSpeciality'])->name('speciality-edit');
    Route::post('speciality-update/{id}', [WebSettingController::class, 'updateSpeciality'])->name('speciality-update');
    Route::delete('speciality-delete', [WebSettingController::class, 'deleteSpeciality'])->name('speciality-delete');

    Route::get('manage-category', [CategoryController::class, 'manageCategory'])->name('manage-category');
    Route::post('manage-category', [CategoryController::class, 'storeCategory'])->name('manage-category');
    Route::get('manage-category/{product_id?}', [CategoryController::class, 'editCategory'])->name('category-edit');
    Route::put('manage-category/{product_id?}', [CategoryController::class, 'updateCategory'])->name('category-edit');
    Route::delete('/manage-category/{product_id?}', [CategoryController::class, 'deleteItem'])->name('category-delete');

    Route::get('post-create', [PostController::class, 'create'])->name('post-create');
    Route::post('post-create', [PostController::class, 'store'])->name('post-create');
    Route::get('post-all', [PostController::class, 'index'])->name('post-all');
    Route::get('post-edit/{id}', [PostController::class, 'edit'])->name('post-edit');
    Route::post('post-update', [PostController::class, 'update'])->name('post-update');
    Route::delete('post-delete', [PostController::class, 'destroy'])->name('post-delete');
    Route::post('post-publish', [PostController::class, 'publish'])->name('post-publish');

    Route::get('plan-create', [PlanController::class, 'create'])->name('plan-create');
    Route::post('plan-create', [PlanController::class, 'store'])->name('plan-create');
    Route::get('plan-all', [PlanController::class, 'index'])->name('plan-all');
    Route::get('plan-edit/{id}', [PlanController::class, 'edit'])->name('plan-edit');
    Route::put('plan-update/{id}', [PlanController::class, 'update'])->name('plan-update');
    Route::delete('plan-delete', [PlanController::class, 'destroy'])->name('plan-delete');

    Route::get('payment-method', [PaymentController::class, 'paymentMethod'])->name('payment-method');
    Route::get('payment-method/{id}/edit', [PaymentController::class, 'editMethod'])->name('edit-payment-method');
    Route::put('payment-method/{id}', [PaymentController::class, 'updatePaymentMethod'])->name('payment-method-update');

    Route::get('manual-payment-method', [PaymentController::class, 'getManualPaymentMethod'])->name('manual-payment-method');
    Route::get('manual-payment-method-create', [PaymentController::class, 'createManualPaymentMethod'])->name('manual-payment-method-create');
    Route::post('manual-payment-method-create', [PaymentController::class, 'storeManualPaymentMethod'])->name('manual-payment-method-create');
    Route::get('manual-payment-method-edit/{id}', [PaymentController::class, 'editManualPaymentMethod'])->name('manual-payment-method-edit');
    Route::put('manual-payment-method-edit/{id}', [PaymentController::class, 'updateManualPaymentMethod'])->name('manual-payment-method-update');

    Route::get('manual-payment-request', [PaymentController::class, 'getManualPaymentRequest'])->name('manual-payment-request');
    Route::get('manual-payment-request/{custom}', [PaymentController::class, 'viewManualPaymentRequest'])->name('manual-payment-request-view');
    Route::post('manual-payment-request-cancel', [PaymentController::class, 'cancelManualPaymentRequest'])->name('manual-payment-request-cancel');
    Route::post('manual-payment-request-confirm', [PaymentController::class, 'confirmManualPaymentRequest'])->name('manual-payment-request-confirm');
    Route::delete('manual-payment-request-delete', [PaymentController::class, 'deleteManualPaymentRequest'])->name('manual-payment-request-delete');

    Route::get('withdraw-create', [WithdrawMethodController::class, 'createMethod'])->name('withdraw-create');
    Route::post('withdraw-create', [WithdrawMethodController::class, 'storeMethod'])->name('withdraw-create');
    Route::get('withdraw-method', [WithdrawMethodController::class, 'allMethod'])->name('withdraw-method');
    Route::get('withdraw-edit/{id}', [WithdrawMethodController::class, 'editMethod'])->name('withdraw-edit');
    Route::put('withdraw-edit/{id}', [WithdrawMethodController::class, 'updateMethod'])->name('withdraw-update');

    Route::get('withdraw-request', [WithdrawController::class, 'allRequest'])->name('withdraw-request');
    Route::get('withdraw-request-view/{custom}', [WithdrawController::class, 'requestView'])->name('withdraw-request-view');

    Route::post('withdraw-refund', [WithdrawController::class, 'WithdrawRefund'])->name('withdraw-refund');
    Route::post('withdraw-confirm', [WithdrawController::class, 'WithdrawConfirm'])->name('withdraw-confirm');
    Route::get('withdraw-request-refund', [WithdrawController::class, 'getRequestRefund'])->name('withdraw-request-refund');
    Route::get('withdraw-request-success', [WithdrawController::class, 'getRequestSuccess'])->name('withdraw-request-success');
    Route::get('withdraw-request-pending', [WithdrawController::class, 'getRequestPending'])->name('withdraw-request-pending');

    Route::get('manage-shortAbout', [WebSettingController::class, 'shortAbout'])->name('manage-shortAbout');
    Route::post('manage-shortAbout', [WebSettingController::class, 'updateShortAbout'])->name('manage-shortAbout');

    Route::get('partner-create', [WebSettingController::class, 'createPartner'])->name('partner-create');
    Route::post('partner-create', [WebSettingController::class, 'submitPartner'])->name('partner-create');
    Route::get('partner-all', [WebSettingController::class, 'allPartner'])->name('partner-all');
    Route::get('partner-edit/{id}', [WebSettingController::class, 'editPartner'])->name('partner-edit');
    Route::put('partner-edit/{id}', [WebSettingController::class, 'updatePartner'])->name('partner-update');
    Route::delete('partner-delete', [WebSettingController::class, 'deletePartner'])->name('partner-delete');

    Route::get('signal-create', [SignalController::class, 'create'])->name('signal-create');
    Route::post('signal-create', [SignalController::class, 'store'])->name('signal-create');
    Route::get('signal-all', [SignalController::class, 'index'])->name('signal-all');
    Route::get('signal-view/{id}', [SignalController::class, 'show'])->name('signal-view');
    Route::get('signal-edit/{id}', [SignalController::class, 'edit'])->name('signal-edit');
    Route::post('signal-update', [SignalController::class, 'update'])->name('signal-update');
    Route::delete('signal-delete', [SignalController::class, 'destroy'])->name('signal-delete');
    Route::post('signal-result', [SignalController::class, 'result'])->name('signal-result');
    Route::post('signal-home', [SignalController::class, 'home'])->name('signal-home');

    Route::post('signal-result-update', [SignalController::class, 'updateResult'])->name('signal-result-update');

    Route::post('comment-submit', [DashboardController::class, 'commentSubmit'])->name('admin-comment-submit');
    Route::post('rating-submit', [DashboardController::class, 'ratingSubmit'])->name('admin-rating-submit');

    Route::get('speciality-section', [SectionController::class, 'getSpecialitySection'])->name('speciality-section');
    Route::post('speciality-section', [SectionController::class, 'submitSpecialitySection'])->name('speciality-section');

    Route::get('currency-section', [SectionController::class, 'getCurrencySection'])->name('currency-section');
    Route::post('currency-section', [SectionController::class, 'submitCurrencySection'])->name('currency-section');

    Route::get('trading-section', [SectionController::class, 'getTradingSection'])->name('trading-section');
    Route::post('trading-section', [SectionController::class, 'submitTradingSection'])->name('trading-section');

    Route::get('advertise-section', [SectionController::class, 'getAdvertiseSection'])->name('advertise-section');
    Route::post('advertise-section', [SectionController::class, 'submitAdvertiseSection'])->name('advertise-section');

    Route::get('plan-section', [SectionController::class, 'getPlanSection'])->name('plan-section');
    Route::post('plan-section', [SectionController::class, 'submitPlanSection'])->name('plan-section');

    Route::get('about-section', [SectionController::class, 'getAboutSection'])->name('about-section');
    Route::post('about-section', [SectionController::class, 'submitAboutSection'])->name('about-section');

    Route::get('coupon-section', [SectionController::class, 'getCouponSection'])->name('coupon-section');
    Route::post('coupon-section', [SectionController::class, 'submitCouponSection'])->name('coupon-section');

    Route::get('counter-section', [SectionController::class, 'getCounterSection'])->name('counter-section');
    Route::post('counter-section', [SectionController::class, 'submitCounterSection'])->name('counter-section');

    Route::get('testimonial-section', [SectionController::class, 'getTestimonialSection'])->name('testimonial-section');
    Route::post('testimonial-section', [SectionController::class, 'submitTestimonialSection'])->name('testimonial-section');

    Route::get('subscriber-section', [SectionController::class, 'getSubscriberSection'])->name('subscriber-section');
    Route::post('subscriber-section', [SectionController::class, 'submitSubscriberSection'])->name('subscriber-section');

    Route::get('blog-section', [SectionController::class, 'getBlogSection'])->name('blog-section');
    Route::post('blog-section', [SectionController::class, 'submitBlogSection'])->name('blog-section');

    Route::get('team-section', [SectionController::class, 'getTeamSection'])->name('team-section');
    Route::post('team-section', [SectionController::class, 'submitTeamSection'])->name('team-section');

    Route::get('result-section', [SectionController::class, 'getResultSection'])->name('result-section');
    Route::post('result-section', [SectionController::class, 'submitResultSection'])->name('result-section');

    Route::get('staff-create', [DashboardController::class, 'createStaff'])->name('staff-create');
    Route::post('staff-create', [DashboardController::class, 'submitStaff'])->name('staff-create');
    Route::get('manage-staff', [DashboardController::class, 'manageStaff'])->name('manage-staff');
    Route::post('staff-password-update', [DashboardController::class, 'passwordUpdateStaff'])->name('staff-password-update');
    Route::get('staff-edit/{id}', [DashboardController::class, 'editStaff'])->name('staff-edit');
    Route::post('staff-update', [DashboardController::class, 'updateStaff'])->name('staff-update');

});

Route::get('staff', [App\Http\Controllers\Staff\LoginController::class, 'showLoginForm'])->name('staff.login');
Route::post('staff', [App\Http\Controllers\Staff\LoginController::class, 'login'])->name('staff.login.post');
Route::get('staff/password/reset', [App\Http\Controllers\Staff\ForgotPasswordController::class, 'showLinkRequestForm'])->name('staff.password.request');
Route::post('staff/password/email', [App\Http\Controllers\Staff\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('staff.password.email');
Route::get('staff/password/reset/{token}', [App\Http\Controllers\Staff\ResetPasswordController::class, 'showResetForm'])->name('staff.password.reset');
Route::post('staff/password/reset', [App\Http\Controllers\Staff\ResetPasswordController::class, 'reset']);
Route::get('staff-logout', [App\Http\Controllers\Staff\LoginController::class, 'logout'])->name('staff.logout');

Route::get('staff-dashboard', [StaffController::class, 'getDashboard'])->name('staff-dashboard');

Route::group(['prefix' => 'staff'], function () {

    Route::get('edit-profile', [StaffController::class, 'editProfile'])->name('staff-edit-profile');
    Route::post('edit-profile', [StaffController::class, 'updateProfile'])->name('staff-update-profile');

    Route::get('change-password', [StaffController::class, 'getChangePass'])->name('staff-change-password');
    Route::post('change-password', [StaffController::class, 'postChangePass'])->name('staff-change-password');

    Route::get('signal-create', [StaffController::class, 'signalCreate'])->name('staff-signal-create');
    Route::post('signal-create', [StaffController::class, 'signalStore'])->name('staff-signal-create');
    Route::get('signal-all', [StaffController::class, 'signalIndex'])->name('staff-signal-all');
    Route::get('signal-view/{id}', [StaffController::class, 'signalShow'])->name('staff-signal-view');
    Route::get('signal-edit/{id}', [StaffController::class, 'signalEdit'])->name('staff-signal-edit');
    Route::post('signal-update', [StaffController::class, 'signalUpdate'])->name('staff-signal-update');
    Route::delete('signal-delete', [StaffController::class, 'signalDestroy'])->name('staff-signal-delete');
    Route::post('signal-result', [StaffController::class, 'signalResult'])->name('staff-signal-result');
    Route::post('signal-home', [StaffController::class, 'signalHome'])->name('staff-signal-home');

    //Route::post('signal-result-update', [StaffController::class, 'updateSignalResult'])->name('staff-signal-result-update');

    Route::post('comment-submit', [StaffController::class, 'commentSubmit'])->name('staff-comment-submit');
    Route::post('rating-submit', [StaffController::class, 'ratingSubmit'])->name('staff-rating-submit');

    Route::get('post-create', [StaffController::class, 'createPost'])->name('staff-post-create');
    Route::post('post-create', [StaffController::class, 'storePost'])->name('staff-post-store');
    Route::get('post-all', [StaffController::class, 'indexPost'])->name('staff-post-all');
    Route::get('post-edit/{id}', [StaffController::class, 'editPost'])->name('staff-post-edit');
    Route::post('post-update', [StaffController::class, 'updatePost'])->name('staff-post-update');
    Route::delete('post-delete', [StaffController::class, 'destroyPost'])->name('staff-post-delete');
    Route::post('post-publish', [StaffController::class, 'publishPost'])->name('staff-post-publish');

    Route::get('user-create', [StaffController::class, 'createUser'])->name('staff-user-create');
    Route::post('user-create', [StaffController::class, 'submitUser'])->name('staff-user-create');
    Route::get('user-edit/{id}', [StaffController::class, 'editUser'])->name('staff-user-edit');
    Route::post('user-update', [StaffController::class, 'updateUser'])->name('staff-user-update');
    Route::delete('user-delete', [StaffController::class, 'deleteUser'])->name('staff-user-delete');
    Route::get('user-list', [StaffController::class, 'userList'])->name('staff-user-list');
    Route::get('manage-user', [StaffController::class, 'manageUser'])->name('staff-manage-user');
    Route::post('user-block', [StaffController::class, 'blockUser'])->name('staff-user-block');
    Route::post('email-block', [StaffController::class, 'blockEmail'])->name('staff-email-block');
    Route::post('phone-block', [StaffController::class, 'blockPhone'])->name('staff-phone-block');

    Route::get('manual-payment-request', [StaffController::class, 'getManualPaymentRequest'])->name('staff-manual-payment-request');
    Route::get('manual-payment-request/{custom}', [StaffController::class, 'viewManualPaymentRequest'])->name('staff-manual-payment-request-view');
    Route::post('manual-payment-request-cancel', [StaffController::class, 'cancelManualPaymentRequest'])->name('staff-manual-payment-request-cancel');
    Route::post('manual-payment-request-confirm', [StaffController::class, 'confirmManualPaymentRequest'])->name('staff-manual-payment-request-confirm');
    Route::delete('manual-payment-request-delete', [StaffController::class, 'deleteManualPaymentRequest'])->name('staff-manual-payment-request-delete');

    Route::get('withdraw-request', [StaffController::class, 'allWithdrawRequest'])->name('staff-withdraw-request');
    Route::get('withdraw-request-view/{custom}', [StaffController::class, 'WithdrawRequestView'])->name('staff-withdraw-request-view');
    Route::post('withdraw-refund', [StaffController::class, 'WithdrawRefund'])->name('staff-withdraw-refund');
    Route::post('withdraw-confirm', [StaffController::class, 'WithdrawConfirm'])->name('staff-withdraw-confirm');
});

Route::get('clear-cache', function () {
    $output = new \Symfony\Component\Console\Output\BufferedOutput();
    Artisan::call('optimize:clear', [], $output);
    echo '<pre>';
    return $output->fetch();
})->name('clear-cache');
