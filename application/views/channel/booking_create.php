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
<div class="container-fluid pad_adjust  mar-top-30">
  <div class="row">

    <div class="col-md-9 col-sm-7 col-xs-12 cls_cmpilrigh">

      <div class="verify_det">
        <h4>  <div id="exp_succ">  </div> </h4>
      </div>
    </div>




  </div>
</div>


<script type="text/javascript">
function sendreq(){
  var form_data = $("#ask_for_connection").serialize();
  console.log(form_data);
  $.ajax({
    url:"<?php echo lang_url(); ?>channel/askforconnection",
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
      }
      else{
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
