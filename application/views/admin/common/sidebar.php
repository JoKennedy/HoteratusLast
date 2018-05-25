<div class="collapse navbar-collapse navbar-ex1-collapse">
             <?php
                  $uri = $this->uri->segment(3); 
                  $sessionvar=$this->session->userdata('logged_user');
                  $getadmin=$this->admin_model->get_admin($sessionvar);
                        foreach ($getadmin as $key) {
                            $control_id=$key->admin_controlid;
                        }
                  ?>
                <ul class="nav navbar-nav side-nav" style="overflow-y: scroll; overflow-x: hidden; height:620px">
               <?php
                  $records=$this->admin_model->get_adminsetting($control_id);
                   foreach($records as $admin_controls)
                    {
                        $id=$admin_controls->admin_controlid;
                        
                 ?>
                    <li <?php if($uri=='dashboard'){ echo 'class="active"';} ?>>
                        <a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <?php
                    if($admin_controls->manage_admin=="yes"){
                    ?>
                        <li  <?php if($uri=='manage_admin_details'){ echo 'class="active"';} ?>>
                            <a href="<?php echo base_url(); ?>admin/manage_admin_details"><i class="fa fa-wrench fa-fw"></i>Manage Admin<span class="fa arrow"></span></a>
                        </li>
                        <?php
                        }
                          if($admin_controls->manage_users=="yes"){
                        ?>
                        <li <?php if($uri=='manage_users'){ echo 'class="active"';} ?>>
                            <a href="<?php echo base_url(); ?>admin/manage_users"><i class="fa fa-wrench fa-fw"></i>Manage Hotelier<span class="fa arrow"></span></a>
                        </li>
                         <?php
                        }
						?>
                        <li <?php if(uri(3)==='manage_payments'){ echo 'class="active"';} ?>>
                            <a href="<?php echo lang_url(); ?>admin/manage_payments"><i class="fa fa-wrench fa-fw"></i>Manage Payment Gateway<span class="fa arrow"></span></a>
                        </li>
                        <li <?php if(uri(3)=='send_newsletter'){ echo 'class="active"';} ?>>
                            <a href="<?php echo lang_url(); ?>admin/send_newsletter"><i class="fa fa-wrench fa-fw"></i>Manage Newsletter<span class="fa arrow"></span></a>
                        </li>
                        <?php
                          if($admin_controls->admin_roles=="yes"){
                        ?>
                        <li <?php if($uri=='admin_setting'){ echo 'class="active"';} ?>>
                            <a href="<?php echo lang_url(); ?>admin/admin_setting"><i class="fa fa-edit fa-fw"></i> Admin Controls</a>
                        </li>
                         <?php
                        }
                          //if($admin_controls->membership=="yes"){
                        ?>
                        <li <?php if($uri=='membership'){ echo 'class="active"';} ?>>
                            <a href="<?php echo lang_url(); ?>admin/membership/view"><i class="fa fa-edit fa-fw"></i> Manage Membership</a>
                        </li>
                      
                        <!--<li <?php //if($uri=='manage_channel'){ echo 'class="active"';} ?>>
                            <a href="<?php //echo lang_url(); ?>admin/channel_plan/view"><i class="fa fa-edit fa-fw"></i> Channel Plan</a>
                        </li>-->

                         <?php
                        //}
                          if($admin_controls->manage_channel=="yes"){
                        ?>
                        <li <?php if($uri=='manage_channel'){ echo 'class="active"';} ?>>
                            <a href="<?php echo lang_url(); ?>admin/manage_channel"><i class="fa fa-edit fa-fw"></i> Manage Channel</a>
                        </li>
                         <?php
                        }
                        if($admin_controls->manage_property=="yes"){
                        ?>
                        <li <?php if($uri=='manage_property'){ echo 'class="active"';} ?>>
                            <a href="<?php echo lang_url(); ?>admin/manage_property/view"><i class="fa fa-edit fa-fw"></i>Manage Property</a>
                        </li>
                        <?php
                        }
                         if($admin_controls->manage_reservation=="yes"){
                        ?>
                       <li <?php if($uri=='manage_reservation'){ echo 'class="active"';} ?>>
                            <a href="<?php echo lang_url(); ?>admin/manage_reservation/view"><i class="fa fa-wrench fa-fw"></i>Manage Reservation<span class="fa arrow"></span></a>
                       </li>
                         <?php
                        }
                          if($admin_controls->manage_emailtemplate=="yes"){
                        ?>
                        
                        <li <?php if($uri=='manage_email'){ echo 'class="active"';} ?>>
                            <a href="<?php echo lang_url(); ?>admin/manage_email/view"><i class="fa fa-envelope fa-fw"></i> Email Templates</a>
                        </li>
                         <?php
                        }
                          if($admin_controls->manage_cms_page=="yes"){
                        ?>
                        <li <?php if($uri=='about' || $uri=='tc' || $uri=='privacy' || $uri=='faq' || $uri=='home' ){ echo 'class="active"';}?>>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> CMS <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" <?php if($uri=='about' || $uri=='tc' || $uri=='privacy' || $uri=='faq' || $uri=='home'){ echo 'class="collapse in"';}else{ echo 'class="collapse"'; } ?> >
                        <li <?php if($uri=='about'){ echo 'class="active"';} ?>>
                             <a href="<?php echo lang_url(); ?>admin/about/view"><i class="fa fa-files-o fa-fw"></i> About us</a>
                        </li> 
                        <li <?php if($uri=='tc'){ echo 'class="active"';} ?>>
                             <a href="<?php echo lang_url(); ?>admin/tc/view"><i class="fa fa-files-o fa-fw"></i> Terms and Conditions</a>
                        </li>
                         
                          <li <?php if($uri=='features'){ echo 'class="active"';} ?>>
                             <a href="<?php echo lang_url(); ?>admin/features/view"><i class="fa fa-files-o fa-fw"></i> Features</a>
                          </li>

                         <li <?php if($uri=='privacy'){ echo 'class="active"';} ?>>
                             <a href="<?php echo lang_url(); ?>admin/privacy/view"><i class="fa fa-files-o fa-fw"></i> Privacy Policy</a>
                        </li>
                       <li <?php if($uri=='faq'){ echo 'class="active"';} ?>>
                             <a href="<?php echo lang_url(); ?>admin/faq/view"><i class="fa fa-files-o fa-fw"></i> FAQ</a>
                        </li>
                         <li <?php if($uri=='home'){ echo 'class="active"';} ?>>
                             <a href="<?php echo lang_url(); ?>admin/home/view"><i class="fa fa-files-o fa-fw"></i> Home Cms</a>
                        </li>
                     </ul>
                    </li>
                     <?php
                        }
                          if($admin_controls->support=="yes"){
                        ?>
                        <li <?php if($uri=='support'){ echo 'class="active"';} ?>>
                            <a href="<?php echo lang_url(); ?>admin/contact"><i class="fa fa-user fa-fw"></i> Contact Us</a>
                        </li>
                    <!--<li <?php if($uri=='support'){ echo 'class="active"';} ?>>
                            <a href="<?php echo lang_url(); ?>admin/support"><i class="fa fa-user fa-fw"></i> Support</a>
                        </li>-->
                        
                    <?php
                }
                    }
                   
                    ?>  
                   
                </ul>
            </div>
            <!-- /.navbar-collapse -->
