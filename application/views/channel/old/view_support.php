<style type="text/css">
  /* 09_03  style starts */

.conversation-wrapper .conversation-inner {
    margin-right: 10px;
    padding: 0 0 5px;
}
.conversation-wrapper .conversation-user {
    background-clip: padding-box;
    border-radius: 50%;
    float: left;
    height: 50px;
    margin-top: 6px;
    overflow: hidden;
    width: 50px;
}
.conversation-wrapper .conversation-item {
    padding: 5px 0;
    position: relative;
}
.conversation-wrapper .conversation-item.item-right .conversation-body {
    background: #0098da none repeat scroll 0 0;
    color: #fff;
}
.conversation-wrapper .conversation-item.item-right .conversation-body::before {
    border-color: transparent transparent transparent #0098da;
    left: auto;
    right: -12px;
}
.conversation-wrapper .conversation-item.item-right .conversation-user {
    float: right;
}
.conversation-wrapper .conversation-item.item-right .conversation-body {
    margin-left: 0;
    margin-right: 60px;
}
.conversation-wrapper .conversation-body {
    background: #f5f5f5 none repeat scroll 0 0 padding-box;
    border-radius: 3px;
    font-size: 0.875em;
    margin-left: 60px;
    padding: 8px 10px;
    position: relative;
    width: auto;
}
.conversation-wrapper .conversation-body::before {
    border-color: transparent #f5f5f5 transparent transparent;
    border-style: solid;
    border-width: 6px;
    content: "";
    cursor: pointer;
    left: -12px;
    position: absolute;
    top: 25px;
}
.conversation-wrapper .conversation-body > .name {
    font-size: 1.125em;
    font-weight: 600;
}
.conversation-wrapper .conversation-body > .time {
    color: #605f5f;
    font-size: 0.875em;
    font-weight: 300;
    margin-top: 10px;
    position: absolute;
    right: 10px;
    top: 0;
}
.conversation-wrapper .conversation-body > .time::before {
    content: "";
    font-family: FontAwesome;
    font-size: 0.875em;
    font-style: normal;
    font-weight: normal;
    margin-top: 4px;
    text-decoration: inherit;
}
.conversation-wrapper .conversation-body > .text {
    padding-top: 6px;
}
.conversation-wrapper .conversation-new-message {
    background: #bbbbbb none repeat scroll 0 0;
    padding: 10px;
}

.tab-content h4 {
    border-bottom: 1px solid #eee;
    padding: 10px 0;
}
.clr-blu {
    color: #0098da !important;
}
.mar-no {
    margin: 0 !important;
}

.mar20 {
    margin: 20px 0 !important;
}

.ticket_title{
  color: #ffffff !important;
    padding: 7px 15px !important;
}

/* 09_03  style ends   */


</style>


<link href="<?php echo base_url(); ?>chat_ticket/css/chat.css" rel="stylesheet">

<link href="<?php echo base_url(); ?>chat_ticket/css/jquery.mCustomScrollbar.css" rel="stylesheet"> 

<!-- <section class="content-n"> -->
<div class="container-fluid pad_adjust  mar-top-30">
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12 cls_cmpilrigh">

  <div class="verify_det">
  <h4><a href="javascript:;">My Property</a>
    <i class="fa fa-angle-right"></i>Manage Ticket</h4>
  </div>  
  <hr>
<?php if($support->subject=='1')
{
  $subject = 'Ticket';
  } 
  else if($support->subject=='2'){
    $subject = 'Buy';
  }
  else
  {
    $subject = 'Sell';
  }
  ?>
  
      <div class="conversation-content">
      <div class="">
      <div class="row">
      <div class="col-md-2">
      <strong>Subject : </strong>
      </div>
      <div class="col-md-6">
      <?php echo ucfirst($support->subject); ?>
      </div>
      </div>
      <div class="row ">
      <div class="col-md-2 mar20">
      <strong>Description : </strong>
      </div>
      <div class="col-md-6 mar20">
      <?php echo $support->description; ?>                   </div>
      </div>
      </div>
      </div>
   
  


<div class="tab-pane  active" >
<h4 class="text-capitalize mar-no clr-blu"> <strong> Messages : </strong> </h4>
<!-- <hr class="mar-bot-no">-->
<div class="row">
<div class="col-lg-12">
<div class=" clearfix mar20">
  <div class="main-box-body clearfix">
    <div class="conversation-wrapper">
      <div class="row">
        <div class="col-md-12">
          <div class="conversation-content" id="replace">
            <div class="conversation-inner" id="ajax">
            <?php 
$this->db->order_by('id','desc');
$get_replay = get_data('Replay_User_Suport',array('support_id'=>$support->id))->result_array();
/*echo '<pre>';
print_r($get_replay);die;*/
if(count($get_replay)!='0')
{

foreach($get_replay as $val)
{
extract($val);
?>
<div <?php if($is_admin=='1')
{
echo "class='conversation-item item-right clearfix'";
}
else
{
echo "class='conversation-item item-left clearfix'";
}?>>
              <?php if($is_admin=='1')
{ 
$name = get_data('manage_admin_details',array('id'=>$from_id))->row();
?>
<div class="conversation-user">
          <img style="height: 50px;width: 50px;" class="avatar img-responsive" alt="" src="<?php echo base_url()?>uploads/<?php echo $name->admin_image;?>" wi/>
          </div>
<?php } else { 
$name = get_data(TBL_USERS,array('user_id'=>$from_id))->row();
?>
          <div class="conversation-user">
          <img style="height: 50px;width: 50px;" class="avatar img-responsive" alt="" src="<?php echo base_url()?>uploads/126899.jpg"/>
          </div>
          <?php } ?>
                <div class="conversation-body">
                  <div class="name"> <?php if($is_admin=='1')
{
$name = get_data('manage_admin_details',array('id'=>$from_id))->row();
if(count($name)!='0')
{
if($name->name!='')
{
echo $name->name;
}
else
{
echo 'Admin';
}
}
else
{
echo 'Admin';
}
}
else
{
$name = get_data(TBL_USERS,array('user_id'=>$from_id))->row();
if(count($name!=''))
{
echo $name->fname.' '.$name->lname;
}
}?> </div>
                  <div class="time hidden-xs"> <?php echo date('d-M-y h:i a',strtotime($created))?></div>
                  <div class="text"> <?php echo $message?></div>
                </div>
              </div>
              <?php } } else { ?>
              <div class="conversation-item item-left clearfix">
               <div class="text"> No Recorde Found....</div>
              </div>
              <?php } ?>
              
            </div>
          </div>
        </div>
        
      </div>
<div class="row">
<div class="col-md-12">
<div class="conversation-new-message">
<form action="" id="form_sample_1">  
<div class="form-group mar-no">
<div class="row">
<div class="col-md-1 pad-rht-no">
<p class="text-center mar-no"><i class="fa fa-link pad-rht clr-wht fa-2x center-block"></i></p>
</div>
<div class="col-md-6 pad-no">
<input type="text" placeholder="Enter your message..." rows="2" class="form-control" name="replay" required>
 <input type="hidden" name="from_id" value="<?php echo $user_id;?>"> 
<input type="hidden" name="to_id" value="1">
<input type="hidden" name="support_id" id="support_id" value="<?php echo $support->id;?>"> 
<input type="hidden" name="is_admin" value="0"> 
<input type="hidden" name="seo_url" value="<?php echo $support->seo_url?>" />
</div>
<div class="col-md-1 pad-lft-no">

<p class="text-center mar-no">
<button type="submit" class="btn blue icn-only"><i class="fa fa-check icon-white"></i></button>
</p>
</div>

</div>
</div>
</form>
</div>
</div>
</div>
    </div>
  </div>
</div>
</div>
</div>
</div>







</div>
</div>
</div>
<?php $this->load->view('channel/dash_sidebar'); ?>
<!-- </section> -->

 <!--    <script src="<?php echo base_url(); ?>chat_ticket/js/jquery.mCustomScrollbar.concat.min.js"></script>  -->

   