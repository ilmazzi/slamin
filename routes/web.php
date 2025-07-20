<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\InvitationController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
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
Route::view('chat', 'chat')->name('chat');
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

Route::view('kanban_board', 'kanban_board')->name('kanban_board');

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
Route::view('notifications', 'notifications')->name('notifications');

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
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/switch-language', [App\Http\Controllers\Dashboard\DashboardController::class, 'switchLanguage'])->name('switch-language');
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

// Public event routes (no auth required)
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// TEST: View semplice per verificare se il sistema view funziona
Route::get('/test-simple-view', function () {
    return view('dashboard.index', ['stats' => []]);
})->name('test-simple-view');

// IMPORTANTE: events/create DEVE stare PRIMA di events/{event} per evitare conflitti!
Route::get('/events/create', function () {
    try {
        $venueOwners = App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'venue_owner');
        })->get();

        // TEST: Prova a renderizzare la view
        return view('events.create', compact('venueOwners'));

    } catch (Exception $e) {
        // Se c'è un errore nella view, mostriamolo
        return response()->json([
            'error' => 'Errore nella view events.create',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'venue_owners_count' => isset($venueOwners) ? $venueOwners->count() : 'non_definito'
        ], 500);
    }
})->name('events.create');

Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/api/events/near', [EventController::class, 'near'])->name('events.near');

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
Route::get('/api/users/search', [EventController::class, 'searchUsers'])->name('users.search');

Route::post('/events', [EventController::class, 'store'])->name('events.store');

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
Route::middleware('auth')->group(function () {

    // Event management (organizers) - SENZA CREATE PER TEST
    Route::resource('events', EventController::class)->except(['index', 'show', 'create', 'store']);
    Route::get('/events/{event}/manage', [EventController::class, 'manage'])->name('events.manage');
    Route::post('/events/{event}/apply', [EventController::class, 'apply'])->name('events.apply');
    Route::get('/api/events/calendar', [EventController::class, 'calendar'])->name('events.calendar');

// Invitation routes
Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations.index');
Route::get('/invitations/{invitation}/accept', [InvitationController::class, 'accept'])->name('invitations.accept');
Route::get('/invitations/{invitation}/decline', [InvitationController::class, 'decline'])->name('invitations.decline');

// Request routes
Route::get('/requests', [EventRequestController::class, 'index'])->name('requests.index');
Route::post('/requests/{eventRequest}/accept', [EventRequestController::class, 'accept'])->name('requests.accept');
Route::post('/requests/{eventRequest}/decline', [EventRequestController::class, 'decline'])->name('requests.decline');
Route::post('/requests/{eventRequest}/accept-ajax', [EventRequestController::class, 'acceptAjax'])->name('requests.accept.ajax');
Route::post('/requests/{eventRequest}/decline-ajax', [EventRequestController::class, 'declineAjax'])->name('requests.decline.ajax');
Route::post('/requests/{eventRequest}/cancel', [EventRequestController::class, 'cancel'])->name('requests.cancel');

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

    // Development/testing routes (only in local environment)
    if (app()->environment('local')) {
        Route::post('/notifications/test', [NotificationController::class, 'test'])->name('notifications.test');
    }

    // Analytics routes
    Route::prefix('analytics')->name('analytics.')->middleware('auth')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('index');
        Route::get('/export', [AnalyticsController::class, 'export'])->name('export');
        Route::get('/realtime', [AnalyticsController::class, 'realtime'])->name('realtime');
    });
});

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


