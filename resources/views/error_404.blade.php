<!DOCTYPE html>
<html lang="en">
@section('title', 'Not Found')
@include('layout.head')

@include('layout.css')

<div class="error-container p-0">
    <div class="container">
        <div>
            <div>
                <img src="{{asset('../assets/images/background/error-404.png')}}" class="img-fluid" alt="">
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 ">
                        <p class="text-center text-secondary f-w-500">Website owners should regularly check for and fix broken links using tools like Google Search Console or other link-checking software.</p>
                    </div>
                </div>
            </div>
            <a role="button" href="{{route('index')}}" class="btn btn-lg btn-primary"><i class="ti ti-home"></i> Back To Home</a>
        </div>
    </div>
</div>

    <!--jquery-->
    <script src="{{asset('assets/js/jquery-3.6.3.min.js')}}"></script>

    <!-- Bootstrap js-->
    <script src="{{asset('assets/vendor/bootstrap/bootstrap.bundle.min.js')}}"></script>

</html>
