<?php $this->load->view('admin/header');?>
  <div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-phone-square"></i> Manage Contact
  </div>
  </div>


  <div class="manage">
  
    <div class="row-fluid clearfix">
     <?php    
      if(isset($error)) { ?> 
       <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Oh! </strong><?php echo $error;?>.
      </div>
      <?php }?>     
      <?php 
       $success=$this->session->flashdata('success');                 
        if($success)  { ?> 
        <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Success! </strong> <?php echo $success;?>.
      </div><?php }?>  
      <?php  $error=$this->session->flashdata('error');                   
        if($error)  { ?> 
       <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Oh! </strong><?php echo $error;?>.
      </div>
      <?php }?> 

      <div class="col-md-12">
      
         <div class="row">
        <div class="col-md-6 col-sm-6">
          <!-- BEGIN PORTLET-->
          <div class="portlet box green">
            <div class="portlet-title line">
              <div class="caption">
                <i class="fa fa-eye"></i>View User Support
              </div>
              <!--<div class="tools">
                <a href="" class="collapse">
                </a>
                <a href="#portlet-config" data-toggle="modal" class="config">
                </a>
                <a href="" class="reload">
                </a>
                <a href="" class="remove">
                </a>
              </div>-->
            </div>
            <div id="chats" class="portlet-body">
              <div class="scroller" style="height: 435px;" data-always-visible="1" data-rail-visible1="1">
                <ul class="chats">
                  <li class="in">
                      <?php $user = get_data(TBL_USERS,array('user_id'=>$single_view->user_id))->row(); ?>
                                        <div class="form-group">
                    <label class="control-label col-sm-3">User Name:
                    </label>
                    <div class="col-sm-9">
                      <label class="control-lable" style="margin-top: 3px"> <?php echo $user->fname.' '.$user->lname?></label>
                    </div>
                  </div>
                                    </li>
                                    <li class="in">
                                    <div class="form-group">
                    <label class="control-label col-sm-3">Email:
                    </label>
                    <div class="col-sm-9">
                      <label class="control-lable"  style="margin-top: 3px"> <?php echo $user->email_address?></label>
                    </div>
                  </div>
                                    </li>
                                    
                                    <li class="in">
                                    <div class="form-group">
                    <label class="control-label col-sm-3">Subject:
                    </label>
                    <div class="col-sm-9">
                      <label class="control-lable"  style="margin-top: 3px"> <?php echo $single_view->subject;?></label>
                    </div>
                  </div>
                                    </li>
                                    <li class="in">
                                    <div class="form-group">
                    <label class="control-label col-sm-3">Description:
                    </label>
                    <div class="col-sm-9">
                      <label class="control-lable"  style="margin-top: 3px"> <?php echo $single_view->description;?></label>
                    </div>
                  </div>
                  </li>
                  
                </ul>
              </div>
              
            </div>
          </div>
          <!-- END PORTLET-->
        </div>
                <div id="replace">
                <div class="col-md-6 col-sm-6">
          <!-- BEGIN PORTLET-->
          <div class="portlet box green">
            <div class="portlet-title line">
              <div class="caption">
                <i class="fa fa-comments"></i>Reply To User
              </div>
              <!--<div class="tools">
                <a href="" class="collapse">
                </a>
                <a href="#portlet-config" data-toggle="modal" class="config">
                </a>
                <a href="" class="reload">
                </a>
                <a href="" class="remove">
                </a>
              </div>-->
            </div>
            <div id="chats" class="portlet-body">
              <div class="scroller" style="height: 435px;" data-always-visible="1" data-rail-visible1="1" id="ajax">
                <ul class="chats">
                                <?php 

                $this->db->order_by('id','desc');
                $get_replay = get_data('Replay_User_Suport',array('support_id'=>$single_view->id))->result_array();
                if(count($get_replay)!='0')
                {
                  
                  foreach($get_replay as $val)
                  {
                    extract($val);
                  ?>
                  <li <?php if($is_admin=='1')
                    {
                  echo "class='in'";
                  }
                  else
                  {
                    echo "class='out'";
                  }?>>
                                      <?php if($is_admin=='1')
                    { 
                      $name = get_data('manage_admin_details',array('id'=>$from_id))->row();
                    ?>
                    <img style="height: 50px;width: 50px;" class="avatar img-responsive" alt="" src="<?php echo site_url()?>uploads/<?php echo $name->admin_image;?>"/>
                    <?php } else { 
                      $name = get_data(TBL_USERS,array('user_id'=>$from_id))->row();
                    ?>
                                        <img style="height: 50px;width: 50px;" class="avatar img-responsive" alt="" src="<?php echo site_url()?>uploads/126899.jpg"/>
                                        <?php } ?>
                    <div class="message">
                      <span class="arrow">
                      </span>
                      <a href="javascript:;" class="name">
                        <?php if($is_admin=='1')
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
                        }?>
                      </a>
                      <span class="datetime">
                         at <?php echo date('d-M-y h:i a',strtotime($created))?>
                      </span>
                      <span class="body">
                         <?php echo $message?>
                      </span>
                    </div>
                  </li>
                                    <?php } } else { ?>
                  <li class="out">
                    <div class="message">
                      <span class="body">
                        No Records Found...
                      </span>
                    </div>
                  </li>
                                    <?php } ?>
                                    
                </ul>
              </div>
                            <form action="" id="form_sample_1">
              <div class="chat-form">
                <div class="input-cont">
                  <input class="form-control" type="text" placeholder="Type a message here..." name="replay" required/>
                                    <?php $tmp = $this->session->userdata('logged_user');
                                    ?>
                                    <input type="hidden" name="from_id" value="<?php echo $tmp;?>">
                                    <input type="hidden" name="to_id" value="<?php echo $single_view->user_id;?>">
                                    <input type="hidden" name="support_id" value="<?php echo $single_view->id;?>"> 
                                    <input type="hidden" name="is_admin" value="1"> 
                                    <input type="hidden" name="seo_url" value="<?php echo $single_view->seo_url?>" />
                </div>
                <div class="btn-cont">
                  <span class="arrow">
                  </span>
                                    <button type="submit" class="btn blue icn-only"><i class="fa fa-check icon-white"></i></button>
                </div>
                                <span for="replay" class="help-block" style="display:none; color:#F00">This field is required.</span>
              </div>
                            </form>
            </div>
          </div>
          <!-- END PORTLET-->
        </div>
                </div>
      </div>

    </div>
  </div>
  
<?php $this->load->view('admin/footer');?>

<script src="<?php echo base_url()?>admin_assets/js/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>

<script>

$('#form_sample_1').on("submit",function(event) {
  event.preventDefault()  
  var data = $('#form_sample_1').serialize();
  $('#saving_container').show();
  $.ajax({
    method:'post',
    url:'<?php echo lang_url()?>admin/Replay_To_User',
    data:data,
    success:function(output)
    {
      $('#saving_container').hide();
      $('#ajax').html(output);
      $('#insert_data').html(output);
      $("input[type='text']").val('');
          $("input[type='textarea']").val('');
      $('input[type=checkbox]').each(function() 
      { 
          this.checked = false; 
      });
      $('#form_sample_1').trigger("reset");
    }
  });
});

setInterval(ajaxCall1, 3000); //300000 MS == 5 minutes
var data = $('#form_sample_1').serialize();
function ajaxCall1() {
    //do your AJAX stuff here
  $.ajax({  
    type:"POST",  
    url:"<?php echo lang_url(); ?>admin/ajax_message",
    data:data,
    success:function(msg)
    {
      $('#ajax').html(msg);
    }
    });
}


</script>

