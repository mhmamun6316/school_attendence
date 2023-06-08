<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">

<!-- inject:css-->
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('backend') }}/css/plugin.min.css">
<link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend') }}/img/favicon.png">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<style>
    .sidebar .menu-text {
        font-size: 16px;
    }
    .dataTables_wrapper .sorting,
    .dataTables_wrapper .sorting_asc,
    .dataTables_wrapper .sorting_desc{
        background-image: none !important;
        padding-right: 0 !important;
    }

    .dataTables_wrapper thead .sorting:before,
    .dataTables_wrapper thead .sorting_asc:before,
    .dataTables_wrapper thead .sorting_desc:before{
        content: none !important;
    }

    .dataTables_wrapper .sorting:after,
    .dataTables_wrapper .sorting_asc:after,
    .dataTables_wrapper .sorting_desc:after {
        display: none !important;
    }

    .dataTables_wrapper .actions{
        display: flex;
    }

    .dataTables_wrapper .actions a,
    .dataTables_wrapper .actions a:hover{
        color: white;
        margin: 5px;
    }
</style>
