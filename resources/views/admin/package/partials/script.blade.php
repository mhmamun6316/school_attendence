<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
</script>
<script>
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    let table;

    $(document).ready(function() {
        table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('admin.packages.list') }}",
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
                {data: 'name', name: 'name', width: '20%'},
                {data: 'price', name: 'price',width: '20%'},
                {data: 'categories', name: 'categories',width: '30%'},
                {data: 'action', name: 'action', width: '20%'},
            ]
        });

        $('#add_package_btn').on('click', function() {
            $('.multi-select').val([]);
            $('#add_package_modal').modal('show');
        });

        $('#package_form').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();
            formData += '&_token=' + $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('admin.packages.store') }}",
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    table.ajax.reload();
                    $('#add_package_modal').modal('hide');
                    toastr.success(response.success);
                },
                error: function(xhr) {
                    let errorResponse = JSON.parse(xhr.responseText);
                    let error = errorResponse.error;
                    toastr.error(error);
                }
            });
        });
    });

    $(document).on('click', '#edit_btn', function(e) {
        e.preventDefault();

        let packageId = $(this).data('package-id');

        $.ajax({
            url: "{{ route('admin.packages.edit',':id') }}".replace(':id',packageId),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let selectedCategories = response.package.categories.map(function(category) {
                    return category.id.toString();
                });
                let packaged = response.package;
                $('.js-example-basic-multiple').val(selectedCategories).trigger('change');
                $('#edit_package_modal #package_id').val(packaged.id);
                $('#edit_package_modal #package_name').val(packaged.name);
                $('#edit_package_modal #package_price').val(packaged.price);

                $('#edit_package_modal').modal('show');
            },
            error: function(xhr) {
                toastr.error('Error loading package data. Please try again.');
            }
        });
    });

    $(document).on("submit",'#edit_package_form',function (e){
        e.preventDefault();
        let packageId = $('#package_id').val();
        let formData = $(this).serialize();

        formData += '&_token=' + $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "{{ route('admin.packages.update',':id') }}".replace(':id',packageId),
            type: 'PUT',
            data: formData,
            dataType: 'json',
            success: function(response) {
                table.ajax.reload();
                $('#edit_package_modal').modal('hide');
                toastr.success(response.success);
            },
            error: function(xhr) {
                let errorResponse = JSON.parse(xhr.responseText);
                let error = errorResponse.error;
                if(error){
                    toastr.error(error);
                } else {
                    toastr.error('Error updating package. Please try again.');
                }
            }
        });
    });

    $(document).on("click",'#delete_btn',function (e){
        let packageId = $(this).data('package-id');
        $('#confirm_delete').data('package-id', packageId);
        $('#delete_package_modal').modal('show');
    })

    $('#confirm_delete').on('click', function() {
        let packageId = $(this).data('package-id');

        $.ajax({
            url: "{{ route('admin.packages.destroy', ':id') }}".replace(':id', packageId),
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
