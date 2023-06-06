<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'School Attendence System')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">

    <!-- inject:css-->

    <link rel="stylesheet" href="{{ asset('backend') }}/css/plugin.min.css">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend') }}/img/favicon.png">

    @yield('css')
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
    @include('admin.layout.footer')
</main>
<div id="overlayer">
    @include('admin.layout.overlay')
</div>

<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDduF2tLXicDEPDMAtC6-NLOekX0A5vlnY"></script>
<!-- inject:js-->
<script src="{{ asset('backend') }}/js/plugins.min.js"></script>
<script src="{{ asset('backend') }}/js/script.min.js"></script>
@yield('script')
<!-- endinject-->
</body>

</html>
