@extends('layout.master')

@section('title', 'Kanban Board')
@section('css')
<!-- Force cache refresh -->
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
@endsection
@section('main-content')
    <div class="container-fluid">

        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Kanban Board</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="#" class="f-s-14 f-w-500">
                      <span>
                        <i class="ph-duotone  ph-stack f-s-16"></i> Apps
                      </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">Kanban Board</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Kanban Board start -->
        <div class="row">
            <div class="col-12">
                <div class="">
                    <div class=" kanban-board-container app-scroll ">
                        <div class="board">
                            <div class="board-column app-scroll">
                                <div class="board-column-header">
                                    <i class="ph-fill  ph-list-bullets me-2 f-s-16"></i> To Do
                                </div>
                                <div class="board-column-content-wrapper">
                                    <div class="board-column-content">
                                        <div class="board-item">
                                            <div class="board-item-content">
                                                <h6 class="mb-0">Create homepage wireframe.</h6>
                                                <div class="board-footer">
                                  <span class="badge text-bg-danger f-s-14"><i class="ph-bold  ph-clock-afternoon"></i>
                                    Nov 22</span>
                                                    <i class="ph-bold  ph-list f-s-14 me-2"></i>
                                                    <span class="f-s-14 me-2">
                                    <i class="ph-bold  ph-chat-text"></i>
                                    <span>2</span>
                                  </span>
                                                    <span class="badge text-bg-primary f-s-14"><i
                                                            class="ph-bold  ph-check-square-offset"></i> 1/2</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="board-item">
                                            <div class="board-item-content p-0">
                                                <div class="board-images">
                                                    <img src="{{asset('../assets/images/profile-app/07.jpg')}}" class="img-fluid" alt="">
                                                </div>
                                                <div class="p-3">
                                                    <h6 class="mb-0">Draft new article content.</h6>
                                                    <div class="board-footer">
                                    <span class="badge text-bg-danger f-s-14"><i
                                            class="ph-bold  ph-clock-afternoon"></i> Dec 19</span>
                                                        <i class="ph-bold  ph-list f-s-14 me-2"></i>
                                                        <span class="f-s-14 me-2">
                                      <i class="ph-bold  ph-chat-text"></i>
                                      <span>2</span>
                                    </span>
                                                        <span class="badge text-bg-primary f-s-14"><i
                                                                class="ph-bold  ph-check-square-offset"></i> 1/2</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="board-item">
                                            <div class="board-item-content">
                                                <h6 class="mb-0">Analyze client comments.</h6>
                                                <div class="board-footer">
                                  <span class="badge text-bg-danger f-s-14"><i class="ph-bold  ph-clock-afternoon"></i>
                                    Sep 28</span>
                                                    <i class="ph-bold  ph-list f-s-14 me-2"></i>
                                                    <span class="f-s-14 me-2">
                                    <i class="ph-bold  ph-chat-text"></i>
                                    <span>2</span>
                                  </span>
                                                    <span class="badge text-bg-primary f-s-14"><i
                                                            class="ph-bold  ph-check-square-offset"></i> 1/2</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="board-column app-scroll">
                                <div class="board-column-header"><i class="ph-bold  ph-chart-line-up me-2 f-s-16"></i> IN PROGRESS</div>
                                <div class="board-column-content-wrapper">
                                    <div class="board-column-content">
                                        <div class="board-item">
                                            <div class="board-item-content p-0">
                                                <div class="board-images">
                                                    <img src="{{asset('../assets/images/profile-app/10.jpg')}}" class="img-fluid" alt="">
                                                </div>
                                                <div class="p-3">
                                                    <h6 class="mb-0">Prepare email marketing.</h6>
                                                    <div class="board-footer">
                                    <span class="badge text-bg-danger f-s-14"><i
                                            class="ph-bold  ph-clock-afternoon"></i>Jul 10</span>
                                                        <i class="ph-bold  ph-list f-s-14 me-2"></i>
                                                        <span class="f-s-14 me-2">
                                      <i class="ph-bold  ph-chat-text"></i>
                                      <span>2</span>
                                    </span>
                                                        <span class="badge text-bg-primary f-s-14"><i
                                                                class="ph-bold  ph-check-square-offset"></i> 1/2</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="board-column app-scroll">
                                <div class="board-column-header"><i class="ph-bold  ph-eye me-2 f-s-16"></i> REVIEW</div>
                                <div class="board-column-content-wrapper">
                                    <div class="board-column-content">
                                        <div class="board-item">
                                            <div class="board-item-content">
                                                <h6 class="mb-0">Revise product listings.</h6>
                                                <div class="board-footer">
                                  <span class="badge text-bg-danger f-s-14"><i class="ph-bold  ph-clock-afternoon"></i>
                                   Mar 27</span>
                                                    <i class="ph-bold  ph-list f-s-14 me-2"></i>
                                                    <span class="f-s-14 me-2">
                                    <i class="ph-bold  ph-chat-text"></i>
                                    <span>2</span>
                                  </span>
                                                    <span class="badge text-bg-primary f-s-14"><i
                                                            class="ph-bold  ph-check-square-offset"></i> 1/2</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="board-item">
                                            <div class="board-item-content">
                                                <h6 class="mb-0">Create initial app mockup.</h6>
                                                <div class="board-footer">
                                  <span class="badge text-bg-danger f-s-14"><i class="ph-bold  ph-clock-afternoon"></i>
                                    Apr 09</span>
                                                    <i class="ph-bold  ph-list f-s-14 me-2"></i>
                                                    <span class="f-s-14 me-2">
                                    <i class="ph-bold  ph-chat-text"></i>
                                    <span>2</span>
                                  </span>
                                                    <span class="badge text-bg-primary f-s-14"><i
                                                            class="ph-bold  ph-check-square-offset"></i> 1/2</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="board-column app-scroll">
                                <div class="board-column-header"><i class="ph-bold ph-check-square-offset me-2 f-s-16"></i>DONE</div>
                                <div class="board-column-content-wrapper">
                                    <div class="board-column-content">

                                        <div class="board-item">
                                            <div class="board-item-content p-0">
                                                <div class="board-images">
                                                    <img src="{{asset('../assets/images/profile-app/05.jpg')}}" class="img-fluid" alt="">
                                                </div>
                                                <div class="p-3">
                                                    <h6 class="mb-0">Compile financial data.</h6>
                                                    <div class="board-footer">
                                    <span class="badge text-bg-danger f-s-14"><i
                                            class="ph-bold  ph-clock-afternoon"></i> Jul 24</span>
                                                        <i class="ph-bold  ph-list f-s-14 me-2"></i>
                                                        <span class="f-s-14 me-2">
                                      <i class="ph-bold  ph-chat-text"></i>
                                      <span>2</span>
                                    </span>
                                                        <span class="badge text-bg-primary f-s-14"><i
                                                                class="ph-bold  ph-check-square-offset"></i> 1/2</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="board-column app-scroll">
                                <div class="board-column-header"><i class="ph-bold  ph-check-circle me-2 f-s-16"></i>TESTED</div>
                                <div class="board-column-content-wrapper">
                                    <div class="board-column-content">
                                        <div class="board-item">
                                            <div class="board-item-content p-0">
                                                <div class="board-images">
                                                    <img src="{{asset('../assets/images/profile-app/09.jpg')}}" class="img-fluid" alt="">
                                                </div>
                                                <div class="p-3">
                                                    <h6 class="mb-0">Gather market insights.</h6>
                                                    <div class="board-footer">
                                    <span class="badge text-bg-danger f-s-14"><i
                                            class="ph-bold  ph-clock-afternoon"></i>Oct 04</span>
                                                        <i class="ph-bold  ph-list f-s-14 me-2"></i>
                                                        <span class="f-s-14 me-2">
                                      <i class="ph-bold  ph-chat-text"></i>
                                      <span>2</span>
                                    </span>
                                                        <span class="badge text-bg-primary f-s-14"><i
                                                                class="ph-bold  ph-check-square-offset"></i> 1/2</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="board-item">
                                            <div class="board-item-content">
                                                <h6 class="mb-0">Improve page load times.</h6>
                                                <div class="board-footer">
                                  <span class="badge text-bg-danger f-s-14"><i class="ph-bold  ph-clock-afternoon"></i>
                                    Aug 23</span>
                                                    <i class="ph-bold  ph-list f-s-14 me-2"></i>
                                                    <span class="f-s-14 me-2">
                                    <i class="ph-bold  ph-chat-text"></i>
                                    <span>2</span>
                                  </span>
                                                    <span class="badge text-bg-primary f-s-14"><i
                                                            class="ph-bold  ph-check-square-offset"></i> 1/2</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Kanban Board end -->
    </div>
@endsection

@section('script')
<!-- kanban_board hammer js-->
<script src="{{ asset('assets/vendor/kanban_board/hammer.min.js') }}?v={{ time() }}"></script>

<!-- kanban_board muuri js-->
<script src="{{ asset('assets/vendor/kanban_board/muuri.min.js') }}?v={{ time() }}"></script>

<!-- kanban_board js-->
<script src="{{ asset('assets/js/kanban_board.js') }}?v={{ time() }}"></script>

<script>
// Setup CSRF token per tutte le richieste AJAX
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Test if libraries are loaded
    console.log('Testing Kanban libraries...');
    if (typeof Hammer !== 'undefined') {
        console.log('✅ Hammer.js loaded successfully');
    } else {
        console.log('❌ Hammer.js not loaded');
    }

    if (typeof Muuri !== 'undefined') {
        console.log('✅ Muuri.js loaded successfully');
    } else {
        console.log('❌ Muuri.js not loaded');
    }
});
</script>
@endsection
