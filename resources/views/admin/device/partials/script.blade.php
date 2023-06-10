<script>
    $(document).ready(function() {

        loadOrganizationTree();

        $('#add_device_btn').on('click', function() {
            $('#add_device_modal').modal('show');
        });

        $('#device_form').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();
            formData += '&_token=' + $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('admin.devices.store') }}",
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    table.ajax.reload();
                    $('#add_device_modal').modal('hide');
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

    function loadOrganizationTree() {
        $.ajax({
            url: "{{ route('admin.organizations.list') }}",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let formattedData = formatOrganizationData(response);
                $('.organization_tree').jstree({
                    core: {
                        data: formattedData
                    },
                    plugins: ['types'],
                    types: {
                        'last-child': {
                            icon: 'jstree-icon jstree-themeicon jstree-themeicon-custom'
                        }
                    }
                }).on('select_node.jstree', function(e, data) {
                    let selectedOrganizationId = data.node.id;

                    $('.selected_organization').val(selectedOrganizationId);
                });
            },
            error: function(xhr) {
                toastr.error('Error loading organization data. Please try again.');
            }
        });
    }

    function formatOrganizationData(data) {
        let formattedData = [];

        function formatChildren(children) {
            let formattedChildren = [];

            children.forEach(function(child) {
                let formattedChild = {
                    id: child.id,
                    text: child.name,
                    address: child.address,
                    children: formatChildren(child.children_recursive),
                    state: {
                        opened: true
                    }
                };

                if (formattedChild.children.length === 0) {
                    formattedChild.icon = 'jstree-icon jstree-themeicon jstree-themeicon-custom';
                }

                formattedChildren.push(formattedChild);
            });

            return formattedChildren;
        }

        data.forEach(function(org) {
            let formattedOrg = {
                id: org.id,
                text: org.name,
                address: org.address,
                children: formatChildren(org.children_recursive),
                state: {
                    opened: true
                }
            };

            if (formattedOrg.children.length === 0) {
                formattedOrg.icon = 'jstree-icon jstree-themeicon jstree-themeicon-custom';
            }

            formattedData.push(formattedOrg);
        });

        return formattedData;
    }
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
                url: "{{ route('admin.devices.list') }}",
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
                {data: 'device_number', name: 'device_number'},
                {data: 'organization', name: 'organization'},
                {data: 'description', name: 'description'},
                {data: 'action', name: 'action'},
            ]
        });
    });

    $(document).on('click', '#edit_btn', function(e) {
        e.preventDefault();

        let deviceId = $(this).data('device-id');

        $.ajax({
            url: "{{ route('admin.devices.edit',':id') }}".replace(':id',deviceId),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let device = response.device;
                $('#edit_device_modal #device_id').val(device.id);
                $('#edit_device_modal #device_name').val(device.name);
                $('#edit_device_modal #device_number').val(device.device_number);
                $('#edit_device_modal #device_description').val(device.description);

                let organizationTree = $('#edit_device_modal .organization_tree');
                organizationTree.jstree('deselect_all');
                organizationTree.jstree('select_node', device.organization_id);

                // Open the modal
                $('#edit_device_modal').modal('show');
            },
            error: function(xhr) {
                toastr.error('Error loading device data. Please try again.');
            }
        });
    });

    $(document).on("submit",'#edit_device_form',function (e){
        e.preventDefault();
        let deviceId = $('#device_id').val();
        let formData = $(this).serialize();

        formData += '&_token=' + $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "{{ route('admin.devices.update',':id') }}".replace(':id',deviceId),
            type: 'PUT',
            data: formData,
            dataType: 'json',
            success: function(response) {
                table.ajax.reload();
                $('#edit_device_modal').modal('hide');
                toastr.success(response.success);
            },
            error: function(xhr) {
                let errorResponse = JSON.parse(xhr.responseText);
                let error = errorResponse.error;
                if(error){
                    toastr.error(error);
                } else {
                    toastr.error('Error updating device. Please try again.');
                }
            }
        });
    });

    $(document).on("click",'#delete_btn',function (e){
        let deviceId = $(this).data('device-id');
        $('#confirm_delete').data('device-id', deviceId);
        $('#delete_device_modal').modal('show');
    })

    $('#confirm_delete').on('click', function() {
        let deviceId = $(this).data('device-id');

        $.ajax({
            url: "{{ route('admin.devices.destroy', ':id') }}".replace(':id', deviceId),
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
