
<div class="dash-b4-n calender-n">
<div class="row-fluid clearfix">
<div class="col-md- col-sm-3">
<div class="cal-lef">
<ul class="list-unstyled">
<li ><a href="<?php echo lang_url();?>channel/channel_listing" <?php if(uri(3)=='channel_listing') {?>class="lef1"<?php } ?>><i class="fa fa-asterisk"></i> All</a></li>
  
 <?php 
 // echo $connected_channel;die;
 $con = $count_all_channels-$connected_channel; ?>

  <li ><a href="<?php echo lang_url();?>channel/all_channel" <?php if(uri(3)=='all_channel') {?>class="lef1"<?php } ?>><i class="fa fa-link"></i> Connect(<?php if($con){echo $con;}else{echo 0;} ?>)</a></li>

  <li ><a href="<?php echo lang_url(); ?>channel/connected_channel" <?php if(uri(3)=='connected_channel') {?>class="lef1" <?php } ?>><i class="fa fa-exchange"></i> Connected Channels(<?php if($connected_channel){echo $connected_channel;}else{echo 0;} ?>)</a></li>
</ul>
</div>

</div>
<div class="col-md-9 col-sm-9" style="padding-right:0;">

<div class="bg-neww">
<!-- <div class="pa-n nn2"><h4><a href="#">Calendar</a>
    <i class="fa fa-angle-right"></i>
    Online Travel Agencies
</h4></div> -->

<div class="box-m hacker-list" id="hacker-list">
<div class="row">
<div class="col-md-3">
  <div class="input-group">
      <input class="form-control search" placeholder="Search for..." type="text">
     <!--  <span class="input-group-btn">
        <button class="btn btn-default" type="button">Go!</button>
      </span> -->
    </div>
</div>
</div>
<br>

  <div class="row">
  <div class="channel_listing">
  <ul class="list-inline nomargin clearfix list">
    <?php
    if($con_cha)
    {
    foreach($con_cha as $connect)
    {
    ?>
     <li class="col-sm-6">
        
                  <div class="row-fluid inr_cont clearfix">
                     <?php $chan_img = $this->channel_model->channel_image($connect->channel_id); ?>
                      <div class="col-sm-4">
                          <p class="name"><?php echo $chan_img->channel_name; ?></p>
                      </div>
                      <div class="col-sm-4">
                        <button class="btn btn-success btn-sm">live</button>
                      </div>
                      <div class="col-sm-4">
                          <a href="<?php echo lang_url(); ?>channel/view_channel/<?php echo $chan_img->seo_url;?>" class="btn btn-default active"><i class="fa fa-exchange"></i> Connected</a>
                      </div>
                  </div>
              
            
      
          </li>
        <?php } } else{ ?>
        <div class="col-md-9 col-sm-9">
          <div class="bb1">
          <br>
          <div class="reser"><center><i class="fa fa-sitemap"></i></center></div>
          <h2 class="text-center">You don't have any connected channels yet</h2>
          <br>
          <a class="btn btn-primary" href="<?php echo lang_url();?>channel/all_channelsplan"><i class="fa fa-plus"></i>  Connect Channels</a>
        </div>
        </div>
        <?php } ?>
        </ul>
        </div>
  </div>

<!-- <div class="row">
<div class="col-md-4 col-sm-4">Displaying <b>1</b> - <b>12</b> of <b>42</b></div>
<div class="col-md-4 col-sm-4 pull-right">
<div class="pull-right"><nav>
  <ul class="pagination">
 
    <li class="active"><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
    <li><a href="#">Next <i class="fa fa-angle-right"></i></a></li>
     <li><a href="#">Last <i class="fa fa-angle-double-right"></i></a></li>
 
  </ul>
</nav></div>
</div>
</div> -->
</div>               
              
              </div>
              
             
              
              </div>
              </div>
</div>



<script>
  $(document).ready(function () {
    var mySelect = $('#first-disabled2');

    $('#special').on('click', function () {
      mySelect.find('option:selected').prop('disabled', true);
      mySelect.selectpicker('refresh');
    });

    $('#special2').on('click', function () {
      mySelect.find('option:disabled').prop('disabled', false);
      mySelect.selectpicker('refresh');
    });

    $('#basic2').selectpicker({
      liveSearch: true,
      maxOptions: 1
    });
  });
</script>


    <!-- jQuery -->
    <script src="channels-list_files/jquery.xml"></script>
    <script src="channels-list_files/bootstrap.xml"></script>
    <script src="channels-list_files/jquery_002.xml"></script>
    <script src="channels-list_files/wow.xml"></script>
    <script src="channels-list_files/creative.xml"></script>
<!-- end jQuery -->

<!-- date -->
 <!-- <script src="assets/js/pre.js"></script>
  <script src="assets/js/pre2.js"></script>  -->
  <script src="channels-list_files/pre3.xml"></script>
  <script>
  if (top.location != location) {
    top.location.href = document.location.href ;
  }
    $(function(){
      window.prettyPrint && prettyPrint();
      $('#dp1').datepicker({
        format: 'mm-dd-yyyy'
      });
      $('#dp1-p').datepicker({
        format: 'mm-dd-yyyy'
      });
      $('#dp1-p2').datepicker({
        format: 'mm-dd-yyyy'
      });
      $('#dpYears').datepicker();
      $('#dpMonths').datepicker();
      
      
      var startDate = new Date(2012,1,20);
      var endDate = new Date(2012,1,25);
    
      

        // disabling dates
        var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

        var checkin = $('#dpd1').datepicker({
          onRender: function(date) {
            return date.valueOf() < now.valueOf() ? 'disabled' : '';
          }
        }).on('changeDate', function(ev) {
          if (ev.date.valueOf() > checkout.date.valueOf()) {
            var newDate = new Date(ev.date)
            newDate.setDate(newDate.getDate() + 1);
            checkout.setValue(newDate);
          }
          checkin.hide();
          $('#dpd2')[0].focus();
        }).data('datepicker');
        var checkout = $('#dpd2').datepicker({
          onRender: function(date) {
            return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
          }
        }).on('changeDate', function(ev) {
          checkout.hide();
        }).data('datepicker');
    });
  </script>
<!-- date -->

<!-- vertical scroll -->
<!--<script src="assets/js/custom-js-ver.js"></script>    -->
  <script src="channels-list_files/custom-js-ver2.xml"></script>
  <script>
    (function($){
      $(window).load(function(){
        
        $.mCustomScrollbar.defaults.theme="inset"; //set "inset" as the default theme
        $.mCustomScrollbar.defaults.scrollButtons.enable=true; //enable scrolling buttons by default
        
        $("tab-n2").mCustomScrollbar({
          axis:"yx",
          scrollbarPosition:"outside"
        });
        
        $(".outer,.nested").mCustomScrollbar();
        
        $(".inner.horizontal-images").mCustomScrollbar({
          axis:"x",
          advanced:{autoExpandHorizontalScroll:true}
        });
        
      });
    })(jQuery);
  </script>

<!-- dialog box -->
<div class="dial">
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">More options</h4>
      </div>
      <div class="modal-body">
      <div class="inner-dia">
       <h4>Rate types</h4>
       
       
       <div class="top-3">
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Apartment - 1 guest</span></div>
       <div class="col-md-1 col-sm-1"><select class="form-control">
  <option selected="selected">+</option>
  <option>-</option></select></div>
  <div class="col-md-1 col-sm-1"><select class="form-control">
  <option selected="selected">%</option>
  <option>Rs</option></select></div>
   <div class="col-md-2 col-sm-2 ssw">
   <div class="form-group">
    <input value="0.00" class="form-control widh" type="text">
  </div>
   </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Apartment - 1 guest Non refundable</span></div>
       <div class="col-md-1 col-sm-1"><select class="form-control">
  <option selected="selected">+</option>
  <option>-</option></select></div>
  <div class="col-md-1 col-sm-1"><select class="form-control">
  <option selected="selected">%</option>
  <option>Rs</option></select></div>
   <div class="col-md-2 col-sm-2 ssw">
   <div class="form-group">
    <input value="0.00" class="form-control widh" type="text">
  </div>
   </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Apartment - 2 guests </span></div>
       <div class="col-md-1 col-sm-1"><select class="form-control">
  <option selected="selected">+</option>
  <option>-</option></select></div>
  <div class="col-md-1 col-sm-1"><select class="form-control">
  <option selected="selected">%</option>
  <option>Rs</option></select></div>
   <div class="col-md-2 col-sm-2 ssw">
   <div class="form-group">
    <input value="0.00" class="form-control widh" type="text">
  </div>
   </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Apartment - 2 guests Non refundable  </span></div>
       <div class="col-md-1 col-sm-1"><select class="form-control">
  <option selected="selected">+</option>
  <option>-</option></select></div>
  <div class="col-md-1 col-sm-1"><select class="form-control">
  <option selected="selected">%</option>
  <option>Rs</option></select></div>
   <div class="col-md-2 col-sm-2 ssw">
   <div class="form-group">
    <input value="0.00" class="form-control widh" type="text">
  </div>
   </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Minimum stay   </span></div>
      <div class="col-md-3 col-sm-3 new-no">
    
      <form class="form-horizontal" role="form">
    <div class="form-group">

      <div class="col-lg-10">
        <select id="basic" class="selectpicker show-tick form-control" data-live-search="true">
          <option>Do not Change</option>
          <option selected="selected">1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
          <option>6</option>
          <option>7</option>
          <option>8</option>
          <option>9</option>
          <option>10</option>
          <option>11</option>
          <option>12</option>
          <option>13</option>
          <option>14</option>
          <option>15</option>
          <option>16</option>
          <option>17</option>
          <option>18</option>
          <option>19</option>
          <option>20</option>
          <option>21</option>
          <option>22</option>
          <option>23</option>
          <option>24</option>
          <option>25</option>
          <option>26</option>
          <option>27</option>
          <option>28</option>
          <option>29</option>
          <option>30</option>
          <option>31</option>
        </select>
      </div>
    </div>
  </form>
  
       </div>
  
   
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Stop sell   </span></div>
      <div class="col-md-3 col-sm-3 new-no">
    
      <form class="form-horizontal" role="form">
    <div class="form-group">

      <div class="col-lg-10">
        <select class="form-control">
          <option>Do not Change</option>
          <option selected="selected">Yes</option>
          <option>No</option>
        </select>
      </div>
    </div>
  </form>
  
       </div>
  
   
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Closed to arrival (CTA)  </span></div>
      <div class="col-md-3 col-sm-3 new-no">
    
      <form class="form-horizontal" role="form">
    <div class="form-group">

      <div class="col-lg-10">
        <select class="form-control">
          <option>Do not Change</option>
          <option selected="selected">Yes</option>
          <option>No</option>
        </select>
      </div>
    </div>
  </form>
  
       </div>
  
   
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Closed to departure (CTD)  </span></div>
      <div class="col-md-3 col-sm-3 new-no">
    
      <form class="form-horizontal" role="form">
    <div class="form-group">

      <div class="col-lg-10">
        <select class="form-control">
          <option>Do not Change</option>
          <option selected="selected">Yes</option>
          <option>No</option>
        </select>
      </div>
    </div>
  </form>
  
       </div>
  
   
       </div>
       </div>
       
       
       
       
       
       
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary">ok</button>
      </div>
    </div>
  </div>
</div>
</div>

<script>


$().ready(function()
{
  var options = { valueNames: [ 'name', 'born' ] };
  
  var hackerList = new List('hacker-list', options);
});
</script>