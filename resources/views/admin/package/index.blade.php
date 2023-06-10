@extends('admin.master')

@section('styles')
    <!-- Start datatable css -->
@endsection

@section('content')
    <div class="main-content-inner">
        <div class="row">
            <!-- data table start -->
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title float-left">Devices List</h4>
                        <p class="float-right mb-2">
                            <button id="add_device_btn" class="btn btn-primary btn-sm">Add Device</button>
                        </p>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            <table class="table table-bordered yajra-datatable">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Device Number</th>
                                    <th>Organization</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{--adding device modal--}}
            <div class="modal-basic modal fade show" id="add_device_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg " role="document">
                    <div class="modal-content modal-bg-white ">
                        <div class="modal-header">
                            <h6 class="modal-title">Add New Devices</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span data-feather="x"></span></button>
                        </div>
                        <form id="device_form">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input placeholder="enter a name" type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email">Device Number</label>
                                            <input placeholder="enter device number" type="text" class="form-control" id="number" name="number" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email">Device Description</label>
                                            <input placeholder="enter device description" type="text" class="form-control" id="description" name="description">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="organization">Organization</label>
                                    <div class="jstree organization_tree"></div>
                                    <input type="hidden" class="selected_organization" name="organization_id">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="save_Organization_Btn" class="btn btn-primary btn-sm">Save changes</button>
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{--editing device modal--}}
            <div class="modal-basic modal fade show" id="edit_device_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg " role="document">
                    <div class="modal-content modal-bg-white ">
                        <div class="modal-header">
                            <h6 class="modal-title">Edit New Devices</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span data-feather="x"></span></button>
                        </div>
                        <form id="edit_device_form" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="device_id" id="device_id">
                                <input type="hidden" class="selected_organization" name="organization_id">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input placeholder="enter a name" type="text" class="form-control" id="device_name" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email">Device Number</label>
                                            <input placeholder="enter device number" type="text" class="form-control" id="device_number" name="number" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email">Device Description</label>
                                            <input placeholder="enter device description" type="text" class="form-control" id="device_description" name="description" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="organization_tree">Organization</label>
                                    <div class="jstree organization_tree"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{--confirm modal--}}
            <div class="modal-info-delete modal fade show" id="delete_device_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-info" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="modal-info-body d-flex">
                                <div class="modal-info-icon warning">
                                    <span data-feather="info"></span>
                                </div>
                                <div class="modal-info-text">
                                    <h6>Do you Want to delete that device?</h6>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-outlined btn-sm" data-dismiss="modal">No</button>
                            <button type="button" id="confirm_delete" class="btn btn-success btn-outlined btn-sm" data-dismiss="modal">Yes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    @include('admin.device.partials.script')

@endsection
