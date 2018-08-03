<link href="<?php echo base_url();?>user_asset/back/css/stylemembership.css" rel="stylesheet">
<div class="outter-wp">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li><a href="">My Property</a></li>
            <li class="active">Manage Membership</li>
        </ol>
    </div>
    <hr>
    <div class="profile-widget" style="background-color: white; padding: 0px; ">
        <?php 
            if(isset($Membership['plan_id']))
            {
                echo '<h4>Your Membership Plan is: '.$Membership['plan_name'].'(US$'.number_format($Membership['plan_price'], 2, '.', ',').'/'.$Membership['plan_types'].')</h4>';
                echo '<h4>Expires on: '.date('d/m/Y',strtotime($Membership['plan_to'])).' </h4>';
            }

        ?>
        <p>Info: To be able connect to channels, you have to select and buy a Membership Plan.</p>
        <p>You are always able to change the Membership plan, by selecting a different one.</p>
        <p>In case you wish to have a multi-property account, Please contact with your Account Manager.</p>
    </div>
<div style="text-align: center;">
    

    
       
       <?php
            $group='';

            $i=0;
            foreach ($ALLMembership as $value) {

                $i++;

                if ($group!=$value['Grouptype']) {


                     if ($group!='') {
                        echo '   </section> </div>';
                    }

                    echo '<div> <section id="pricePlans">
                     <h2><span class="label label-primary">'.$value['Grouptype'].'</span></h2>';

                   $group=$value['Grouptype'];
                    $i=1;

                }


               echo '<ul id="plans">  
                 <li class="plan">
                <ul class="planContainer " >
                        <li class="title">
                            <h4 class="bestPlanTitle">'.$value['plan_name'].'</h4></li>
                        <li class="price">
                            <p class="bestPlanPrice">$'.number_format($value['plan_price'], 2, '.', ',').'/'.$value['plan_types'].'</p>
                        </li>
                        <li>
                            <ul class="options">
                                <li>'.$value['number_of_hotels'].' Hotels</li>
                                <li> '.($value['number_of_channels']==99?'Unlimited':$value['number_of_channels']).' Channels</li>                               
                            </ul>
                        </li>
                        <li class="button"><a class="bestPlanButton" href="#">Choose</a></li>
                    </ul>
                </li>
                  </ul>  ';

                  if($i==3)
                  {
                    echo'<div class="clearfix"> </div>';
                    $i=0;
                  }
            }


        echo '   </section> </div>';
       ?>


    </div>

</div>
</div>
</div>