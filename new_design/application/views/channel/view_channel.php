<style type="text/css">
  .askcon{
    font-size: 15px;
    color: blue;
    font-weight: bold;
  }
  .askheader{
    padding: 10px;
    font-size: 17px;
    font-weight: bold;
    color: blue;
  }
</style>
<div class="dash-b4">
<div class="row-fluid clearfix">
<div class="col-md-12 col-sm-12">
<div class="pa-n">
<h4>
<?php 
if($channel_status=='0')
{
?>
<a href="<?php echo lang_url();?>channel/all_channel">Connect Channels</a> 
<?php } else if($channel_status=='1') {  ?>
<a href="<?php echo lang_url();?>channel/connected_channel">Connected Channels</a>
<?php } ?>

<!--<i class="fa fa-angle-right"></i> <a href="#">Online Travel Agencies</a>-->
<i class="fa fa-angle-right"></i><?php echo ucfirst($channel_name);?>
</h4>
</div>
</div>
</div>
</div>

<div class="booking">
<div class="container">
<div class="row">
<div class="col-md-4 col-sm-4">
<div class="bb1"><center><img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/".$image));?>" class="img img-responsive"></center>
<div class="row mar-top20" align="center">
<?php if($channel_status=='1' && ($channel_id==2 && ( $ch->xml_type == 2 || $ch->xml_type == 3 )) || $channel_id!=2)  {  ?>
<a href="<?php echo lang_url();?>mapping/settings/<?php echo insep_encode($channel_id);?>">
<button type="button" class="btn btn-info"><i class="fa fa-link"></i> Mapping</button></a>
<?php } ?>
</div>
</div>

<br>
<div class="bb1">
<!-- <h3 class="text-center pad-bot-10">Quick Contact</h3>

<form>
  <div class="form-group">
   <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Name">
  </div>
    <div class="form-group">
   <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
  </div>
    <div class="form-group">
   <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter phone">
  </div>
    <div class="form-group"><input class="btn btn-success" type="submit" value="Submit">
    </div>
  </form> -->
  
  <section class="sub_content">
      <p class="marT10 marL10"><b>Supported operations </b></p>
      <hr class="marT0 marB10">
      <div class="marL10 marR10">
    <?php if($supported_operations!=''){
		$supported = explode(',',$supported_operations);
		foreach($supported as $support)
		{
		?>
      <p><i class="fa fa-check"></i> <?php echo get_data(OPEATIONS,array('operations_id'=>$support))->row()->operation_name;?> </p>
    <?php }} ?>
      </div>
      <div class="clearfix"></div>
    </section>
    
</div>
<div class="row mar-top20" align="center">
<?php if($channel_status=='1' && ($channel_id==2 && ( $ch->xml_type == 2 || $ch->xml_type == 1 )) || $channel_id!=2 ) {
if($channel_id==2)
{
  ?>
<button type="button" class="btn btn-success" id="getResrvationSummery" channel_id="<?php echo insep_encode($channel_id);?>">Import Past Bookings</button>
<?php
}
?>
<button type="button" class="btn btn-primary" id="getResrvationFromChannel" channel_id="<?php echo insep_encode($channel_id);?>">Import Bookings</button>
<?php } ?>
</div>
</div>

<?php
if(isset($ch->user_connect_id))
{

}
else
{
	if($channel_id=='2')
	{
		$ch = get_data(B_WAY,array('id'=>'3','type'=>'2','channel_id'=>'2'),'username as user_name,password as user_password')->row();
	}
	if($channel_id=='1')
	{
		$ch = get_data(B_WAY,array('id'=>'6','type'=>'0','channel_id'=>'1'),'username as user_name,password as user_password')->row();
	}
}
?>

<div class="room_form  col-md-8">

  <form class="form-horizontal" method="post" name="connect_channel" id="connect_channel" action="<?php echo lang_url();?>mapping/connect_channel">
  
	<input type="hidden" name="channel_id" value="<?php echo insep_encode($channel_id); ?>" id="channel_id">
	<input type="hidden" name="save_clicking" value="save_clicking">
	
  <div class="bb1">
 
  <div class="form-group border-channel">
    <label class="col-sm-5 control-label" for="inputPassword3">Status</label>
    <div class="col-sm-6">
    <label class="radio-inline">
		<input type="radio" value="enabled" <?php if(isset($ch->status)){ if($ch->status=='enabled'){ echo 'checked="checked"';} } ?>  id="inlineRadio2" name="optionenable"> Enabled
	</label>
	<label class="radio-inline">
		<input type="radio" value="disabled" <?php if(isset($ch->status)){ if($ch->status=='disabled'){ echo 'checked="checked"';} } ?> id="inlineRadio3" name="optionenable"> Disabled
	</label>
	<label class="radio-inline">
	</label>
	<label class="radio-inline">
	<span class="errors mar-top7">*</span><i>Required</i>
	</label>
	 <label class="error mar-top7" for="optionenable"></label>
    </div>
  </div>
  <input type="hidden" name="user_connect" value="<?php if(isset($ch->user_connect_id)){echo $ch->user_connect_id; } ?>">
  <!--<div class="form-group border-channel">
    <label class="col-sm-5 control-label" for="inputEmail3">Rate Conversion Multiplier  <i class="fa fa-exclamation-circle"></i></label>
    <div class="col-sm-2 mar-top7">
      <input type="text" name="rate_conversion" value="<?php if(isset($ch->rate_multiplier)){echo $ch->rate_multiplier; }?>" placeholder="" id="inputEmail3" class="form-control">
    </div>
	<div class="col-sm-5">
	<i>Optional Decimal number Cannot be lower than 0.5</i>
	</div>
    <div class="col-sm-5">
      <label for="exampleInputName2"></label>
    </div>
  </div>-->
  <?php if($channel_id != 15 && $channel_id != 2 && $channel_id != 1){ ?>
  <div class="form-group border-channel">
    <label class="col-sm-5 control-label" for="inputEmail3">Username <i class="fa fa-exclamation-circle"></i></label>
    <div class="col-sm-4 mar-top7">
      <input type="text" value="<?php if(isset($ch->user_name)){echo $ch->user_name;} ?>" name="user_name" placeholder="" id="ch_user_name" class="form-control">
	</div>
     	<span class="errors mar-top7">*</span><i>Required</i>
    <div class="col-sm-5">
      <label for="exampleInputName2"></label>
    </div>
  </div>
  
  <div class="form-group border-channel">
    <label class="col-sm-5 control-label" for="inputEmail3">Password  <i class="fa fa-exclamation-circle"></i></label>
		<div class="col-sm-4 mar-top7">
		  <input type="password" value="<?php if(isset($ch->user_password)){echo $ch->user_password;} ?>" name="user_password" placeholder="" id="ch_user_password" class="form-control">
		  </div>
		  <span class="errors mar-top7">*</span><i>Required</i>
		
		<div class="col-sm-5">
		  <label for="exampleInputName2"></label>
		</div>
  </div>
  <?php } else { ?>
  <div class="form-group border-channel">
    <label class="col-sm-5 control-label" for="inputEmail3">Username <i class="fa fa-exclamation-circle"></i></label>
    <div class="col-sm-4 mar-top7">
      <input type="text" value="<?php echo $channel_username; ?>" name="user_name" placeholder="" id="ch_user_name" class="form-control"> <!--readonly-->
  </div>
      <span class="errors mar-top7">*</span><i>Required</i>
    <div class="col-sm-5">
      <label for="exampleInputName2"></label>
    </div>
  </div>
  
  <div class="form-group border-channel">
    <label class="col-sm-5 control-label" for="inputEmail3">Password  <i class="fa fa-exclamation-circle"></i></label>
    <div class="col-sm-4 mar-top7">
      <input type="password" value="<?php echo $channel_password; ?>" name="user_password" placeholder="" id="ch_user_password" class="form-control"> <!--readonly-->
      </div>
      <span class="errors mar-top7">*</span><i>Required</i>
    
    <div class="col-sm-5">
      <label for="exampleInputName2"></label>
    </div>
  </div>
  <?php } ?>
  <?php if(($channel_id == 14 && isset($ch->hotel_channel_id)!='') || $channel_id!='14')
{
  ?>
  <div class="form-group border-channel">
    <label class="col-sm-5 control-label" for="inputEmail3">Hotel ID  <i class="fa fa-exclamation-circle"></i></label>
		<div class="col-sm-4 mar-top7">
		  <input type="text" value="<?php if(isset($ch->hotel_channel_id)){echo $ch->hotel_channel_id;} ?>" name="hotel_channel_id" placeholder="" id="hotel_channel_id" class="form-control">
		  </div>
		  <span class="errors mar-top7">*</span><i>Required</i>
		
		<div class="col-sm-5">
		  <label for="exampleInputName2"></label>
		</div>
  </div>
  <?php } ?>
  
  <div class="form-group border-channel">
    <label class="col-sm-5 control-label" for="inputEmail3">Reservation Email Address  <i class="fa fa-exclamation-circle"></i></label>
		<div class="col-sm-4 mar-top7">
		  <input type="email" value="<?php if(isset($ch->reservation_email)){echo $ch->reservation_email;} ?>" name="email_address" placeholder="" id="inputEmail3" class="form-control">
          <div align="right">
          <a href="javascript:;"> <i class="fa fa-plus"> </i> </a>
          </div>
		</div>
		  <span class="errors mar-top7">*</span><i>Required</i>
		
  </div>
  <?php if($channel_id == 15 /* || $channel_id == 14 */){ ?>
  <div class="form-group border-channel">
    <label class="col-sm-5 control-label" for="inputEmail3"> <?php echo $channel_id == '15' ? 'CMId' : 'codeSupplier';?> <i class="fa fa-exclamation-circle"></i></label>
    <div class="col-sm-4 mar-top7">
      <input type="text" value="<?php echo $ch->cmid; ?>" name="cmid" placeholder="" id="cmid" class="form-control"><!--readonly-->
          <div align="right">
        
          </div>
    </div> 
      <!--<span class="errors mar-top7">*</span><i>Required</i> -->
    
  </div>
  <?php } ?>


  
  <?php if($channel_id == 8){ ?>
  <div class="form-group border-channel">
    <label class="col-sm-5 control-label" for="inputEmail3">WEB ID  <i class="fa fa-exclamation-circle"></i></label>
		<div class="col-sm-4 mar-top7">
		  <input type="text" value="<?php if(isset($ch->web_id)){echo $ch->web_id;} ?>" name="web_id" placeholder="" id="web_id" class="form-control">
          <div align="right">
        
          </div>
		</div>
		  <!--<span class="errors mar-top7">*</span><i>Required</i> -->
		
  </div>
  <?php } ?>
  <?php if($channel_id == 2){
   ?>
  <div class="form-group border-channel">
    <label class="col-sm-5 control-label" for="inputPassword3">XML Connectivity</label>
    <div class="col-sm-6">
      <label class="radio-inline">
        <input type="radio" value="2" id="inlineRadio2" name="xml_select" onchange="getusercredentials(this.value)" <?php if(isset($ch->xml_type)){if($ch->xml_type == 2){ ?>checked<?php }} ?>> 2 ways (Import Rates-Update Availabilities-Rates-Restrictions)
      </label>
    </div>
    <label class="col-sm-3 control-label" for="inputPassword3"></label>
    <div class="col-sm-4">
    <label class="radio-inline">
      <input type="radio" value="1" onchange="getusercredentials(this.value)" id="inlineRadio2" name="xml_select" <?php if(isset($ch->xml_type)){if($ch->xml_type == 1){ ?>checked<?php } }?>> 1 way (Import Reservation Only)
    </label>
    </div>
    <div class="col-sm-4">
    <label class="radio-inline">
      <input type="radio" value="3" onchange="getusercredentials(this.value)" id="inlineRadio3" name="xml_select" <?php if(isset($ch->xml_type)){if($ch->xml_type == 3){ ?>checked<?php } }?>> 1 way (Update Rates And Availability Only)
    </label>

    </div>
  </div>
  <?php } ?>

  







  <div align="left">
  <?php /*if($channel_id == 5){ ?>
    <a href="#">Ask for Hotelbeds Connection > </a>
  <?php } */?>
   </div>

  </div>
  <div align="left">
  <?php if($channel_id == 5 && isset($ch->user_name)){ ?>
    <a href="#askforconnection" data-toggle="modal" class="askcon">Ask for Hotelbeds Connection > </a>
  <?php } ?>
   </div>
    <div align="right" class="mar-top7"> 
		<button type="submit" class="btn btn-info text-center"> <i class="fa fa-check"></i> Save </button> 
    </div>
	
</form>
</div>

</div>
</div>
<div class="booking col-md-12">
<div id="exp_succ">
</div>
</div>
</div>
<div class="modal fade bs-modal-lg" id="askforconnection" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                   <div class="portlet">
                        <div class="">
                            <div class="col-lg-12" style="padding: 15px 0px;">
                                <div class="col-lg-6 caption" align="left" style="font-weight: bold;color: blue;">
                                    Ask for connection 
                                </div>
                                <div class="col-lg-6 caption" align="right">
                                    <a href="#" data-dismiss="modal">Close</a>
                                </div>
                            </div>
                            <div class="portlet-body col-lg-12">
                              <div class="col-lg-12 caption askheader" align="center">
                                Ask for Connection
                              </div>
                              <form class="form-horizontal" method="post" name="ask_for_connection" id="ask_for_connection" action="<?php echo lang_url();?>channel/askforconnection" onsubmit="return validate();">
                                <div class="col-md-12 col-sm-12">
                                  <div class="room_form">
                                      <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputEmail3">Recipients </label>
                                        <div class="col-sm-10">
                                          <input type="text" name="recipients" placeholder="" id="inputEmail3" class="form-control" value="hotelconnect@hotelbeds.com">
                                        </div>                                        
                                      </div>
                                      <input type="hidden" name="sender" value="<?php echo $askforconnection->email_address; ?>" />
                                      <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputEmail3">Subject </label>
                                        <div class="col-sm-10">
                                          <input type="text" name="subject" placeholder="" id="inputEmail3" class="form-control" value="Xml connection Requested for Property <?php echo $askforconnection->property_name;?>(Id <?php echo $ch->hotel_channel_id;?>)">
                                        </div>                                        
                                      </div>
                                      <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputEmail3">content </label>
                                        <div class="col-sm-10">
                                          <textarea style="height: 350px;" rows="10" class="form-control" name="content" id="content" value="" readonly>Dear Xml Department,

Kindly could you enable xml connection for the following property and provide us the XML credentials, as well. Property:
<?php echo $askforconnection->property_name;?> Id <?php echo $ch->hotel_channel_id;?>

Hotel User <?php if(isset($ch->user_name)){echo $ch->user_name;} ?>

Address: <?php echo $askforconnection->address.",".$askforconnection->town."-".$askforconnection->zip_code ?>

Email: <?php echo $askforconnection->email_address; ?>

Web: <?php echo $askforconnection->web_site; ?>


Please enable reservation push method to 
url: <?php echo lang_url();?>reservation/getHotelbedsReservation/<?php echo insep_encode($channel_id); ?>



Thanks for cooperation
Best regards,
HotelAvailabilities Team.</textarea>
                                        </div>                                        
                                      </div>
                                      <div align="center"> 
                                        <button type="button" id="send_permission" onclick="sendreq()" class="btn btn-info text-center"> Send </button> 
                                      </div>
                                  </div>
                                </div>
                              </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button> 
                    </div> 
                </div>
            </div>
        </div>

<script type="text/javascript">
function sendreq(){
  var form_data = $("#ask_for_connection").serialize();
  console.log(form_data);
  $.ajax({
    url:"<?php echo lang_url();?>channel/askforconnection",
    data:form_data,
    type:"post",
    beforeSend: function() {
      $('#heading_loader').show();
    },
    success:function(response){
      $('#heading_loader').hide();
      $("#askforconnection").modal('hide');
      if(response == 1){
        $('#exp_succ').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Request sent Successfully.</div>');
        $('#exp_succ').show();
        setTimeout(function()
        {
          $('#exp_succ').hide();
        },5000);
      }else{
        $('#exp_succ').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error In Sending Request, Try After some time.</div>');
        setTimeout(function()
        {
            $('#exp_succ').hide();
        },5000);
      }
    },
    error:function(response){
      $("#askforconnection").modal('hide');
      $('#heading_loader').hide;
      console.log(response);
    }
  });
}
</script>
