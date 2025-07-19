<!-- Menu Navigation starts -->
<nav>
    <div class="app-logo">
        <a class="logo d-inline-block" href="{{route('index')}}">
            <img alt="#" src="{{asset('../assets/images/logo/1.png')}}">
        </a>

        <span class="bg-light-primary toggle-semi-nav d-flex-center">
                <i class="ti ti-chevron-right"></i>
            </span>

        <div class="d-flex align-items-center nav-profile p-3">
                <span class="h-45 w-45 d-flex-center b-r-10 position-relative bg-danger m-auto">
                    <img alt="avatar" class="img-fluid b-r-10" src="{{asset('../assets/images/avatar/woman.jpg')}}">
                    <span class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                </span>
            <div class="flex-grow-1 ps-2">
                <h6 class="text-primary mb-0"> Ninfa Monaldo</h6>
                <p class="text-muted f-s-12 mb-0">Web Developer</p>
            </div>


            <div class="dropdown profile-menu-dropdown">
                <a aria-expanded="false" data-bs-auto-close="true" data-bs-placement="top" data-bs-toggle="dropdown"
                   role="button">
                    <i class="ti ti-settings fs-5"></i>
                </a>
                <ul class="dropdown-menu">
                    <li class="dropdown-item">
                        <a class="f-w-500" href="{{route('profile')}}" target="_blank">
                            <i class="ph-duotone  ph-user-circle pe-1 f-s-20"></i> Profile Details
                        </a>
                    </li>
                    <li class="dropdown-item">
                        <a class="f-w-500" href="{{route('setting')}}" target="_blank">
                            <i class="ph-duotone  ph-gear pe-1 f-s-20"></i> Settings
                        </a>
                    </li>
                    <li class="dropdown-item">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <a class="f-w-500" href="#">
                                    <i class="ph-duotone  ph-detective pe-1 f-s-20"></i> Incognito
                                </a>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="form-check form-switch">
                                    <input class="form-check-input form-check-primary" id="incognitoSwitch"
                                           type="checkbox">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown-item">
                        <a class="mb-0 text-secondary f-w-500" href="{{route('sign_up')}}" target="_blank">
                            <i class="ph-bold  ph-plus pe-1 f-s-20"></i> Add account
                        </a>
                    </li>

                    <li class="app-divider-v dotted py-1"></li>

                    <li class="dropdown-item">
                        <a class="mb-0 text-danger" href="{{route('sign_in')}}" target="_blank">
                            <i class="ph-duotone  ph-sign-out pe-1 f-s-20"></i> Log Out
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
    <div class="app-nav" id="app-simple-bar">
        <ul class="main-nav p-0 mt-2">
            <li class="menu-title">
                <span>Dashboard</span>
            </li>
            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#dashboard">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#home"></use>
                    </svg>
                    dashboard
                    <span class="badge bg-danger  badge-dashboard badge-notification ms-2">New</span>

                </a>
                <ul class="collapse" id="dashboard">
                    <li><a href="{{route('index')}}">Ecommerce</a></li>
                    <li><a href="{{route('project_dashboard')}}">Project</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#apps">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#stack"></use>
                    </svg>
                    Apps
                </a>
                <ul class="collapse" id="apps">
                    <li><a href="{{route('calendar')}}">Calender</a></li>
                    <li class="another-level">
                        <a aria-expanded="false" data-bs-toggle="collapse" href="#Profile-page">
                            Profile
                        </a>
                        <ul class="collapse" id="Profile-page">
                            <li><a href="{{route('profile')}}">Profile</a></li>
                            <li><a href="{{route('setting')}}">Setting</a></li>
                        </ul>
                    </li>
                    <li class="another-level">
                        <a aria-expanded="false" data-bs-toggle="collapse" href="#projects-page">
                            Projects Page
                        </a>
                        <ul class="collapse" id="projects-page">
                            <li><a href="{{route('project_app')}}">projects</a></li>
                            <li><a href="{{route('project_details')}}">projects Details</a></li>
                        </ul>
                    </li>
                    <li><a href="{{route('to_do')}}">To-Do</a></li>
                    <li><a href="{{route('team')}}">Team</a></li>
                    <li><a href="{{route('api')}}">API</a></li>
                    <li class="another-level">
                        <a aria-expanded="false" data-bs-toggle="collapse" href="#ticket-page">
                            Ticket
                        </a>
                        <ul class="collapse" id="ticket-page">
                            <li><a href="{{route('ticket')}}">Ticket</a></li>
                            <li><a href="{{route('ticket_details')}}">Ticket Details</a></li>
                        </ul>
                    </li>
                    <li class="another-level">
                        <a aria-expanded="false" data-bs-toggle="collapse" href="#email-page">
                            Email Page
                        </a>
                        <ul class="collapse" id="email-page">
                            <li><a href="{{route('email')}}"> Email</a></li>
                            <li><a href="{{route('read_email')}}">Read Email</a></li>
                        </ul>
                    </li>
                    <li class="another-level">
                        <a aria-expanded="false" data-bs-toggle="collapse" href="#e-shop">
                            E-shop
                        </a>
                        <ul class="collapse" id="e-shop">
                            <li><a href="{{route('cart')}}">Cart</a></li>
                            <li><a href="{{route('product')}}">Product</a></li>
                            <li><a href="{{route('add_product')}}">Add Product</a></li>
                            <li><a href="{{route('product_details')}}">Product-Details</a></li>
                            <li><a href="{{route('product_list')}}">Product list</a></li>
                            <li><a href="{{route('orders')}}">Orders</a></li>
                            <li><a href="{{route('order_details')}}">Orders Details</a></li>
                            <li><a href="{{route('order_list')}}">Orders List</a></li>
                            <li><a href="{{route('checkout')}}">Check out</a></li>
                            <li><a href="{{route('wishlist')}}">Wishlist</a></li>
                        </ul>
                    </li>
                    <li><a href="{{route('invoice')}}">Invoice</a></li>
                    <li><a href="{{route('chat')}}">Chat</a></li>
                    <li><a href="{{route('file_manager')}}">File manager</a></li>
                    <li><a href="{{route('bookmark')}}">Bookmark</a></li>
                    <li><a href="{{route('kanban_board')}}">Kanban board</a></li>
                    <li><a href="{{route('timeline')}}">Timeline</a></li>
                    <li><a href="{{route('faq')}}">FAQS</a></li>
                    <li><a href="{{route('pricing')}}">Pricing</a></li>
                    <li><a href="{{route('gallery')}}">Gallery</a></li>
                    <li class="another-level">
                        <a aria-expanded="false" data-bs-toggle="collapse" href="#blog-page">
                            Blog Page
                        </a>
                        <ul class="collapse" id="blog-page">
                            <li><a href="{{route('blog')}}">Blog</a></li>
                            <li><a href="{{route('blog_details')}}">Blog Details</a></li>
                            <li><a href="{{route('add_blog')}}">Add Blog</a></li>

                        </ul>
                    </li>
                </ul>
            </li>
            <li class="no-sub">
                <a href="{{route('widget')}}">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#squares"></use>
                    </svg>
                    Widgets
                </a>
            </li>

            <li class="menu-title"><span>Component</span></li>
            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#ui-kits">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#briefcase"></use>
                    </svg>
                    UI kits
                </a>
                <ul class="collapse" id="ui-kits">
                    <li><a href="{{route('cheatsheet')}}">Cheatsheet</a></li>
                    <li><a href="{{route('alert')}}">Alert</a></li>
                    <li><a href="{{route('badges')}}">Badges</a></li>
                    <li><a href="{{route('buttons')}}">Buttons</a></li>
                    <li><a href="{{route('cards')}}">Cards</a></li>
                    <li><a href="{{route('dropdown')}}">Dropdown</a></li>
                    <li><a href="{{route('grid')}}">Grid</a></li>
                    <li><a href="{{route('avatar')}}">Avatar</a></li>
                    <li><a href="{{route('tabs')}}">Tabs</a></li>
                    <li><a href="{{route('accordions')}}">Accordions</a></li>
                    <li><a href="{{route('progress')}}">Progress</a></li>
                    <li><a href="{{route('notifications')}}">Notifications</a></li>
                    <li><a href="{{route('list')}}">Lists</a></li>
                    <li><a href="{{route('helper_classes')}}">Helper Classes</a></li>
                    <li><a href="{{route('background')}}">Background</a></li>
                    <li><a href="{{route('divider')}}">Divider</a></li>
                    <li><a href="{{route('ribbons')}}">Ribbons</a></li>
                    <li><a href="{{route('editor')}}">Editor </a></li>
                    <li><a href="{{route('collapse')}}">Collapse</a></li>
                    <li><a href="{{route('footer_page')}}">Footer</a></li>
                    <li><a href="{{route('shadow')}}">Shadow</a></li>
                    <li><a href="{{route('wrapper')}}">Wrapper</a></li>
                    <li><a href="{{route('bullet')}}">Bullet</a></li>
                    <li><a href="{{route('placeholder')}}">Placeholder</a></li>
                    <li><a href="{{route('alignment')}}">Alignment Thing</a></li>
                </ul>
            </li>


            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#advance-ui">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#briefcase-advance"></use>
                    </svg>
                    Advance UI
                    <span class=" badge rounded-pill bg-warning badge-notification ms-2">
                12+
                <span class="visually-hidden">unread messages</span>
              </span>
                </a>
                <ul class="collapse" id="advance-ui">
                    <li><a href="{{route('modals')}}">Modals</a></li>
                    <li><a href="{{route('offcanvas')}}">Offcanvas Toggle</a></li>
                    <li><a href="{{route('sweetalert')}}">Sweat Alert</a></li>
                    <li><a href="{{route('scrollbar')}}">Scrollbar</a></li>
                    <li><a href="{{route('spinners')}}">Spinners</a></li>
                    <li><a href="{{route('animation')}}">Animation</a></li>
                    <li><a href="{{route('video_embed')}}">Video Embed</a></li>
                    <li><a href="{{route('tour')}}">Tour</a></li>
                    <li><a href="{{route('slick_slider')}}">Slider</a></li>
                    <li><a href="{{route('bootstrap_slider')}}">Bootstrap Slider</a></li>
                    <li><a href="{{route('scrollpy')}}">Scrollpy</a></li>
                    <li><a href="{{route('tooltips_popovers')}}">Tooltip & Popovers</a></li>
                    <li><a href="{{route('ratings')}}">Rating</a></li>
                    <li><a href="{{route('prismjs')}}">Prismjs</a></li>
                    <li><a href="{{route('count_down')}}">Count Down</a></li>
                    <li><a href="{{route('count_up')}}"> Count up </a></li>
                    <li><a href="{{route('draggable')}}">Draggable</a></li>
                    <li><a href="{{route('tree-view')}}">Tree View</a></li>
                    <li><a href="{{route('block_ui')}}">Block Ui </a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#icons">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#gift"></use>
                    </svg>
                    Icons
                </a>
                <ul class="collapse" id="icons">
                    <li><a href="{{route('fontawesome')}}">Fontawesome</a></li>
                    <li><a href="{{route('flag_icons')}}">Flag</a></li>
                    <li><a href="{{route('tabler_icons')}}">Tabler</a></li>
                    <li><a href="{{route('weather_icon')}}">Weather</a></li>
                    <li><a href="{{route('animated_icon')}}">Animated</a></li>
                    <li><a href="{{route('iconoir_icon')}}">Iconoir</a></li>
                    <li><a href="{{route('phosphor')}}">Phosphor</a></li>
                </ul>
            </li>
            <li class="no-sub">
                <a href="{{route('misc')}}">

                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#rectangle"></use>
                    </svg>
                    Misc
                </a>
            </li>
            <li class="menu-title"><span>Map & Charts </span></li>
            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#maps">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#location"></use>
                    </svg>
                    Map
                </a>
                <ul class="collapse" id="maps">
                    <li><a href="{{route('google_map')}}">Google Maps</a></li>
                    <li><a href="{{route('leaflet_map')}}">Leaflet map</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#chart">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#chart"></use>
                    </svg>
                    Chart
                </a>
                <ul class="collapse" id="chart">
                    <li><a href="{{route('chart_js')}}">Chart js</a></li>


                    <li class="another-level">
                        <a aria-expanded="false" data-bs-toggle="collapse" href="#apexcharts-page">
                            Apexcharts
                        </a>
                        <ul class="collapse" id="apexcharts-page">
                            <li><a href="{{route('line_chart')}}">Line</a></li>
                            <li><a href="{{route('area_chart')}}">Area</a></li>
                            <li><a href="{{route('column_chart')}}">Column</a></li>
                            <li><a href="{{route('bar_chart')}}">Bar</a></li>
                            <li><a href="{{route('mixed_chart')}}">Mixed</a></li>
                            <li><a href="{{route('timeline_range_charts')}}">Timeline & Range-Bars</a></li>
                            <li><a href="{{route('candlestick_chart')}}">Candlestick</a></li>
                            <li><a href="{{route('boxplot_chart')}}">Boxplot</a></li>
                            <li><a href="{{route('bubble_chart')}}">Bubble</a></li>
                            <li><a href="{{route('scatter_chart')}}">Scatter</a></li>
                            <li><a href="{{route('heatmap')}}">Heatmap</a></li>
                            <li><a href="{{route('treemap_chart')}}">Treemap</a></li>
                            <li><a href="{{route('pie_charts')}}">Pie</a></li>
                            <li><a href="{{route('radial_bar_chart')}}">Radial bar</a></li>
                            <li><a href="{{route('radar_chart')}}">Radar</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="menu-title"><span>Table & forms </span></li>

            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#table">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#table"></use>
                    </svg>
                    Table
                </a>
                <ul class="collapse" id="table">
                    <li><a href="{{route('basic_table')}}">BasicTable</a></li>
                    <li><a href="{{route('data_table')}}">Data Table</a></li>
                    <li><a href="{{route('list_table')}}">List Js</a></li>
                    <li><a href="{{route('advance_table')}}">Advance Table</a></li>
                </ul>
            </li>


            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#forms">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#wallet"></use>
                    </svg>
                    Forms elements
                </a>
                <ul class="collapse" id="forms">
                    <li><a href="{{route('form_validation')}}">Form Validation</a></li>
                    <li><a href="{{route('base_inputs')}}">Base Input</a></li>
                    <li><a href="{{route('checkbox_radio')}}">Checkbox & Radio</a></li>
                    <li><a href="{{route('input_groups')}}">Input Groups</a></li>
                    <li><a href="{{route('input_masks')}}">Input Masks</a></li>
                    <li><a href="{{route('floating_labels')}}">Floating Labels</a></li>
                    <li><a href="{{route('date_picker')}}">Datetimepicker</a></li>
                    <li><a href="{{route('touch_spin')}}">Touch spin</a></li>
                    <li><a href="{{route('select')}}">Select2</a></li>
                    <li><a href="{{route('switch')}}">Switch</a></li>
                    <li><a href="{{route('range_slider')}}">Range Slider</a></li>
                    <li><a href="{{route('typeahead')}}">Typeahead</a></li>
                    <li><a href="{{route('textarea')}}">Textarea</a></li>
                    <li><a href="{{route('clipboard')}}">Clipboard</a></li>
                    <li><a href="{{route('file_upload')}}">File Upload</a></li>
                    <li><a href="{{route('dual_list_boxes')}}">Dual List Boxes</a></li>
                    <li><a href="{{route('default_forms')}}">Default Forms</a></li>
                </ul>
            </li>

            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#ready_to_use">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#newspaper"></use>
                    </svg>
                    Ready to use
                    <span class="badge text-bg-success badge-notification ms-2">2</span>
                </a>
                <ul class="collapse" id="ready_to_use">
                    <li><a href="{{route('form_wizards')}}">Form wizards</a></li>
                    <li><a href="{{route('form_wizard_1')}}">Form wizards 1</a></li>
                    <li><a href="{{route('form_wizard_2')}}">Form wizards 2</a></li>
                    <li><a href="{{route('ready_to_use_form')}}">Ready To Use Form</a></li>
                    <li><a href="{{route('ready_to_use_table')}}">Ready To Use Tables</a></li>
                </ul>
            </li>

            <li class="menu-title"><span>Pages</span></li>

            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#auth_pages">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#window"></use>
                    </svg>
                    Auth Pages
                </a>
                <ul class="collapse" id="auth_pages">
                    <li><a href="{{route('sign_in')}}">Sign In</a></li>
                    <li><a href="{{route('sign_in_1')}}">Sign In with Bg-image</a></li>
                    <li><a href="{{route('sign_up')}}">Sign Up</a></li>
                    <li><a href="{{route('sign_up_1')}}">Sign Up with Bg-image</a></li>
                    <li><a href="{{route('password_reset')}}">Password Reset</a></li>
                    <li><a href="{{route('password_reset_1')}}">Password Reset with Bg-image</a></li>
                    <li><a href="{{route('password_create')}}">Password Create</a></li>
                    <li><a href="{{route('password_create_1')}}">Password Create with Bg-image</a></li>
                    <li><a href="{{route('lock_screen')}}">Lock Screen</a></li>
                    <li><a href="{{route('lock_screen_1')}}">Lock Screen with Bg-image</a></li>
                    <li><a href="{{route('two_step_verification')}}">Two-Step Verification</a></li>
                    <li><a href="{{route('two_step_verification_1')}}">Two-Step Verification with Bg-image</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#error_pages">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#exclamation-circle"></use>
                    </svg>
                    Error Pages
                </a>
                <ul class="collapse" id="error_pages">
                    <li><a href="{{route('error_400')}}">Bad Request </a></li>
                    <li><a href="{{route('error_403')}}">Forbidden </a></li>
                    <li><a href="{{route('error_404')}}">Not Found</a></li>
                    <li><a href="{{route('error_500')}}">Internal Server</a></li>
                    <li><a href="{{route('error_503')}}">Service Unavailable</a></li>
                </ul>
            </li>

            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#other_pages">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#document"></use>
                    </svg>
                    Other Pages
                </a>
                <ul class="collapse" id="other_pages">
                    <li><a href="{{route('blank')}}">Blank</a></li>
                    <li><a href="{{route('maintenance')}}">Maintenance</a></li>
                    <li><a href="{{route('landing')}}">Landing Page</a></li>
                    <li><a href="{{route('coming_soon')}}">Coming Soon</a></li>
                    <li><a href="{{route('sitemap')}}">Sitemap</a></li>
                    <li><a href="{{route('privacy_policy')}}">Privacy Policy</a></li>
                    <li><a href="{{route('terms_condition')}}">Terms &amp; Condition</a></li>
                </ul>
            </li>

            <li class="menu-title"><span>Others</span></li>

            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#level">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#arrow-down"></use>
                    </svg>
                    2 level
                </a>
                <ul class="collapse" id="level">
                    <li><a href="#">Blank</a></li>
                    <li class="another-level">
                        <a aria-expanded="false" data-bs-toggle="collapse" href="#level2">
                            Another level
                        </a>
                        <ul class="collapse" id="level2">
                            <li><a href="{{route('blank')}}">Blank</a></li>
                        </ul>
                    </li>

                </ul>
            </li>
            <li class="no-sub">
                <a href="https://phpstack-1384472-5121645.cloudwaysapps.com/document/laravel/ki-admin/index.html">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#document-text"></use>
                    </svg>
                    Document
                </a>
            </li>

            <li class="no-sub">
                <a href="mailto:teqlathemes@gmail.com">
                    <svg stroke="currentColor" stroke-width="1.5">
                        <use xlink:href="../assets/svg/_sprite.svg#chat-bubble"></use>
                    </svg>
                    Support
                </a>
            </li>


        </ul>
    </div>

    <div class="menu-navs">
        <span class="menu-previous"><i class="ti ti-chevron-left"></i></span>
        <span class="menu-next"><i class="ti ti-chevron-right"></i></span>
    </div>

</nav>
<!-- Menu Navigation ends -->
