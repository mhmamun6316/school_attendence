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
                    <div class="card-header flex-column">
                        <h2 class="py-4">Attendances List</h2>
                        <div class="filter-container row w-100">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Organizations</label>
                                    <select class="organization form-control" id="organization">
                                        <option value="" selected>Please select a organization</option>
                                        @foreach($organizations as $organization)
                                            <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Device</label>
                                    <select class="device form-control" id="device">
                                        <option value="" selected>Please select a device</option>
                                        @foreach($devices as $device)
                                            <option value="{{ $device->id }}">{{ $device->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Students</label>
                                    <select class="student form-control" id="student">
                                        <option value="" selected>Please select a student</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="form-group">
                                    <a class="btn btn-success btn-sm text-white">Search</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            <table class="table table-bordered yajra-datatable">
                                <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Organization</th>
                                    <th>Device</th>
                                    <th>Date</th>
                                    <th>Arrived Time</th>
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
                    url: "{{ route('admin.attendances.list') }}",
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
                            toastr.error('Error fetching students. Please try again.');
                        }
                    }
                },
                columns: [
                    {data: 'student', name: 'student'},
                    {data: 'organization', name: 'organization'},
                    {data: 'device', name: 'device'},
                    {data: 'date', name: 'date'},
                    {data: 'time', name: 'time'},
                ]
            });
        });
    </script>
@endsection
