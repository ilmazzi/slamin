@extends('layout.master')
@section('title', 'Scatter Chart')
@section('css')
    <!-- apexcharts css-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendor/apexcharts/apexcharts.css')}}">
@endsection
@section('main-content')
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Scatter</h4>
                
            </div>
        </div>
        <!-- Breadcrumb end -->
        <div class="row">
            <!-- Scatter (XY) Chart start -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5> Scatter (XY) Chart</h5>
                    </div>
                    <div class="card-body">
                        <div id="scatter1"></div>
                    </div>
                </div>
            </div>
            <!-- Scatter (XY) Chart end -->

            <!-- Scatter – Image fill start -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5> Scatter – Image fill</h5>
                    </div>
                    <div class="card-body">
                        <div id="scatter3"></div>
                    </div>
                </div>
            </div>
            <!-- Scatter – Image fill end -->
        </div>
    </div>
@endsection

@push('scripts')


<!-- apexcharts-->
<script src="{{asset('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>

<!-- js-->
<script src="{{asset('assets/js/scatter.js')}}"></script>

@endsection
