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
</div>
</div>
</div>