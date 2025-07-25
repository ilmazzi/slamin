<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaController;


use Illuminate\Http\Request;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');



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

Route::get('kanban_board', [App\Http\Controllers\TaskController::class, 'index'])->name('kanban_board');

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
Route::get('/invitations/{invitation}', [InvitationController::class, 'show'])->name('invitations.show');
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

    // API routes for user search and suggestions
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
                'user' => auth()->user() ? auth()->user()->name : 'Not authenticated',
                'is_admin' => auth()->user() ? auth()->user()->is_admin : false,
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
        Route::prefix('logs')->name('logs.')->middleware('logging')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\LogController::class, 'index'])->name('index');
            Route::get('/{log}', [App\Http\Controllers\Admin\LogController::class, 'show'])->name('show');
            Route::get('/export/csv', [App\Http\Controllers\Admin\LogController::class, 'export'])->name('export');
            Route::get('/stats', [App\Http\Controllers\Admin\LogController::class, 'stats'])->name('stats');
            Route::post('/clear', [App\Http\Controllers\Admin\LogController::class, 'clear'])->name('clear');
            Route::get('/realtime', [App\Http\Controllers\Admin\LogController::class, 'realtime'])->name('realtime');
        });
        Route::get('/settings/api', [App\Http\Controllers\Admin\SystemSettingsController::class, 'getSettings'])->name('settings.api');
        Route::post('/settings/thumbnails', [App\Http\Controllers\Admin\SystemSettingsController::class, 'manageThumbnails'])->name('settings.thumbnails');

        // Translation Management
        Route::get('/translations', [App\Http\Controllers\Admin\TranslationController::class, 'index'])->name('translations.index');
        Route::get('/translations/{language}/{file}', [App\Http\Controllers\Admin\TranslationController::class, 'show'])->name('translations.show');
        Route::put('/translations/{language}/{file}', [App\Http\Controllers\Admin\TranslationController::class, 'update'])->name('translations.update');
        Route::post('/translations/language', [App\Http\Controllers\Admin\TranslationController::class, 'createLanguage'])->name('translations.create-language');
        Route::delete('/translations/language/{language}', [App\Http\Controllers\Admin\TranslationController::class, 'deleteLanguage'])->name('translations.delete-language');
        Route::post('/translations/sync', [App\Http\Controllers\Admin\TranslationController::class, 'syncLanguages'])->name('translations.sync');
        
        // Auto Translation Routes
        Route::post('/translations/auto-translate/{language}/{file}', [App\Http\Controllers\Admin\TranslationController::class, 'autoTranslate'])->name('translations.auto-translate');
        Route::post('/translations/auto-translate-all', [App\Http\Controllers\Admin\TranslationController::class, 'autoTranslateAll'])->name('translations.auto-translate-all');
        Route::post('/translations/test-service', [App\Http\Controllers\Admin\TranslationController::class, 'testTranslationService'])->name('translations.test-service');



        // Kanban Board Routes
        Route::get('/kanban', [App\Http\Controllers\Admin\KanbanController::class, 'index'])->name('kanban.index');
        Route::post('/kanban/update-status', [App\Http\Controllers\Admin\KanbanController::class, 'updateTaskStatus'])->name('kanban.update-status');
        Route::post('/kanban/task-details', [App\Http\Controllers\Admin\KanbanController::class, 'getTaskDetails'])->name('kanban.task-details');
        Route::post('/kanban/tasks', [App\Http\Controllers\Admin\KanbanController::class, 'storeTask'])->name('kanban.store-task');
        Route::post('/kanban/comments', [App\Http\Controllers\Admin\KanbanController::class, 'addComment'])->name('kanban.add-comment');
        Route::post('/kanban/update-task', [App\Http\Controllers\Admin\KanbanController::class, 'updateTask'])->name('kanban.update-task');
        Route::post('/kanban/delete-image', [App\Http\Controllers\Admin\KanbanController::class, 'deleteImage'])->name('kanban.delete-image');
        Route::post('/kanban/delete-task', [App\Http\Controllers\Admin\KanbanController::class, 'deleteTask'])->name('kanban.delete-task');

        // PeerTube Management Routes
        Route::get('/peertube', [App\Http\Controllers\Admin\PeerTubeController::class, 'index'])->name('peertube.index');
        Route::put('/peertube', [App\Http\Controllers\Admin\PeerTubeController::class, 'update'])->name('peertube.update');
        Route::get('/peertube/test-connection', [App\Http\Controllers\Admin\PeerTubeController::class, 'testConnectionApi'])->name('peertube.test-connection');
        Route::get('/peertube/statistics', [App\Http\Controllers\Admin\PeerTubeController::class, 'statistics'])->name('peertube.statistics');
        Route::get('/peertube/users', [App\Http\Controllers\Admin\PeerTubeController::class, 'users'])->name('peertube.users');

        // User Management Routes
        Route::resource('users', App\Http\Controllers\Admin\UserController::class)->names('users');
        Route::post('/users/bulk-assign', [App\Http\Controllers\Admin\UserController::class, 'bulkAssign'])->name('users.bulk-assign');
        Route::get('/users/export', [App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');
    });

    // Profile Routes (accessibili a tutti gli utenti autenticati)
    Route::prefix('profile')->name('profile.')->middleware('auth')->group(function () {
        Route::get('/', [App\Http\Controllers\ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('update');
        Route::get('/videos', [App\Http\Controllers\ProfileController::class, 'videos'])->name('videos');
        Route::delete('/videos/{video}', [App\Http\Controllers\ProfileController::class, 'deleteVideo'])->name('videos.delete');
        Route::get('/activity', [App\Http\Controllers\ProfileController::class, 'activity'])->name('activity');
    });

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
        Route::post('/upload', [App\Http\Controllers\ProfileController::class, 'uploadVideo'])->name('upload');

        // Video playback and views
        Route::get('/{video}', [App\Http\Controllers\VideoController::class, 'show'])->name('show');
        Route::post('/{video}/views', [App\Http\Controllers\VideoController::class, 'incrementViews'])->name('increment-views');
        Route::get('/{video}/download', [App\Http\Controllers\VideoController::class, 'download'])->name('download');
        Route::get('/{video}/peertube-url', [App\Http\Controllers\VideoController::class, 'getPeerTubeUrl'])->name('peertube-url');


        // Video interactions (comments, likes, snaps)
        Route::post('/{video}/comments', [App\Http\Controllers\VideoController::class, 'addComment'])->name('add-comment');
        Route::post('/{video}/like', [App\Http\Controllers\VideoController::class, 'toggleLike'])->name('toggle-like');
        Route::post('/{video}/snaps', [App\Http\Controllers\VideoController::class, 'addSnap'])->name('add-snap');
        Route::delete('/comments/{comment}', [App\Http\Controllers\VideoController::class, 'deleteComment'])->name('delete-comment');
        Route::delete('/snaps/{snap}', [App\Http\Controllers\VideoController::class, 'deleteSnap'])->name('delete-snap');
        Route::post('/snaps/{snap}/like', [App\Http\Controllers\VideoController::class, 'toggleSnapLike'])->name('snap-like');
    });

    // API routes for videos (without auth middleware)
    Route::prefix('api/videos')->group(function () {
        Route::get('/{video}', [App\Http\Controllers\VideoController::class, 'getVideoData'])->name('api.videos.get');
    });

    // Media Routes (pubbliche)
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/', [App\Http\Controllers\MediaController::class, 'index'])->name('index');
        Route::post('/like', [App\Http\Controllers\MediaController::class, 'like'])->name('like');
        Route::post('/comment', [App\Http\Controllers\MediaController::class, 'comment'])->name('comment');
    });

    // PeerTube upload route alias
    Route::get('/peertube/upload', function() {
        $user = Auth::user();
        return view('videos.upload', compact('user'));
    })->name('peertube.upload-video')->middleware('auth');

    // Premium Routes
    Route::prefix('premium')->name('premium.')->middleware('auth')->group(function () {
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
})->middleware('auth')->name('test.upload');

Route::post('/test-upload', function (Request $request) {
    \Log::info('Test upload chiamato', [
        'has_file' => $request->hasFile('profile_photo'),
        'all_data' => $request->all(),
        'files' => $request->allFiles()
    ]);

    if ($request->hasFile('profile_photo')) {
        $file = $request->file('profile_photo');
        \Log::info('File ricevuto', [
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
        ]);

        try {
            $path = $file->store('profile-photos', 'public');
            \Log::info('File salvato', ['path' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'File caricato con successo',
                'path' => $path,
                'url' => asset('storage/' . $path)
            ]);
        } catch (\Exception $e) {
            \Log::error('Errore salvataggio', ['error' => $e->getMessage()]);
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

// Routes per le poesie
Route::prefix('poems')->name('poems.')->group(function () {
    // Routes pubbliche
    Route::get('/', [App\Http\Controllers\PoemController::class, 'index'])->name('index');
    Route::get('/search', [App\Http\Controllers\PoemController::class, 'search'])->name('search');
    Route::get('/{poem:slug}', [App\Http\Controllers\PoemController::class, 'show'])->name('show');
    
    // Routes autenticate
    Route::middleware('auth')->group(function () {
        Route::get('/create', [App\Http\Controllers\PoemController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\PoemController::class, 'store'])->name('store');
        Route::get('/{poem}/edit', [App\Http\Controllers\PoemController::class, 'edit'])->name('edit');
        Route::put('/{poem}', [App\Http\Controllers\PoemController::class, 'update'])->name('update');
        Route::delete('/{poem}', [App\Http\Controllers\PoemController::class, 'destroy'])->name('destroy');
        
        // Poesie personali
        Route::get('/my/poems', [App\Http\Controllers\PoemController::class, 'myPoems'])->name('my-poems');
        Route::get('/my/drafts', [App\Http\Controllers\PoemController::class, 'drafts'])->name('drafts');
        
        // Azioni social
        Route::post('/{poem}/like', [App\Http\Controllers\PoemActionController::class, 'toggleLike'])->name('like');
        Route::post('/{poem}/bookmark', [App\Http\Controllers\PoemActionController::class, 'toggleBookmark'])->name('bookmark');
        Route::post('/{poem}/share', [App\Http\Controllers\PoemActionController::class, 'share'])->name('share');
        Route::post('/{poem}/request-translation', [App\Http\Controllers\PoemActionController::class, 'requestTranslation'])->name('request-translation');
        
        // Segnalibri e preferiti
        Route::get('/my/bookmarks', [App\Http\Controllers\PoemActionController::class, 'bookmarks'])->name('bookmarks');
        Route::get('/my/liked', [App\Http\Controllers\PoemActionController::class, 'liked'])->name('liked');
        
        // Commenti
        Route::get('/{poem}/comments', [App\Http\Controllers\PoemCommentController::class, 'index'])->name('comments.index');
        Route::post('/{poem}/comments', [App\Http\Controllers\PoemCommentController::class, 'store'])->name('comments.store');
        Route::put('/comments/{comment}', [App\Http\Controllers\PoemCommentController::class, 'update'])->name('comments.update');
        Route::delete('/comments/{comment}', [App\Http\Controllers\PoemCommentController::class, 'destroy'])->name('comments.destroy');
        Route::post('/comments/{comment}/like', [App\Http\Controllers\PoemCommentController::class, 'toggleLike'])->name('comments.like');
        
        // Moderazione commenti (solo admin)
        Route::middleware('can:moderate,App\Models\PoemComment')->group(function () {
            Route::post('/comments/{comment}/moderate', [App\Http\Controllers\PoemCommentController::class, 'moderate'])->name('comments.moderate');
        });
    });
});

