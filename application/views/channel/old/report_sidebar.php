<div class="col-md-12 col-sm-4 col-xs-12 cls_resp50">
        <div class="cls_side_bar">
          <ul class="list-unstyled clearfix cls_menu_side">

            <li class="<?php if(uri(3)=='report_revenue'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>reservation/report_revenue" >  Revenue </a> </li>

            <li class="<?php if(uri(3)=='report_reservation'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>reservation/report_reservation">  Reservation </a></li>

            <li class="<?php if(uri(3)=='nights_revenue'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>reservation/nights_revenue">  Nights </a> </li>

            <li class="<?php if(uri(3)=='report_guest'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>reservation/report_guest">  Guests</a> </li>

            <li class="<?php if(uri(3)=='average_revenue'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>reservation/average_revenue">  Average Revenue </a> </li>

            <li class="<?php if(uri(3)=='report_noshows'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>reservation/report_noshows">  No Shows </a> </li>

            <li class="<?php if(uri(3)=='report_cancellation'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>reservation/report_cancellation">  Cancellation </a> </li>
            
          </ul>          
        </div>
      </div>