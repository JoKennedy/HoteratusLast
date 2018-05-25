<?php $this->load->view('admin/header');?>
 <style type="text/css">
 #heading_loader {
    background: none repeat scroll 0 0 rgba(255, 255, 255, 0.8);
    margin: -50px auto 0;
    min-height: 150px;
    width: 100%;
}
.loadinh_bg {
    margin-top: 350px !important;
}
.full-width {
    width: 100% !important;
}
.error {
    color: #f00;
    font-size: 14px;
}
#heading_loader {
    bottom: 0;
    left: 0;
    position: fixed;
    right: 0;
    top: 0;
    z-index: 10000;
}
.loadinh_bg {
    border-radius: 10px;
    margin: 200px auto 0;
    padding: 10px;
    width: 150px;
}
.loader {
    border: 2px solid #555555 !important;
    font-style: italic;
    height: 75px;
    position: relative;
}</style>
 <div class="loading-circle-overlay" id="heading_loader" style="display:none">
  <div id="model-back">
    <div class="loadinh_bg">
      <div class="main_content_bg">
        <div class="details_bg">
          <div style="overflow:hidden;clear:both;">
            <div align="center" style="color:#003580; float: left; font-size: 15px; text-align:center; font-weight:bold;"><br> <!--<font size="-1" color="#A2A2A2" style="margin-left: 26px;">Please Wait...</font>-->
            <br>
            <img src="http://localhost/channel_live/user_assets/loader/loader.gif">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  <div class="breadcrumbs">
  <div class="row-fluid clearfix">
  <i class="fa fa-home"></i> PMS Integration
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
        </div>
      <?php 
      }
      $error=$this->session->flashdata('error');                   
      if($error)  { ?> 
        <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Oh! </strong><?php echo $error;?>.
        </div>
      <?php }?> 
       <?php 
          if($action== "add") $title ="Add PMS Integration" ;
          else if($action== "edit") $title ="Edit PMS Integration" ;
          else if($action== "view_single" || $action== "view") $title ="View PMS Integration";
        ?>
      <div class="col-md-12">
      <div class="table-responsive">
      <div class="cls_box">
      <h4><?php echo $title;?></h4>
      <br><br>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Confirm XML/API Integration</h4>
      </div>
      <div class="modal-body" id="pass_create">
         <div class="form-group">
          <label><font color="#CC0000">*</font>Email</label>
       <input class="form-control" id="email_id" type="subject" value="" readonly="readonly" />
              </div>
     <div class="form-group">
          <label><font color="#CC0000">*</font>Password</label>
         <input class="form-control" id="password_pms" type="subject" value="" readonly="readonly" />
              </div>              
             <input type="button" tabindex="2" onclick="generate();" value="Generate New" class="btn-info btn-sm"> 
      </div>
      <div class="modal-footer">
        <button type="button" id="confirm" class="btn btn-info" data-dismiss="modal">Confirm</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id="myModalIP" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ADD Ip's For Whitelisting</h4>
      </div>
      <div class="modal-body" >
      <form id="formdata" name="formdata">
        <input type="hidden" id="userpartner" name="partner_id"></input>
        <div id="appendtext">
           <div class="form-group">
            <label><font color="#CC0000">*</font>IP</label>
              <input class="form-control" name="ip[]" type="text" value="" style="width: 95%;" />
                </div>
          </div>
      </form>     
      <input type="button" tabindex="2" onclick="addip();" value="Add More" class="btn-info btn-sm"> 
      </div>
      <div class="modal-footer">
      <span class="alert alert-danger" id="errorinip" style="display: none;"></span>
        <button type="button" id="addip" class="btn btn-info">Add IP</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id="exp_succ"></div>
      <!-- view -->
<?php if($action== "view"){?>
<?php echo form_open('admin/pms/bulk_delete', array('id'=>'delete_form', 'onsubmit'=>'return submit_form();', 'class="form-inline"')); ?>
      <table id="example" class="display table table-hover table-bordered" 
      cellspacing="0">
            <thead>
              <tr class="top-tr">
              <th><input type="checkbox" id="gc_check_all" /> <button type="submit" class="btn btn-small btn-danger"><i class="fa fa-trash-o"></i></button></th>
                <th width="4%">S.No</th>
                <th >Name</th>
                <th >Email</th>
                <th  >Company</th>
             <!--    <th  >Country</th>
 -->                <th  >Status</th>
                <th >Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i=0;
              foreach($users as $row)
              {    
               $i++;
              ?>
                <tr>
                <td><input name="order[]" type="checkbox" value="<?php echo insep_encode($row->partnar_id); ?>" class="gc_check"/></td>
                <td><?php echo $i; ?> </td>
                <td><?php echo $row->firstname; ?> </td>
                <td><?php echo $row->email; ?> </td>
                <td><?php echo $row->company; ?> </td>
               <!--  <td><?php echo $row->country; ?> </td> -->
                <td><?php echo $row->status; ?> </td>

                <?php
                  echo "<td align='center'>";
                  $value=array('class'=>'edit');
                  echo '<a href="javascript:;" id="settings_'.$row->partnar_id.'" class="settingsbtn btn-info btn-sm" style="margin-right:5px;" data-toggle="modal" data-target="#myModal"><i class="fa fa-gears" title="Confirm"></i></a><a href="javascript:;" id="'.$row->partnar_id.'" class="btn-info btn-sm adduserip" style="margin-right:5px;" title="Add Ips" data-toggle="modal" data-target="#myModalIP"><i class="fa fa-users" aria-hidden="true"></i></a><a class="btn-info btn-sm" style="margin-right:5px;" href="'.lang_url().'admin/pms/edit/'.$row->partnar_id.'" title="Edit Partner"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></';
                 //echo anchor('admin/pms/edit/'.$row->partnar_id   ,'<i class="fa fa-gears" title="edit"></i>',$value);
                  echo "</td>";
                  echo "</tr>";
              }
              ?>
            </tbody>
        </table>
        <?php
		echo form_close();
      }
      ?>
    <?php if($action== "edit"){
      extract($partner);
      ?>
       <div class="panel-body">
       <div class="row">
        <div class="col-lg-1">
        </div>
      <div class="col-lg-9">
          <?php //echo form_open_multipart('admin/manage_email/update/'.$id); ?>
          <form role="form" method="post" action="<?php echo lang_url()?>admin/pms/update/<?php echo $partner_id; ?>">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Country: *</label>
               <select class="form-control" name="country">
    <?php $country = get_data('country')->result_array();
  foreach($country as $value) { 
  extract($value); ?>
    <option value="<?php echo $id;?>" <?php if($country == $id) { echo "selected"; } ?> ><?php echo $country_name;?></option>
    <?php  } ?>
  </select>
            </div>
          <!--   <div class="form-group">
              <label for="">Company Type: *</label>
              <select class="form-control">
                <option value="Property Management System">Property Management System</option>
                <option value="Revenue Manager">Revenue Manager</option>
                <option value="Website Builder">Website Builder</option>
                <option value="Other">Other</option>
              </select>
            </div>         
            <div class="form-group">
              <label for="">How many properties (hotels, hostels, B&Bs, guesthouses, etc) do you have in your network?</label>
              <select id="property" name="" class="form-control">
                <option value="Please select number">Please select number</option>
                <option value="0 - I'm just starting!">0 - I'm just starting!</option>
                <option value="1 - 10">1 - 10</option>
                <option value="11 - 50">11 - 50</option>
                <option value="51 - 100">51 - 100</option>
                <option value="101 - 500">101 - 500</option>
                <option value="501 - 1000">501 - 1000</option>
                <option value="1000+">1000+</option>
                <option value="We're not that type of partner">We're not that type of partner</option>
              </select>
            </div>-->    
            


         <div class="form-group">
                              <label for="">Company Name: *</label>
              <input type="text" name="company" id="company"  class="form-control" value="<?php echo $company; ?>">
          <?php if(form_error('company')) {?><font color="#CC0000"><?php echo form_error('company'); ?></font><?php } ?>
          </div>
                    
<div class="form-group">
                            <label for="">Company Website: *</label>
              <input type="text" name="website"id="website" class="form-control" value="<?php echo $website; ?>">
          <?php if(form_error('website')) {?><font color="#CC0000"><?php echo form_error('website'); ?></font><?php } ?>
          </div>
            
                              
<div class="form-group">
                            <label for="">Contact Name: *</label>
 <div class="row">
                <div class="col-sm-6">
                <label>First</label>
              <input type="text" name="contact" id="contact" class="form-control" value="<?php echo $firstname;?>">
          <?php if(form_error('contact')) {?><font color="#CC0000"><?php echo form_error('contact'); ?></font><?php } ?>
                </div>
                <div class="col-sm-6">
                <label>Last</label>
                  <input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo $lastname; ?>">
                  
               
                </div>
              </div>


                            
          </div> 
            

            <div class="form-group">
                          <label for="">Email: *</label>
              <input type="text" name="email"id="email" onblur="check_mail(this.value)" class="form-control" value="<?php echo $email;?>">
          <?php if(form_error('email')) {?><font color="#CC0000"><?php echo form_error('email'); ?></font><?php } ?>
          <span id="error_mail" style="color:red"></span>
          </div>

        

            <div class="form-group">
              <label for="">Phone:</label>
              <input type="text" name="phone"id="phone" class="form-control" value="<?php echo $phone; ?>">
            </div>
        <!--     <div class="form-group">
              <label for="">Your Technology Platform and Language:</label>
              <input type="text" class="form-control">
              <p>Please let us know what your technology stack looks like and the language / languages your application is written in. This will give us information to help you with your integration and get your questions answered quickly.</p>
            </div> -->
            <div class="form-group">
              <label for="">comments:</label>
              <textarea id="comments" name="comments" class="form-control" value=""><?php echo $comments; ?></textarea>
            </div>    
            </div>
            <div class="col-md-6">
            <label for="">Ip's:</label>
            <div class="form-group">
            <div id="addmoreip">
              
            </div>
            <input type="button" tabindex="2" onclick="addmore();" value="Add More" class="btn-info btn-sm"> 
            <ul style="margin: 0px;padding: 0px;">
              <?php
              $ips = explode(',', rtrim($ip,',')); 
              $ii = 1;
              foreach($ips as $ip){ 
                if($ip != ""){
                ?>
                <li id="ip_ip<?php echo $ii; ?>" class="front-bor clearfix" style="border-left: none;width: 70%"> 
                <span style="width: 50%;text-align: right;"><a href="javascript:deleteip('<?php echo $partnar_id; ?>','<?php echo $ip; ?>','ip<?php echo $ii; ?>');"><i title="delete" class="fa fa-times"></i></a> </span>
                <span style="width: 50%;padding-left: 10px;"><?php echo $ip; ?></span>
                </li>
              <?php } 
                $ii++;
              } ?>
              </ul>
            </div>      
           </div>
            <div class="col-md-12 s2 button">
            <button type="submit" class="btn btn-success hvr-sweep-to-right">Submit</button>
            </div>
          </form>
                    <?php //echo form_close(); ?>
                </div>
                <!-- /.col-lg-6 (nested) -->
               
                <!-- /.col-lg-6 (nested) -->
            </div>
            <!-- /.row (nested) -->
        </div>
        <!-- /.panel-body -->
      <?php
      }
      ?>
      </div>
     </div>
    </div>
  </div>
<?php $this->load->view('admin/footer');?>
<script type="text/javascript">

function randomPassword(length) {
    var chars = "abcdefghijklmnopqrstuvwxyz!@#$%<>ABCDEFGHIJKLMNOP1234567890";
    var pass = "";
    for (var x = 0; x < length; x++) {
        var i = Math.floor(Math.random() * chars.length);
        pass += chars.charAt(i);
    }
    return pass;
}
function deleteip(id,ip,list)
{
  $.ajax({
    url:"<?php echo lang_url(); ?>admin/removeip",
    type:"POST",
    data:"id="+id+"&ip="+ip,
    success:function(res){
      $("#ip_"+list).remove();
    }
  });
}
function addmore()
{
  var len = $("#addmoreip").children().length;
  
  $text = '<div class="form-group" id="more_'+len+'" style="padding-bottom:25px;"><input class="form-control" style="float:left;width:95%" name="ip[]" type="text" value="" /><a href="javascript:removediv('+len+')" style="padding-left:10px;"><i class="fa fa-times" title="delete"></i></a></div>';
  $("#addmoreip").append($text);
}
function removediv(len)
{
  $("#more_"+len).remove();
}
function addip()
{
  var len = $("#appendtext").children().length;
  $text = '<div class="form-group" id="ipmore_'+len+'" style="padding-bottom:25px;"><input class="form-control" name="ip[]" type="text" value="" style="float:left;width:95%"/><a href="javascript:removeipdiv('+len+')" style="padding-left:10px;"><i class="fa fa-times" title="delete"></i></a></div>';
  $("#appendtext").append($text);
}
function removeipdiv(len)
{
  $("#ipmore_"+len).remove();
}
function generate() {
    var pass= randomPassword(7);
  $('#password_pms').val(pass);
}
  $(document).ready(function() {

    $(".adduserip").click(function(){
      var id = this.id;
      $("#userpartner").val(id);
    });

    $("#addip").click(function(){
      var formdata = $("#formdata").serialize();
      $.ajax({
        url:"<?php echo lang_url(); ?>admin/addip",
        data:formdata,
        type:"POST",
        success:function(data){
          console.log(data);
          if(data == "1")
          {
            $("#formdata")[0].reset();
            $("#myModalIP").modal('hide');
          }else{
            $("#errorinip").show();
            $("#errorinip").text("Please Enter the Ip");
            setTimeout(function(){
              $("#errorinip").hide();
            },5000);
          }
        }
      });
      
    });

    $('#example').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );

    $('.settingsbtn').click(function(){
    var id=this.id;
    $.ajax({
  type: "POST",
  url: '<?php echo lang_url();?>admin/getpartner',
  data: 'id='+id,
  success: function(result)
  {
   var results=$.trim(result);
 $('#email_id').val(results);
  var pass= randomPassword(7);
  $('#password_pms').val(pass);
  }
});
    });

$('#confirm').click(function(){
var email=$('#email_id').val();
var pass=$('#password_pms').val();
  var dataString="email="+email+"&pass="+pass;
  $.ajax({
  type: "POST",
  url: '<?php echo lang_url();?>admin/confirmpass',
  data: dataString,
   beforeSend: function() {
     $('#heading_loader').show();
    },
  success: function(result)
  {
    $('#heading_loader').hide();
    var results=$.trim(result);
   window.location.reload(true);
  }
});

});


});

  
$('#gc_check_all').click(function(event) 
{ 
	if(this.checked)
	{ 
		$(".gc_check").each(function()
		{ 
			this.checked = true;
		});
	}
	else
	{
		$('.gc_check').each(function() 
		{
			this.checked = false; 
		});        
	}
});

function submit_form()
{
	if($(".gc_check:checked").length > 0)
	{
		return confirm('Are u sure want to delete the users?');
	}
	else
	{
		alert('No users selected to delete!!!');
		return false;
	}
}

</script>
