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
                var formattedData = formatOrganizationData(response);

                $('#organization_tree').jstree({
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

                    $('#selected_organization').val(selectedOrganizationId);
                });
            },
            error: function(xhr) {
                toastr.error('Error loading organization data. Please try again.');
            }
        });
    }

    function formatOrganizationData(data) {
        var formattedData = [];

        function formatChildren(children) {
            var formattedChildren = [];

            children.forEach(function(child) {
                var formattedChild = {
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
            var formattedOrg = {
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
