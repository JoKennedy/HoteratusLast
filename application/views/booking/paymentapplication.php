
 <center><h1><span class="label label-warning">Check Out</span></h1></center>
<div class="graph-form">

<div class="tab-main">
<div class="tab-inner">
<div id="tabs" class="tabs">
<div class="">
<nav>
<ul>
    <?php
        
        $card=array_search(5,array_column($paymentprocess, 'providerid'));
        $paypal=array_search(3,array_column($paymentprocess, 'providerid'));
        $baintree=array_search(2,array_column($paymentprocess, 'providerid'));

    ?>
<li><a onclick="showtab(1);" style=" <?=$card===false?'display:none':''; ?>" class="icon-shop tab"><img src="<?=base_url()?>user_asset\images\creditcard.jpg" width="200px" height="50px" /></a></li>
<li><a onclick="showtab(2);" style=" <?=$paypal===false?'display:none':''; ?>"  class="icon-cup"> <img src="<?=base_url()?>user_asset\images\paypal.jpg" width="200px" height="50px" /> </a></li>
<li><a onclick="showtab(3);" style=" <?=$baintree===false?'display:none':''; ?>"  class="icon-food"><img src="<?=base_url()?>user_asset\images\baintree.png" width="200px" height="50px" /></a></li>
</ul>
</nav>
<div class="content tab">

<section id="section-1" class="content-current sec">
   
    
<div class="col-md-6" style="float:left; <?=$card===false?'display:none':''; ?>">

         <div class="col-md-12 form-group1 " >
            <label class="control-label"><strong> <?=$this->lang->line('ccname')?></strong></label>
            <input autocomplete="false" required="true" style="background:white; color:black; width: 100%;"  Name="ccholder" id="ccholder">
        </div>

        <div class="col-md-12 form-group1 " >
            <label class="control-label"><strong> <?=$this->lang->line('ccnumber')?></strong></label>
            <input autocomplete="false" onkeypress="return justNumbers(event);" required="true" style="background:white; color:black; width: 100%;"  Name="ccnumber" id="ccnumber">
        </div>
        <div class="col-md-12 form-group1 " >
            <label class="control-label"><strong> <?=$this->lang->line('cccvv')?></strong></label>
            <input autocomplete="false" onkeypress="return justNumbers(event);" required="true" style="background:white; color:black; width: 100%;"  Name="cccvv" id="cccvv">
        </div>

        <div class="col-md-12 form-group1 form-last">
            <label style="padding:4px;" class="control-label controls">Expiration</label>
        </div>     
          <div class="col-md-5 form-group1 form-last">
             <select style="width: 50%; padding: 9px;" name="ccmonth" id="ccmonth">
                     <?php 
                    $curr_mn = date('m');
                    for($i=1; $i<=12; $i++) { ?>
                    <option value="<?php echo $i;?>" <?php if($i==$curr_mn) {  ?> selected="selected" <?php } ?>><?php echo $i;?></option>
                    <?php } ?>
            </select>
            
        </div> 
          <div class="col-md-7 form-group1 form-last">
            <select style="width: 50%; padding: 9px;" name="ccyear" id="ccyear">
            <?php 
                $curr_year = date('Y');
                $end_year = $curr_year+20;
                for($i=$curr_year; $i<=$end_year; $i++) { ?>
                <option value="<?php echo $i;?>" <?php if($curr_year==$i){?> selected="selected" <?php } ?>><?php echo $i;?></option>
                <?php } 
            ?>
            </select>
        </div>                
        <br>
        <br>            
     <div class="buttons-ui">
        <a onclick="processpayment();" class="btn green"><i class="fa fa-credit-card"></i> <?=$this->lang->line('process')?></a>
    </div>
             
 
    
</div>
<div  style="float:right;">
      
    <center><h3><span class="label label-default"><?=$this->lang->line('inforecap')?></span></h3></center>
    <hr>
    <table>
        <tbody>
            <tr style="padding: 2px;">
                <td><strong><?=$this->lang->line('checkin')?>:</strong></td>
                <td style="text-align: right;"><?=date('m/d/Y',strtotime($date1))?></td>
            </tr>
            <tr>
                <td><strong><?=$this->lang->line('checkout')?>:</strong></td>
                <td style="text-align: right;"><?=date('m/d/Y',strtotime($date2))?></span></td>
            </tr>
        </tbody>
    </table>
    <hr size="20">
    <h5><strong ><?=$this->lang->line('charges')?></strong></h5>
    <table>
        <tbody>
            <tr style="padding-bottom: 5px;">
                <td><strong><?=$numroom.' '.($numroom==1?$this->lang->line('room'):$this->lang->line('rooms')).' x '.$numnight.' '.($numnight==1?$this->lang->line('night'):$this->lang->line('nights'))?> </td>
                <td style="text-align: right;"> &nbsp;<?=$currency.number_format(($totalstay*$numroom),2,'.',',')?></td>
            </tr>
        
        <?php
            if(count($taxes)>0)
            {   $totaltaxes=0;
                
                foreach ($taxes as $tax) {
                    $taxamount=($totalstay*$numroom)*($tax['taxrate']/100);
                    echo ' <tr style="padding-bottom: 5px;">
                            <td><strong>'.$tax['name'].' </td>
                            <td style="text-align: right;"> &nbsp;'.$currency.number_format($taxamount,2,'.',',').'</td>
                        </tr>';
                     $totaltaxes+=($tax['includedprice']==0?$taxamount:0);   
                }
                
            }
        ?>
        <div class="clearfix"></div>
        </tbody>
    </table>

    <div style="text-align: center;">

        <h2><?=$this->lang->line('duenow')?></h2>
        <center><h4><span class="label label-default"> <?=$currency.number_format(($totalstay*$numroom)+ $totaltaxes,2,'.',',')?></span></h4></center>
     
     
    </div>
                               
    
</div>
<div class="clearfix"></div>

</section>
<section id="section-2" class="sec">

<div style="float:left;">
    
</div>
<div style="float:right;">
     
    <center><h3><span class="label label-default"><?=$this->lang->line('inforecap')?></span></h3></center>
    <hr>
    <table>
        <tbody>
            <tr style="padding: 2px;">
                <td><strong><?=$this->lang->line('checkin')?>:</strong></td>
                <td style="text-align: right;"><?=date('m/d/Y',strtotime($date1))?></td>
            </tr>
            <tr>
                <td><strong><?=$this->lang->line('checkout')?>:</strong></td>
                <td style="text-align: right;"><?=date('m/d/Y',strtotime($date2))?></span></td>
            </tr>
        </tbody>
    </table>
    <hr size="20">
    <h5><strong ><?=$this->lang->line('charges')?></strong></h5>
    <table>
        <tbody>
            <tr style="padding-bottom: 5px;">
                <td><strong><?=$numroom.' '.($numroom==1?$this->lang->line('room'):$this->lang->line('rooms')).' x '.$numnight.' '.($numnight==1?$this->lang->line('night'):$this->lang->line('nights'))?> </td>
                <td style="text-align: right;"> &nbsp;<?=$currency.number_format(($totalstay*$numroom),2,'.',',')?></td>
            </tr>
        
        <?php
            if(count($taxes)>0)
            {   $totaltaxes=0;
                
                foreach ($taxes as $tax) {
                    $taxamount=($totalstay*$numroom)*($tax['taxrate']/100);
                    echo ' <tr style="padding-bottom: 5px;">
                            <td><strong>'.$tax['name'].' </td>
                            <td style="text-align: right;"> &nbsp;'.$currency.number_format($taxamount,2,'.',',').'</td>
                        </tr>';
                     $totaltaxes+=($tax['includedprice']==0?$taxamount:0);   
                }
                
            }
        ?>
        <div class="clearfix"></div>
        </tbody>
    </table>

    <div style="text-align: center;">

        <h2><?=$this->lang->line('duenow')?></h2>
        <center><h4><span class="label label-default"> <?=$currency.number_format(($totalstay*$numroom)+ $totaltaxes,2,'.',',')?></span></h4></center>
     
     
    </div>         
    
</div>
</section>
<section id="section-3" class="sec">
    <div style="float:left;">
    
</div>
<div style="float:right;">
     
   <center><h3><span class="label label-default"><?=$this->lang->line('inforecap')?></span></h3></center>
    <hr>
    <table>
        <tbody>
            <tr style="padding: 2px;">
                <td><strong><?=$this->lang->line('checkin')?>:</strong></td>
                <td style="text-align: right;"><?=date('m/d/Y',strtotime($date1))?></td>
            </tr>
            <tr>
                <td><strong><?=$this->lang->line('checkout')?>:</strong></td>
                <td style="text-align: right;"><?=date('m/d/Y',strtotime($date2))?></span></td>
            </tr>
        </tbody>
    </table>
    <hr size="20">
    <h5><strong ><?=$this->lang->line('charges')?></strong></h5>
    <table>
        <tbody>
            <tr style="padding-bottom: 5px;">
                <td><strong><?=$numroom.' '.($numroom==1?$this->lang->line('room'):$this->lang->line('rooms')).' x '.$numnight.' '.($numnight==1?$this->lang->line('night'):$this->lang->line('nights'))?> </td>
                <td style="text-align: right;"> &nbsp;<?=$currency.number_format(($totalstay*$numroom),2,'.',',')?></td>
            </tr>
        
        <?php
            if(count($taxes)>0)
            {   $totaltaxes=0;
                
                foreach ($taxes as $tax) {
                    $taxamount=($totalstay*$numroom)*($tax['taxrate']/100);
                    echo ' <tr style="padding-bottom: 5px;">
                            <td><strong>'.$tax['name'].' </td>
                            <td style="text-align: right;"> &nbsp;'.$currency.number_format($taxamount,2,'.',',').'</td>
                        </tr>';
                     $totaltaxes+=($tax['includedprice']==0?$taxamount:0);   
                }
                
            }
        ?>
        <div class="clearfix"></div>
        </tbody>
    </table>
    
    <div style="text-align: center;">

        <h2><?=$this->lang->line('duenow')?></h2>
        <center><h4><span class="label label-default"> <?=$currency.number_format(($totalstay*$numroom)+ $totaltaxes,2,'.',',')?></span></h4></center>
     
     
    </div>
                               
    
</div>

</section>

</div>
<!-- /content -->
</div>
<!-- /tabs -->
</div>
</div>
</div>

</div>
<script type="text/javascript">
    
    function showtab(id) {
    $(".sec").removeClass("content-current");
    $("#section-" + id).addClass("content-current");
    }
    
</script>