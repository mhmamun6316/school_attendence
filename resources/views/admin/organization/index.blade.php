@extends('admin.master')

@section('styles')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.11/themes/default/style.min.css" />

    <style>
        .jstree-icon.jstree-themeicon.jstree-themeicon-custom {
            background-image: url('//jstree.com/tree-icon.png');
            background-position: center center;
            background-size: auto;
        }
        button{
            margin: 5px;
        }
    </style>

@endsection

@section('content')
    <div class="main-content-inner">
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-end">
                        <button type="button" id="add_organization_btn" class="btn btn-primary btn-sm">Add</button>
                        <button type="button" id="edit_organization_btn" class="btn btn-warning btn-sm">Edit</button>
                        <button type="button" id="delete_organization_btn" class="btn btn-danger btn-sm">Delete</button>
                    </div>
                    <div class="card-body">
                        <div id="organization-tree"></div>
                    </div>
                </div>
            </div>
        </div>

        {{--modal for organization add--}}
        <div class="modal-basic modal fade show" id="add_organization_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm " role="document">
                <div class="modal-content modal-bg-white ">
                    <div class="modal-header">
                        <h6 class="modal-title">Add New Organizations</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span data-feather="x"></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="organizationName">Organization Name:</label>
                            <input type="text" class="form-control" id="organization_name" placeholder="Enter organization name">
                        </div>
                        <div class="form-group">
                            <label for="organizationAddress">Organization Address:</label>
                            <input type="text" class="form-control" id="organization_address" placeholder="Enter organization address">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="save_Organization_Btn" class="btn btn-primary btn-sm">Save changes</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        {{--modal for organization edit--}}
        <div class="modal-basic modal fade show" id="edit_organization_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm " role="document">
                <div class="modal-content modal-bg-white ">
                    <div class="modal-header">
                        <h6 class="modal-title">Add New Organizations</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span data-feather="x"></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="organizationName">Organization Name:</label>
                            <input type="text" class="form-control" id="edit_organization_name" placeholder="Enter organization name">
                        </div>
                        <div class="form-group">
                            <label for="organizationAddress">Organization Address:</label>
                            <input type="text" class="form-control" id="edit_organization_address" placeholder="Enter organization address">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="update_Organization_Btn" class="btn btn-primary btn-sm">Save changes</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        {{--modal for organization delete--}}
        <div class="modal-info-delete modal fade show" id="delete_organization_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-info" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-info-body d-flex">
                            <div class="modal-info-icon warning">
                                <span data-feather="info"></span>
                            </div>
                            <div class="modal-info-text">
                                <h6>Do you Want to delete that organization?</h6>
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

        {{--modal for warning--}}
        <div class="modal-info-warning modal fade show" id="warning_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-info" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-info-body d-flex">
                            <div class="modal-info-icon warning">
                                <span data-feather="info"></span>
                            </div>
                            <div class="modal-info-text">
                                <p>Please select a parent organization ....</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.11/jstree.min.js"></script>

    <script>
        $(document).ready(function (){
            let csrfToken = $('meta[name="csrf-token"]').attr('content');

            loadJSTree();

            function loadJSTree() {
                $.ajax({
                    url: "{{ route('admin.organizations.list') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        let formattedData = formatOrganizationData(response);
                        $('#organization-tree').jstree('destroy');
                        $('#organization-tree').jstree({
                            core: {
                                data: formattedData,
                                multiple: false
                            },
                            types: {
                                'last-child': {
                                    icon: 'jstree-icon jstree-themeicon jstree-themeicon-custom'
                                }
                            },
                            plugins: ['types']
                        });
                    },
                    error: function (xhr) {
                        toastr.error('Error fetching organization. Please try again.')
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
                                opened: true // Open the node by default
                            }
                        };

                        if (formattedChild.children.length === 0) {
                            // Set custom icon for last child nodes
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
                        // Set custom icon for last child nodes
                        formattedOrg.icon = 'jstree-icon jstree-themeicon jstree-themeicon-custom';
                    }

                    formattedData.push(formattedOrg);
                });

                return formattedData;
            }

            // Add Organization button click event
            $('#add_organization_btn').on('click', function() {
                let selectedNode = $('#organization-tree').jstree('get_selected', true)[0];

                if (selectedNode) {
                    let parentId = selectedNode.id;

                    $('#add_organization_modal').modal('show');

                    $('#save_Organization_Btn').on('click', function() {
                        let organizationName = $('#organization_name').val().trim();
                        let organizationAddress = $('#organization_address').val().trim();

                        $.ajax({
                                url: "{{ route('admin.organizations.store') }}",
                                type: 'POST',
                                data: {
                                    _token: csrfToken,
                                    name: organizationName,
                                    address: organizationAddress,
                                    parent_id: parentId
                                },
                                dataType: 'json',
                                success: function(response) {
                                    loadJSTree();
                                    $('#add_organization_modal').modal('hide');
                                    toastr.success(response.success)
                                },
                                error: function(xhr) {
                                    let errorResponse = JSON.parse(xhr.responseText);
                                    let errorMessage = errorResponse.error;

                                    toastr.error(errorMessage);
                                }
                            });
                    });
                } else {
                    $("#warning_modal").modal('show')
                }
            });

            //edit Organization button click event
            $('#edit_organization_btn').on('click', function() {
                let selectedNode = $('#organization-tree').jstree('get_selected', true)[0];

                if (selectedNode) {
                    let Id = selectedNode.id;
                    let parentName = selectedNode.text;
                    let parentAddress = selectedNode.original.address;

                    $("#edit_organization_name").val(parentName);
                    $("#edit_organization_address").val(parentAddress);

                    $('#edit_organization_modal').modal('show');

                    $('#update_Organization_Btn').on('click', function() {
                        let organizationName = $('#edit_organization_name').val().trim();
                        let organizationAddress = $('#edit_organization_address').val().trim();

                        $.ajax({
                            url: "{{ route('admin.organizations.update', ':id') }}".replace(':id', Id),
                            type: 'PUT',
                            data: {
                                _token: csrfToken,
                                id: Id,
                                name: organizationName,
                                address: organizationAddress,
                            },
                            dataType: 'json',
                            success: function(response) {
                                loadJSTree();
                                $('#edit_organization_modal').modal('hide');
                                toastr.success(response.success)
                            },
                            error: function(xhr) {
                                let errorResponse = JSON.parse(xhr.responseText);
                                let errorMessage = errorResponse.error;

                                toastr.error(errorMessage);
                            }
                        });
                    });
                } else {
                    $("#warning_modal").modal('show')
                }
            });

            //delete organization button click event
            $('#delete_organization_btn').on('click',function (){
                let selectedNode = $('#organization-tree').jstree('get_selected', true)[0];

                if (selectedNode) {
                    $('#delete_organization_modal').modal('show');
                    let Id = selectedNode.id;

                    $('#confirm_delete').on('click', function() {
                        $.ajax({
                            url: "{{ route('admin.organizations.destroy',':id') }}".replace(':id',Id),
                            type: 'DELETE',
                            data: {
                                _token: csrfToken,
                            },
                            dataType: 'json',
                            success: function(response) {
                                loadJSTree();
                                $('#delete_organization_btn').modal('hide');
                                toastr.success(response.success)
                            },
                            error: function(xhr) {
                                let errorResponse = JSON.parse(xhr.responseText);
                                let errorMessage = errorResponse.error;

                                toastr.error(errorMessage);
                            }
                        });
                    });
                } else{
                    $("#warning_modal").modal('show')
                }
            })
        });
    </script>

@endsection
