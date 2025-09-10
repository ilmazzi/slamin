@extends('layout.master')
@section('title', 'Bar Chart')
@section('css')

<!-- apexcharts css-->
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendor/apexcharts/apexcharts.css')}}">

@endsection
@section('main-content')

    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Bar</h4>
                
            </div>
        </div>
        <!-- Breadcrumb end -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Basic Bar Chart</h5>
                    </div>
                    <div class="card-body">
                        <div id="basic-bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Patterned Bar Chart </h5>
                    </div>
                    <div class="card-body">
                        <div id="patterned-bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Stacked Bar Chart </h5>
                    </div>
                    <div class="card-body">
                        <div id="stacked-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Grouped Stacked Bars</h5>
                    </div>
                    <div class="card-body">
                        <div id="grouped-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')


<!-- apexcharts-->
<script src="{{asset('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>

<!-- js-->
<script src="{{('assets/js/bar.js')}}"></script>
@endsection

