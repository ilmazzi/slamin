@extends('layout.master')
@section('title', 'Misc')
@section('css')

@endsection
@section('main-content')
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Misc</h4>
                
            </div>
        </div>
        <!-- Breadcrumb end -->
        <!-- breadcrumb section start  -->
        <div class="row">

            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h5>Breadcrumbs</h5>
                    </div>
                    <div class="card-body app-breadcrumb">
                        <div>
                            
                        </div>

                        <div>
                            
                        </div>

                        <div>
                            
                        </div>

                        <div class="bootstrap-breadcrumb divider">
                            
                        </div>

                        <div class="bootstrap-breadcrumb divider1">
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card equal-card">
                    <div class="card-header">
                        <h5>Custom Breadcrumbs</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            
                        </div>

                        <div class="mt-4">
                            
                        </div>

                        <div class="mt-4">
                            
                        </div>

                        <div class="mt-4">
                            
                        </div>

                        <div class="mt-4">
                            
                        </div>


                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card equal-card">
                    <div class="card-header">
                        <h5>Custom Breadcrumb</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            
                        </div>
                        <div class="">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card equal-card">
                    <div class="card-header">
                        <h5>Rounded Breadcrumb</h5>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            
                        </div>

                        <div class="mb-3">
                            
                        </div>

                        <div>
                            
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Steps</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-wizard">

                            <div class="form-wizard-header">
                                <ul class="form-wizard-steps">
                                    <li class="active">
                                        <span class="wizard-steps">1</span>
                                    </li>
                                    <li>
                                        <span class="wizard-steps">2</span>
                                    </li>
                                    <li>
                                        <span class="wizard-steps">3</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="form-wizard">
                            <div class="form-wizard-header">
                                <ul class="form-wizard-steps">
                                    <li class="active">
                                        <span class="wizard-steps circle-steps"><i class="ti ti-users"></i></span>
                                    </li>
                                    <li>
                                                    <span class="wizard-steps circle-steps"><i
                                                            class="ti ti-info-circle"></i></span>
                                    </li>
                                    <li>
                                                    <span class="wizard-steps circle-steps"><i
                                                            class="ti ti-table-export"></i></span>
                                    </li>
                                </ul>
                            </div>


                        </div>
                        <div class="mb-3">
                            <ul class="shape-step">
                                <li class="active"><a href="#"><i class="ti ti-circle-check-filled"></i>Cart</a>
                                </li>
                                <li class="active"><a href="#">Billing</a></li>
                                <li><a href="#">Shipping</a></li>
                                <li><a href="#">Payment</a></li>

                            </ul>
                        </div>

                    </div>
                </div>


            </div>
            <div class="col-lg-6">
                <div class="card equal-card">
                    <div class="card-header">
                        <h5>Pagination</h5>
                    </div>
                    <div class="card-body">
                        <div class="app-pagination-link">
                            <ul class="pagination app-pagination">
                                <li class="page-item"><a class="page-link b-r-left" href="#">Previous</a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                        </div>

                        <div class="mt-3">
                            <ul class="pagination app-pagination">
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="mt-3">
                            <ul class="pagination app-pagination">
                                <li class="page-item disabled">
                                    <a class="page-link b-r-left">Previous</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item active" aria-current="page">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </div>

                        <div class="mt-3">
                            <ul class="pagination pagination-sm justify-content-end app-pagination">
                                <li class="page-item disabled">
                                    <a class="page-link b-r-left">Previous</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item active" aria-current="page">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </div>

                        <div class="mt-3">
                            <div>
                                <ul class="pagination pagination-lg justify-content-end app-pagination">
                                    <li class="page-item disabled">
                                        <a class="page-link b-r-left">«</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item active" aria-current="page">
                                        <a class="page-link" href="#">2</a>
                                    </li>
                                    <li class="page-item d-none d-sm-block"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link b-r-right" href="#">»</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- breadcrumb section end  -->
    </div>
@endsection

@push('scripts')


@endsection
