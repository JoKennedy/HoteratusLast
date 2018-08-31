<?php
  if($result){
	  foreach($result as $res){
 ?>  
      
  
	 <!-- <div class="form-group">
		<p class="col-sm-4 control-label" for="inputEmail3">
		User Name <span class="errors">*</span></p>
		<div class="col-sm-7">
		  <select class="form-control" value="selected" name="user_name" id="user_name">
		  
		   <option value="<?php echo $get->user_id; ?>"><?php echo $get->user_name; ?></option>
		  </select>
		</div>
	  </div> -->
	  
	   <div class="form-group">

    <p class="col-sm-4 control-label" for="inputEmail3">
	 <?php $get = $this->channel_model->get_username($res->user_id); ?>
    User Name <span class="errors">*</span></p>

    <div class="col-sm-7">

      <input type="text" placeholder="User Name" id="user_name" name="user_name" class="form-control" value="<?php echo $get->user_name; ?>">

    </div>

  </div>
 	
	<input type="hidden" name="user_id" id="sub_user_id" value="<?php echo insep_encode($res->user_id); ?>">
    <input type="hidden" name="priviledge_id" value="<?php echo insep_encode($res->priviledge_id); ?>">
  
 <div class="form-group">

    <p class="col-sm-4 control-label" for="inputEmail3">

    User Email <span class="errors">*</span></p>

    <div class="col-sm-7">

      <input type="email" placeholder="User Email" id="email_address" name="email_address" class="form-control" value="<?php echo $get->email_address; ?>">

    </div>

  </div>
  
  <div class="form-group">

    <p class="col-sm-4 control-label" for="inputEmail3">

    User Password <span class="errors">*</span></p>
	
    <div class="col-sm-7">

      <input type="text" id="user_password" name="user_password" class="form-control" value="<?php echo $get->spass; ?>">

    </div>

  </div>
  
  <div class="form-group">

    <p class="col-sm-4 control-label" for="inputEmail3">

    Confirm Password <span class="errors">*</span></p>

    <div class="col-sm-7">

      <input type="text" id="confirm_password" name="confirm_password" class="form-control" value="<?php echo $get->spass; ?>">

    </div>

  </div>
<div class="form-group">
	<div class="col-sm-12 nf-select">
  	<div class="col-md-1">
   </div>
	<div class="col-md-10">
    <table class="table table-striped table-bordereds table-hover">
     <thead>
     <tr>
     <th> User Access <span class="errors">*</span></th>
     <th> View </th>
     <th> Edit</th>
     </tr>
     <tbody>
     <tr> <td> All </td> <td> <input type="checkbox" id="select_all"> </td> <td> <input type="checkbox" id="selectall"></td> </tr>
     <?php 	
		$access = get_data(TBL_ACCESS)->result_array();
		foreach($access as $u_acc)
		{
			extract($u_acc);
			foreach(json_decode($res->access) as $photo_id=>$photo_obj)
			{
				if(!empty($photo_obj))
				{
					$photo = (array)$photo_obj;
					if(isset($photo['view'])!='')
					{
						$user_access_view[]=$photo_id;
					}
					else
					{
						$user_access_view[]='';
					}
					if(isset($photo['edit'])!='')
					{
						//echo 'ee'.$photo = $photo_id;
						$user_access_edit[]=$photo_id;
					}
					else
					{
						$user_access_edit[]='';
					}
				}
			}
			
	 ?>
     <tr> 
     <td> <?php echo $acc_name;?> </td> 
    <?php 
	if($acc_id!='5')
	{
	?>
     <td> <input type="checkbox" class="my_checkbox edit_<?php echo $acc_id; ?>"  onclick="return view_check('<?php echo $acc_id; ?>');" <?php if(in_array($acc_id,$user_access_view)){?> checked="checked" <?php } ?> value="<?php echo $acc_id;?>" name="access_options[<?php echo $acc_id;?>][view]" id="view_<?php echo $acc_id; ?>"> </td> 
    <?php } else { ?> <td> </td><?php } ?>
    <?php
	if($acc_id!='8' && $acc_id!='1')
	{
	?>
     <td><input type="checkbox" class="mycheckbox" onclick="return edit_check('<?php echo $acc_id; ?>');" value="<?php echo $acc_id;?>" name="access_options[<?php echo $acc_id;?>][edit]" <?php if(in_array($acc_id,$user_access_edit)){?> checked="checked" <?php } ?> id="edit_<?php echo $acc_id; ?>"> </td>
    <?php
	}
	else
	{
	?>
    <td> </td>
    <?php 
	} 
	?>
     
     </tr>
     <?php 
		}
     ?>
     </tbody>
     </thead>
     </table>
    </div>
	</div>
<!--<p class="col-sm-4 control-label" for="inputPassword3">

    User Access <span class="errors">*</span></p>-->

<!--<div class="col-sm-7 nf-select">

<div class="col-md-6">
<h5>View</h5>
<?php 	
/*echo $get->access;
	$user_access = get_data(TBL_ACCESS,array('status'=>1))->result_array();*/?>
	<input type="checkbox" id="select_all"> All
<?php 
	/*$a=1;
	foreach($user_access as $u_acc)
	{
		extract($u_acc);
		foreach(json_decode($res->access) as $photo_id=>$photo_obj)
		{
			if(!empty($photo_obj))
			{
					$photo = (array)$photo_obj;
					if(isset($photo['view'])!='')
					{
						$user_access_view[]=$photo_id;
					}
					else
					{
						$user_access_view[]='';
					}
			}
		}*/
	?>
	<br>
    <?php //if($acc_id!=5){ ?>
    <input type="checkbox" class="my_checkbox edit_<?php //echo $acc_id; ?>" onclick="return view_check('<?php //echo $acc_id; ?>');" id="view_<?php //echo $acc_id; ?>" value="<?php //echo $acc_id;?>" <?php //if(in_array($acc_id,$user_access_view)){?> checked="checked" <?php //} ?> name="access_options[<?php //echo $acc_id;?>][view]"> <?php //echo $acc_name;?> 
   <?php //} ?>
    <?php //$a++;} ?>
		
		<input type="hidden" name="total_views" value="<?php //echo $a; ?>">
		
     </div>
	 
<div class="col-md-6">
		<h5>Edit</h5>
    <?php 	
		//$access = get_data(TBL_ACCESS,array('status'=>1))->result_array();?>

		<input type="checkbox" id="selectall"> All

		<?php 
        /* $a=1;
		 
		foreach($access as $u_acc)

		{

			extract($u_acc);
			foreach(json_decode($res->access) as $photo_id=>$photo_obj)
			{
				if(!empty($photo_obj))
				{
						$photo = (array)$photo_obj;
						if(isset($photo['edit'])!='')
						{
							$user_access_edit[]=$photo_id;
						}
						else
						{
							$user_access_edit[]='';
						}
				}
			}*/

	?>
	<br>
<?php //if($acc_id!=1){ ?>  
    <input type="checkbox" class="mycheckbox" onclick="return edit_check('<?php //echo $acc_id; ?>');" value="<?php //echo $acc_id;?>" <?php //if(in_array($acc_id,$user_access_edit)){?> checked="checked" <?php } ?> name="access_options[<?php //echo $acc_id;?>][edit]" id="edit_<?php //echo $acc_id; ?>"> <?php //echo $acc_name;?> 
   <?php //} ?>	
    <?php //$a++;} ?>
	
		<input type="hidden" name="total_success" value="<?php //echo $a; ?>">
        <input type="hidden" name="save" value="save" />
     </div>
</div>-->
</div>
  
	   
	  	<input type="hidden" value="save" name="save" />
	   <div class="form-group">
		<div class="col-sm-offset-2 col-sm-8">
		  <button class="btn btn-success hover-shadow pull-right" type="submit" name="save" value="edit">Update User</button> 
		</div>
	   </div>
    
  <?php } //} ?>