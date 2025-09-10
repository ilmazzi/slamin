@extends('layout.master')
@section('title', 'Column Chart')
@section('css')
    <!-- apexcharts css-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendor/apexcharts/apexcharts.css')}}">
@endsection
@section('main-content')
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Column</h4>
                
            </div>
        </div>
        <!-- Breadcrumb end -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Basic Column Chart</h5>
                    </div>

                    <div class="card-body">
                        <div id="basic-colum"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5> Dumbbell Chart </h5>
                    </div>

                    <div class="card-body">
                        <div id="dumbbell-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5> Column Chart </h5>
                    </div>

                    <div class="card-body">
                        <div id="column-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5> Column with Markers</h5>
                    </div>

                    <div class="card-body">
                        <div id="markers-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')


<!-- apexcharts js-->
<script src="{{asset('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{asset('assets/vendor/apexcharts/column/dayjs.min.js')}}"></script>
<script src="{{asset('assets/vendor/apexcharts/column/quarterOfYear.min.js')}}"></script>



<!-- js-->
<script src="{{asset('assets/js/column.js')}}"></script>
@endsection
