<style type="text/css">
.loader { background-color: #CF4342; color: white; top: 30%; left: 50%; margin-left: -50px; position: fixed; padding: 3px; width:100px;	height:100px; background:url('<?php echo $this->config->base_url(); ?>assets/img/wheel.gif') no-repeat center; }
.blackbg { z-index: 5000; background-color: #666; -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=50)"; filter: alpha(opacity=20); opacity: 0.2; width:100%; height:100%; top:0; left:0; position:absolute;}
</style>
<link href="<?php echo $this->config->base_url(); ?>assets/css/bootstrap-fileupload.css" rel="stylesheet">
<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('form').form();
		$('#category').change(function() {
			var v = $(this).val();
			$('#loading').show();
					$.ajax({
					  type: "get",
					  async: false,
					  url: "index.php?module=products&view=getSubCategories",
					  data: { <?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash() ?>", category_id: v },
					  dataType: "html",
					  success: function(data) {
						if(data != "") {
							$('#subcat_data').empty();
							$('#subcat_data').html(data);
						} else {
							$('#subcat_data').empty();
							var default_data = '<select name="subcategory" class="span4" id="subcategory" data-placeholder="<?php echo $this->lang->line("select_category_to_load"); ?>"></select>';
							$('#subcat_data').html(default_data);
							bootbox.alert('<?php echo $this->lang->line('no_subcategory'); ?>');
						}
					  },
					  error: function(){
       					bootbox.alert('<?php echo $this->lang->line('ajax_error'); ?>');
						$('#loading').hide();
    				  }
					  
					});
					$("form select").chosen({no_results_text: "No results matched", disable_search_threshold: 5, allow_single_deselect:true });
					$('#loading').hide();
		});
	});

</script>

<script type="text/javascript">
$(document).ready(function(){
	$('form').form();
		$('#cf1').change(function() {
			var v = $(this).val();
			$('#loading').show();
					$.ajax({
					  type: "get",
					  async: false,
					  url: "index.php?module=products&view=getRacks",
					  data: { <?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash() ?>", shelf_id: v },
					  dataType: "html",
					  success: function(data) {
						  
						if(data != "") {
							$('#rack_data').empty();
							$('#rack_data').html(data);
						} else {
							$('#rack_data').empty();
							var default_data = '<select name="rack" class="span4" id="cf3" data-placeholder="<?php echo $this->lang->line("select_category_to_load"); ?>"></select>';
							$('#rackt_data').html(default_data);
							//bootbox.alert('<?php echo $this->lang->line('no_subcategory'); ?>');
						}
					  },
					  error: function(){
       					bootbox.alert('<?php echo $this->lang->line('ajax_error'); ?>');
						$('#loading').hide();
    				  }
					  
					});
					$("form select").chosen({no_results_text: "No results matched", disable_search_threshold: 5, allow_single_deselect:true });
					$('#loading').hide();
		});
	});

</script>
<?php if($message) { echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>"; } ?>

	<h3 class="title"><?php echo $page_title; ?></h3>
	<p><?php echo $this->lang->line("enter_product_info"); ?></p>
    
	<?php $attrib = array('class' => 'form-horizontal'); echo form_open_multipart("module=products&view=edit&id=".$id, $attrib); ?>

<div class="control-group">
  <label class="control-label" for="code"><?php echo $this->lang->line("product_code"); ?></label>
  <div class="controls"> <?php echo form_input('code', $product->code, 'class="span4 tip" id="code" title="'.$this->lang->line("pr_code_tip").'" required="required" data-error="'.$this->lang->line("product_code").' '.$this->lang->line("is_required").'"'); ?> </div>
</div>
<div class="control-group">
  <label class="control-label" for="name"><?php echo $this->lang->line("product_name"); ?></label>
  <div class="controls"> <?php echo form_input('name', $product->name, 'class="span4 tip" id="name" title="'.$this->lang->line("pr_name_tip").'" required="required" data-error="'.$this->lang->line("product_name").' '.$this->lang->line("is_required").'"');?> </div>
</div>
<div class="control-group">
  <label class="control-label" for="category"><?php echo $this->lang->line("category"); ?></label>
  <div class="controls">  <?php 
	  $cat[''] = "";
	  	foreach($categories as $category) {
    		$cat[$category->id] = $category->name;
		}
		echo form_dropdown('category', $cat, $product->category_id, 'class="tip chzn-select span4" id="category" data-placeholder="'.$this->lang->line("select")." ".$this->lang->line("category").'" title="'.$this->lang->line("pr_category_tip").'" required="required" data-error="'.$this->lang->line("category").' '.$this->lang->line("is_required").'"'); ?> </div>
</div>
<div class="control-group">
  <label class="control-label" for="subcategory"><?php echo $this->lang->line("subcategory"); ?></label>
  <div class="controls" id="subcat_data"> <?php 
	   	$sct[""] = '';
	   	foreach($subcategories as $subcategory) {
    		$sct[$subcategory->id] = $subcategory->name;
		}
			echo form_dropdown('subcategory', $sct, $product->subcategory_id, 'class="span4" id="subcategory"  data-placeholder="'.$this->lang->line("select_category_to_load").'"');  ?> </div>
</div>

<div class="control-group">
  <label class="control-label" for="shelf"><?php echo $this->lang->line("shelf"); ?></label>
  <div class="controls">  <?php 
	  $sh[''] = "";
	  	foreach($shelfs as $shelf) {
    		$sh[$shelf->code] = $shelf->name;
		}
		echo form_dropdown('cf1', $sh, $product->cf1, 'class="tip chzn-select span4" id="cf1" data-placeholder="'.$this->lang->line("select")." ".$this->lang->line("shelf").'" title="'.$this->lang->line("pr_shelf_tip").'" required="required" data-error="'.$this->lang->line("shelf").' '.$this->lang->line("is_required").'"'); ?> </div>
</div>

<div class="control-group">
  <label class="control-label" for="cf3">Rack</label>
  <div class="controls" id="rack_data"> <?php 
	   		$sct[""] = '';
	   		foreach($racks as $racks1) {
    		$sct[$racks1->code] = $racks1->name;
		}
			echo form_dropdown('rack', $sct, $product->cf3, 'class="span4" id="cf3"  data-placeholder="Rack"');  ?> </div>
</div>

<div class="control-group">
  <label class="control-label" for="unit"><?php echo $this->lang->line("product_unit"); ?></label>
  <div class="controls"> <?php echo form_input('unit', $product->unit, 'class="span4 tip" id="unit" title="'.$this->lang->line("pr_unit_tip").'" required="required" data-error="'.$this->lang->line("product_unit").' '.$this->lang->line("is_required").'"'); ?> </div>
</div>
<div class="control-group">
  <label class="control-label" for="size"><?php echo $this->lang->line("size"); ?></label>
  <div class="controls"> <?php echo form_input('size', $product->size, 'class="span4 tip" id="size" title="'.$this->lang->line("pr_size_tip").'"'); ?> </div>
</div>

<div class="control-group">
  <label class="control-label" for="cf2"><?php echo $this->lang->line("pcf2"); ?></label>
  <div class="controls"> <?php echo form_input('cf2', $product->cf2, 'class="span4" id="cf2"');?>
  </div>
</div> 

<div class="control-group">
  <label class="control-label" for="cost"><?php echo $this->lang->line("product_cost"); ?></label>
  <div class="controls"> <?php echo form_input('cost', $product->cost, 'class="span4 tip" id="cost" title="'.$this->lang->line("pr_cost_tip").'" required="required" data-error="'.$this->lang->line("product_cost").' '.$this->lang->line("is_required").'"'); ?> </div>
</div>
<div class="control-group">
  <label class="control-label" for="price"><?php echo $this->lang->line("product_price"); ?></label>
  <div class="controls"> <?php echo form_input('price', $product->price, 'class="span4 tip" id="price" title="'.$this->lang->line("pr_price_tip").'" required="required" data-error="'.$this->lang->line("product_price").' '.$this->lang->line("is_required").'"'); ?> </div>
</div>
<div class="control-group">
  <label class="control-label" for="alert_quantity"><?php echo $this->lang->line("alert_quantity"); ?></label>
  <div class="controls"> <div class=" span4 input-prepend">
          <span class="add-on" style="padding:1px 5px 7px;"><input type="checkbox" name="track_quantity" id="inlineCheckbox1" value="1" <?php echo $product->track_quantity ? 'checked="checked"' : ''; ?>></span>
    <?php echo form_input('alert_quantity', (isset($_POST['alert_quantity']) ? $_POST['alert_quantity'] : $product->alert_quantity), 'class="input-block-level" id="alert_quantity" required="required" data-error="'.$this->lang->line("alert_quantity").' '.$this->lang->line("is_required").'" onClick="this.select();"'); ?>
    
    </div>
</div>
</div>
<div class="control-group">
  <label class="control-label" id="tax_rate"><?php echo $this->lang->line("product_tax"); ?></label>
  <div class="controls">  <?php 
	  $tr[""] = "";
	   		foreach($tax_rates as $tax){
				$tr[$tax->id] = $tax->name;
			}
		echo form_dropdown('tax_rate', $tr, (isset($_POST['tax_rate']) ? $_POST['tax_rate'] : $product->tax_rate), 'id="tax_rate" data-placeholder="'.$this->lang->line("select").' '.$this->lang->line("product_tax").'" required="required" data-error="'.$this->lang->line("product_tax").' '.$this->lang->line("is_required").'"'); ?> </div>
</div>

<div class="control-group">
  <label class="control-label" for="discount">Product Discount</label>
  <div class="controls">  <?php 
	  $dis[''] = "";
	  	foreach($discounts as $discount) {
    		$dis[$discount->promo_id] = $discount->promo_title;
		}
		echo form_dropdown('discount_id', $dis, $product->discount_id, 'class="tip chzn-select span4" id="discount_id" data-placeholder="'.$this->lang->line("select").' Discount"  title="Discount"'); ?> </div>
</div>

<div class="control-group">
  <label class="control-label" for="product_image"><?php echo $this->lang->line("product_image"); ?></label>
  <div class="controls">
<div class="fileupload fileupload-new" data-provides="fileupload">
<span class="btn btn-file"><span class="fileupload-new"><?php echo $this->lang->line("select_image"); ?></span><span class="fileupload-exists"><?php echo $this->lang->line("change"); ?></span><input type="file" name="userfile" id="product_image" /></span>
<span class="fileupload-preview"></span>
<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>
</div>
  </div>
</div>


<div class="control-group">
  <label class="control-label" for="cf4">Custom Barcode</label>
  <div class="controls"> <?php echo form_input('cf4', $product->cf4, 'class="span4" id="cf4"');?>
  </div>
</div>

<div class="control-group">
    <label class="control-label" for="cf5">Select Package</label>
    <div class="controls">  <?php
        $pr[""] = "";
        foreach($packages as $pac){
            $pr[$pac->package_name] = $pac->package_name;
        }
    echo form_dropdown('package_name', $pr, $product->package_name, 'class="tip chzn-select span4" id="package_name" data-placeholder="Select Package"  title="Package"'); ?> </div>

</div>
</div>

<div class="control-group">
  <label class="control-label" for="cf6">Is Paragon Products</label>
  <div class="controls"> 
    Yes &nbsp;<input type="radio" name="cf6" value="1" <?php if($product->cf6==1){ echo "checked"; } ?> />&nbsp; No:&nbsp; <input type="radio" name="cf6" value="0" <?php if($product->cf6==0){ echo "checked"; } ?> />
  	
  </div>
</div> 




<div class="control-group" style="display:none">
  <label class="control-label" for="cf5"><?php echo $this->lang->line("pcf5"); ?></label>
  <div class="controls"> <?php echo form_input('cf5', $product->cf5, 'class="span4" id="cf5"');?>
  </div>
</div> 

<div class="control-group">
<label class="control-label" for="note"><?php echo $this->lang->line("product_details_for_invoice"); ?></label>
  <div class="controls">
  <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : html_entity_decode($product->details)), 'class="input-block-level" id="note" style="margin-top: 10px; height: 100px;"');?> 
 </div>
</div>
        
<div class="control-group">
  <div class="controls"> <?php echo form_submit('submit', $this->lang->line("update_product"), 'class="btn btn-primary"');?> </div>
</div>
<?php echo form_close();?> 
<div id="loading" style="display: none;">
<div class="blackbg"></div><div class="loader"></div>
</div>
