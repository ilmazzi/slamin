<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\WebhookController;


// Broadcasting routes per l'autenticazione dei canali privati
Broadcast::routes(['middleware' => ['web', 'auth']]);

// Webhook routes (senza middleware CSRF)
Route::post('/webhook/stripe', [WebhookController::class, 'stripe'])->name('webhook.stripe');

// Translator Payout Routes
Route::prefix('translator/payouts')->name('translator.payouts.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\Translator\PayoutController::class, 'index'])->name('index');
    Route::get('/setup', [App\Http\Controllers\Translator\PayoutController::class, 'setup'])->name('setup');
    Route::post('/stripe/create', [App\Http\Controllers\Translator\PayoutController::class, 'createStripeAccount'])->name('create-stripe-account');
    Route::get('/stripe/update-status', [App\Http\Controllers\Translator\PayoutController::class, 'updateStripeStatus'])->name('update-stripe-status');
    Route::post('/paypal/setup', [App\Http\Controllers\Translator\PayoutController::class, 'setupPayPal'])->name('setup-paypal');
    Route::get('/{payment}', [App\Http\Controllers\Translator\PayoutController::class, 'show'])->name('show');
    Route::post('/{payment}/request-manual', [App\Http\Controllers\Translator\PayoutController::class, 'requestManualPayout'])->name('request-manual');
});

// Profile Payment Accounts Routes
Route::prefix('profile/payment-accounts')->name('profile.payment-accounts.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\Profile\PaymentAccountsController::class, 'index'])->name('index');
    Route::post('/stripe/create', [App\Http\Controllers\Profile\PaymentAccountsController::class, 'createStripeAccount'])->name('create-stripe');
    Route::get('/stripe/update-status', [App\Http\Controllers\Profile\PaymentAccountsController::class, 'updateStripeStatus'])->name('update-stripe-status');
    Route::post('/paypal/setup', [App\Http\Controllers\Profile\PaymentAccountsController::class, 'setupPayPal'])->name('setup-paypal');
    Route::post('/bank/setup', [App\Http\Controllers\Profile\PaymentAccountsController::class, 'setupBankDetails'])->name('setup-bank');
    Route::post('/preferred-method', [App\Http\Controllers\Profile\PaymentAccountsController::class, 'setPreferredPayoutMethod'])->name('set-preferred-method');
    Route::post('/disconnect', [App\Http\Controllers\Profile\PaymentAccountsController::class, 'disconnectAccount'])->name('disconnect');
    Route::get('/stripe/onboarding', [App\Http\Controllers\Profile\PaymentAccountsController::class, 'createStripeOnboardingLink'])->name('stripe-onboarding');
});

// Admin Payment Accounts Routes
Route::prefix('admin/payment-accounts')->name('admin.payment-accounts.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\PaymentAccountsController::class, 'index'])->name('index');
    Route::get('/{user}', [App\Http\Controllers\Admin\PaymentAccountsController::class, 'show'])->name('show');
    Route::post('/{user}/verify-paypal', [App\Http\Controllers\Admin\PaymentAccountsController::class, 'verifyPayPal'])->name('verify-paypal');
    Route::post('/{user}/unverify-paypal', [App\Http\Controllers\Admin\PaymentAccountsController::class, 'unverifyPayPal'])->name('unverify-paypal');
    Route::get('/{user}/update-stripe-status', [App\Http\Controllers\Admin\PaymentAccountsController::class, 'updateStripeStatus'])->name('update-stripe-status');
    Route::post('/{user}/disconnect', [App\Http\Controllers\Admin\PaymentAccountsController::class, 'disconnectAccount'])->name('disconnect');
    Route::get('/paypal/verification', [App\Http\Controllers\Admin\PaymentAccountsController::class, 'paypalVerification'])->name('paypal-verification');
    Route::get('/stripe/issues', [App\Http\Controllers\Admin\PaymentAccountsController::class, 'stripeIssues'])->name('stripe-issues');
    Route::get('/statistics', [App\Http\Controllers\Admin\PaymentAccountsController::class, 'statistics'])->name('statistics');
    Route::post('/export', [App\Http\Controllers\Admin\PaymentAccountsController::class, 'export'])->name('export');
});

// Admin Dashboard
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
});

// Admin Settings - Separate sections
Route::prefix('admin/settings')->name('admin.settings.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\SystemSettingsController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\Admin\SystemSettingsController::class, 'update'])->name('update');

    // Payment Settings
    Route::get('/payment', [App\Http\Controllers\Admin\PaymentSettingsController::class, 'index'])->name('payment.index');
    Route::post('/payment', [App\Http\Controllers\Admin\PaymentSettingsController::class, 'update'])->name('payment.update');
    Route::get('/payment/reset', [App\Http\Controllers\Admin\PaymentSettingsController::class, 'reset'])->name('payment.reset');

    // Upload Settings
    Route::get('/upload', [App\Http\Controllers\Admin\UploadSettingsController::class, 'index'])->name('upload.index');
    Route::post('/upload', [App\Http\Controllers\Admin\UploadSettingsController::class, 'update'])->name('upload.update');
});

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Test route for debugging gigs issue
Route::get('/gigs-test', function() {
    $categories = __('gigs.categories');
    $types = __('gigs.types');
    $sortOptions = __('gigs.filters.sort_options');
    return view('gigs.test', compact('categories', 'types', 'sortOptions'));
})->name('gigs.test');

// Simple test route for gigs
Route::get('/gigs-simple', function() {
    $categories = __('gigs.categories');
    $types = __('gigs.types');
    $sortOptions = __('gigs.filters.sort_options');
    return view('gigs.index-simple', compact('categories', 'types', 'sortOptions'));
})->name('gigs.simple');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Report Routes (autenticati)
Route::prefix('reports')->name('reports.')->middleware('auth')->group(function () {
    Route::get('/create', [App\Http\Controllers\ReportController::class, 'showReportForm'])->name('create');
    Route::post('/store', [App\Http\Controllers\ReportController::class, 'store'])->name('store');
    Route::post('/remove', [App\Http\Controllers\ReportController::class, 'remove'])->name('remove');
});

// API Routes per paginazione profilo
Route::prefix('api/profile')->name('api.profile.')->middleware('auth')->group(function () {
    Route::get('/{user}/articles', [App\Http\Controllers\ProfileController::class, 'getArticles'])->name('articles');
});



// Authentication Routes (pubbliche)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::get('/register', [AuthController::class, 'showSignup'])->name('register');
Route::post('/register', [AuthController::class, 'processSignup'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');





Route::view('index', 'index')->name('index');
Route::view('project_dashboard', 'project_dashboard')->name('project_dashboard');

Route::view('accordions', 'accordions')->name('accordions');
Route::view('add_blog', 'add_blog')->name('add_blog');
Route::view('add_product', 'add_product')->name('add_product');
Route::view('advance_table', 'advance_table')->name('advance_table');
Route::view('alert', 'alert')->name('alert');
Route::view('alignment', 'alignment')->name('alignment');
Route::view('animated_icon', 'animated_icon')->name('animated_icon');
Route::view('animation', 'animation')->name('animation');
Route::view('api', 'api')->name('api');
Route::view('area_chart', 'area_chart')->name('area_chart');
Route::view('avatar', 'avatar')->name('avatar');

Route::view('background', 'background')->name('background');
Route::view('badges', 'badges')->name('badges');
Route::view('bar_chart', 'bar_chart')->name('bar_chart');
Route::view('base_inputs', 'base_inputs')->name('base_inputs');
Route::view('basic_table', 'basic_table')->name('basic_table');
Route::view('blank', 'blank')->name('blank');
Route::view('block_ui', 'block_ui')->name('block_ui');
Route::view('blog', 'blog')->name('blog');
Route::view('blog_details', 'blog_details')->name('blog_details');
Route::view('bookmark', 'bookmark')->name('bookmark');
Route::view('bootstrap_slider', 'bootstrap_slider')->name('bootstrap_slider');
Route::view('boxplot_chart', 'boxplot_chart')->name('boxplot_chart');
Route::view('bubble_chart', 'bubble_chart')->name('bubble_chart');
Route::view('bullet', 'bullet')->name('bullet');
Route::view('buttons', 'buttons')->name('buttons');

Route::view('calendar', 'calendar')->name('calendar');
Route::view('candlestick_chart', 'candlestick_chart')->name('candlestick_chart');
Route::view('cards', 'cards')->name('cards');
Route::view('cart', 'cart')->name('cart');
Route::view('chart_js', 'chart_js')->name('chart_js');







Route::view('cheatsheet', 'cheatsheet')->name('cheatsheet');
Route::view('checkbox_radio', 'checkbox_radio')->name('checkbox_radio');
Route::view('checkout', 'checkout')->name('checkout');
Route::view('clipboard', 'clipboard')->name('clipboard');
Route::view('collapse', 'collapse')->name('collapse');
Route::view('column_chart', 'column_chart')->name('column_chart');
Route::view('coming_soon', 'coming_soon')->name('coming_soon');
Route::view('count_down', 'count_down')->name('count_down');
Route::view('count_up', 'count_up')->name('count_up');

Route::view('data_table', 'data_table')->name('data_table');
Route::view('date_picker', 'date_picker')->name('date_picker');
Route::view('default_forms', 'default_forms')->name('default_forms');
Route::view('divider', 'divider')->name('divider');
Route::view('draggable', 'draggable')->name('draggable');
Route::view('dropdown', 'dropdown')->name('dropdown');
Route::view('dual_list_boxes', 'dual_list_boxes')->name('dual_list_boxes');

Route::view('editor', 'editor')->name('editor');
Route::view('email', 'email')->name('email');
Route::view('error_400', 'error_400')->name('error_400');
Route::view('error_403', 'error_403')->name('error_403');
Route::view('error_404', 'error_404')->name('error_404');
Route::view('error_500', 'error_500')->name('error_500');
Route::view('error_503', 'error_503')->name('error_503');

Route::view('faq', 'faq')->name('faq');
Route::view('file_manager', 'file_manager')->name('file_manager');
Route::view('file_upload', 'file_upload')->name('file_upload');
Route::view('flag_icons', 'flag_icons')->name('flag_icons');
Route::view('floating_labels', 'floating_labels')->name('floating_labels');
Route::view('fontawesome', 'fontawesome')->name('fontawesome');
Route::view('footer_page', 'footer_page')->name('footer_page');
Route::view('form_validation', 'form_validation')->name('form_validation');
Route::view('form_wizard_1', 'form_wizard_1')->name('form_wizard_1');
Route::view('form_wizard_2', 'form_wizard_2')->name('form_wizard_2');
Route::view('form_wizards', 'form_wizards')->name('form_wizards');

Route::view('gallery', 'gallery')->name('gallery');
Route::view('google_map', 'google_map')->name('google_map');
Route::view('grid', 'grid')->name('grid');

Route::view('heatmap', 'heatmap')->name('heatmap');
Route::view('helper_classes', 'helper_classes')->name('helper_classes');

Route::view('iconoir_icon', 'iconoir_icon')->name('iconoir_icon');
Route::view('input_groups', 'input_groups')->name('input_groups');
Route::view('input_masks', 'input_masks')->name('input_masks');
Route::view('invoice', 'invoice')->name('invoice');

Route::get('kanban_board', [App\Http\Controllers\TaskController::class, 'index'])->name('kanban_board');

// Task/Kanban routes for public kanban
Route::get('/tasks/{task}/details', [App\Http\Controllers\TaskController::class, 'show'])->name('tasks.details');
Route::patch('/tasks/{task}/status', [App\Http\Controllers\TaskController::class, 'updateStatus'])->name('tasks.update-status');
Route::put('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');
// Route moved to tasks group below

Route::view('landing', 'landing')->name('landing');
Route::view('leaflet_map', 'leaflet_map')->name('leaflet_map');
Route::view('line_chart', 'line_chart')->name('line_chart');
Route::view('list', 'list')->name('list');
Route::view('list_table', 'list_table')->name('list_table');
Route::view('lock_screen', 'lock_screen')->name('lock_screen');
Route::view('lock_screen_1', 'lock_screen_1')->name('lock_screen_1');


Route::view('maintenance', 'maintenance')->name('maintenance');
Route::view('misc', 'misc')->name('misc');
Route::view('mixed_chart', 'mixed_chart')->name('mixed_chart');
Route::view('modals', 'modals')->name('modals');


Route::view('offcanvas', 'offcanvas')->name('offcanvas');
Route::view('orders', 'orders')->name('orders');
Route::view('order_details', 'order_details')->name('order_details');
Route::view('order_list', 'order_list')->name('order_list');

Route::view('password_create_1', 'password_create_1')->name('password_create_1');
Route::view('password_reset_1', 'password_reset_1')->name('password_reset_1');
Route::view('phosphor', 'phosphor')->name('phosphor');
Route::view('pie_charts', 'pie_charts')->name('pie_charts');
Route::view('placeholder', 'placeholder')->name('placeholder');
Route::view('pricing', 'pricing')->name('pricing');
Route::view('prismjs', 'prismjs')->name('prismjs');
Route::view('privacy_policy', 'privacy_policy')->name('privacy_policy');
Route::view('product', 'product')->name('product');
Route::view('product_details', 'product_details')->name('product_details');
Route::view('product_list', 'product_list')->name('product_list');
Route::view('profile', 'profile')->name('profile');
Route::view('progress', 'progress')->name('progress');

// Photo Routes
Route::prefix('photos')->name('photos.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\PhotoController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\PhotoController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\PhotoController::class, 'store'])->name('store');
    Route::get('/{photo}', [App\Http\Controllers\PhotoController::class, 'show'])->name('show');
    Route::get('/{photo}/edit', [App\Http\Controllers\PhotoController::class, 'edit'])->name('edit');
    Route::put('/{photo}', [App\Http\Controllers\PhotoController::class, 'update'])->name('update');
    Route::delete('/{photo}', [App\Http\Controllers\PhotoController::class, 'destroy'])->name('destroy');

    // API per ottenere foto di un utente
    Route::get('/user/{userId}', [App\Http\Controllers\PhotoController::class, 'getUserPhotos'])->name('user');
});
Route::view('project_app', 'project_app')->name('project_app');
Route::view('project_details', 'project_details')->name('project_details');
Route::view('password_create', 'password_create')->name('password_create');
Route::view('password_reset', 'password_reset')->name('password_reset');

Route::view('radar_chart', 'radar_chart')->name('radar_chart');
Route::view('radial_bar_chart', 'radial_bar_chart')->name('radial_bar_chart');
Route::view('range_slider', 'range_slider')->name('range_slider');
Route::view('ratings', 'ratings')->name('ratings');
Route::view('read_email', 'read_email')->name('read_email');
Route::view('ready_to_use_form', 'ready_to_use_form')->name('ready_to_use_form');
Route::view('ready_to_use_table', 'ready_to_use_table')->name('ready_to_use_table');
Route::view('ribbons', 'ribbons')->name('ribbons');

Route::view('scatter_chart', 'scatter_chart')->name('scatter_chart');
Route::view('scrollbar', 'scrollbar')->name('scrollbar');
Route::view('scrollpy', 'scrollpy')->name('scrollpy');
Route::view('select', 'select')->name('select');
Route::view('setting', 'setting')->name('setting');
Route::view('shadow', 'shadow')->name('shadow');
Route::view('sign_in', 'sign_in')->name('sign_in');
Route::view('sign_in_1', 'sign_in_1')->name('sign_in_1');
Route::view('sign_up', 'sign_up')->name('sign_up');
Route::view('sign_up_1', 'sign_up_1')->name('sign_up_1');
Route::view('sitemap', 'sitemap')->name('sitemap');
Route::view('slick_slider', 'slick_slider')->name('slick_slider');
Route::view('spinners', 'spinners')->name('spinners');
Route::view('sweetalert', 'sweetalert')->name('sweetalert');
Route::view('switch', 'switch')->name('switch');

Route::view('tabler_icons', 'tabler_icons')->name('tabler_icons');
Route::view('tabs', 'tabs')->name('tabs');
Route::view('team', 'team')->name('team');
Route::view('terms_condition', 'terms_condition')->name('terms_condition');
Route::view('textarea', 'textarea')->name('textarea');
Route::view('ticket', 'ticket')->name('ticket');
Route::view('ticket_details', 'ticket_details')->name('ticket_details');
Route::view('timeline', 'timeline')->name('timeline');
Route::view('timeline_range_charts', 'timeline_range_charts')->name('timeline_range_charts');
Route::view('to_do', 'to_do')->name('to_do');
Route::view('tooltips_popovers', 'tooltips_popovers')->name('tooltips_popovers');
Route::view('touch_spin', 'touch_spin')->name('touch_spin');
Route::view('tour', 'tour')->name('tour');
Route::view('tree-view', 'tree-view')->name('tree-view');
Route::view('treemap_chart', 'treemap_chart')->name('treemap_chart');
Route::view('two_step_verification', 'two_step_verification')->name('two_step_verification');
Route::view('two_step_verification_1', 'two_step_verification_1')->name('two_step_verification_1');
Route::view('typeahead', 'typeahead')->name('typeahead');


Route::view('video_embed', 'video_embed')->name('video_embed');
Route::view('weather_icon', 'weather_icon')->name('weather_icon');
Route::view('widget', 'widget')->name('widget');
Route::view('wishlist', 'wishlist')->name('wishlist');
Route::view('wrapper', 'wrapper')->name('wrapper');

/*
|--------------------------------------------------------------------------
| 🎭 Slamin Test Routes
|--------------------------------------------------------------------------
| Route per testare il sistema di ruoli e permessi di Slamin
| SOLO PER TESTING - Rimuovere in produzione
*/

use App\Http\Controllers\TestController;

Route::prefix('slamin-test')->name('slamin.test.')->group(function () {
    // Login di test (pubblico)
    Route::get('/login', [TestController::class, 'loginTest'])->name('login');
    Route::post('/quick-login', [TestController::class, 'quickLogin'])->name('quick-login');
    Route::post('/logout', [TestController::class, 'logout'])->name('logout');

    // Signup e Login reali
    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'processSignup'])->name('signup.process');
    Route::get('/real-login', [AuthController::class, 'showLogin'])->name('real-login');
    Route::post('/real-login', [AuthController::class, 'processLogin'])->name('real-login.process');

    // Pagine protette (richiedono autenticazione)
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [TestController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [TestController::class, 'users'])->name('users');
        Route::get('/permissions', [TestController::class, 'permissions'])->name('permissions');

        // API per test permessi
        Route::post('/test-permission', [TestController::class, 'testPermission'])->name('test-permission');
        Route::post('/assign-role/{user}', [TestController::class, 'assignRole'])->name('assign-role');
    });
});

// Route di accesso rapido al test
Route::get('/test', function () {
    return redirect()->route('slamin.test.login');
});

// Dashboard moderna multilanguage
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/switch-language', [App\Http\Controllers\Dashboard\DashboardController::class, 'switchLanguage'])->name('switch-language');

    // User Statistics
    Route::get('/user-stats', [App\Http\Controllers\UserStatsController::class, 'index'])->name('user-stats.index');
});

/*
|--------------------------------------------------------------------------
| 🎪 Slamin Events System
|--------------------------------------------------------------------------
| Sistema completo per la gestione eventi Slamin:
| - Creazione e gestione eventi
| - Sistema inviti organizzatori → artisti
| - Richieste partecipazione artisti → eventi pubblici
| - Notifiche real-time
| - Geolocalizzazione e mappa eventi
*/

use App\Http\Controllers\EventController;
use App\Http\Controllers\EventInvitationController;
use App\Http\Controllers\EventRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Public event routes (no auth required)
Route::get('/events', [App\Http\Controllers\EventController::class, 'index'])->name('events.index');

// TEST: View semplice per verificare se il sistema view funziona
Route::get('/test-simple-view', function () {
    return view('dashboard.index', ['stats' => []]);
})->name('test-simple-view');

// IMPORTANTE: events/create DEVE stare PRIMA di events/{event} per evitare conflitti!
Route::get('/events/create', [EventController::class, 'create'])->name('events.create')->middleware('auth');

// Route per i luoghi recenti (pubblica)
Route::get('/events/recent-venues', [EventController::class, 'getRecentVenues'])->name('events.recent-venues')->middleware('auth');

Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/api/events/near', [EventController::class, 'near'])->name('events.near');
Route::get('/api/events', [EventController::class, 'events'])->name('events.api');
Route::get('/api/events/search', [EventController::class, 'searchEvents'])->name('events.search');
Route::get('/api/festivals', [EventController::class, 'getFestivals'])->name('festivals.api');

// Test endpoint semplificato
Route::get('/api/events/test', function() {
    try {
        $events = \App\Models\Event::where('is_public', true)
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->limit(5)
                    ->get(['id', 'title', 'latitude', 'longitude', 'venue_name', 'city', 'start_datetime']);

        return response()->json([
            'success' => true,
            'count' => $events->count(),
            'events' => $events,
            'debug' => [
                'total_events' => \App\Models\Event::count(),
                'public_events' => \App\Models\Event::where('is_public', true)->count(),
                'events_with_coords' => \App\Models\Event::whereNotNull('latitude')->whereNotNull('longitude')->count()
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});


// Route per i luoghi recenti (pubblica)
// Route::get('/events/recent-venues', [EventController::class, 'getRecentVenues'])->name('events.recent-venues');

Route::post('/events', [EventController::class, 'store'])->name('events.store')->middleware('auth');

// API route per ricerca utenti (pubblica)
Route::get('/api/users/search', [EventController::class, 'searchUsers'])->name('api.users.search');

// Test routes per le pagine di errore
Route::get('/test/error/403', function () {
    abort(403, 'Test 403');
})->name('test.403');

Route::get('/test/error/404', function () {
    abort(404, 'Test 404');
})->name('test.404');

Route::get('/test/error/500', function () {
    abort(500, 'Test 500');
})->name('test.500');

// TEST: Route identica ma con URL diverso
Route::get('/create-event-test', [EventController::class, 'create'])->name('create-event-test');

// TEST: Route con closure semplice per bypassare controller
Route::get('/test-simple-create', function () {
    $venueOwners = App\Models\User::whereHas('roles', function ($query) {
        $query->where('name', 'venue_owner');
    })->get();

    return view('events.create', compact('venueOwners'));
})->name('test-simple-create');

// Protected event routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Event management (organizers) - SENZA CREATE PER TEST
    Route::resource('events', EventController::class)->except(['index', 'show', 'create', 'store']);
    Route::get('/events/{event}/manage', [EventController::class, 'manage'])->name('events.manage');
    Route::post('/events/{event}/apply', [EventController::class, 'apply'])->name('events.apply');
    Route::get('/api/events/calendar', [EventController::class, 'calendar'])->name('events.calendar');

    // Event availability routes
    Route::prefix('events/{event}/availability')->name('events.availability.')->group(function () {
        Route::get('/', [App\Http\Controllers\EventAvailabilityController::class, 'show'])->name('show');
        Route::get('/respond', [App\Http\Controllers\EventAvailabilityController::class, 'respond'])->name('respond');
        Route::post('/options', [App\Http\Controllers\EventAvailabilityController::class, 'storeOptions'])->name('store-options');
        Route::post('/response', [App\Http\Controllers\EventAvailabilityController::class, 'storeResponse'])->name('store-response');
        Route::delete('/options/{option}', [App\Http\Controllers\EventAvailabilityController::class, 'deleteOption'])->name('delete-option');
        Route::get('/options/{option}/responses', [App\Http\Controllers\EventAvailabilityController::class, 'getOptionResponses'])->name('option-responses');
    });

    // Wishlist routes
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [App\Http\Controllers\WishlistController::class, 'index'])->name('index');
        Route::post('/{event}/add', [App\Http\Controllers\WishlistController::class, 'add'])->name('add');
        Route::delete('/{event}/remove', [App\Http\Controllers\WishlistController::class, 'remove'])->name('remove');
        Route::post('/{event}/toggle', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('toggle');
        Route::get('/{event}/check', [App\Http\Controllers\WishlistController::class, 'check'])->name('check');
        Route::get('/calendar', [App\Http\Controllers\WishlistController::class, 'calendar'])->name('calendar');
    });

// Invitation routes
Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations.index');
Route::get('/invitations/{invitation}', [InvitationController::class, 'show'])->name('invitations.show');
Route::get('/invitations/{invitation}/accept', [InvitationController::class, 'accept'])->name('invitations.accept');
Route::get('/invitations/{invitation}/decline', [InvitationController::class, 'decline'])->name('invitations.decline');

// Request routes (moved to group below)

    // Event invitations (organizer management)
    Route::prefix('events/{event}/invitations')->name('event-invitations.')->group(function () {
        Route::get('/', [EventInvitationController::class, 'index'])->name('index');
        Route::get('/{invitation}', [EventInvitationController::class, 'show'])->name('show');
        Route::post('/', [EventInvitationController::class, 'store'])->name('store');
        Route::patch('/{invitation}/accept', [EventInvitationController::class, 'accept'])->name('accept');
        Route::patch('/{invitation}/decline', [EventInvitationController::class, 'decline'])->name('decline');
        Route::delete('/{invitation}', [EventInvitationController::class, 'cancel'])->name('cancel');
        Route::post('/{invitation}/resend', [EventInvitationController::class, 'resend'])->name('resend');

        // API routes for invitations
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/statistics', [EventInvitationController::class, 'statistics'])->name('statistics');
            Route::post('/mark-expired', [EventInvitationController::class, 'markExpired'])->name('mark-expired');
            Route::post('/bulk-action', [EventInvitationController::class, 'bulkAction'])->name('bulk-action');
        });
    });

    // Event requests
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/', [EventRequestController::class, 'index'])->name('index');
        Route::get('/{eventRequest}', [EventRequestController::class, 'show'])->name('show');
        Route::patch('/{eventRequest}/accept', [EventRequestController::class, 'accept'])->name('accept');
        Route::patch('/{eventRequest}/decline', [EventRequestController::class, 'decline'])->name('decline');
        Route::delete('/{eventRequest}', [EventRequestController::class, 'cancel'])->name('cancel');
        Route::post('/{eventRequest}/accept-ajax', [EventRequestController::class, 'acceptAjax'])->name('accept.ajax');
        Route::post('/{eventRequest}/decline-ajax', [EventRequestController::class, 'declineAjax'])->name('decline.ajax');

        // API routes for requests
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/events/{event}/statistics', [EventRequestController::class, 'statistics'])->name('statistics');
            Route::post('/events/{event}/bulk-action', [EventRequestController::class, 'bulkAction'])->name('bulk-action');
            Route::get('/pending', [EventRequestController::class, 'pending'])->name('pending');
            Route::post('/{eventRequest}/quick-response', [EventRequestController::class, 'quickResponse'])->name('quick-response');
            Route::get('/events/{event}/form-data', [EventRequestController::class, 'formData'])->name('form-data');
        });
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::patch('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::patch('/{notification}/unread', [NotificationController::class, 'markAsUnread'])->name('mark-unread');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::post('/bulk-action', [NotificationController::class, 'bulkAction'])->name('bulk-action');
        Route::delete('/cleanup', [NotificationController::class, 'cleanup'])->name('cleanup');

        // API routes for real-time notifications
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/dropdown', [NotificationController::class, 'dropdown'])->name('dropdown');
            Route::get('/statistics', [NotificationController::class, 'statistics'])->name('statistics');
            Route::get('/realtime', [NotificationController::class, 'realtime'])->name('realtime');
        });
    });



    // Social API Routes (unified system) - PUBBLICHE - FUORI DA TUTTI I MIDDLEWARE
    Route::prefix('api/social')->name('api.social.')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])->group(function () {
        // View routes (pubbliche - non richiedono autenticazione)
        Route::post('/views/increment', [App\Http\Controllers\ViewController::class, 'increment'])->name('views.increment');
        Route::get('/views/content', [App\Http\Controllers\ViewController::class, 'getViewedContent'])->name('views.content');
        Route::get('/views/stats', [App\Http\Controllers\ViewController::class, 'getViewStats'])->name('views.stats');

        // Like routes (richiedono autenticazione)
        Route::middleware(['auth', 'verified'])->group(function () {
            Route::post('/likes/toggle', [App\Http\Controllers\LikeController::class, 'toggle'])->name('likes.toggle');
            Route::get('/likes/content', [App\Http\Controllers\LikeController::class, 'getLikedContent'])->name('likes.content');
            Route::get('/likes/stats', [App\Http\Controllers\LikeController::class, 'getLikeStats'])->name('likes.stats');
        });

        // Comment routes (richiedono autenticazione)
        Route::middleware(['auth', 'verified'])->group(function () {
            Route::post('/comments', [App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
            Route::put('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'update'])->name('comments.update');
            Route::delete('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
            Route::post('/comments/{comment}/approve', [App\Http\Controllers\CommentController::class, 'approve'])->name('comments.approve');
            Route::post('/comments/{comment}/reject', [App\Http\Controllers\CommentController::class, 'reject'])->name('comments.reject');
            Route::get('/comments', [App\Http\Controllers\CommentController::class, 'getComments'])->name('comments.list');
            Route::get('/comments/{comment}/replies', [App\Http\Controllers\CommentController::class, 'getReplies'])->name('comments.replies');
        });
    });

    // Development/testing routes (only in local environment)
    if (app()->environment('local')) {
        Route::post('/notifications/test', [NotificationController::class, 'test'])->name('notifications.test');
    }

    // API routes for user search and suggestions (excluding social routes)
    Route::prefix('api')->name('api.')->middleware('auth')->group(function () {
        Route::get('/users/search', [App\Http\Controllers\Api\UserController::class, 'search'])->name('users.search');
        Route::get('/users/suggested', [App\Http\Controllers\Api\UserController::class, 'suggested'])->name('users.suggested');
    });

    // Task/Kanban routes
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [App\Http\Controllers\TaskController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\TaskController::class, 'store'])->name('store');
        Route::get('/{task}', [App\Http\Controllers\TaskController::class, 'show'])->name('show');
        Route::put('/{task}', [App\Http\Controllers\TaskController::class, 'update'])->name('update');
        Route::patch('/{task}/status', [App\Http\Controllers\TaskController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{task}', [App\Http\Controllers\TaskController::class, 'destroy'])->name('destroy');
        Route::delete('/{task}/image', [App\Http\Controllers\TaskController::class, 'deleteImage'])->name('delete-image');
    });

    // Analytics routes
    Route::prefix('analytics')->name('analytics.')->middleware('auth')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('index');
        Route::get('/export', [AnalyticsController::class, 'export'])->name('export');
        Route::get('/realtime', [AnalyticsController::class, 'realtime'])->name('realtime');
    });

        // Permissions Management Routes
    Route::prefix('permissions')->name('permissions.')->middleware(['auth', 'admin.access'])->group(function () {
        Route::get('/', [App\Http\Controllers\PermissionController::class, 'index'])->name('index');
        Route::get('/roles', [App\Http\Controllers\PermissionController::class, 'roles'])->name('roles');
        Route::get('/permissions', [App\Http\Controllers\PermissionController::class, 'permissions'])->name('permissions');
        Route::get('/users', [App\Http\Controllers\PermissionController::class, 'users'])->name('users');

        // Role management
        Route::get('/roles/index', [App\Http\Controllers\PermissionController::class, 'rolesIndex'])->name('roles.index');
        Route::post('/roles', [App\Http\Controllers\PermissionController::class, 'storeRole'])->name('roles.store');
        Route::get('/roles/{role}/edit', [App\Http\Controllers\PermissionController::class, 'editRole'])->name('roles.edit');
        Route::post('/roles/{role}', [App\Http\Controllers\PermissionController::class, 'updateRole'])->name('roles.update');
        Route::delete('/roles/{role}', [App\Http\Controllers\PermissionController::class, 'deleteRole'])->name('roles.delete');

        // Permission management
        Route::get('/permissions/index', [App\Http\Controllers\PermissionController::class, 'permissionsIndex'])->name('permissions.index');
        Route::post('/permissions', [App\Http\Controllers\PermissionController::class, 'storePermission'])->name('permissions.store');
        Route::get('/permissions/{permission}/edit', [App\Http\Controllers\PermissionController::class, 'editPermission'])->name('permissions.edit');
        Route::post('/permissions/{permission}', [App\Http\Controllers\PermissionController::class, 'updatePermission'])->name('permissions.update');
        Route::delete('/permissions/{permission}', [App\Http\Controllers\PermissionController::class, 'deletePermission'])->name('permissions.delete');

        // User role/permission assignment
        Route::get('/users/index', [App\Http\Controllers\PermissionController::class, 'usersIndex'])->name('users.index');
        Route::get('/users/{user}', [App\Http\Controllers\PermissionController::class, 'getUser'])->name('users.show');
        Route::post('/users/{user}/roles', [App\Http\Controllers\PermissionController::class, 'assignUserRoles'])->name('users.roles');
        Route::post('/users/{user}/permissions', [App\Http\Controllers\PermissionController::class, 'assignUserPermissions'])->name('users.permissions');
        Route::delete('/users/{user}', [App\Http\Controllers\PermissionController::class, 'deleteUser'])->name('users.delete');

        // API routes
        Route::get('/stats', [App\Http\Controllers\PermissionController::class, 'getStats'])->name('stats');
    });

        // Carousel Management (Admin only)
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
        // Rota di test temporanea per debug (DEVE essere PRIMA del resource)
        Route::get('/carousels/test-search', function(\Illuminate\Http\Request $request) {
            return response()->json([
                'message' => 'Test route working',
                'user' => Auth::check() ? Auth::user()->name : 'Not authenticated',
                'is_admin' => Auth::check() ? Auth::user()->is_admin : false,
                'params' => $request->all()
            ]);
        })->name('carousels.test-search');

        // Test del metodo searchContent
        Route::get('/carousels/test-search-content', [App\Http\Controllers\Admin\CarouselController::class, 'searchContent'])->name('carousels.test-search-content');

        // Rota search-content DEVE essere PRIMA del resource
        Route::get('/carousels/search-content', [App\Http\Controllers\Admin\CarouselController::class, 'searchContent'])->name('carousels.search-content');

        Route::resource('carousels', App\Http\Controllers\Admin\CarouselController::class)->names('carousels');
        Route::post('/carousels/order', [App\Http\Controllers\Admin\CarouselController::class, 'updateOrder'])->name('carousels.order');

        // System Settings
        Route::get('/settings', [App\Http\Controllers\Admin\SystemSettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [App\Http\Controllers\Admin\SystemSettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/reset', [App\Http\Controllers\Admin\SystemSettingsController::class, 'reset'])->name('settings.reset');

                // System Logs
        Route::prefix('logs')->name('logs.')->group(function () {
            // Test route
            Route::get('/test', [App\Http\Controllers\Admin\TestLogsController::class, 'index'])->name('test');

            // Dashboard principale
            Route::get('/', [App\Http\Controllers\Admin\LogsController::class, 'index'])->name('index');

            // Log di attività (database)
            Route::get('/activity', [App\Http\Controllers\Admin\LogsController::class, 'activity'])->name('activity');

            // Log di errore (file)
            Route::get('/errors', [App\Http\Controllers\Admin\LogsController::class, 'errors'])->name('errors');

            // Dettagli log specifico
            Route::get('/{id}', [App\Http\Controllers\Admin\LogsController::class, 'show'])->name('show');

            // Download log
            Route::get('/download', [App\Http\Controllers\Admin\LogsController::class, 'download'])->name('download');

            // Pulizia log
            Route::post('/clear', [App\Http\Controllers\Admin\LogsController::class, 'clear'])->name('clear');
        });
        Route::get('/settings/api', [App\Http\Controllers\Admin\SystemSettingsController::class, 'getSettings'])->name('settings.api');
        Route::post('/settings/thumbnails', [App\Http\Controllers\Admin\SystemSettingsController::class, 'manageThumbnails'])->name('settings.thumbnails');




        // Kanban Board Routes
        Route::get('/kanban', [App\Http\Controllers\Admin\KanbanController::class, 'index'])->name('kanban.index');
        Route::post('/kanban/update-status', [App\Http\Controllers\Admin\KanbanController::class, 'updateTaskStatus'])->name('kanban.update-status');
        Route::post('/kanban/task-details', [App\Http\Controllers\Admin\KanbanController::class, 'getTaskDetails'])->name('kanban.task-details');
        Route::post('/kanban/tasks', [App\Http\Controllers\Admin\KanbanController::class, 'storeTask'])->name('kanban.store-task');
        Route::post('/kanban/comments', [App\Http\Controllers\Admin\KanbanController::class, 'addComment'])->name('kanban.add-comment');
        Route::post('/kanban/update-task', [App\Http\Controllers\Admin\KanbanController::class, 'updateTask'])->name('kanban.update-task');
        Route::post('/kanban/delete-image', [App\Http\Controllers\Admin\KanbanController::class, 'deleteImage'])->name('kanban.delete-image');
        Route::post('/kanban/delete-task', [App\Http\Controllers\Admin\KanbanController::class, 'deleteTask'])->name('kanban.delete-task');

        // Nuove route per le funzionalità interattive
        Route::get('/kanban/task/{taskId}/details', [App\Http\Controllers\Admin\KanbanController::class, 'getTaskDetailsForOverlay'])->name('kanban.task-details-overlay');
        Route::get('/kanban/task/{taskId}/edit', [App\Http\Controllers\Admin\KanbanController::class, 'getTaskForEdit'])->name('kanban.task-edit');
        Route::post('/kanban/task/comment', [App\Http\Controllers\Admin\KanbanController::class, 'addTaskComment'])->name('kanban.task-comment');

        // PeerTube Management Routes
        Route::get('/peertube', [App\Http\Controllers\Admin\PeerTubeController::class, 'index'])->name('peertube.index');
        Route::put('/peertube', [App\Http\Controllers\Admin\PeerTubeController::class, 'update'])->name('peertube.update');
        Route::get('/peertube/test-connection', [App\Http\Controllers\Admin\PeerTubeController::class, 'testConnectionApi'])->name('peertube.test-connection');
        Route::get('/peertube/statistics', [App\Http\Controllers\Admin\PeerTubeController::class, 'statistics'])->name('peertube.statistics');
        Route::get('/peertube/users', [App\Http\Controllers\Admin\PeerTubeController::class, 'users'])->name('peertube.users');

        // PeerTube User Management Routes
        Route::get('/peertube/manage-users', [App\Http\Controllers\Admin\PeerTubeController::class, 'manageUsers'])->name('peertube.manage-users');
        Route::post('/peertube/show-user', [App\Http\Controllers\Admin\PeerTubeController::class, 'showUser'])->name('peertube.show-user');
        Route::post('/peertube/create-user-account', [App\Http\Controllers\Admin\PeerTubeController::class, 'createUserAccount'])->name('peertube.create-user-account');
        Route::put('/peertube/update-user-data', [App\Http\Controllers\Admin\PeerTubeController::class, 'updateUserData'])->name('peertube.update-user-data');
        Route::post('/peertube/verify-user-exists', [App\Http\Controllers\Admin\PeerTubeController::class, 'verifyUserExists'])->name('peertube.verify-user-exists');
        Route::post('/peertube/sync-user-data', [App\Http\Controllers\Admin\PeerTubeController::class, 'syncUserData'])->name('peertube.sync-user-data');
        Route::put('/peertube/change-user-email', [App\Http\Controllers\Admin\PeerTubeController::class, 'changeUserEmail'])->name('peertube.change-user-email');
        Route::delete('/peertube/delete-user', [App\Http\Controllers\Admin\PeerTubeController::class, 'deletePeerTubeUser'])->name('peertube.delete-user');

        // User Management Routes
        Route::resource('users', App\Http\Controllers\Admin\UserController::class)->names('users');
        Route::post('/users/bulk-assign', [App\Http\Controllers\Admin\UserController::class, 'bulkAssign'])->name('users.bulk-assign');
        Route::get('/users/export', [App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');

        // Moderation Routes
        Route::prefix('moderation')->name('moderation.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ModerationController::class, 'index'])->name('index');
            Route::get('/pending', [App\Http\Controllers\Admin\ModerationController::class, 'index'])->name('pending');
            Route::post('/approve/{type}/{id}', [App\Http\Controllers\Admin\ModerationController::class, 'approve'])->name('approve');
            Route::post('/reject/{type}/{id}', [App\Http\Controllers\Admin\ModerationController::class, 'reject'])->name('reject');
            Route::post('/approve-all/{type}', [App\Http\Controllers\Admin\ModerationController::class, 'approveAll'])->name('approve-all');
            Route::post('/reports/{report}/handle', [App\Http\Controllers\Admin\ModerationController::class, 'handleReport'])->name('reports.handle');
                Route::get('/reports/{report}/details', [App\Http\Controllers\Admin\ModerationController::class, 'getReportedContentDetails'])->name('reports.details');
    Route::get('/reports/content/{type}/{id}', [App\Http\Controllers\Admin\ModerationController::class, 'getContentReports'])->name('reports.content');
    Route::get('/reports/{report}/content', [App\Http\Controllers\Admin\ModerationController::class, 'getReportContent'])->name('reports.content.view');
    Route::get('/content/{type}/{id}', [App\Http\Controllers\Admin\ModerationController::class, 'getContent'])->name('content.view');
            Route::get('/settings', [App\Http\Controllers\Admin\ModerationController::class, 'settings'])->name('settings');
            Route::post('/settings', [App\Http\Controllers\Admin\ModerationController::class, 'updateSettings'])->name('settings.update');
        });


    });

    // Profile Routes (accessibili a tutti gli utenti autenticati)
    Route::prefix('profile')->name('profile.')->middleware('auth')->group(function () {
        Route::get('/', [App\Http\Controllers\ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('update');
        Route::get('/videos', [App\Http\Controllers\ProfileController::class, 'videos'])->name('videos');
        Route::delete('/videos/{video}', [App\Http\Controllers\ProfileController::class, 'deleteVideo'])->name('videos.delete');
        Route::get('/activity', [App\Http\Controllers\ProfileController::class, 'activity'])->name('activity');
        Route::get('/followers', [App\Http\Controllers\ProfileController::class, 'followers'])->name('followers');
        Route::get('/following', [App\Http\Controllers\ProfileController::class, 'following'])->name('following');

        // Language Routes
        Route::prefix('languages')->name('languages.')->group(function () {
            Route::get('/', [App\Http\Controllers\UserLanguageController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\UserLanguageController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\UserLanguageController::class, 'store'])->name('store');
            Route::get('/{userLanguage}/edit', [App\Http\Controllers\UserLanguageController::class, 'edit'])->name('edit');
            Route::put('/{userLanguage}', [App\Http\Controllers\UserLanguageController::class, 'update'])->name('update');
            Route::delete('/{userLanguage}', [App\Http\Controllers\UserLanguageController::class, 'destroy'])->name('destroy');
            Route::get('/search', [App\Http\Controllers\UserLanguageController::class, 'search'])->name('search');
        });
    });

    // Public Profile Routes (accessibili a tutti)
    Route::get('/user/{user}', [App\Http\Controllers\ProfileController::class, 'show'])->name('user.show');
    Route::get('/user/{user}/followers', [App\Http\Controllers\ProfileController::class, 'followers'])->name('user.followers');
    Route::get('/user/{user}/following', [App\Http\Controllers\ProfileController::class, 'following'])->name('user.following');

    // Video Routes
    Route::prefix('videos')->name('videos.')->middleware('auth')->group(function () {
        Route::get('/', function() {
            return redirect()->route('profile.videos');
        })->name('index');

        // Video upload page
        Route::get('/upload', function() {
            $user = Auth::user();
            return view('videos.upload', compact('user'));
        })->name('upload');

        // Video upload processing
        Route::post('/upload', [App\Http\Controllers\ProfileController::class, 'uploadVideo'])->name('store');

        // Video playback and views
        Route::get('/{video}', [App\Http\Controllers\VideoController::class, 'show'])->name('show');
        Route::post('/{video}/views', [App\Http\Controllers\VideoController::class, 'incrementViews'])->name('increment-views');
        Route::get('/{video}/download', [App\Http\Controllers\VideoController::class, 'download'])->name('download');


        // Video interactions (comments, likes, snaps)
        Route::post('/{video}/comments', [App\Http\Controllers\VideoController::class, 'addComment'])->name('add-comment');
        Route::post('/{video}/like', [App\Http\Controllers\VideoController::class, 'toggleLike'])->name('toggle-like');
        Route::post('/{video}/snaps', [App\Http\Controllers\VideoController::class, 'addSnap'])->name('add-snap');
        Route::delete('/comments/{comment}', [App\Http\Controllers\VideoController::class, 'deleteComment'])->name('delete-comment');
        Route::delete('/snaps/{snap}', [App\Http\Controllers\VideoController::class, 'deleteSnap'])->name('delete-snap');
        Route::post('/snaps/{snap}/like', [App\Http\Controllers\VideoController::class, 'toggleSnapLike'])->name('snap-like');
    });





    // PeerTube upload route alias
    Route::get('/peertube/upload', function() {
        $user = Auth::user();
        return view('videos.upload', compact('user'));
    })->name('peertube.upload-video')->middleware(['auth', 'verified']);

// Premium Routes
Route::prefix('premium')->name('premium.')->middleware(['auth', 'verified'])->group(function () {
        Route::get('/', [App\Http\Controllers\PremiumController::class, 'index'])->name('index');
        Route::get('/packages/{package}', [App\Http\Controllers\PremiumController::class, 'show'])->name('show');
        Route::get('/checkout/{package}', [App\Http\Controllers\PremiumController::class, 'checkout'])->name('checkout');
        Route::post('/purchase/{package}', [App\Http\Controllers\PremiumController::class, 'processPurchase'])->name('purchase');
        Route::get('/success/{subscription}', [App\Http\Controllers\PremiumController::class, 'success'])->name('success');
        Route::get('/manage', [App\Http\Controllers\PremiumController::class, 'manage'])->name('manage');
        Route::post('/cancel/{subscription}', [App\Http\Controllers\PremiumController::class, 'cancel'])->name('cancel');
        Route::post('/renew/{subscription}', [App\Http\Controllers\PremiumController::class, 'renew'])->name('renew');
        Route::get('/compare', [App\Http\Controllers\PremiumController::class, 'compare'])->name('compare');
        Route::get('/faq', [App\Http\Controllers\PremiumController::class, 'faq'])->name('faq');
    });

    // Public Profile Routes (accessibili a tutti)
    Route::get('/user/{user}', [App\Http\Controllers\ProfileController::class, 'show'])->name('user.show');
});

    // Media Routes (pubbliche - fuori dal gruppo auth)
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/', [App\Http\Controllers\MediaController::class, 'index'])->name('index');
        Route::post('/like', [App\Http\Controllers\MediaController::class, 'like'])->name('like');
        Route::post('/comment', [App\Http\Controllers\MediaController::class, 'comment'])->name('comment');
    });

    // Report Routes
    Route::prefix('reports')->name('reports.')->middleware('auth')->group(function () {
    Route::post('/store', [App\Http\Controllers\ReportController::class, 'store'])->name('store');
    Route::post('/remove', [App\Http\Controllers\ReportController::class, 'remove'])->name('remove');
});

// Route per le conversazioni di moderazione
Route::prefix('moderation')->name('moderation.')->middleware('auth')->group(function () {
    Route::get('/conversation/{report}', [App\Http\Controllers\ModerationConversationController::class, 'show'])->name('conversation');
    Route::post('/conversation/{report}/message', [App\Http\Controllers\ModerationConversationController::class, 'sendMessage'])->name('conversation.message');
    Route::get('/conversation/{report}/messages', [App\Http\Controllers\ModerationConversationController::class, 'getMessages'])->name('conversation.messages');
    Route::post('/conversation/{report}/read', [App\Http\Controllers\ModerationConversationController::class, 'markAsRead'])->name('conversation.read');
});



// Admin Social Settings Routes
Route::prefix('admin/social-settings')->name('admin.social-settings.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\SocialSettingsController::class, 'index'])->name('index');
    Route::post('/update', [App\Http\Controllers\Admin\SocialSettingsController::class, 'update'])->name('update');
    Route::post('/toggle', [App\Http\Controllers\Admin\SocialSettingsController::class, 'toggleFeature'])->name('toggle');
    Route::get('/api/settings', [App\Http\Controllers\Admin\SocialSettingsController::class, 'getSettings'])->name('api.settings');
    Route::post('/reset', [App\Http\Controllers\Admin\SocialSettingsController::class, 'reset'])->name('reset');
});

// Test Routes (solo in ambiente locale)
if (app()->environment('local')) {
    Route::prefix('test')->name('test.')->middleware('auth')->group(function () {
        Route::get('/simple', function () {
            return view('test.simple');
        })->name('simple');
        Route::get('/upload', function () {
            $user = Auth::user();
            return view('test.upload', compact('user'));
        })->name('upload');

    });
}

// DEBUG: Simula esattamente la route events.create
Route::get('/debug-simulate-create', function () {
    try {
        $controller = new App\Http\Controllers\EventController();

        // Simula esattamente quello che fa il metodo create
        Log::info('Debug: Iniziando simulazione create');

        $venueOwners = App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'venue_owner');
        })->get();

        Log::info('Debug: VenueOwners trovati', ['count' => $venueOwners->count()]);

        return view('events.create', compact('venueOwners'));

    } catch (Exception $e) {
        Log::error('Debug: Errore nella simulazione', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'error' => 'Errore nella simulazione create',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
})->name('debug-simulate-create');

// Test route per upload
Route::get('/test-upload', function () {
    return view('test.upload');
})->middleware('auth')->name('test-upload');

Route::post('/test-upload', function (Request $request) {
    Log::info('Test upload chiamato', [
        'has_file' => $request->hasFile('profile_photo'),
        'all_data' => $request->all(),
        'files' => $request->allFiles()
    ]);

    if ($request->hasFile('profile_photo')) {
        $file = $request->file('profile_photo');
        Log::info('File ricevuto', [
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
        ]);

        try {
            $path = $file->store('profile-photos', 'public');
            Log::info('File salvato', ['path' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'File caricato con successo',
                'path' => $path,
                'url' => asset('storage/' . $path)
            ]);
        } catch (\Exception $e) {
            Log::error('Errore salvataggio', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Errore: ' . $e->getMessage()
            ], 500);
        }
    }

    return response()->json([
        'success' => false,
        'message' => 'Nessun file ricevuto'
    ], 400);
})->middleware('auth');

// Routes per ricerca globale
Route::prefix('search')->name('search.')->group(function () {
    Route::get('/', [App\Http\Controllers\SearchController::class, 'index'])->name('index');
    Route::get('/api', [App\Http\Controllers\SearchController::class, 'search'])->name('api');
});

// Routes per verifica email
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        // Dopo la verifica, crea l'utente PeerTube se necessario
        $user = $request->user();
        if ($user->peertube_roles && !$user->peertube_user_id) {
            $roles = json_decode($user->peertube_roles, true);
            $shouldCreatePeerTubeUser = false;

            foreach ($roles as $role) {
                if (in_array($role, ['poet', 'organizer'])) {
                    $shouldCreatePeerTubeUser = true;
                    break;
                }
            }

            if ($shouldCreatePeerTubeUser) {
                try {
                    Log::info('Creazione utente PeerTube post-verifica email', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'roles' => $roles
                    ]);

                    $peerTubeService = new \App\Services\PeerTubeService();
                    $peerTubePassword = \Illuminate\Support\Str::random(12);
                    $peerTubeUser = $peerTubeService->createPeerTubeUser($user, $peerTubePassword);

                    if ($peerTubeUser) {
                        Log::info('Utente PeerTube creato con successo post-verifica', [
                            'user_id' => $user->id,
                            'peertube_user_id' => $user->peertube_user_id
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Errore creazione utente PeerTube post-verifica', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        return redirect()->route('dashboard')->with('success', '🎉 Email verificata con successo! Il tuo account è ora completamente attivo.');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Email di verifica inviata!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// Routes per le poesie
Route::prefix('poems')->name('poems.')->group(function () {
    // Routes pubbliche
    Route::get('/', [App\Http\Controllers\PoemController::class, 'index'])->name('index');
    Route::get('/search', [App\Http\Controllers\PoemController::class, 'search'])->name('search');

    // Routes statiche PRIMA delle dinamiche
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/create', [App\Http\Controllers\PoemController::class, 'create'])->name('create');
    });

    // Route dinamica DOPO le statiche
    Route::get('/{poem:slug}', [App\Http\Controllers\PoemController::class, 'show'])->name('show');

    // Routes autenticate
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::post('/', [App\Http\Controllers\PoemController::class, 'store'])->name('store');
        Route::get('/{poem:slug}/edit', [App\Http\Controllers\PoemController::class, 'edit'])->name('edit');
        Route::put('/{poem:slug}', [App\Http\Controllers\PoemController::class, 'update'])->name('update');
        Route::delete('/{poem:slug}', [App\Http\Controllers\PoemController::class, 'destroy'])->name('destroy');

        // Poesie personali
        Route::get('/my/poems', [App\Http\Controllers\PoemController::class, 'myPoems'])->name('my-poems');
        Route::get('/my/drafts', [App\Http\Controllers\PoemController::class, 'drafts'])->name('drafts');

        // Azioni social
        Route::post('/{poem:slug}/like', [App\Http\Controllers\PoemActionController::class, 'toggleLike'])->name('like');
        Route::post('/{poem:slug}/bookmark', [App\Http\Controllers\PoemActionController::class, 'toggleBookmark'])->name('bookmark');
        Route::post('/{poem:slug}/share', [App\Http\Controllers\PoemActionController::class, 'share'])->name('share');





        // Segnalibri e preferiti
        Route::get('/my/bookmarks', [App\Http\Controllers\PoemActionController::class, 'bookmarks'])->name('bookmarks');
        Route::get('/my/liked', [App\Http\Controllers\PoemActionController::class, 'liked'])->name('liked');

        // Commenti
        Route::get('/{poem:slug}/comments', [App\Http\Controllers\PoemCommentController::class, 'index'])->name('comments.index');
        Route::post('/{poem:slug}/comments', [App\Http\Controllers\PoemCommentController::class, 'store'])->name('comments.store');
        Route::put('/comments/{comment}', [App\Http\Controllers\PoemCommentController::class, 'update'])->name('comments.update');
        Route::delete('/comments/{comment}', [App\Http\Controllers\PoemCommentController::class, 'destroy'])->name('comments.destroy');
        Route::post('/comments/{comment}/like', [App\Http\Controllers\PoemCommentController::class, 'toggleLike'])->name('comments.like');

        // Moderazione commenti (solo admin)
        Route::middleware('can:moderate,App\Models\PoemComment')->group(function () {
            Route::post('/comments/{comment}/moderate', [App\Http\Controllers\PoemCommentController::class, 'moderate'])->name('comments.moderate');
        });
    });
});

// Routes per le traduzioni
Route::prefix('translations')->name('translations.')->middleware(['auth', 'verified'])->group(function () {
    // Lista gigs di traduzione
    Route::get('/', [App\Http\Controllers\TranslationController::class, 'index'])->name('index');

    // Dettaglio gig di traduzione
    Route::get('/{gig}', [App\Http\Controllers\TranslationController::class, 'show'])->name('show');

    // Gigs di traduzione dell'utente
    Route::get('/my/translations', [App\Http\Controllers\TranslationController::class, 'myTranslations'])->name('my-translations');

    // Candidature dell'utente per traduzioni
    Route::get('/my/applications', [App\Http\Controllers\TranslationController::class, 'myApplications'])->name('my-applications');
});

// Routes per negoziazioni traduzioni
Route::prefix('translations/negotiation')->name('translations.negotiation.')->middleware(['auth', 'verified'])->group(function () {
    // Chat di negoziazione
    Route::get('/{application}', [App\Http\Controllers\TranslationNegotiationController::class, 'show'])->name('show');

    // Invia messaggio
    Route::post('/{application}', [App\Http\Controllers\TranslationNegotiationController::class, 'store'])->name('store');

    // Accetta proposta
    Route::post('/{application}/accept', [App\Http\Controllers\TranslationNegotiationController::class, 'acceptProposal'])->name('accept');

    // Rifiuta proposta
    Route::post('/{application}/reject', [App\Http\Controllers\TranslationNegotiationController::class, 'rejectProposal'])->name('reject');
});

// Routes per pagamenti traduzioni
Route::prefix('translations/payment')->name('translations.payment.')->middleware(['auth', 'verified'])->group(function () {
    // Mostra form pagamento
    Route::get('/{application}', [App\Http\Controllers\TranslationPaymentController::class, 'show'])->name('show');

    // Crea PaymentIntent Stripe
    Route::post('/{application}/create-intent', [App\Http\Controllers\TranslationPaymentController::class, 'createPaymentIntent'])->name('create-intent');

    // Conferma pagamento
    Route::post('/{application}/confirm', [App\Http\Controllers\TranslationPaymentController::class, 'confirmPayment'])->name('confirm');

    // Pagina successo
    Route::get('/success/{payment}', [App\Http\Controllers\TranslationPaymentController::class, 'success'])->name('success');

    // Lista pagamenti utente
    Route::get('/', [App\Http\Controllers\TranslationPaymentController::class, 'index'])->name('index');
});

// Routes per traduzioni finali
Route::prefix('poem-translations')->name('poem-translations.')->middleware(['auth', 'verified'])->group(function () {
    // Crea traduzione
    Route::get('/create/{application}', [App\Http\Controllers\PoemTranslationController::class, 'create'])->name('create');
    Route::post('/create/{application}', [App\Http\Controllers\PoemTranslationController::class, 'store'])->name('store');

    // Visualizza traduzione
    Route::get('/{translation}', [App\Http\Controllers\PoemTranslationController::class, 'show'])->name('show');

    // Modifica traduzione
    Route::get('/{translation}/edit', [App\Http\Controllers\PoemTranslationController::class, 'edit'])->name('edit');
    Route::put('/{translation}', [App\Http\Controllers\PoemTranslationController::class, 'update'])->name('update');

    // Invia per approvazione
    Route::post('/{translation}/submit', [App\Http\Controllers\PoemTranslationController::class, 'submit'])->name('submit');

    // Approva traduzione
    Route::post('/{translation}/approve', [App\Http\Controllers\PoemTranslationController::class, 'approve'])->name('approve');

    // Rifiuta traduzione
    Route::post('/{translation}/reject', [App\Http\Controllers\PoemTranslationController::class, 'reject'])->name('reject');

    // Traduzioni dell'utente
    Route::get('/my/translations', [App\Http\Controllers\PoemTranslationController::class, 'myTranslations'])->name('my-translations');
});

// ========================================
// ROUTES PER I GRUPPI
// ========================================

Route::prefix('groups')->name('groups.')->middleware('auth')->group(function () {
    // Routes principali dei gruppi
    Route::get('/', [App\Http\Controllers\GroupController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\GroupController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\GroupController::class, 'store'])->name('store');

    Route::get('/{group}', [App\Http\Controllers\GroupController::class, 'show'])->name('show');
    Route::get('/{group}/edit', [App\Http\Controllers\GroupController::class, 'edit'])->name('edit');
    Route::put('/{group}', [App\Http\Controllers\GroupController::class, 'update'])->name('update');
    Route::delete('/{group}', [App\Http\Controllers\GroupController::class, 'destroy'])->name('destroy');
    Route::get('/{group}/dashboard', [App\Http\Controllers\GroupController::class, 'dashboard'])->name('dashboard');

    // Partecipazione ai gruppi
    Route::post('/{group}/join', [App\Http\Controllers\GroupController::class, 'join'])->name('join');
    Route::post('/{group}/leave', [App\Http\Controllers\GroupController::class, 'leave'])->name('leave');

    // Gestione membri
    Route::prefix('{group}/members')->name('members.')->group(function () {
        Route::get('/', [App\Http\Controllers\GroupMemberController::class, 'index'])->name('index');
        Route::post('/{member}/promote', [App\Http\Controllers\GroupMemberController::class, 'promote'])->name('promote');
        Route::post('/{member}/demote', [App\Http\Controllers\GroupMemberController::class, 'demote'])->name('demote');
        Route::post('/{member}/promote-moderator', [App\Http\Controllers\GroupMemberController::class, 'promoteToModerator'])->name('promote-moderator');
        Route::post('/{member}/demote-member', [App\Http\Controllers\GroupMemberController::class, 'demoteToMember'])->name('demote-member');
        Route::delete('/{member}', [App\Http\Controllers\GroupMemberController::class, 'remove'])->name('remove');
        Route::get('/search', [App\Http\Controllers\GroupMemberController::class, 'searchUsers'])->name('search');
        Route::post('/invite', [App\Http\Controllers\GroupMemberController::class, 'invite'])->name('invite');
    });

    // Gestione annunci
    Route::prefix('{group}/announcements')->name('announcements.')->group(function () {
        Route::get('/', [App\Http\Controllers\GroupAnnouncementController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\GroupAnnouncementController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\GroupAnnouncementController::class, 'store'])->name('store');
        Route::get('/{announcement}', [App\Http\Controllers\GroupAnnouncementController::class, 'show'])->name('show');
        Route::get('/{announcement}/edit', [App\Http\Controllers\GroupAnnouncementController::class, 'edit'])->name('edit');
        Route::put('/{announcement}', [App\Http\Controllers\GroupAnnouncementController::class, 'update'])->name('update');
        Route::delete('/{announcement}', [App\Http\Controllers\GroupAnnouncementController::class, 'destroy'])->name('destroy');
        Route::post('/{announcement}/vote', [App\Http\Controllers\GroupAnnouncementController::class, 'vote'])->name('vote');
    });

    // Gestione inviti
    Route::prefix('{group}/invitations')->name('invitations.')->group(function () {
        Route::get('/pending', [App\Http\Controllers\GroupInvitationController::class, 'pending'])->name('pending');
        Route::get('/create', [App\Http\Controllers\GroupInvitationController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\GroupInvitationController::class, 'store'])->name('store');
    });
});

// Routes per inviti (globali)
Route::prefix('group-invitations')->name('group-invitations.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\GroupInvitationController::class, 'index'])->name('index');
    Route::get('/sent', [App\Http\Controllers\GroupInvitationController::class, 'sent'])->name('sent');
    Route::get('/{invitation}', [App\Http\Controllers\GroupInvitationController::class, 'show'])->name('show');
    Route::post('/{invitation}/accept', [App\Http\Controllers\GroupInvitationController::class, 'accept'])->name('accept');
    Route::post('/{invitation}/decline', [App\Http\Controllers\GroupInvitationController::class, 'decline'])->name('decline');
    Route::delete('/{invitation}', [App\Http\Controllers\GroupInvitationController::class, 'cancel'])->name('cancel');
    Route::post('/{invitation}/resend', [App\Http\Controllers\GroupInvitationController::class, 'resend'])->name('resend');
});

// Routes per richieste di partecipazione
Route::prefix('group-requests')->name('group-requests.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\GroupJoinRequestController::class, 'index'])->name('index');
    Route::get('/{request}', [App\Http\Controllers\GroupJoinRequestController::class, 'show'])->name('show');
    Route::post('/{request}/accept', [App\Http\Controllers\GroupJoinRequestController::class, 'accept'])->name('accept');
    Route::post('/{request}/decline', [App\Http\Controllers\GroupJoinRequestController::class, 'decline'])->name('decline');
    Route::delete('/{request}', [App\Http\Controllers\GroupJoinRequestController::class, 'cancel'])->name('cancel');
});

// Routes per richieste pendenti di un gruppo
Route::prefix('groups/{group}/requests')->name('groups.requests.')->middleware('auth')->group(function () {
    Route::get('/pending', [App\Http\Controllers\GroupJoinRequestController::class, 'pending'])->name('pending');
    Route::get('/stats', [App\Http\Controllers\GroupJoinRequestController::class, 'stats'])->name('stats');
    Route::post('/store', [App\Http\Controllers\GroupJoinRequestController::class, 'store'])->name('store');
});

// ===== ROUTE API PUBBLICHE (fuori da qualsiasi middleware) =====

// API routes for videos (completamente pubbliche)
Route::prefix('api/videos')->group(function () {
    Route::get('/{video}', [App\Http\Controllers\VideoController::class, 'getVideoData'])->name('api.videos.get');
    Route::get('/{video}/snaps', [App\Http\Controllers\VideoController::class, 'getVideoSnaps'])->name('api.videos.snaps');
    Route::post('/{video}/snaps', [App\Http\Controllers\VideoController::class, 'addSnap'])->name('api.videos.add-snap');
});

// Route pubblica per URL PeerTube
Route::get('/videos/{video}/peertube-url', [App\Http\Controllers\VideoController::class, 'getPeerTubeUrl'])->name('videos.peertube-url');

// API routes for photos (completamente pubbliche)
Route::prefix('api/photos')->group(function () {
    Route::get('/{photo}', [App\Http\Controllers\PhotoController::class, 'getPhotoData'])->name('api.photos.get');
});

// API route per ricerca media (completamente pubblica)
Route::get('/api/media/search', [App\Http\Controllers\MediaController::class, 'search'])->name('api.media.search');

// API routes per commenti (pubbliche per lettura)
Route::get('/api/videos/{video}/comments', [App\Http\Controllers\VideoController::class, 'getComments'])->name('api.videos.comments');
Route::get('/api/photos/{photo}/comments', [App\Http\Controllers\PhotoController::class, 'getComments'])->name('api.photos.comments');

// API routes per sistema commenti unificato (senza CSRF)
Route::prefix('api/social')->middleware('web')->group(function () {
    Route::post('/comments', [App\Http\Controllers\CommentController::class, 'store'])->name('api.social.comments.store');
    Route::put('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'update'])->name('api.social.comments.update');
    Route::delete('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'destroy'])->name('api.social.comments.destroy');
    Route::post('/comments/{comment}/approve', [App\Http\Controllers\CommentController::class, 'approve'])->name('api.social.comments.approve');
    Route::post('/comments/{comment}/reject', [App\Http\Controllers\CommentController::class, 'reject'])->name('api.social.comments.reject');
    Route::get('/comments', [App\Http\Controllers\CommentController::class, 'getComments'])->name('api.social.comments.list');
    Route::get('/comments/{comment}/replies', [App\Http\Controllers\CommentController::class, 'getReplies'])->name('api.social.comments.replies');

    // Route per like unificato
    Route::post('/likes/toggle', [App\Http\Controllers\LikeController::class, 'toggle'])->name('api.social.likes.toggle');
    Route::get('/likes/check', [App\Http\Controllers\LikeController::class, 'check'])->name('api.social.likes.check');
    Route::get('/likes/stats', [App\Http\Controllers\LikeController::class, 'getLikeStats'])->name('api.social.likes.stats');

    // Route per incremento visualizzazioni (pubblica)
    Route::post('/views/increment', [App\Http\Controllers\ViewController::class, 'increment'])->name('api.social.views.increment');
});

// API routes per sistema follow
Route::prefix('api/follow')->middleware('auth')->group(function () {
    Route::post('/toggle', [App\Http\Controllers\FollowController::class, 'toggle'])->name('api.follow.toggle');
    Route::get('/check', [App\Http\Controllers\FollowController::class, 'check'])->name('api.follow.check');
    Route::get('/{user}/followers', [App\Http\Controllers\FollowController::class, 'followers'])->name('api.follow.followers');
    Route::get('/{user}/following', [App\Http\Controllers\FollowController::class, 'following'])->name('api.follow.following');
});

// ===== ROUTES PER GIGS =====

// Routes principali per gigs
Route::prefix('gigs')->name('gigs.')->group(function () {
    Route::get('/', [App\Http\Controllers\GigController::class, 'index'])->name('index');
    Route::get('/my-gigs', [App\Http\Controllers\GigController::class, 'myGigs'])->name('my-gigs');
    Route::get('/my-applications', [App\Http\Controllers\GigController::class, 'myApplications'])->name('my-applications');

    // CRUD per gigs (solo utenti autenticati non audience)
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/create', [App\Http\Controllers\GigController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\GigController::class, 'store'])->name('store');
        Route::get('/{gig}', [App\Http\Controllers\GigController::class, 'show'])->name('show');
        Route::get('/{gig}/edit', [App\Http\Controllers\GigController::class, 'edit'])->name('edit');
        Route::put('/{gig}', [App\Http\Controllers\GigController::class, 'update'])->name('update');
        Route::delete('/{gig}', [App\Http\Controllers\GigController::class, 'destroy'])->name('destroy');

        // Gestione candidature
        Route::get('/{gig}/applications', [App\Http\Controllers\GigController::class, 'manageApplications'])->name('manage-applications');
        Route::post('/{gig}/apply', [App\Http\Controllers\GigController::class, 'apply'])->name('apply');

        // Azioni sui gigs
        Route::post('/{gig}/close', [App\Http\Controllers\GigController::class, 'close'])->name('close');
        Route::post('/{gig}/reopen', [App\Http\Controllers\GigController::class, 'reopen'])->name('reopen');
        Route::post('/{gig}/share', [App\Http\Controllers\GigController::class, 'share'])->name('share');

        // Azioni sulle candidature
        Route::post('/applications/{application}/accept', [App\Http\Controllers\GigController::class, 'acceptApplication'])->name('applications.accept');
        Route::post('/applications/{application}/reject', [App\Http\Controllers\GigController::class, 'rejectApplication'])->name('applications.reject');
        Route::post('/applications/{application}/withdraw', [App\Http\Controllers\GigController::class, 'withdrawApplication'])->name('applications.withdraw');

        // Messaggio globale
        Route::post('/{gig}/global-message', [App\Http\Controllers\GigController::class, 'sendGlobalMessage'])->name('global-message');
    });
});

// ===== ROUTES PER ADMIN GIG POSITIONS =====
Route::prefix('admin/gig-positions')->name('admin.gig-positions.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\GigPositionController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\GigPositionController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\GigPositionController::class, 'store'])->name('store');
    Route::get('/{id}', [App\Http\Controllers\Admin\GigPositionController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [App\Http\Controllers\Admin\GigPositionController::class, 'edit'])->name('edit');
    Route::put('/{id}', [App\Http\Controllers\Admin\GigPositionController::class, 'update'])->name('update');
    Route::delete('/{id}', [App\Http\Controllers\Admin\GigPositionController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/toggle-status', [App\Http\Controllers\Admin\GigPositionController::class, 'toggleStatus'])->name('toggle-status');
});

// Test route per broadcasting
Route::post('/test-broadcast', function () {
    broadcast(new \App\Events\UserLoggedIn('Test Frontend', 'test@frontend.com'));
    return response()->json(['success' => true, 'message' => 'Event sent']);
});


// ===== ROUTES PER CHAT =====
Route::prefix('chat')->name('chat.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\ChatController::class, 'index'])->name('index');
    Route::get('/search-users', [App\Http\Controllers\ChatController::class, 'searchUsers'])->name('search-users');
    Route::post('/create-private/{userId}', [App\Http\Controllers\ChatController::class, 'createPrivateChat'])->name('create-private');

    // Chat notifications routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::post('/mark-read', [App\Http\Controllers\ChatNotificationController::class, 'markChatAsRead'])->name('mark-read');
        Route::get('/unread-count', [App\Http\Controllers\ChatNotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('/room/{chatRoomId}', [App\Http\Controllers\ChatNotificationController::class, 'getChatNotifications'])->name('room');
        Route::post('/mark-all-read', [App\Http\Controllers\ChatNotificationController::class, 'markAllChatAsRead'])->name('mark-all-read');
        Route::post('/mark-notifications-read', [App\Http\Controllers\ChatController::class, 'markNotificationsAsRead'])->name('mark-notifications-read');
    });

    // Typing indicators
    Route::post('/{room}/typing/start', [App\Http\Controllers\ChatController::class, 'startTyping'])->name('typing.start');
    Route::post('/{room}/typing/stop', [App\Http\Controllers\ChatController::class, 'stopTyping'])->name('typing.stop');
    Route::get('/{room}/typing/users', [App\Http\Controllers\ChatController::class, 'getTypingUsers'])->name('typing.users');

    // Messages
    Route::post('/{room}/messages', [App\Http\Controllers\ChatController::class, 'store'])->name('store');

            // Message reactions
        Route::post('/{room}/messages/{message}/reactions', [App\Http\Controllers\ChatReactionController::class, 'addReaction'])->name('reactions.add');
        Route::delete('/{room}/messages/{message}/reactions', [App\Http\Controllers\ChatReactionController::class, 'removeReaction'])->name('reactions.remove');
        Route::get('/{room}/messages/{message}/reactions', [App\Http\Controllers\ChatReactionController::class, 'getReactions'])->name('reactions.get');
        Route::post('/{room}/messages/reactions/batch', [App\Http\Controllers\ChatReactionController::class, 'getReactionsBatch'])->name('reactions.batch');

// Route semplici per le reazioni (compatibili con il JavaScript frontend)
Route::post('/reactions/add', [App\Http\Controllers\ChatReactionController::class, 'addReactionSimple'])->name('reactions.add-simple');
Route::post('/reactions/toggle', [App\Http\Controllers\ChatReactionController::class, 'toggleReactionSimple'])->name('reactions.toggle-simple');
});

// ===== ROUTES PER ARTICOLI/NOTIZIE =====

// Routes per le poesie
Route::prefix('articles')->name('articles.')->group(function () {
    // Routes pubbliche
    Route::get('/', [App\Http\Controllers\ArticleController::class, 'index'])->name('index');
    Route::get('/search', [App\Http\Controllers\ArticleController::class, 'search'])->name('search');
    // Spostata qui la route statica prima della dinamica
    Route::middleware('auth')->group(function () {
        Route::get('/create', [App\Http\Controllers\ArticleController::class, 'create'])->name('create');
    });
    Route::get('/{article:slug}', [App\Http\Controllers\ArticleController::class, 'show'])->name('show');

    // Routes autenticate
    Route::middleware('auth')->group(function () {


        Route::post('/', [App\Http\Controllers\ArticleController::class, 'store'])->name('store');
        Route::get('/{article}/edit', [App\Http\Controllers\ArticleController::class, 'edit'])->name('edit')->where('article', '[0-9]+');
        Route::put('/{article}', [App\Http\Controllers\ArticleController::class, 'update'])->name('update')->where('article', '[0-9]+');
        Route::delete('/{article}', [App\Http\Controllers\ArticleController::class, 'destroy'])->name('destroy')->where('article', '[0-9]+');

                         // Azioni sugli articoli
                 Route::post('/{article}/publish', [App\Http\Controllers\ArticleController::class, 'publish'])->name('publish')->where('article', '[0-9]+');
                 Route::post('/{article}/unpublish', [App\Http\Controllers\ArticleController::class, 'unpublish'])->name('unpublish')->where('article', '[0-9]+');
                 Route::post('/{id}/feature', [App\Http\Controllers\ArticleController::class, 'feature'])->name('feature');
                 Route::post('/{id}/unfeature', [App\Http\Controllers\ArticleController::class, 'unfeature'])->name('unfeature');

        // Like degli articoli
        Route::prefix('{article}/likes')->name('likes.')->group(function () {
            Route::post('/toggle', [App\Http\Controllers\ArticleLikeController::class, 'toggle'])->name('toggle');
            Route::post('/', [App\Http\Controllers\ArticleLikeController::class, 'like'])->name('like');
            Route::delete('/', [App\Http\Controllers\ArticleLikeController::class, 'unlike'])->name('unlike');
            Route::get('/likers', [App\Http\Controllers\ArticleLikeController::class, 'getLikers'])->name('likers');
            Route::get('/status', [App\Http\Controllers\ArticleLikeController::class, 'getStatus'])->name('status');
        });

        // Commenti degli articoli
        Route::prefix('{article}/comments')->name('comments.')->group(function () {
            Route::get('/index', [App\Http\Controllers\ArticleCommentController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\ArticleCommentController::class, 'store'])->name('store');
            Route::put('/{comment}', [App\Http\Controllers\ArticleCommentController::class, 'update'])->name('update');
            Route::delete('/{comment}', [App\Http\Controllers\ArticleCommentController::class, 'destroy'])->name('destroy');
            Route::get('/{comment}/replies', [App\Http\Controllers\ArticleCommentController::class, 'getReplies'])->name('replies');

            // Like dei commenti
            Route::post('/{comment}/like', [App\Http\Controllers\ArticleCommentController::class, 'like'])->name('like');
            Route::delete('/{comment}/like', [App\Http\Controllers\ArticleCommentController::class, 'unlike'])->name('unlike');

            // Moderazione commenti (admin/editor)
            Route::post('/{comment}/approve', [App\Http\Controllers\ArticleCommentController::class, 'approve'])->name('approve');
            Route::post('/{comment}/reject', [App\Http\Controllers\ArticleCommentController::class, 'reject'])->name('reject');
        });

        // Segnalazioni articoli
        Route::prefix('{article}/reports')->name('reports.')->group(function () {
            Route::post('/', [App\Http\Controllers\ArticleReportController::class, 'store'])->name('store');
            Route::get('/check', [App\Http\Controllers\ArticleCommentController::class, 'checkReport'])->name('check');
        });
    });
});

// Routes per layout articoli (admin/editor)
Route::prefix('articles/layout')->name('articles.layout.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\ArticleLayoutController::class, 'index'])->name('index');
    Route::post('/update', [App\Http\Controllers\ArticleLayoutController::class, 'update'])->name('update');
    Route::post('/clear', [App\Http\Controllers\ArticleLayoutController::class, 'clear'])->name('clear');
    Route::post('/bulk-update', [App\Http\Controllers\ArticleLayoutController::class, 'bulkUpdate'])->name('bulk-update');
    Route::get('/preview', [App\Http\Controllers\ArticleLayoutController::class, 'preview'])->name('preview');
    Route::get('/articles', [App\Http\Controllers\ArticleLayoutController::class, 'getArticles'])->name('articles');
    Route::get('/current', [App\Http\Controllers\ArticleLayoutController::class, 'getLayout'])->name('current');
});

// Dashboard principale articoli (admin/editor)
Route::prefix('admin/articles')->name('admin.articles.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\ArticleController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\ArticleController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\ArticleController::class, 'store'])->name('store');
    Route::get('/{article}/edit', [App\Http\Controllers\Admin\ArticleController::class, 'edit'])->name('edit')->where('article', '[0-9]+');
    Route::put('/{article}', [App\Http\Controllers\Admin\ArticleController::class, 'update'])->name('update')->where('article', '[0-9]+');
    Route::delete('/{article}', [App\Http\Controllers\Admin\ArticleController::class, 'destroy'])->name('destroy')->where('article', '[0-9]+');
    Route::post('/{article}/publish', [App\Http\Controllers\Admin\ArticleController::class, 'publish'])->name('publish')->where('article', '[0-9]+');
    Route::post('/{article}/unpublish', [App\Http\Controllers\Admin\ArticleController::class, 'unpublish'])->name('unpublish')->where('article', '[0-9]+');
    Route::post('/{article}/approve', [App\Http\Controllers\Admin\ArticleController::class, 'approve'])->name('approve')->where('article', '[0-9]+');
    Route::post('/{article}/reject', [App\Http\Controllers\Admin\ArticleController::class, 'reject'])->name('reject')->where('article', '[0-9]+');
    Route::post('/{article}/toggle-featured', [App\Http\Controllers\Admin\ArticleController::class, 'toggleFeatured'])->name('toggle-featured')->where('article', '[0-9]+');

    // Route di test temporanea
    Route::get('/test-toggle-featured/{article}', function($article) {
        return response()->json([
            'success' => true,
            'message' => 'Route di test funziona',
            'article_id' => $article,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'user_roles' => \Illuminate\Support\Facades\Auth::user()->getRoleNames()->toArray()
        ]);
    })->name('test-toggle-featured');



});

