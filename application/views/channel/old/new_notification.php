<?php
$this->db->where('status','unseen');
$this->db->where('reservation_id',0);
$this->db->order_by('n_id','desc');
$fetch=$this->db->get('notifications');
$number=$fetch->num_rows();
?>
 <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <input type="hidden" name="number_count" id="number_count" value="<?php echo $number; ?>">
                                <span class="badge badge-danger" id="new_notification"><?php echo $number;?></span>
                                <i class="fa fa-bell-o font-blue-hoki"></i>
                                <span class="badge badge-danger"> <?php echo $number;?> </span>
                            </a>
        <ul class="dropdown-menu">
                                <li class="external">
                                    
                                        <span class="light"><a href="javascript:;">view log file</a></span>
                                    
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">

                  <?php 
                  if($number){
                    
                  foreach ($fetch->result() as $notification) {
                  $type=$notification->type;
                  if($type==1){
                  $title='New Announcement';
                  $alert='bullhorn';
				  $lable='label-success';
                  }
				  else if($type==2)
				  {
                  $title='New Alerts';
                  $alert='terminal';
				   $lable='label-danger';
                  }
                  $saved_date=$notification->created_date;
                  $timediff = $this->admin_model->time_elapsed_string($saved_date);
                  // $timediff=$this->admin_model->time2string(time()-strtotime($saved_date)).' ago';
                  ?>
                    <li class="notify" id="id_<?php echo $notification->n_id;?>">

                        <a href="javascript:;" onclick="return update_announcement('<?php echo $notification->n_id; ?>','<?php echo $notification->type?>');">

                            <span class="time"><?php echo $timediff;?></span>
                            <span class="details">
                                <span class="label label-sm label-icon <?php echo $lable;?>">
                                    <i class="fa fa-<?php echo $alert;?>"></i>
                                </span> <?php echo $title;?> </span>
                        </a>
                    </li>

                    <?php } }else{ ?>  
                          <li>
                            <center>No Notifications Found !!! </center>
                          </li>
                    <?php } ?>
                                     
                                    </ul>
                                </li>
                            </ul>