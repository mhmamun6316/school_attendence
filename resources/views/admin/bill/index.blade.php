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
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            <table class="table table-bordered yajra-datatable">
                                <thead>
                                <tr>
                                    <th>Organization Name</th>
                                    <th>Due Amount</th>
                                    <th>Students</th>
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
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        let table;

        $(document).ready(function() {
            table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                horizontal: true,
                ajax: {
                    url: "{{ route('admin.bills.list') }}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    error: function (xhr) {
                        let errorResponse = JSON.parse(xhr.responseText);
                        let error = errorResponse.error;
                        if(error){
                            toastr.error(error);
                        } else {
                            toastr.error('Error fetching organizations. Please try again.');
                        }
                    }
                },
                columns: [
                    {data: 'organization', name: 'organization'},
                    {data: 'dueAmount', name: 'dueAmount'},
                    {data: 'students', name: 'students'},
                ]
            });
        });
    </script>
@endsection
