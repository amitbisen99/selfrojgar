//Delete
$("body").on("click",".remove-crud",function(e){
    e.preventDefault();
    var current_object = $(this);
    Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary file!",
        type: "error",
        showCancelButton: true,
        cancelButtonClass: 'btn-default btn-md waves-effect',
        confirmButtonClass: 'btn-danger btn-md waves-effect waves-light',
        confirmButtonText: 'Delete!'
    }).then((result) => {  
        if (result.value) {
            var action = current_object.attr('data-action');
            var token = $("meta[name='csrf-token']").attr('content');
            var id = current_object.attr('data-id');
            $('body').html("<form class='form-inline remove-form' method='POST' action='"+action+"'></form>");
            $('body').find('.remove-form').append('<input name="_method" type="hidden" value="DELETE">');
            $('body').find('.remove-form').append('<input name="_token" type="hidden" value="'+ token +'">');
            $('body').find('.remove-form').append('<input name="id" type="hidden" value="'+ id +'">');
            $('body').find('.remove-form').submit();
        }
       
    });
});