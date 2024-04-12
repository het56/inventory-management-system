var manageBrandTable;

$(document).ready(function() {
    $("#navBrand").addClass('active');

    manageBrandTable = $("#manageBrandTable").DataTable({

        'ajax': 'php_action/fetchBrand.php',
		'order': []	
    });

    
    $("#submitBrandForm").unbind('submit').bind('submit', function() {

        $(".text-danger").remove();
		
		$('.form-group').removeClass('has-error').removeClass('has-success');

        var brandName = $("#brandName").val();
        var brandStatus = $("#brandStatus").val();

        if (brandName === "") {
            $("#brandName").after('<p class="text-danger">Brand Name field is required</p>');
            $("#brandName").closest('.form-group').addClass('has-error');
        } else {
            $("#brandName").find('.text-danger').remove();
            $("#brandName").closest('.form-group').addClass('has-success');
        }

        if (brandStatus === "") {
            $("#brandStatus").after('<p class="text-danger">Brand Status field is required</p>');
            $("#brandStatus").closest('.form-group').addClass('has-error');
        } else {
            $("#brandStatus").find('.text-danger').remove();
            $("#brandStatus").closest('.form-group').addClass('has-success');
        }

        if (brandName && brandStatus) {
            var form = $(this);

            $("#createBrandBtn").button('loading');

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    $("#createBrandBtn").button('reset');
                    if (response.success === true) {
                        manageBrandTable.ajax.reload(null, false);
                        $("#submitBrandForm")[0].reset();
                        $(".text-danger").remove();
                        $(".form-group").removeClass('has-error').removeClass('has-success');
                        $("#add-brand-messages").html('<div class="alert alert-success">'+
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                        '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
                      '</div>');

                      
  	  			$(".alert-success").delay(500).show(10, function() {
                    $(this).delay(3000).hide(10, function() {
                        $(this).remove();
                    });
                }); 
                    }
                }
            });
        }

        return false;

    });

});

function removeBrands(brandId =null){

    if(brandId){
        $("#removeBrandBtn").unbind('click').bind('click', function() {
        $.ajax({
        url: 'php_action/removeBrand.php',
        type: 'post',
        data: {brandId : brandId},
        dataType: 'json', 
        success:function(response) {
            
            if (response.success == true) {

                $("#removeBrandModal").modal('hide');
                manageBrandTable.ajax.reload(null, false);
                


                $(".remove-messages").html('<div class="alert alert-success">'+
                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
              '</div>');

              
                $(".alert-success").delay(500).show(10, function() {
                $(this).delay(3000).hide(10, function() {
                $(this).remove();
            });
        }); 
            }
        }

    }); 
});
} 
}