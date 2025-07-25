@extends('layout.master')
@section('title', 'Google Map')
@section('css')

@endsection
@section('main-content')
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">Google Maps</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="#" class="f-s-14 f-w-500">
                      <span>
                        <i class="ph-duotone  ph-map-pin-line f-s-16"></i> Map
                      </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">Google Maps</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb end -->

        <div class="row">
            <!-- Terrain Type Map start  -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Terrain Type Map</h5>
                    </div>

                    <div class="card-body">
                        <div class="w-100 h-400" id="mapTypes"></div>
                    </div>
                </div>
            </div>
            <!-- Terrain Type Map start  -->

            <!-- satellite Type Map start  -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>satellite Type Map</h5>
                    </div>

                    <div class="card-body">
                        <div class="w-100 h-400" id="mapTypes1"></div>
                    </div>
                </div>
            </div>
            <!-- satellite Type Map end  -->



            <!-- Polygons map start  -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Polygons</h5>
                    </div>

                    <div class="card-body">
                        <div class="w-100 h-400" id="map-types"></div>
                    </div>
                </div>
            </div>
            <!-- Polygons map end  -->





            <!-- Google Map start  -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Google Map</h5>
                    </div>

                    <div class="card-body">
                        <div class="w-100 h-400" id="g-map"></div>
                    </div>
                </div>
            </div>
            <!-- Google Map end  -->
            <!-- Markers Map start  -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Markers Map</h5>
                    </div>

                    <div class="card-body">
                        <div class="w-100 h-400" id="mapMarkers"></div>
                    </div>
                </div>
            </div>
            <!-- Markers Map end  -->



            <!-- Overlays Map start  -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Overlays Map</h5>
                    </div>
                    <div class="card-body">
                        <div class="w-100 h-400" id="mapOverlays"></div>
                    </div>
                </div>
            </div>
            <!-- Overlays Map end  -->



        </div>
    </div>
@endsection

@section('script')


<!-- google maps api -->
<script src="https://maps.google.com/maps/api/js"></script>

<!--  gmaps plugins -->
<script src="{{asset('assets/vendor/googlemap/gmaps.js')}}"></script>

<!-- google-map js -->
<script src="{{asset('assets/js/google-map.js')}}"></script>

@endsection

