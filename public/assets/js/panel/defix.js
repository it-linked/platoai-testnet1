function defixSave(defix_id = null) {

    console.log("Saving DefiX entry...");

    document.getElementById("item_edit_button").disabled = true;
    document.getElementById("item_edit_button").innerHTML = magicai_localize.please_wait;

    var formData = new FormData();
    if (defix_id !== null) {
        formData.append('id', defix_id);
    }

    formData.append('title', $("#title").val());
    formData.append('external_link', $("#external_link").val());
    formData.append('parent_id', $("#parent_id").val());

    $.ajax({
        type: "post",
        url: "/dashboard/admin/defix-gateway/save", // Adjust the URL based on your routes
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            toastr.success('DefiX Entry Saved Successfully. Redirecting...');
            setTimeout(function() {
                if (!isNaN($("#parent_id").val())) {
                    location.href = '/dashboard/admin/defix-gateway/index/' + $('#parent_id').val();
                } else
                    location.href = '/dashboard/admin/defix-gateway/index';
            }, 1000);
            console.info("DefiX Entry Saved Successfully");
        },
        error: function(data) {
            var err = data.responseJSON.errors;
            $.each(err, function(index, value) {
                toastr.error(value);
                console.error(value);
            });
            document.getElementById("item_edit_button").disabled = false;
            document.getElementById("item_edit_button").innerHTML = "Save";
        }
    });
    return false;
}