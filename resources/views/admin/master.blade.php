<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'School Attendence System')</title>

    @include('admin.layout.css')

    @yield('styles')
</head>

<body class="layout-light side-menu overlayScroll">

{{--header code --}}
@include('admin.layout.header')

<main class="main-content">

    <aside class="sidebar">
        @include('admin.layout.sidebar')
    </aside>

    <div class="contents">

        <div class="container-fluid">
            @yield('content')
        </div>

    </div>
{{--    @include('admin.layout.footer')--}}
</main>
<div id="overlayer">
    @include('admin.layout.overlay')
</div>

@include('admin.layout.script')
<!-- inject:js-->
@yield('script')
<!-- endinject-->

</body>
<script>
    $(document).ready(function() {
        $('.nav-link').click(function(e) {
            $('.nav-item').removeClass('active');
            $(this).closest('.nav-link').addClass('active');
        });
    });
</script>

</html>
