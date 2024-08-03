<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Dashboard</title>
    <!-- Favicon icon -->

    <link rel="stylesheet" type="text/css" href="{{asset('vendor/sweetalert2/dist/sweetalert2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/datatable/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/datatable/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('vendor/bootstrap/bootstrap.min.css')}}">
    <link href="{{asset('css/reset.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    @stack('style')
</head>

<body>
    <div class="main-wrapper flex">
        @include('layouts.sidebar')
        <div class="main-content">
            @include('layouts.header')
            @yield('content')
        </div>
        <!-- <div class="dashboard-open-button">
            <i class="fa-solid fa-bars"></i>
        </div> -->
    </div>

    <!-- Required vendors -->
    <script src="{{asset('vendor/jquery/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('vendor/jquery/popper.min.js')}}">
    </script>
    <script src="{{asset('/vendor/bootstrap/bootstrap.min.js')}}">
    </script>
    <script src="{{asset('vendor/sweetalert2/dist/sweetalert2.min.js')}}"></script>
    <script src="https://cdn.tiny.cloud/1/e180jc2niw1am56cy9796zpbwjdn9ux6ewhj5bwnbx5qz7f9/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

    <script type="text/javascript" src="{{asset('vendor/datatable/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatable/dataTables.bootstrap4.min.js')}}"></script>
    @include('scripts.alertmessage')

    <script type="text/javascript">
        $(document).ready(function() {
            $('.user-info').on('click', function() {
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active')
                    $('.top-dropdown-menu').slideUp()

                } else {
                    $(this).addClass('active')
                    $('.top-dropdown-menu').slideDown()
                }
            })

            // $('.dashboard-close-button button').on('click', function() {
            //     $('.sidebar-wrapper').addClass('close-dash')
            //     $('.dashboard-open-button').addClass('close-dash')
            //     $('.main-wrapper').addClass('close-dash')
            //     $('.sidebar-wrapper').removeClass('open-dash')
            //     $('.dashboard-open-button').removeClass('open-dash')
            //     $('.main-wrapper').removeClass('open-dash')

            // })
            // $('.dashboard-open-button button').on('click', function() {
            //     $('.sidebar-wrapper').removeClass('close-dash')
            //     $('.dashboard-open-button').removeClass('close-dash')
            //     $('.main-wrapper').removeClass('close-dash')

            //     $('.sidebar-wrapper').addClass('open-dash')
            //     $('.dashboard-open-button').addClass('open-dash')
            //     $('.main-wrapper').addClass('open-dash')

            // })

            $('.change-password-btn').on('click', function() {
                $('.change-password-modal').show()
            })
            $('.close-change').on('click', function() {
                $('.change-password-modal').hide()
            })
            $('#change-password').on('submit', function(e) {
                const newPass = $('#newPass').val()
                const confirmPass = $('#confirmPass').val()
                $('.change-error').remove()
                if (newPass === confirmPass) {

                } else {
                    $('#confirmPass').parent().append('<p class="text-danger change-error">Confirm Password did not match with new password</p>')
                    e.preventDefault()
                }
            })

        })

        function afterPrint() {
            location.reload();
        }

        if (window.matchMedia) {
            var mediaQueryList = window.matchMedia('print');
            mediaQueryList.addListener(function(mql) {
                if (!mql.matches) {
                    afterPrint();
                }
            });
        }

        window.onafterprint = afterPrint;
    </script>
    @stack('scripts')

</body>

</html>s