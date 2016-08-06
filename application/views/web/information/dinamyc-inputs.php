<?php 
$MODE=(empty($MODE)?"view":$MODE);

$form["MODE"]=form_hidden("MODE",$MODE);
$form["id"]=form_hidden("id",encode_id($id));


if($MODE=="do_it"):

$form["name"]        =form_input("name",$name," id='name'  placeholder='nombre'" );
$form["main_module"] =form_dropdown('main_module',$sys["forms_fields"]["main_modules"],(!empty($main_module)?$main_module:""),"id='main_module' style='float:left'");
$form["description"]  =form_textarea(array('name'=>'description', 'id'=>'description', 'rows'=>'3', 'value'=>$description,"placeholder"=>"Descripcion" ) );

$txt_boton="Guardar";

else:

$form["name"]        =$name;
$form["main_module"] =(!empty($main_module)?$main_module:"");
$form["description"]=!empty($description)?$description:"";  

$txt_boton="Editar";

endif;
 ?>
    <div class="row">
        <div class="col-lg-12">
		
       	<div class="panel panel-default">
            <?php echo $this->load->view("recycled/menu/panel_heading","",true); ?>
	        <div class="panel-body">
	            <div class="row">

<?php $attributes_form = array('class' => 'formBasic'); ?>
<?php  echo form_open("form",$attributes_form);?>

							<div class="form-group" style='display:none' id="hidden">
	                            <?php echo $form["MODE"]."/"; ?>
	                            <?php echo $form["id"]; ?>
	                        </div>
	                        <div class="form-group">
	                            <?php echo form_label("Nombre:"); ?>
	                            <?php echo $form["name"]; ?>
	                        </div>

<!-- TABS -->
					<ul class="nav nav-tabs">
					  <li class="active"><a data-toggle="tab" href="#tab1">Html</a></li>
	                        <?php if($id): ?>

					  		<li><a data-toggle="tab" href="#tab2">Categorias</a></li>
					  		<li><a data-toggle="tab" href="#tab3">Marcas</a></li>
					  		<li><a data-toggle="tab" href="#tab4">Custom</a></li>
					  		<li><a data-toggle="tab" href="#tab5">Productos</a></li>
					  		<li><a data-toggle="tab" href="#tab6">Mixed</a></li>

	                        <?php endif; ?>
					</ul>
					<div class="tab-content">
		                    <?php $main_module="<div class='form-group' style='float:left'>".form_label("Menu link:").$form["main_module"]." <span style='float:left' class='add_record'></span></div>";?>
						  	
						  	<div id="tab1" class="tab-pane fade in active">
								<?php //echo $main_module; ?>
	                        <div class="form-group">
	                            <?php echo form_label("Descripción:"); ?>
	                            <?php echo $form["description"]; ?>
	                        </div>


	                        </div>
						  	
						  	<div id="tab2" class="tab-pane fade">
						  		SSSSSSS
	                        </div>
						  	<div id="tab3" class="tab-pane fade">

	                        </div>
						  	<div id="tab4" class="tab-pane fade">

	                        </div>
						  	<div id="tab5" class="tab-pane fade">

	                        </div>
						  	<div id="tab6" class="tab-pane fade">

	                        </div>
	                </div>
	                
<?php  echo form_close();?>
						  							                        
	                        <div class="form-group">
	                        	<div class="btn btn-primary" id="submit"><?php echo $txt_boton; ?></div>
	                        	<?php if(!empty($id)): ?>
                        			<?php if($MODE=="do_it"): ?>
	                        		<div class="btn btn-warning" id="cancel"><?php echo "Cancelar"; ?></div>
	                        		<?php endif; ?>

	                        	<div class="btn btn-danger" id="delete"><?php echo "Eliminar"; ?></div>
	                        	<?php endif; ?>
	                        </div>


                </div>
	            <!-- /.row (nested) -->
	        </div>
	        <!-- /.panel-body -->
	    </div>
		<!-- /.panel-default -->
	    </div>
        <!-- /.col-lg-12 -->
       	</div>
		<!-- /.row -->

    </div>

 <script type="text/javascript">
   $(function() {

     var $editors = $('#description');
     // var $editors = $('textarea');
     if ($editors.length) {

       $editors.each(function() {
         var editorID = $(this).attr("id");
         var instance = CKEDITOR.instances[editorID];
         if (instance) { CKEDITOR.remove(instance); }
         CKEDITOR.replace(editorID);
       });
     }

   });
</script>