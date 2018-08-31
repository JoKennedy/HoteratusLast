<ul id="menu-content" class="menu-content out">
<?php if(uri(3)=='property_info' || 	uri(3)=='tax_categories' || uri(3)=='policies' || uri(3)=='manage_subusers' || uri(3)=='billing_info' ||  uri(3)=='manage_rooms' || uri(3)=='payment_list' || uri(3)=='payment_bank' || uri(3)=='rate_management' || uri(3)=='manage_channel' || uri(3) == 'manage_newsletter') {?>
				<?php if(user_type()=='1' || admin_id()!='' && admin_type()=='1'){ ?>
				
				<li <?php if(uri(3)=='manage_property' || uri(3)=='property_info' || uri(3)=='payment_list') {?>class="active" <?php } ?>>
                  <a href="<?php echo lang_url();?>channel/property_info">
                  <i class="fa fa-building fa-lg"></i> Hotel Profile
                  </a>
                </li>
                  
                <li <?php if(uri(3)=='manage_rooms') { ?> class="active" <?php } ?>>
                  <a href="<?php echo lang_url();?>channel/manage_rooms">
                  <i class="fa fa-bed fa-lg"></i> Manage Rooms
                  </a>
                </li>
				
				<li <?php if(uri(3)=='manage_subusers'){?> class="active" <?php } ?>>
                  <a href="<?php echo lang_url(); ?>channel/manage_subusers">
                  <i class="fa fa-users fa-lg"></i> Manage Users
                  </a>
                </li>

               <li <?php if(uri(3)=='payment_list' || uri(3)=='payment_bank'){?> class="active" <?php } ?>><a href="<?php echo lang_url(); ?>reservation/payment_list"><i class="fa fa-money fa-lg"></i> Payment methods </a></li>

               <li <?php if(uri(3)=='policies') {?> class="active" <?php } ?>><a href="<?php echo lang_url(); ?>channel/policies"> <i class="fa fa-star fa-lg"></i> Policies   </a>  </li>

              <li <?php if(uri(3)=='tax_categories') {?> class="active" <?php } ?>><a href="<?php echo lang_url(); ?>reservation/tax_categories"><i class="fa fa fa-object-ungroup fa-lg"></i> Tax categories </a> </li>

               <li <?php if(uri(3)=='billing_info'){ ?> class="active" <?php } ?>><a href="<?php echo lang_url(); ?>channel/billing_info"><i class="fa fa-file-text"></i> Billing Details </a> </li>
			   
			   <li <?php if(uri(3)=='rate_management'){ ?> class="active" <?php } ?>><a href="<?php echo lang_url(); ?>inventory/rate_management"><i class="fa fa-list-alt"></i> Membership Plan </a> </li>
         <li <?php if(uri(3)=='manage_channel'){ ?> class="active" <?php } ?>><a href="<?php echo lang_url(); ?>channel/manage_channel"><i class="fa fa-exchange"></i> Channel Details </a> </li>

            <li <?php if(uri(3)=='manage_newsletter') {?>class="active" <?php } ?>><a href="<?php echo lang_url();?>channel/manage_newsletter"><i class="fa fa-h-square fa-lg"></i>Manage NewsLetter</a></li>

				 <?php } else if(user_type()=='2'){ ?>
				 
				<li <?php if(uri(3)=='manage_property' || uri(3)=='property_info') {?>class="active" <?php } ?>>
                  <a href="<?php echo lang_url();?>channel/property_info">
                  <i class="fa fa-h-square fa-lg"></i> Property Info
                  </a>
                </li>
                  
                <li>
                  <a href="<?php echo lang_url();?>channel/manage_rooms">
                  <i class="fa fa-bed fa-lg"></i> Manage Rooms
                  </a>
                </li>

                <li <?php if(uri(3)=='payment_list'){?> class="active" <?php } ?>><a href="<?php echo lang_url(); ?>reservation/payment_list"><i class="fa fa-money fa-lg"></i> Payment methods </a></li>

                 <li <?php if(uri(3)=='policies') {?> class="active" <?php } ?> > <a href="<?php echo lang_url(); ?>channel/policies"> <i class="fa fa-star fa-lg"></i> Policies   </a>  </li>

              <li><a href="<?php echo lang_url(); ?>reservation/tax_categories"><i class="fa fa fa-object-ungroup fa-lg"></i> Tax categories </a> </li>

               <li><a href="<?php echo lang_url(); ?>channel/billing_info"><i class="fa fa-file-text"></i> Billing Details </a> </li>
			   
			   <li><a href="<?php echo lang_url(); ?>inventory/rate_management"><i class="fa fa-file-text"></i> Membership Plan </a> </li>
         <li><a href="<?php echo lang_url(); ?>channel/manage_channel"><i class="fa fa-exchange"></i> Channel Details </a> </li>

            <li <?php if(uri(3)=='manage_newsletter') {?>class="active" <?php } ?>><a href="<?php echo lang_url();?>channel/manage_newsletter"><i class="fa fa-h-square fa-lg"></i>Manage NewsLetter</a></li>
				
				 <?php } ?>
				  
<?php } else { ?>
                <li <?php if(uri(3)=='manage_property' || uri(3)=='property_info') {?>class="active" <?php } ?>>
                  <a href="<?php echo lang_url();?>channel/manage_property">
                  <i class="fa fa-h-square fa-lg"></i> My Property
                  </a>
                  </li>
<?php } ?>
                
                  
            </ul>