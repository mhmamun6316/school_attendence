@extends('admin.master')

@section('styles')
    <style>
        .gradient-color-name{
            min-width: 300px;
        }
        .gradient2{
            background: linear-gradient(to right, #F6A09A, #8A1F1D);
        }
        .gradient5{
            background: linear-gradient(to right, #58126A, #D13ABD);
        }
        .gradient6{
            background: linear-gradient(to right, #50D5B7, #067D68);
        }
    </style>
@endsection

@section('content')
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Attendence Management Dashboard</h4>
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <div class="action-btn">

                            <div class="form-group mb-0">
                                <div class="input-container icon-left position-relative">
                                    <span class="input-icon icon-left">
                                        <span data-feather="calendar"></span>
                                    </span>
                                    <input type="text" class="form-control form-control-default date-ranger" name="date-ranger" placeholder="{{ $currentDate }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @permission('dashboard.view')
                <div class="card  card-md mb-30 shadow1">
                    <div class="card-body p-30">
                        <div class="d-flex flex-wrap m-n10 ">
                            <div class="gradient-color-name gradient1 py-35 px-20 color-white rounded-xl m-10 ">
                                <span class="fs-18">Roles</span>
                                <span class="fs-18">{{ $roles }}</span>
                            </div>
                            <div class="gradient-color-name gradient2 py-35 px-20 color-white rounded-xl m-10 ">
                                <span class="fs-18">Permissions</span>
                                <span class="fs-18">{{ $permissions }}</span>
                            </div>
                            <div class="gradient-color-name gradient3 py-35 px-20 color-white rounded-xl m-10 ">
                                <span class="fs-18">Admins</span>
                                <span class="fs-18">{{ $admins }}</span>
                            </div>
                            <div class="gradient-color-name gradient4 py-35 px-20 color-white rounded-xl m-10 ">
                                <span class="fs-18">Organizations</span>
                                <span class="fs-18">{{ $organizations }}</span>
                            </div>
                            <div class="gradient-color-name gradient5 py-35 px-20 color-white rounded-xl m-10 ">
                                <span class="fs-18">Devices</span>
                                <span class="fs-18">{{ $devices }}</span>
                            </div>
                            <div class="gradient-color-name gradient6 py-35 px-20 color-white rounded-xl m-10 ">
                                <span class="fs-18">Packages</span>
                                <span class="fs-18">{{ $packages }}</span>
                            </div>
                        </div>
                        <!-- ends: .atbd-button-list" -->
                    </div>
                </div>
                @else
                    <h2>You don't have permission to view the statistics data</h2>
                @endpermission
            </div>
        </div>
    </div>
@endsection
