<!-- Header Section starts -->
<header class="header-main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8 col-sm-6 d-flex align-items-center header-left p-0">
                           <span class="header-toggle ">
                             <i class="ph ph-squares-four"></i>
                           </span>

                <div class="header-searchbar w-100">
                    <form action="#" class="mx-sm-3 app-form app-icon-form ">
                        <div class="position-relative">
                            <input aria-label="Search" class="form-control" placeholder="Search..."
                                   type="search">
                            <i class="ti ti-search text-dark"></i>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-4 col-sm-6 d-flex align-items-center justify-content-end header-right p-0">

                <ul class="d-flex align-items-center">

                    <li class="header-language">
                        <div class="flex-shrink-0 dropdown" id="lang_selector">
                            <a aria-expanded="false" class="d-block head-icon ps-0"
                               data-bs-toggle="dropdown"
                               href="#">
                                <div class="lang-flag lang-{{ app()->getLocale() }}">
                                    <span class="flag rounded-circle overflow-hidden">
                                        <i class="flag-icon flag-icon-{{
                                            app()->getLocale() == 'en' ? 'gbr' :
                                            (app()->getLocale() == 'de' ? 'deu' :
                                            (app()->getLocale() == 'es' ? 'esp' :
                                            (app()->getLocale() == 'fr' ? 'fra' : 'ita')))
                                        }}"></i>
                                    </span>
                                </div>
                            </a>
                            <ul class="dropdown-menu language-dropdown header-card border-0">
                                <li class="lang lang-it {{ app()->getLocale() == 'it' ? 'selected' : '' }} dropdown-item p-2" data-bs-placement="top" data-bs-toggle="tooltip" title="IT">
                                    <a href="{{ url()->current() }}?lang=it" class="d-flex align-items-center text-decoration-none">
                                        <i class="flag-icon flag-icon-ita flag-icon-squared rounded-circle f-s-20"></i>
                                        <span class="ps-2">Italiano</span>
                                    </a>
                                </li>
                                <li class="lang lang-en {{ app()->getLocale() == 'en' ? 'selected' : '' }} dropdown-item p-2" title="EN">
                                    <a href="{{ url()->current() }}?lang=en" class="d-flex align-items-center text-decoration-none">
                                        <i class="flag-icon flag-icon-gbr flag-icon-squared rounded-circle f-s-20"></i>
                                        <span class="ps-2">English</span>
                                    </a>
                                </li>
                                <li class="lang lang-fr {{ app()->getLocale() == 'fr' ? 'selected' : '' }} dropdown-item p-2" title="FR">
                                    <a href="{{ url()->current() }}?lang=fr" class="d-flex align-items-center text-decoration-none">
                                        <i class="flag-icon flag-icon-fra flag-icon-squared rounded-circle f-s-20"></i>
                                        <span class="ps-2">Français</span>
                                    </a>
                                </li>
                                <li class="lang lang-es {{ app()->getLocale() == 'es' ? 'selected' : '' }} dropdown-item p-2" title="ES">
                                    <a href="{{ url()->current() }}?lang=es" class="d-flex align-items-center text-decoration-none">
                                        <i class="flag-icon flag-icon-esp flag-icon-squared rounded-circle f-s-20"></i>
                                        <span class="ps-2">Español</span>
                                    </a>
                                </li>
                                <li class="lang lang-de {{ app()->getLocale() == 'de' ? 'selected' : '' }} dropdown-item p-2" title="DE">
                                    <a href="{{ url()->current() }}?lang=de" class="d-flex align-items-center text-decoration-none">
                                        <i class="flag-icon flag-icon-deu flag-icon-squared rounded-circle f-s-20"></i>
                                        <span class="ps-2">Deutsch</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </li>

                    <li class="header-apps">
                        <a aria-controls="appscanvasRights"
                           class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2"
                           data-bs-target="#appscanvasRights" data-bs-toggle="offcanvas"
                           href="#" role="button">
                            <i class="ph ph-bounding-box"></i>

                        </a>

                        <div aria-labelledby="appscanvasRightsLabel"
                             class="offcanvas offcanvas-end header-apps-canvas"
                             id="appscanvasRights"
                             tabindex="-1">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="appscanvasRightsLabel">Shortcut</h5>
                                <div class="app-dropdown flex-shrink-0">
                                    <a aria-expanded="false" class=" p-1" data-bs-auto-close="outside"
                                       data-bs-toggle="dropdown"
                                       href="#"
                                       role="button">
                                        <i class="ph-bold  ph-faders-horizontal f-s-20"></i>


                                    </a>
                                    <ul class="dropdown-menu mb-3">
                                        <li class="dropdown-item">
                                            <a href="{{route('setting')}}" target="_blank">
                                                Privacy Settings
                                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="{{route('setting')}}" target="_blank">
                                                Account Settings
                                            </a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a href="{{route('setting')}}" target="_blank">
                                                Accessibility
                                            </a>
                                        </li>
                                        <li class="dropdown-divider"></li>
                                        <li class="dropdown-item border-0">
                                            <a aria-expanded="false" data-bs-toggle="dropdown" href="#"
                                               role="button">
                                                More Settings
                                            </a>
                                            <ul class="dropdown-menu sub-menu">
                                                <li class="dropdown-item">
                                                    <a href="{{route('setting')}}" target="_blank">
                                                        Backup and Restore
                                                    </a>
                                                </li>
                                                <li class="dropdown-item">
                                                    <a href="{{route('setting')}}" target="_blank">
                                                        <span>Data Usage</span>
                                                    </a>
                                                </li>
                                                <li class="dropdown-item">
                                                    <a href="{{route('setting')}}" target="_blank">
                                                        <span>Theme</span>
                                                    </a>
                                                </li>
                                                <li class="dropdown-item d-flex align-items-center justify-content-between">
                                                    <a href="#">
                                                        <p class="mb-0">Notification</p>
                                                    </a>
                                                    <div class="flex-shrink-0">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input  form-check-primary"
                                                                   id="notificationSwitch"
                                                                   type="checkbox">
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                            <div class="offcanvas-body app-scroll">
                                <div class="row row-cols-3 g-2">
                                    <div class="d-flex-center text-center">
                                        <a class="text-light-primary w-100 rounded-3 py-3 px-2 "
                                           href="{{route('product')}}"
                                           target="_blank">
                                                          <span>
                                                            <i class="ph-light  ph-shopping-bag-open  f-s-30"></i>
                                                          </span>
                                            <p class="mb-0 f-w-500 text-dark">E-shop</p>
                                        </a>
                                    </div>
                                    <div class="d-flex-center text-center">
                                        <a class="text-light-danger w-100 rounded-3 py-3 px-2 "
                                           href="{{route('email')}}"
                                           target="_blank">
                                                         <span>
                                                           <i class="ph-light  ph-envelope  f-s-30"></i>
                                                         </span>
                                            <p class="mb-0 f-w-500 text-dark">Email</p>
                                        </a>
                                    </div>
                                    <div class="d-flex-center text-center">
                                        <a class="text-light-success w-100 rounded-3 py-3 px-2 "
                                           href="{{route('chat')}}"
                                           target="_blank">
                                                         <span>
                                                           <i class="ph-light  ph-chat-circle-text  f-s-30"></i>
                                                         </span>
                                            <p class="mb-0 f-w-500 text-dark">Chat</p>
                                        </a>
                                    </div>
                                    <div class="d-flex-center text-center">
                                        <a class="text-light-warning w-100 rounded-3 py-3 px-2 "
                                           href="{{route('project_app')}}"
                                           target="_blank">
                                                         <span>
                                                           <i class="ph-light  ph-projector-screen-chart  f-s-30"></i>
                                                         </span>
                                            <p class="mb-0 f-w-500 text-dark">Project</p>
                                        </a>
                                    </div>
                                    <div class="d-flex-center text-center">
                                        <a class="text-light-info w-100 rounded-3 py-3 px-2 "
                                           href="{{route('invoice')}}"
                                           target="_blank">
                                                         <span>
                                                           <i class="ph-light  ph-scroll f-s-30"></i>
                                                         </span>
                                            <p class="mb-0 f-w-500 text-dark">Invoice</p>
                                        </a>
                                    </div>
                                    <div class="d-flex-center text-center">
                                        <a class="text-light-dark w-100 rounded-3 py-3 px-2 "
                                           href="{{route('blog')}}"
                                           target="_blank">
                                                         <span>
                                                           <i class="ph-light  ph-notebook f-s-30"></i>
                                                         </span>
                                            <p class="mb-0 f-w-500 text-dark">Blog</p>
                                        </a>
                                    </div>
                                    <div class="d-flex-center text-center">
                                        <a class="text-light-danger w-100 rounded-3 py-3 px-2 "
                                           href="{{route('calendar')}}"
                                           target="_blank">
                                                         <span>
                                                           <i class="ph-light  ph-calendar f-s-30"></i>
                                                         </span>
                                            <p class="mb-0 f-w-500 text-dark">Calender</p>
                                        </a>
                                    </div>
                                    <div class="d-flex-center text-center">
                                        <a class="text-light-warning w-100 rounded-3 py-3 px-2 "
                                           href="{{route('file_manager')}}"
                                           target="_blank">
                                                        <span>
                                                          <i class="ph-light  ph-folder-open f-s-30"></i>
                                                        </span>
                                            <p class="mb-0 f-w-500 text-dark txt-ellipsis-1">File
                                                Manager</p>
                                        </a>
                                    </div>
                                    <div class="d-flex-center text-center">
                                        <a class="text-light-primary w-100 rounded-3 py-3 px-2 "
                                           href="{{route('gallery')}} "
                                           target="_blank">
                                                        <span>
                                                          <i class="ph-light  ph-google-photos-logo f-s-30"></i>
                                                        </span>
                                            <p class="mb-0 f-w-500 text-dark">Gallery</p>
                                        </a>
                                    </div>
                                    <div class="d-flex-center text-center">
                                        <a class="text-light-success w-100 rounded-3 py-3 px-2 "
                                           href="{{route('profile.show')}}"
                                           target="_blank">
                                                        <span>
                                                          <i class="ph-light  ph-users-three f-s-30"></i>
                                                        </span>
                                            <p class="mb-0 f-w-500 text-dark">Profile</p>
                                        </a>
                                    </div>
                                    <div class="d-flex-center text-center">
                                        <a class="text-light-secondary w-100 rounded-3 py-3 px-2 "
                                           href="{{route('kanban_board')}}"
                                           target="_blank">
                                                        <span>
                                                          <i class="ph-light  ph-selection-foreground f-s-30"></i>
                                                        </span>
                                            <p class="mb-0 f-w-500 text-dark">Task Board</p>
                                        </a>
                                    </div>
                                    <div class="d-flex-center text-center">
                                        <a class="d-flex-center text-light-secondary w-100 h-100 rounded-3 p-2 dashed-1-secondary"
                                           href="{{route('kanban_board')}}"
                                           target="_blank">
                                                        <span>
                                                          <i class="ph-light  ph-plus f-s-30"></i>
                                                        </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="header-cart">
                        <a aria-controls="cartcanvasRight"
                           class="d-block head-icon position-relative bg-light-dark rounded-circle f-s-22 p-2"
                           data-bs-target="#cartcanvasRight"
                           data-bs-toggle="offcanvas"
                           href="#" role="button">
                            <i class="ph ph-shopping-cart-simple"></i>
                            <span
                                class="position-absolute translate-middle badge rounded-pill bg-danger badge-notification">4</span>
                        </a>
                        <div aria-labelledby="cartcanvasRightLabel"
                             class="offcanvas offcanvas-end header-cart-canvas"
                             id="cartcanvasRight"
                             tabindex="-1">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="cartcanvasRightLabel">Cart</h5>
                                <button aria-label="Close" class="btn-close" data-bs-dismiss="offcanvas"
                                        type="button"></button>
                            </div>
                            <div class="offcanvas-body app-scroll p-0">
                                <div class="head-container">
                                    <div class="head-box">
                                                  <span class="b-1-light bg-light-primary h-45 w-45 d-flex-center b-r-6">
                                                      <img alt="cart" class="img-fluid p-1"
                                                           src="{{asset('../assets/images/header/cart/01.png')}}">
                                                  </span>

                                        <div class="flex-grow-1 ms-2">
                                            <a class="mb-0 f-w-600 f-s-16" href="{{route('product_details')}}"
                                               target="_blank"> Backpacks (3<i
                                                    class="ti ti-star-filled text-warning f-s-12"></i>)
                                            </a>
                                            <div>
                                                            <span class="text-dark"><span
                                                                    class="text-secondary f-w-400">size</span> : M</span>
                                                <span class="text-dark ms-2"><span
                                                        class="text-secondary f-w-400">color</span> :Pink</span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <i class="ph ph-trash f-s-18 text-danger close-btn"></i>
                                            <p class="text-muted f-w-500 mb-0">$600.50 x 1</p>
                                        </div>
                                    </div>
                                    <div class="head-box">
                                                    <span class="b-1-light bg-light-primary h-45 w-45 d-flex-center b-r-6">
                                                      <img alt="cart" class="img-fluid p-1"
                                                           src="{{asset('../assets/images/header/cart/05.png')}}">
                                                  </span>
                                        <div class="flex-grow-1 ms-2">
                                            <a class="mb-0 f-w-600 f-s-16" href="{{route('product_details')}}"
                                               target="_blank"> Women's Watch (4<i
                                                    class="ti ti-star-filled text-warning f-s-12"></i>)</a>
                                            <div>
                                                            <span class="text-dark"><span
                                                                    class="text-secondary f-w-400">size</span> : S</span>
                                                <span class="text-dark ms-2"><span
                                                        class="text-secondary f-w-400">color</span> :Rose Gold</span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <i class="ph ph-trash f-s-18 text-danger close-btn"></i>
                                            <p class="text-muted f-w-500 mb-0">$519.10 x 2</p>
                                        </div>
                                    </div>
                                    <div class="head-box">
                                                    <span class="b-1-light bg-light-primary h-45 w-45 d-flex-center b-r-6">
                                                      <img alt="cart" class="img-fluid p-1"
                                                           src="{{asset('../assets/images/header/cart/04.png')}}">
                                                  </span>
                                        <div class="flex-grow-1 ms-2">
                                            <a class="mb-0 f-w-600 f-s-16" href="{{route('product_details')}}"
                                               target="_blank">Sandals (5 <i
                                                    class="ti ti-star-filled text-warning f-s-12"></i>)</a>
                                            <div>
                                                            <span class="text-dark"><span
                                                                    class="text-secondary f-w-400">size</span> : 8</span>
                                                <span class="text-dark ms-2"><span
                                                        class="text-secondary f-w-400">color</span> :White</span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <i class="ph ph-trash f-s-18 text-danger close-btn"></i>
                                            <p class="text-muted f-w-500 mb-0">$390 x 2</p>
                                        </div>
                                    </div>
                                    <div class="head-box ">
                                                    <span class="b-1-light bg-light-primary h-45 w-45 d-flex-center b-r-6">
                                                      <img alt="cart" class="img-fluid p-1"
                                                           src="{{asset('../assets/images/header/cart/03.png')}}">
                                                  </span>
                                        <div class="flex-grow-1 ms-2">
                                            <a class="mb-0 f-w-600 f-s-16" href="{{route('product_details')}}"
                                               target="_blank"> Jackets (3<i
                                                    class="ti ti-star-filled text-warning f-s-12"></i>)</a>
                                            <div>
                                                            <span class="text-dark"><span
                                                                    class="text-secondary f-w-400">size</span> : XL</span>
                                                <span class="text-dark ms-2"><span
                                                        class="text-secondary f-w-400">color</span> :Blue</span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <i class="ph ph-trash f-s-18 text-danger close-btn"></i>
                                            <p class="text-muted f-w-500 mb-0">$300.00 x 2</p>
                                        </div>
                                    </div>
                                    <div class="head-box ">
                                                    <span class="b-1-light bg-light-primary h-45 w-45 d-flex-center b-r-6">
                                                      <img alt="cart" class="img-fluid p-1"
                                                           src="{{asset('../assets/images/header/cart/02.png')}}">
                                                  </span>
                                        <div class="flex-grow-1 ms-2">
                                            <a class="mb-0 f-w-600 f-s-16" href="{{route('product_details')}}"
                                               target="_blank"> Shoes (3<i
                                                    class="ti ti-star-filled text-warning f-s-12"></i>)</a>
                                            <div>
                                                            <span class="text-dark"><span
                                                                    class="text-secondary f-w-400">size</span> : 9</span>
                                                <span class="text-dark ms-2"><span
                                                        class="text-secondary f-w-400">color</span> :White</span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <i class="ph ph-trash f-s-18 text-danger close-btn"></i>
                                            <p class="text-muted f-w-500 mb-0">$450.00 x 1</p>
                                        </div>
                                    </div>
                                    <div class="hidden-massage py-4 px-3">

                                        <div>
                                            <i class="ph-duotone  ph-shopping-bag-open f-s-50 text-primary"></i>
                                            <h6 class="mb-0">Your Cart is Empty</h6>
                                            <p class="text-secondary mb-0">Add some items :)</p>
                                            <a class="btn btn-light-primary btn-xs mt-2"
                                               href="{{route('product_details')}}">Shop
                                                Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="offcanvas-footer">
                                <div class="head-box-footer p-3">
                                    <div class="mb-4">
                                        <h6 class="text-muted f-w-600">Total <span
                                                class="float-end text-primary">$3,468.00</span></h6>
                                    </div>
                                    <div class="header-cart-btn">
                                        <a class="btn btn-primary" href="{{route('cart')}}" role="button"
                                           target="_blank">
                                            <i class="ti ti-eye"></i> View Cart</a>
                                        <a class="btn btn-success" href="{{route('checkout')}}" role="button"
                                           target="_blank">
                                            Checkout <i class="ti ti-shopping-cart"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="header-dark">
                        <div class="sun-logo head-icon bg-light-dark rounded-circle f-s-22 p-2">
                            <i class="ph ph-moon-stars"></i>
                        </div>
                        <div class="moon-logo head-icon bg-light-dark rounded-circle f-s-22 p-2">
                            <i class="ph ph-sun-dim"></i>
                        </div>
                    </li>

                    <li class="header-notification">
                        <a aria-controls="notificationcanvasRight"
                           class="d-block head-icon position-relative bg-light-dark rounded-circle f-s-22 p-2"
                           data-bs-target="#notificationcanvasRight"
                           data-bs-toggle="offcanvas"
                           href="#"
                           role="button"
                           id="notificationTrigger">
                            <i class="ph ph-bell"></i>
                            <!-- Dynamic notification badge -->
                            <span id="notificationBadge" class="position-absolute translate-middle badge rounded-pill bg-danger badge-notification" style="display: none;">0</span>
                        </a>
                        <div aria-labelledby="notificationcanvasRightLabel"
                             class="offcanvas offcanvas-end header-notification-canvas"
                             id="notificationcanvasRight" tabindex="-1">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="notificationcanvasRightLabel">
                                    <i class="ph ph-bell me-2"></i>Notifiche
                                    <span id="notificationCount" class="badge bg-primary ms-2">0</span>
                                </h5>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-outline-primary btn-sm me-2" onclick="markAllNotificationsRead()" title="Segna tutte come lette">
                                        <i class="ph ph-check-circle"></i>
                                    </button>
                                    <button aria-label="Close" class="btn-close" data-bs-dismiss="offcanvas" type="button"></button>
                                </div>
                            </div>
                            <div class="offcanvas-body app-scroll p-0" id="notificationsContainer">
                                <!-- Loading state -->
                                <div id="notificationsLoading" class="text-center p-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Caricamento...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Caricamento notifiche...</p>
                                </div>

                                <!-- Notifications will be loaded here -->
                                <div id="notificationsList" class="head-container" style="display: none;">
                                    <!-- Dynamic notifications loaded via JavaScript -->
                                </div>

                                <!-- Empty state -->
                                <div id="notificationsEmpty" class="text-center p-4" style="display: none;">
                                    <i class="ph ph-bell-slash display-4 text-muted mb-3"></i>
                                    <h6 class="text-muted">Nessuna notifica</h6>
                                    <p class="text-muted small">Le tue notifiche appariranno qui</p>
                                </div>

                                <!-- Footer actions -->
                                <div class="p-3 border-top" id="notificationsFooter" style="display: none;">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <a href="{{ route('notifications.index') }}" class="btn btn-outline-primary btn-sm w-100">
                                                <i class="ph ph-list me-1"></i>Vedi Tutte
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-outline-secondary btn-sm w-100" onclick="clearOldNotifications()">
                                                <i class="ph ph-trash me-1"></i>Pulisci
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<!-- Header Section ends -->
