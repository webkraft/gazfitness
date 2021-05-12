<div class="wrap lcp-content my-personal-profile">

<h2><?php echo $page_title; ?></h2>

<?php if( @$message ) : ?>
<p class="text-left alert alert-success" role="alert"><?php echo $message; ?></p>
<?php endif; ?>

<form class="income" action="" method="post" enctype="multipart/form-data">

<div class="row">
	<div class="col-sm-12">
		<div class="form-group pull-right">
			<span class="text-left alert alert-warning" role="alert">Name each attachment file with a unique file name.</span>
			<input type="submit" class="btn btn-success" name="op" value="Save">
		</div>
	</div>
</div>

<!--
<div class="panel panel-primary">
<div class="panel-heading">
<h3 class="panel-title">Personal Information</h3>
</div>
<div class="panel-body">		
-->

<div class="well well-lg">
<div class="row">
	<div class="col-sm-1">
	<div class="form-group">
		<label for="">Salutation<span class="form-required">*</span></label><br>
		<select name="salutation" id="">
		  <option value="Mr" <?php if(@$company->salutation == "Mr"){echo 'selected="selected"';} ?>>Mr</option>
		  <option value="Mrs" <?php if(@$company->salutation == "Mrs"){echo 'selected="selected"';} ?>>Mrs</option>
		  <option value="Miss" <?php if(@$company->salutation == "Miss"){echo 'selected="selected"';} ?>>Miss</option>
		  <option value="Miss" <?php if(@$company->salutation == "Ms"){echo 'selected="selected"';} ?>>Ms</option>
		</select>
	</div>
	</div>
	
	<div class="col-sm-4">
	<div class="form-group">
		<label for="">First Name<span class="form-required">*</span></label>
		<input type="text" required class="form-control" name="first_name" id="" placeholder="" value="<?php echo @$company->first_name; ?>">
	</div>
	</div>
	
	<div class="col-sm-4">
	<div class="form-group">
		<label for="">Surname<span class="form-required"><span class="form-required">*</span></span></label>
		<input type="text" required class="form-control" name="last_name" id="" value="<?php echo @$company->last_name; ?>" placeholder="">
	</div>
	</div>
	
	<div class="col-sm-3">
	<div class="form-group">
		<label for="">Other Name </label>
		<input type="text" class="form-control" name="other_name" id="" value="<?php echo @$company->other_name; ?>" placeholder="">
	</div>
	</div>					
</div>

<div class="row">
<div class="col-sm-6">
<div class="form-group">
	<label>Has your name changed since you last completed your tax return? <span class="form-required">*</span></label> 
	<label class="radio-inline">
		<input type="radio" class="has-div-to-show prev_name" name="name_changed" id="" value="yes" <?php if( @$company->name_changed == "yes" ) { echo "checked"; } ?>> Yes								
	</label>
	<label class="radio-inline">							
		<input type="radio" class="has-div-to-show prev_name" name="name_changed" id="" value="no" <?php if( @$company->name_changed == "no" ||  @$company->name_changed == "") { echo "checked"; } ?>> No
	</label>
	
	<br>
	<div class="hidden to-show">
		<p><input type="text" class="form-control" name="previous_name" id="" value="<?php echo @$company->previous_name ?>" placeholder="Previous Name"></p>
	</div>
</div>
</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label for="">Telephone No<span class="form-required">*</span></label>
			<input type="text" required class="form-control" name="day_phone" id="" value="<?php echo @$company->day_phone; ?>" placeholder="">
		</div>
	</div>

	<div class="col-sm-6">
		<div class="form-group">
			<label for="">Email Address<span class="form-required">*</span></label>
			<input type="email" required class="form-control" name="email_address" id="" value="<?php echo @$company->email_address; ?>" placeholder="">
		</div>
	</div>					
</div>				

<div class="row">
<div class="col-sm-3">
<div class="form-group">
	<label for="">Address<span class="form-required">*</span></label>
	<input type="text" required class="form-control" name="address1" id="" value="<?php echo @$company->address1; ?>" placeholder="">
</div>
</div>
<div class="col-sm-3">
<div class="form-group">
	<label for="">Suburb<span class="form-required">*</span></label>
	<input type="text" required class="form-control" name="address2" id="" value="<?php echo @$company->address2; ?>" placeholder="">
</div>
</div>

<div class="col-sm-2">
<div class="form-group">
	<label for="">City<span class="form-required">*</span></label>
	<input type="text" required class="form-control" name="city" id="" value="<?php echo @$company->city; ?>" placeholder="">
</div>
</div>
<div class="col-sm-2">
<div class="form-group">
	<label for="">Post Code<span class="form-required">*</span></label>
	<input type="text" required class="form-control" name="post_code" id="" value="<?php echo @$company->post_code; ?>" placeholder="">
</div>
</div>
<div class="col-sm-2">
<div class="form-group">
	<label for="stateoption">State<span class="form-required">*</span></label>	
	<select name="state" id="stateoption">
	  <option value="New South Wales" <?php if(@$company->state == "New South Wales"){echo 'selected="selected"';} ?>>New South Wales</option>
	  <option value="Queensland" <?php if(@$company->state == "Queensland"){echo 'selected="selected"';} ?>>Queensland</option>
	  <option value="Tasmania" <?php if(@$company->state == "Tasmania"){echo 'selected="selected"';} ?>>Tasmania</option>
	  <option value="Western Australia" <?php if(@$company->state == "Western Australia"){echo 'selected="selected"';} ?>>Western Australia</option>
	  <option value="South Australia" <?php if(@$company->state == "South Australia"){echo 'selected="selected"';} ?>>South Australia</option>
	  <option value="Victoria" <?php if(@$company->state == "Victoria"){echo 'selected="selected"';} ?>>Victoria</option>
	</select>
</div>
</div>									
</div>
<div class="row">
<div class="col-sm-12">
<div class="form-group">
	<label>Is your postal address the same as your residential address? <span class="form-required">*</span></label> 
	<label class="radio-inline">
		<input type="radio" class="has-div-to-show postaladdress" name="postal_address_q" id="" value="yes" <?php if( @$company->postal_address_q == "yes" ) { echo "checked"; } ?>> No
	</label>
	<label class="radio-inline">							
		<input type="radio" class="has-div-to-show postaladdress" name="postal_address_q" id="" value="no" <?php if( @$company->postal_address_q == "no" || @$company->postal_address_q == "" ) { echo "checked"; } ?>> Yes
	</label>	
	
	<div class="hidden to-show">								
		<div class="form-group">
			<label for="">Postal Address<span class="form-required">*</span></label>
			<input type="text" class="form-control" name="postal_address" id="" value="<?php echo @$company->postal_address; ?>" placeholder="Postal Address">
		</div>		
	</div>
</div>
</div>
</div>
</div>
			


<div class="well well-lg">
<div class="row">
	<div class="col-sm-6">
	<div class="form-group">
		<label>Date of Birth<span class="form-required">*</span></label>
		<div class='input-group date' id='date_of_birth'>
		<input type="text" required name="date_of_birth" class="form-control datepicker" value="<?php echo @$company->date_of_birth; ?>" />
		<span class="input-group-addon">
		    <span class="glyphicon glyphicon-calendar"></span>
		</span>
		</div>
		</div>
	</div>	
	
	<div class="col-sm-6">
		<div class="form-group">
		<label>Tax File Number<span class="form-required">*</span></label>
		<input type="text" required class="form-control" name="tax_file_no" id="" value="<?php echo @$company->tax_file_no; ?>" placeholder="">
		</div>
	</div>		
</div>
</div>

<div class="well well-lg">
<div class="row">
<div class="col-sm-12">
<div class="form-group">
	<label>Were you an Australian tax resident for the whole tax year? <span class="form-required">*</span></label> 
	<label class="radio-inline">
		<input type="radio" name="whole_yr_tax_resident" class="has-div-to-show tax_res_year" id="" value="no" <?php if( @$company->whole_yr_tax_resident == "no" ) { echo "checked"; } ?>> Yes
	</label>
	<label class="radio-inline">							
		<input type="radio" name="whole_yr_tax_resident" class="has-div-to-show tax_res_year" id="" value="yes" <?php if( @$company->whole_yr_tax_resident == "yes" || @$company->whole_yr_tax_resident == "" ) { echo "checked"; } ?>> No
	</label>
	
	
	<div class="hidden to-show">
	<div class="row">
		
		<div class="col-sm-3">
		<div class="form-group">
		<label>Did you</label>
			<select name="tax_resident_part_year">
			  <option value="became" <?php if(@$company->tax_resident_part_year == "became"){echo 'selected="selected"';} ?>>Become a resident?</option>
			  <option value="ceased" <?php if(@$company->tax_resident_part_year == "ceased"){echo 'selected="selected"';} ?>>Ceased to be a resident?</option>
			</select>
		</div>
						
			<div class="form-group">
				<div class="form-inline"><label>Date became or ceased to be a tax resident<span class="form-required">*</span></label></div>
			   	<input type="text" name="tax_resident_part_year_date" class="form-control datepicker" id="" value="<?php echo @$company->tax_resident_part_year_date; ?>"/>
			 </div>
		</div>
			
	</div>
	</div>
	

</div>
</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label for="">What is your main occupation? <span class="form-required">*</span></label>
			<input type="text" required class="form-control" name="main_occupation" id="" value="<?php echo @$company->main_occupation; ?>" placeholder="">
		</div>
	</div>													
</div>				

<div class="row">
<div class="col-sm-6">
<div class="form-group">
	<label>Do you have an ABN number? <span class="form-required">*</span></label> 
	<label class="radio-inline">
		<input type="radio" class="has-div-to-show abn_num" name="have_abn_no" id="" value="yes" <?php if( @$company->have_abn_no == "yes" ) { echo "checked"; } ?>> Yes								
	</label>
	<label class="radio-inline">							
		<input type="radio" class="has-div-to-show abn_num" name="have_abn_no" id="" value="no" <?php if( @$company->have_abn_no == "no" || @$company->have_abn_no == "" ) { echo "checked"; } ?>> No
	</label>
	<br>
	<div class="hidden to-show">		
		<label for="">ABN Number</label>
		<input type="text" class="form-control" name="abn_no" id="" value="<?php echo @$company->abn_no ?>" placeholder="ABN Number">
		<br>
		<label for="">Nature of the Business Conducted</label>
		<input type="text" class="form-control" name="nature_of_business" id="" value="<?php echo @$company->nature_of_business ?>" placeholder="Nature of the Business Conducted">		
		<div class="form-group">
			<label for="">Company Name<span class="form-required">*</span></label>
			<input type="text" class="form-control" name="company_name" id="" value="<?php echo @$company->company_name; ?>" placeholder="">
		</div>		
	</div>
</div>
</div>
</div>
</div>

<div class="well well-lg">
<div class="row">
<div class="col-sm-12">
<div class="form-group">
	<label>Are you married or in a de facto relationship<span class="form-required">*</span></label> 
	<label class="radio-inline">
		<input type="radio" class="has-div-to-show are_you_married" name="married_defacto" id="" value="yes" <?php if( @$company->married_defacto == "yes" ) { echo "checked"; } ?>> Yes								
	</label>
	<label class="radio-inline">							
		<input type="radio" class="has-div-to-show re_you_married" name="married_defacto" id="" value="no" <?php if( @$company->married_defacto == "no" || @$company->married_defacto == "" ) { echo "checked"; } ?>> No
	</label>
	<br><br>
	<div class="hidden to-show">
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label for="">Spouse's Surname<span class="form-required">*</span></label>
					<input type="text" class="form-control" name="spouse_last_name" value="<?php echo @$company->spouse_last_name; ?>" placeholder="">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="">Spouse's First Name<span class="form-required">*</span></label>
					<input type="text" class="form-control" name="spouse_first_name" value="<?php echo @$company->spouse_first_name; ?>" placeholder="">
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="">Spouse's Other Name</label>
					<input type="text" class="form-control" name="spouse_other_name" value="<?php echo @$company->spouse_other_name; ?>" placeholder="">
				</div>
			</div>					
		</div>	
		
		<div class="row">
			<div class="col-sm-12">
				<label>Spouse's Date of Birth<span class="form-required">*</span></label>
			</div>
			
			<div class="col-sm-4">						
				<div class="form-group">
				   	
				   <div class='input-group date' id='spouse_date_birth'>
				   <input type="text" required name="spouse_date_birth" class="form-control datepicker" value="<?php echo @$company->spouse_date_birth; ?>" />
				   <span class="input-group-addon">
				       <span class="glyphicon glyphicon-calendar"></span>
				   </span>
				   </div>
				   </div>
				   
				   
			</div>			
		</div>								
		
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label for="">Spouse’s Tax File No<span class="form-required">*</span></label>
					<input type="text" class="form-control" name="spouse_tax_file_no" id="" value="<?php echo @$company->spouse_tax_file_no; ?>" placeholder="">
				</div>
			</div>													
		</div>
		
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label for="">Spouse's Taxable Income<span class="form-required">*</span></label>
					<input type="text" class="form-control" name="spouse_tax_income" id="" value="<?php echo @$company->spouse_tax_income; ?>" placeholder="">
				</div>
			</div>													
		</div>
		
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label>Spouse for whole year? <span class="form-required">*</span></label> 
					<label class="radio-inline">
						<input type="radio" name="spouse_whole_year" class="has-div-to-show spouce_from_date" id="" value="yes" <?php if( @$company->spouse_whole_year == "yes" ) { echo "checked"; } ?>> No								
					</label>
					<label class="radio-inline">							
						<input type="radio" name="spouse_whole_year" class="has-div-to-show spouce_from_date" id="" value="no" <?php if( @$company->spouse_whole_year == "no" || $company->spouse_whole_year == "") { echo "checked"; } ?>> Yes
					</label>
					<br><br>
					<div class="hidden to-show">
						<div class="col-sm-4">	
						<label for="">Date from/to<span class="form-required">*</span></label>					
						<div class="form-group">
						   	<input type="text" name="spouse_from_to" class="form-control datepicker" value="<?php echo @$company->spouse_from_to; ?>" />
						</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label>Do you have dependent children? <span class="form-required">*</span></label> 
					<label class="radio-inline">
						<input type="radio" name="have_children" class="has-div-to-show have_kids" id="" value="yes" <?php if( @$company->have_children == "yes" ) { echo "checked"; } ?>> Yes								
					</label>
					<label class="radio-inline">							
						<input type="radio" name="have_children" class="has-div-to-show have_kids" id="" value="no" <?php if( @$company->have_children == "no" || $company->have_children == "") { echo "checked"; } ?>> No
					</label>												
				</div>
			</div>
		</div>		
	</div>
</div>
</div>
</div>
</div>


<div class="well well-lg">
	<div class="form-group">
    <label>Did you and all your dependents have private medical health?</label> 
    <label class="radio-inline">
        <input type="radio" class="has-div-to-show" name="private_hlth_insurnce_q" value="yes" <?php if (@$company->private_hlth_insurnce_q == "yes") {echo "checked";} ?>> Yes</label>
    <label class="radio-inline">							
        <input type="radio" class="has-div-to-show" name="private_hlth_insurnce_q" value="no" <?php if (@$company->private_hlth_insurnce_q == "no" || @$company->private_hlth_insurnce_q == "") {echo "checked";} ?>> No</label>
    <div class="hidden to-show">
        <div class="row">
            <div class="col-sm-6">
                <label class="control-label">Attach Evidence (.pdf, .jpg)</label>							
                <input multiple id="private_hlth_insurnce_docs" name="private_hlth_insurnce_docs[]" type="file" accept=".pdf,.jpeg,.jpg" class="file">                 
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">					
                
                <?php $arr_file_names = explode(",", @$company->private_hlth_insurnce_docs); ?>	
                	<br>
                	<p>Current upload(s): 
                	<?php foreach( $arr_file_names as $name ) : ?>
                		<a target="_blank" href="<?php echo plugins_url() .'/ssas/client-docs/'.@$company->user_login.'/personalprofile/'.@$name; ?>"> <?php echo @$name; ?></a>	
                	<?php if(end( $arr_file_names) !== $name ){ echo " , "; }; ?>	
                	<?php endforeach; ?>	
                	</p>      
            </div>				
        </div>
	</div>	
</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="form-group pull-right">
			<span class="text-left alert alert-warning" role="alert">Name each attachment file with a unique file name.</span>
			<input type="submit" class="btn btn-success" name="option" value="Save">
		</div>
	</div>
</div>

</div>
</form>
<div>

<!--
</div>
</div>
-->