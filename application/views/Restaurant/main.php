<div class="outter-wp">
      <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="<?php echo base_url();?>channel/managepos">All POS</a></li>
            <li class="active"><?= $Posinfo['description']?></li>
        </ol>
    </div>

    <div >
      <?php include("menu.php") ?>
    </div>
    <div class="graph-form">
        <h4>All Table</h4>
        <div class="graph-form">
             <div class="buttons-ui">
            <?php
              if (count($AllTable)>0) {
                  foreach ($AllTable as  $value) {

                    $appointment='';
                    $fecha1='';
                    $fecha2='';
                    $timellegada='';
                    if ($value['appointment']>0) {
                        $time = date("h:i:s");
                        $today=date("Y-m-d");
                        $appointment=$this->db->query("Select  * from mypostablereservation where mypostableid = ".$value['postableid']." and datetimereservation='$today' and starttime>='$time' order by starttime asc Limit 1")->row_array();

                        if( $appointment['starttime']-$time==1)
                        {
                            $value['active']=4;
                            $fecha1 = new DateTime("$today $time");//fecha inicial
                            $fecha2 = new DateTime("$today ".$appointment['starttime']);//fecha de cierre

                            $intervalo = $fecha1->diff($fecha2);

                            $timellegada=$intervalo->format('00:%i:%s');
                        }

                    }

                    if ($value['used']>0)
                    {
                        $value['active']=2;
                    }

                   echo ' <div class="col-md-4" style="padding: 10px;">
                    <a href="'.site_url('pos/viewtable/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']).'/'.insep_encode($value['postableid'])).'" style="width:200px; height:200px "
                    class="'.($value['active']==1?"btn green":($value['active']==2?"btn red":($value['active']==3?"btn yellow":($value['active']==4?"btn blue":"btn purple")))).'">'.$value['description'].' <br> '.($value['active']==1?"Available":($value['active']==2?"In Use":($value['active']==3?"Cleaning":($value['active']==4?"Reserved by <br>".$appointment['signer']." <br> For <br> ".$appointment['starttime']."<br> Time to Arrival <br> $timellegada ":"Unknown")))).' </a>
                    </div> ';
                }

              }
              else {
                echo '<h2>Does not have tables configured</h2>';
              }

            ?>


            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
</div>
</div>
