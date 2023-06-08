<script>
    //check all permission
    $("#checkPermissionAll").click(function(){
        if($(this).is(':checked')){
            $('input[type=checkbox]').prop('checked', true);
        }else{
            $('input[type=checkbox]').prop('checked', false);
        }
    });

    //check all permission of a group
    function checkPermissionByGroup(className, checkThis){
        const groupIdName = $("#"+checkThis.id);
        const classCheckBox = $('.'+className+' input');

        if(groupIdName.is(':checked')){
            classCheckBox.prop('checked', true);
        }else{
            classCheckBox.prop('checked', false);
        }

        implementAllChecked();
    }

    function checkSinglePermission(groupClassName, groupID, countTotalPermission) {
        const groupIDCheckBox = $("#"+groupID);

        // if there is any occurance where something is not selected then make selected = false
        if($('.'+groupClassName+ ' input:checked').length === countTotalPermission){
            groupIDCheckBox.prop('checked', true);
        }else{
            groupIDCheckBox.prop('checked', false);
        }
        implementAllChecked();
    }

    function implementAllChecked() {
        const countPermissions = {{ count($allPermissions) }};
        const countPermissionGroups = {{ count($permissionGroups) }};

        if ($('input[type="checkbox"]:checked').length >= (countPermissions + countPermissionGroups)) {
            $("#checkPermissionAll").prop('checked', true);
        } else {
            $("#checkPermissionAll").prop('checked', false);
        }
    }
</script>
