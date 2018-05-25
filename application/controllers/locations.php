<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class Locations extends Base_Controller {	
	
	function __construct()
	{		
		parent::__construct();
	/*	if(uri(3)!='getResrvationCronFromExpdiaTest')
		{
			$ip = $this->input->ip_address();
			if( !in_array($ip, explode(',', str_replace(' ', '', IPWHITELIST))) )
			{
				mail("datahernandez@gmail.com","Illegal Access From Hoteratus",$ip);
				?>
				<img width="1350" height="600" src="data:image/png;base64,<?php echo base64_encode(file_get_contents('user_assets/images/under.jpg'));?>" class="img-responsive" data="<?php echo insep_encode($ip); ?>">
				<?php
				die;
			}
		}*/
		
	}
	function get_state()
	{
		extract($this->input->post());
		$states = get_data(TBL_STATE,array('country_id'=>$country_id))->result_array();
		?>
        <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">

 State  <span class="errors">*</span></p>
    <div class="col-sm-7">
  
     <select name="state" id="add_state" class="form-control" >
       <?php foreach($states as $s_val) { extract($s_val);?>
      <option value="<?php echo $id;?>"><?php echo $state_name;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
        <?php
	}
	function edit_state()
	{
		extract($this->input->post());
		$states = get_data(TBL_STATE,array('country_id'=>$country_id))->result_array();
		?>

       <?php foreach($states as $s_val) { extract($s_val);?>
      <option value="<?php echo $id;?>"><?php echo $state_name;?></option>
      <?php } ?>
     
        <?php
	}
	
	
	
	function get_city()
	{
		extract($this->input->post());
		$ctys = get_data(TBL_CITY,array('state_id'=>$state_id))->result_array();
		?>
        <div class="form-group">
    <p class="col-sm-4 control-label" for="inputPassword3">

 City  <span class="errors">*</span></p>
    <div class="col-sm-7">
  
     <select name="city" id="add_city" class="form-control" >
     <?php foreach($ctys as $c_val) { extract($c_val);?>
      <option value="<?php echo $id;?>"><?php echo $city_name;?></option>
      <?php } ?>
      </select>
    </div>
  </div>
        <?php
		
	}
	
	function edit_city()
	{
		extract($this->input->post());
		$ctys = get_data(TBL_CITY,array('state_id'=>$state_id))->result_array();
		?>

     <?php foreach($ctys as $c_val) { extract($c_val);?>
      <option value="<?php echo $id;?>"><?php echo $city_name;?></option>
      <?php } ?>

        <?php
		
	}
	
	
	
}