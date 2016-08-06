<?php $sessioMode=$this->session->userdata("sessionMode_client");?>
<style type="text/css"> input,select{width: 180px;}</style>
<div class="dinamic_record">
    <?php echo $this->load->view($data['module_data']["link"]."dinamyc-inputs",$data,true); ?>
</div>
<!-- /.container-fluid -->
<script>
form = Object();
form.submit=function(mode,item){
		var	url="<?php echo base_url(); ?>"
		id="<?php echo encode_id($id); ?>",
		formData=""
		;

		if(mode=="do_it")
		formData=$("form.formBasic").serialize();

		if(mode=="cancel")
		formData={MODE:"cancel",id:id};

		if(mode=="add")
		formData={MODE:"add",id:null};

// envia la informacion
		$.ajax({
		    url: url+"<?php echo $data['module_data_method_do_it']; ?>",
		    type: 'POST',
		    dataType: 'json',
		    data: formData,
		    beforeSend: function(response){
// ajax
			$("input").prop("disabled",true);
			$("button").prop("disabled",true);
			$("div#ajax_loading").addClass("ajax_loading");
// ...		    	

		    },
		    success: function(response){

		    	if(response.status==1)
		    	$("div.dinamic_record").html(response.html); 
		    	else{

		    		if(response.redirect){
						$("#dialog > p").text(response.redirect);
						$("#dialog").html("<p></p>");
		    			return;
		    		}
						$("#dialog > p").text("");
						$("#dialog > p").text(response.msg);
						$("#dialog > p").dialog({
							resizable: false,
							modal: true,
								buttons: {
									Aceptar: function() {

										$("#dialog").append("<p></p>");
										$(this).dialog( "close" );
									}
								}
						});
		    	}

// ajax
		    $("input").prop("disabled",false);
		    $("button").prop("disabled",false);
		    $("div#ajax_loading").removeClass("ajax_loading");
// ...

		    }
		 });

	return false;	
};

$(document).on("click","div#submit",function(){
form.submit("do_it",$(this).get(0));
});
$(document).on("click","div#cancel",function(){
form.submit("cancel",$(this).get(0));
});
$(document).on("click","span#add",function(){
form.submit("add",$(this).get(0));
});

$(document).on("click","div.form-group > div#delete",function(){

var 	id=$(this).parent().parent().find("div#hidden > input[name=id]").val(),
		url="<?php echo base_url(); ?>"
		;

$("#dialog > p").text("");
$("#dialog > p").text("Realmente desea eliminar este registro");
$("#dialog > p").dialog({
resizable: false,
modal: true,
    buttons: {
        Si: function() {
        	

			$.ajax({
				    url: url+"admin/client/client_delete",
				    type: 'POST',
				    dataType: 'json',
				    data: {
				    	id:id
				    },
				    beforeSend: function(response){
// ajax
			$("input").prop("disabled",true);
			$("button").prop("disabled",true);
			$("div#ajax_loading").addClass("ajax_loading");
// ...						    	
				    },
				    success: function(response){

				    		$("#dialog > p").text("");
							$("#dialog > p").text(response.msg);
							$("#dialog > p").dialog({
							resizable: false,
							modal: true,
							    buttons: {
							        Correcto: function() {

							    	if(response.status)
							    	{window.location.href="<?php echo base_url().'admin/client/'; ?>";}

							       	$("#dialog").append("<p></p>");
        							$(this).dialog( "close" );
							        }
							     }

							 });      		
// ajax
		    $("input").prop("disabled",false);
		    $("button").prop("disabled",false);
		    $("div#ajax_loading").removeClass("ajax_loading");
// ...
				    }
			 });


        $("#dialog").append("<p></p>");
        $(this).dialog( "close" );

        },
        No: function() {

        $("#dialog").append("<p></p>");
        $(this).dialog( "close" );
        }
    }
});

 });
    
</script>

<!-- + -->
<script>
// $(document).on("click","span.glyphicon-plus",function(){

// 		$.ajax({
// 		    url: '<?php echo base_url().$data["module_data_method_do_it"];?>',
// 		    type: 'POST',
// 		    dataType: 'json',
// 		    data: {span_do:"span_do"},
// 		    beforeSend: function(response){
// // ajax
// 			// $("input").prop("disabled",true);
// 			// $("button").prop("disabled",true);
// 			// $("div#ajax_loading").addClass("ajax_loading");
// // ...		    	

// 		    },
// 		    success: function(response){
// 		    // window.location.href="<?php echo base_url().$data['module_data_method_do_it'];?>";	
// 		        window.location = '/users/login';
// 		        alert(4);

// // ajax
// 		    $("input").prop("disabled",false);
// 		    $("button").prop("disabled",false);
// 		    $("div#ajax_loading").removeClass("ajax_loading");
// // ...

// 		    }
// 		 });

// 	return false;


// // $.redirect("<?php echo base_url().$data["module_data_method_do_it"];?>",{ span_do: "span_do"}); 
// });
</script>
<!-- F5 -->
<?php if($sessioMode=="do_it" and !empty($id)){ ?>
<script> $(document).ready(function(){$("div#submit").focus().click(); }); </script>
<?php } ?>

<!-- clientSubsidiary  -->
<script>

form.submitTab2=function(mode,item){

var formData="";
		var	url="<?php echo base_url(); ?>",
		 	fk_client=$("input[name='fk_client']").val(),
		 	formInputs=$(item).parent().parent().get(0)
		 	;

		if(mode=="view")
		formData="MODE=view&fk_client="+fk_client;

		if(mode=="do_it")
		formData=$(formInputs).serialize()+"&fk_client="+fk_client;

		// if(mode=="cancel")
		// formData={MODE:"cancel",id:id};

	$.ajax({

	    url: url+"admin/client/clientSubsidiaryView",
	    type: 'POST',
	    dataType: 'json',
	    data:formData ,
	    beforeSend: function(response){
// ajax
			$("input").prop("disabled",true);
			$("button").prop("disabled",true);
			$("div#ajax_loading").addClass("ajax_loading");
// ...	    	
	    },
	    success: function(response){
		    	if(response.status==1){

		    		if(mode=="do_it")
		    		$(formInputs).remove(); 

		    		$("div#tab2 > div#dataTab2").append(response.html); 
					
					if(mode=="view")
					$(formInputs).find("div.form-group > ul.token-input-list :input:first").focus();
		    		// $(formInputs).find("input#city").focus();

				}
		    	else{

		    		if(response.redirect){
						$("#dialog > p").text(response.redirect);
						$("#dialog").html("<p></p>");
		    			return;
		    		}
						$("#dialog > p").text("");
						$("#dialog > p").text(response.msg);
						$("#dialog > p").dialog({
							resizable: false,
							modal: true,
								buttons: {
									Aceptar: function() {

										$("#dialog").append("<p></p>");
										$(this).dialog( "close" );
									}
								}
						});
		    	}	    	

	    	// ajax
		    $("input").prop("disabled",false);
		    $("button").prop("disabled",false);
		    $("div#ajax_loading").removeClass("ajax_loading");
			// ...
	    }

	}) ;

};


$(document).on("click","div#add_clientSubsidiary",function(){
form.submitTab2("view",$(this).get(0));
});

$(document).on("click","div#submitclientSubsidiary",function(){
form.submitTab2("do_it",$(this).get(0));
});
// $(document).on("click","div#cancel",function(){
// form.submitTab2("cancel",$(this).get(0));
// });
// $(document).on("click","span#add",function(){
// form.submitTab2("add",$(this).get(0));
// });
</script>

<script>
$(document).on("click","div#delete_clientSubsidiary",function(){

var 	id=$(this).data("client_subsidiary")
		url="<?php echo base_url(); ?>",
		item=$(this).get(0)
		;

$(item).parent().removeClass("form-group");
$(item).parent().addClass("deleteBackground");
$("#dialog > p").text("");
$("#dialog > p").text("Realmente desea eliminar este registro");
$("#dialog > p").dialog({
resizable: false,
modal: true,
    buttons: {
        Si: function() {

			$.ajax({

				    url: url+"admin/client/clientSubsidiary_delete",
				    type: 'POST',
				    dataType: 'json',
				    data: {
				    	id:id
				    },
				    beforeSend: function(response){
				    },
				    success: function(response){
				    
				    	if(response.status){
						$(item).parent().parent().remove();
				    		$("#dialog > p").text("");
							$("#dialog > p").text("se elimino con exito");
							$("#dialog > p").dialog({
							resizable: false,
							modal: true,
							    buttons: {
							        Correcto: function() {


							       	$("#dialog").append("<p></p>");
        							$(this).dialog( "close" );
									$(item).parent().parent().remove();

							        }
							     }

							 });      		
				    	}

				    }
			 });


        $("#dialog").append("<p></p>");
        $(this).dialog( "close" );

        },
        No: function() {

		$(item).parent().addClass("form-group");

        $("#dialog").append("<p></p>");
        $(this).dialog( "close" );
        }
    }
});

});

</script>
<!-- /clientSubsidiary -->