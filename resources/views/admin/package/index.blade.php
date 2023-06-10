@extends('admin.master')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container {
            z-index: 9999 !important;
        }
    </style>
@endsection

@section('content')
    <div class="main-content-inner">
        <div class="row">
            <!-- data table start -->
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title float-left">Packages List</h4>
                        <p class="float-right mb-2">
                            <button id="add_package_btn" class="btn btn-primary btn-sm">Add Package</button>
                        </p>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            <table class="table table-bordered yajra-datatable">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Categories</th>
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

            {{--adding package modal--}}
            <div class="modal-basic modal fade show" id="add_package_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content modal-bg-white ">
                        <div class="modal-header">
                            <h6 class="modal-title">Add New Packages</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span data-feather="x"></span></button>
                        </div>
                        <form id="package_form">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input placeholder="enter a name" type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="price">Package Price</label>
                                    <input placeholder="enter package price" type="text" class="form-control" id="price" name="price" required>
                                </div>
                                <div class="form-group">
                                    <label for="categories">Categories</label>
                                    <select class="js-example-basic-multiple" name="categories[]" multiple="multiple">
                                        <option value="" disabled>Please select a category</option>
                                        @foreach($categories as $category)
                                            <option  value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
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

            {{--editing package modal--}}
            <div class="modal-basic modal fade show" id="edit_package_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content modal-bg-white ">
                        <div class="modal-header">
                            <h6 class="modal-title">Edit New Packages</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span data-feather="x"></span></button>
                        </div>
                        <form id="edit_package_form" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="package_id" id="package_id">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input placeholder="enter a name" type="text" class="form-control" id="package_name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="price">Package Price</label>
                                    <input placeholder="enter package price" type="text" class="form-control" id="package_price" name="price" required>
                                </div>
                                <div class="form-group">
                                    <label for="categories">Categories</label>
                                    <select class="js-example-basic-multiple" name="categories[]" multiple="multiple">
                                        <option value="" disabled>Please select a category</option>
                                        @foreach($categories as $category)
                                            <option  value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
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
            <div class="modal-info-delete modal fade show" id="delete_package_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-info" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="modal-info-body d-flex">
                                <div class="modal-info-icon warning">
                                    <span data-feather="info"></span>
                                </div>
                                <div class="modal-info-text">
                                    <h6>Do you Want to delete that package?</h6>
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

    @include('admin.package.partials.script')

@endsection
