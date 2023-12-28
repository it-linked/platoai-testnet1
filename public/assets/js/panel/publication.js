function publicationSave( publication_id ) {
	"use strict";

    console.log("Saving publication...");

    document.getElementById( "item_edit_button" ).disabled = true;
	document.getElementById( "item_edit_button" ).innerHTML = magicai_localize.please_wait;

	var formData = new FormData();
	if ( publication_id != 'undefined' ) {
		formData.append( 'publication_id', publication_id );
	} else {
		formData.append( 'publication_id', null );
	}

    

	formData.append( 'title', $( "#title" ).val() );
	formData.append( 'external_link', $( "#external_link" ).val() );


	$.ajax( {
		type: "post",
		url: "/dashboard/admin/publications/save",
		data: formData,
		contentType: false,
		processData: false,
		success: function ( data ) {
			toastr.success( 'publication Saved Succesfully. Redirecting...' );
			setTimeout( function () {
				location.href = '/dashboard/admin/publications/create'
			}, 1000 );
            console.info("publication Saved Succesfully");
		},
		error: function ( data ) {
			var err = data.responseJSON.errors;
			$.each( err, function ( index, value ) {
				toastr.error( value );
                console.error(value);
			} );
			document.getElementById( "item_edit_button" ).disabled = false;
			document.getElementById( "item_edit_button" ).innerHTML = "Save";
		}
	} );
	return false;
}



