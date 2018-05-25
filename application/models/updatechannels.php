<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
class updatechannels extends CI_Model
{


 function updateallchannels($channel_id,$datepicker_full_start,$datepicker_full_end)
    {

          return;  
        if(!isset($channel_id) || isset($channel_id)=="" )
        {
            print '<script language="JavaScript">';
            print 'alert("Not Channels Selected");';
            print '</script>';
            return;
        }


        $start = $datepicker_full_start;
        $end   = $datepicker_full_end;
		$datepicker_full_start = date('Y-m-d',strtotime(str_replace('/','-',$datepicker_full_start)));
        $datepicker_full_end = date('Y-m-d',strtotime(str_replace('/','-',$datepicker_full_end)));
		$period = $this->getDateForSpecificDayBetweenDates($datepicker_full_start,$datepicker_full_end,"1,2,3,4,5,6,7");

        $datetime1 = new DateTime($datepicker_full_start);
        $datetime2 = new DateTime($datepicker_full_end);
        $interval = $datetime1->diff($datetime2);
        $dias = $interval->format('%R%a');
        $DespegarErrors='';
        $BookingErrors='';
        $ExpediaErrors='';
        $inicialdate = $datepicker_full_start;
            $dias=$dias*1;

            while ($dias > 0) {

                $FinalDate = strtotime (($dias>=90?'+90 day':'+'.$dias.' day') , strtotime ( $inicialdate) ) ;
                $FinalDate  = date ( 'Y-m-d' , $FinalDate );



                        foreach ($channel_id as $Channelid) 
                        {



                            if($Channelid==36){
                                $this->load->model("despegar_model");
                                $DespegarErrors .=$this->despegar_model->SincroCalender($inicialdate,$FinalDate);


                            }
                            elseif($Channelid==1){
                                $this->load->model("expedia_model");
                                $ExpediaErrors .=$this->expedia_model->SincroCalender($inicialdate,$FinalDate);

                            }
                            elseif ($Channelid==2) {

                                $this->load->model("booking_model");
                                $BookingErrors .=$this->booking_model->SincroCalender($inicialdate,$FinalDate);
                            }
			                elseif ($Channelid==9) {

                                $this->load->model("airbnb_model");
                                $AirbnbErrors .=$this->airbnb_model->SincroCalender($inicialdate,$FinalDate);
                            }
                        }




                $inicialdate = strtotime ('+1 day' , strtotime ( $FinalDate) ) ;
                $inicialdate  = date ( 'Y-m-d' , $inicialdate  );
                $dias =$dias-90;
            }

            foreach ($channel_id as $Channelid) 
            {
                
      
                if($Channelid==36){
                    print '<script language="JavaScript">';
                    print 'alert("'.$DespegarErrors.'");';
                    print '</script>';
                }
                elseif($Channelid==1){
                    print '<script language="JavaScript">';
                    print 'alert("'.$ExpediaErrors.'");';
                    print '</script>';

                }
                elseif ($Channelid==2) {

                    print '<script language="JavaScript">';
                    print 'alert("'.$BookingErrors.'");';
                    print '</script>';
                }
                elseif ($Channelid==9) {

                    print '<script language="JavaScript">';
                    print 'alert("'.$AirbnbErrors.'");';
                    print '</script>';
                }
            }

return;
	}

    function getDateForSpecificDayBetweenDates($start, $end, $weekday)
    {
        if($weekday != ""){
            $weekdays="Day,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday";
            $arr_weekdays=explode(",", $weekdays);
            $string = "";
            $arr_weekdays_day = explode(",", $weekday);
            $i = 1;
            foreach($arr_weekdays_day as $weekdays)
            {
                $weekday = @$arr_weekdays[$weekdays];
                if(!$weekday)
                $this->inventory_model->store_error(current_user_type(),"0","2",'Invalid WeekDay','Bulk Update',date('m/d/Y h:i:s a', time()));
                $starts= strtotime("+0 day", strtotime($start) );
                $ends= strtotime($end);
                //$dateArr = array();
                $friday = strtotime($weekday, $starts);
                while($friday <= $ends)
                {
                    $dateArr[] = date("d/m/Y", $friday);
                    $date = date("Y-m-d", $friday);
                    $string .= "value".$i."='".$date."' ";
                    $friday = strtotime("+1 weeks", $friday);
                    $i++;
                }
                //$dateArr[] = date("Y-m-d", $friday);
            }
            //print_r($dateArr);
            return $dateArr;
        }
    }


}

?>    