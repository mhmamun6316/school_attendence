<script>
    $(document).ready(function() {

        loadOrganizationTree();

        $('#add_user_btn').on('click', function() {
            $('#add_user_modal').modal('show');
        });

        $('#user_form').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();
            formData += '&_token=' + $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('admin.users.store') }}",
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    table.ajax.reload();
                    $('#add_user_modal').modal('hide');
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
