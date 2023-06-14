<script>
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    let table;

    $(document).ready(function() {
        table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('admin.students.list') }}",
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
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'organization', name: 'organization'},
                {data: 'package', name: 'package'},
                {data: 'action', name: 'action'},
            ]
        });

        $('#add_student_btn').on('click', function() {
            $('#add_student_modal').modal('show');
        });

        $('#student_form').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();
            formData += '&_token=' + $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('admin.students.store') }}",
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    table.ajax.reload();
                    $('#add_student_modal').modal('hide');
                    toastr.success(response.success);
                },
                error: function (xhr) {
                    let errorResponse = JSON.parse(xhr.responseText);
                    let error = errorResponse.error;
                    if(error){
                        toastr.error(error);
                    } else {
                        toastr.error('Error storing student. Please try again.');
                    }
                }
            });
        });
    });

    $(document).on('click', '#edit_btn', function(e) {
        e.preventDefault();
        let studentId = $(this).data('student-id');

        $.ajax({
            url: "{{ route('admin.students.edit',':id') }}".replace(':id',studentId),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let student = response.student;
                $('#edit_student_modal #student_id').val(student.id);
                $('#edit_student_modal #student_name').val(student.name);
                $('#edit_student_modal #student_phone').val(student.phone);
                $('#edit_student_modal #student_email').val(student.email);
                $('#edit_student_modal #student_address').val(student.address);
                $('#edit_student_modal #student_guardian_phone').val(student.guardian_phone);
                $('#edit_student_modal #student_guardian_email').val(student.guardian_email);
                if (student.active_package.length > 0){
                    $('#edit_student_modal #student_package_id').val(student.active_package[0].id);
                }

                $('#edit_student_modal').modal('show');
            },
            error: function(xhr) {
                toastr.error('Error loading student data. Please try again.');
            }
        });
    });

    $(document).on("submit",'#edit_student_form',function (e){
        e.preventDefault();
        let studentId = $('#student_id').val();
        let formData = $(this).serialize();

        formData += '&_token=' + $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "{{ route('admin.students.update',':id') }}".replace(':id',studentId),
            type: 'PUT',
            data: formData,
            dataType: 'json',
            success: function(response) {
                table.ajax.reload();
                $('#edit_student_modal').modal('hide');
                toastr.success(response.success);
            },
            error: function(xhr) {
                let errorResponse = JSON.parse(xhr.responseText);
                let error = errorResponse.error;
                if(error){
                    toastr.error(error);
                } else {
                    toastr.error('Error updating admin. Please try again.');
                }
            }
        });
    });

    $(document).on("click",'#delete_btn',function (e){
        let studentId = $(this).data('student-id');
        $('#confirm_delete').data('student-id', studentId);
        $('#delete_student_modal').modal('show');
    })

    $('#confirm_delete').on('click', function() {
        let studentId = $(this).data('student-id');

        $.ajax({
            url: "{{ route('admin.students.destroy', ':id') }}".replace(':id', studentId),
            type: "POST",
            data: {
                _method: "DELETE",
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                toastr.success(response.success);
                table.ajax.reload();
            },
            error: function (xhr) {
                let errorResponse = JSON.parse(xhr.responseText);
                let error = errorResponse.error;
                if(error){
                    toastr.error(error);
                } else {
                    toastr.error('Error deleting admin. Please try again.');
                }
            }
        });
    });

    $(document).on("click",'#log_btn',function (){
        let studentId = $(this).data('student-id');

        $.ajax({
            url: "{{ route('admin.students.log', ':id') }}".replace(':id', studentId),
            method: 'GET',
            success: function(response) {
                $(".packageLog").empty();
                $(".packageLog").append(response);
                $('#student_history_modal').show();
            },
            error: function (xhr) {
                let errorResponse = JSON.parse(xhr.responseText);
                let error = errorResponse.error;
                if(error){
                    toastr.error(error);
                } else {
                    toastr.error('Error getting student history. Please try again.');
                }
            }
        });
    });

    $(document).on("click",'.close_btn',function (){
        $('#student_history_modal').hide();
    });

    $(document).on("click",'#deactive_btn',function (){
        let studentId = $(this).data('student-id');

        $.ajax({
            url: "{{ route('admin.students.deactive', ':id') }}".replace(':id', studentId),
            method: 'GET',
            success: function (response) {
                toastr.success(response.success);
                table.ajax.reload();
            },
            error: function (xhr) {
                let errorResponse = JSON.parse(xhr.responseText);
                let error = errorResponse.error;
                if(error){
                    toastr.error(error);
                } else {
                    toastr.error('Error deactive student. Please try again.');
                }
            }
        });
    });
</script>
