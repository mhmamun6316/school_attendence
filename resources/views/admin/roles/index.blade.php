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
                            <a class="btn btn-primary text-white" href="{{ route('admin.roles.create') }}">Create New Role</a>
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
            e.preventDefault();

            if (confirm('Are you sure you want to delete this role?')) {
                let roleId = $(this).data('role-id');

                $.ajax({
                    url: "{{ route('admin.roles.destroy', ':id') }}".replace(':id', roleId),
                    type: "POST",
                    data: {
                        _method: "DELETE",
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        toastr.success('Role deleted successfully!');
                        table.ajax.reload();
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        toastr.error('Error deleting role: ' + errorThrown);
                    }
                });
            }
        })
    </script>
@endsection
