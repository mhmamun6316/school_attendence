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
                        <h4 class="header-title float-left">Roles List</h4>
                        <p class="float-right mb-2">
                            <a class="btn btn-sm btn-primary text-white" href="{{ route('admin.roles.create') }}">Create New Role</a>
                        </p>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            <table class="table table-bordered yajra-datatable">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Permissions</th>
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

            <div class="modal-info-delete modal fade show" id="delete_roles_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-info" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="modal-info-body d-flex">
                                <div class="modal-info-icon warning">
                                    <span data-feather="info"></span>
                                </div>
                                <div class="modal-info-text">
                                    <h6>Do you Want to delete that role?</h6>
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
    <script>
        let table;

        $(document).ready(function() {
            table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('admin.roles.list') }}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    error: function (error) {

                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', width: '10%'},
                    {data: 'name', name: 'name', width: '10%'},
                    {data: 'permission', name: 'permission', width: '60%'},
                    {data: 'action', name: 'action', width: '20%'},
                ]
            });
        });

        $(document).on("click",'#delete_btn',function (e){
            let roleId = $(this).data('role-id');
            $('#confirm_delete').data('role-id', roleId);
            $('#delete_roles_modal').modal('show');
        })

        $('#confirm_delete').on('click', function() {
            let roleId = $(this).data('role-id');

            $.ajax({
                url: "{{ route('admin.roles.destroy', ':id') }}".replace(':id', roleId),
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
