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
                        <h4 class="header-title float-left">Admins List</h4>
                        <p class="float-right mb-2">
                            @permission('admin.create')
                                <button id="add_user_btn" class="btn btn-primary btn-sm">Add Admin</button>
                            @endpermission
                        </p>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            <table class="table table-bordered yajra-datatable">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Organization</th>
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

            {{--adding user modal--}}
            <div class="modal-basic modal fade show" id="add_user_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg " role="document">
                    <div class="modal-content modal-bg-white ">
                        <div class="modal-header">
                            <h6 class="modal-title">Add New Users</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span data-feather="x"></span></button>
                        </div>
                        <form id="user_form">
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
                                            <label for="email">Email</label>
                                            <input placeholder="enter email address" type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select class="form-control" id="role" name="role_id" required>
                                                <option selected value="">Please select a role</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
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

            {{--editing user modal--}}
            <div class="modal-basic modal fade show" id="edit_user_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg " role="document">
                    <div class="modal-content modal-bg-white ">
                        <div class="modal-header">
                            <h6 class="modal-title">Edit New Users</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span data-feather="x"></span></button>
                        </div>
                        <form id="edit_user_form" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="user_id" id="user_id">
                                <input type="hidden" class="selected_organization" name="organization_id">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input placeholder="enter a name" type="text" class="form-control" id="user_name" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input placeholder="enter email address" type="email" class="form-control" id="user_email" name="email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select class="form-control" id="user_role" name="role_id" required>
                                                <option selected value="">Please select a role</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
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
            <div class="modal-info-delete modal fade show" id="delete_user_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-info" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="modal-info-body d-flex">
                                <div class="modal-info-icon warning">
                                    <span data-feather="info"></span>
                                </div>
                                <div class="modal-info-text">
                                    <h6>Do you Want to delete that user?</h6>
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

    @include('user.partials.script')
    <script>
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        let table;

        $(document).ready(function() {
            table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('admin.users.list') }}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    error: function (error) {

                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'role', name: 'role'},
                    {data: 'organization', name: 'organization'},
                    {data: 'action', name: 'action'},
                ]
            });
        });

        $(document).on('click', '#edit_btn', function(e) {
            e.preventDefault();

            let userId = $(this).data('user-id');

            $.ajax({
                url: "{{ route('admin.users.edit',':id') }}".replace(':id',userId),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let user = response.user;
                    $('#edit_user_modal #user_id').val(user.id);
                    $('#edit_user_modal #user_name').val(user.name);
                    $('#edit_user_modal #user_email').val(user.email);
                    $('#edit_user_modal #user_role').val(user.role_id);

                    let organizationTree = $('#edit_user_modal .organization_tree');
                    organizationTree.jstree('deselect_all');
                    organizationTree.jstree('select_node', user.organization_id);

                    // Open the modal
                    $('#edit_user_modal').modal('show');
                },
                error: function(xhr) {
                    toastr.error('Error loading user data. Please try again.');
                }
            });
        });

        $(document).on("submit",'#edit_user_form',function (e){
            e.preventDefault();
            let userId = $('#user_id').val();
            let formData = $(this).serialize();

            formData += '&_token=' + $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('admin.users.update',':id') }}".replace(':id',userId),
                type: 'PUT',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    table.ajax.reload();
                    $('#edit_user_modal').modal('hide');
                    toastr.success(response.success);
                },
                error: function(xhr) {
                    let errorResponse = JSON.parse(xhr.responseText);
                    let error = errorResponse.error;
                    if(error){
                        toastr.error(error);
                    } else {
                        toastr.error('Error updating user. Please try again.');
                    }
                }
            });
        });

        $(document).on("click",'#delete_btn',function (e){
            let userId = $(this).data('user-id');
            $('#confirm_delete').data('user-id', userId);
            $('#delete_user_modal').modal('show');
        })

        $('#confirm_delete').on('click', function() {
            let userId = $(this).data('user-id');

            $.ajax({
                url: "{{ route('admin.users.destroy', ':id') }}".replace(':id', userId),
                type: "POST",
                data: {
                    _method: "DELETE",
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    toastr.success(response.success);
                    table.ajax.reload();
                },
                error: function(xhr) {
                    let errorResponse = JSON.parse(xhr.responseText);
                    let errorMessage = errorResponse.error;

                    toastr.error(errorMessage);
                }
            });
        });
    </script>
@endsection
