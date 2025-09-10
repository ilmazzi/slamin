@extends('layout.master')
@section('title', 'Bubble Chart')
@section('css')
    <!-- apexcharts css-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendor/apexcharts/apexcharts.css')}}">
@endsection
@section('main-content')
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Bubble</h4>
                
            </div>
        </div>
        <!-- Breadcrumb end -->
        <div class="row">
            <!-- Simple Bubble Chart start -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5> Simple Bubble Chart</h5>
                    </div>
                    <div class="card-body">
                        <div id="bubble1"></div>
                    </div>
                </div>
            </div>
            <!-- Simple Bubble Chart end -->
            <!-- 3D Bubble Chart start -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>3D Bubble Chart</h5>
                    </div>
                    <div class="card-body">
                        <div id="bubble2"></div>
                    </div>
                </div>
            </div>
            <!-- 3D Bubble Chart end -->
        </div>
    </div>
@endsection

@push('scripts')


    <!-- apexcharts-->
    <script src="{{asset('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>

    <!-- js-->
    <script src="{{asset('assets/js/bubble.js')}}"></script>
@endsection

