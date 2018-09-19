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
    <div class="col-md-12">
        <div class="col-md-3 form-group1">
            <label class="control-label">
                Turns
            </label>
            <select onchange="changeturn(this.value)" style="width: 100%; padding: 9px; " id="turnid" name="turnid">
                <?php

                if(count($AllTurns)>0 && $Posinfo['postypeID']==1  )
                {
                    echo '<option value="-1">Select a Turns</option>'; 
                    echo '<option value="0" '.(isset($Turnuser['turnid']) && $Turnuser['turnid']==0?'selected':'').'>All Turns</option>'; 
                    foreach ($AllTurns as  $value) {
                        echo '<option value="'.$value['posturnid'].'" '.(isset($Turnuser['turnid']) && $Turnuser['turnid']==$value['posturnid']?'selected':'').'>'.$value['name'].'</option>';
                    }
                }
                else
                {
                    echo '<option value="0">there are no Turns created</option>'; 
                }

                ?>
            </select>
        </div>
    </div>
    
    <div class="graph-form">
        <h4>All <?=($Posinfo['postypeID']==1?'Tables':'Treatment Room')?></h4>
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
                        $time = date("H:i:s");
                        $today=date("Y-m-d");
                        $appointment=$this->db->query("Select  * from mypostablereservation where mypostableid = ".$value['postableid']." and datetimereservation='$today' and starttime1>='$time' order by starttime1 asc Limit 1")->row_array();

                    if(count($appointment)>0)
                    {
                            
                            $fecha1 = new DateTime("$today $time");//fecha inicial
                            $fecha2 = new DateTime("$today ".$appointment['starttime']);//fecha de cierre

                            $intervalo = $fecha1->diff($fecha2);

                            $timellegada=$intervalo->format('%h:%i:%s');
                            if($intervalo->format('%h')<2)
                            {
                                
                                $value['active']=4;
                            }
                    }
                            
                            


                    }

                    if ($value['used']>0)
                    {
                        $value['active']=2;
                    }

                   echo ' <div class="col-md-4" style="padding: 10px;">
                    <a onclick=" showWait('."Opening <br> Please Wait".')" href="'.site_url('pos/viewtable/'.secure($Posinfo['hotelId']).'/'.insep_encode($Posinfo['myposId']).'/'.insep_encode($value['postableid'])).'" style="width:200px; height:200px "
                    class="'.($value['active']==1?"btn green":($value['active']==2?"btn red":($value['active']==3?"btn yellow":($value['active']==4?"btn blue":"btn purple")))).'">'.$value['description'].' <br> '.($value['active']==1?"Available":($value['active']==2?"In Use":($value['active']==3?"Cleaning":($value['active']==4?"Reserved by <br>".$appointment['signer']." <br> For <br> ".$appointment['starttime1']."<br> Time to Arrival <br> $timellegada ":"Unknown")))).' </a>
                    </div> ';
                }

              }
              else {
                echo '<h2>Does not have '.($Posinfo['postypeID']==1?'Tables':'Treatment Room').' configured</h2>';
              }

            ?>


            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
</div>
</div>
<script type="text/javascript">
    var posid="<?=$Posinfo['myposId']?>"
    function changeturn(id)
    {


        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo lang_url(); ?>pos/saveposturnuser",
            data: {'id':id,'posid':posid},
            beforeSend: function() {
                showWait();
                setTimeout(function() { unShowWait(); }, 10000);
            },
            success: function(msg) {
                unShowWait();
                if (msg["success"]) {
                    swal({
                        title: "Success",
                        text: "Turn Changed!!",
                        icon: "success",
                        button: "Ok!",
                    });
                } else {

                    swal({
                        title: "upps, Sorry",
                        text: msg["message"],
                        icon: "warning",
                        button: "Ok!",
                    });
                }

            }
        });
    }

</script>