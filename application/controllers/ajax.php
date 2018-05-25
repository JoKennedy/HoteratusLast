 <?php
ini_set('memory_limit', '-1');
ini_set('display_errors', '1');
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends Front_Controller
{
    public $resvArr = array();

    function is_login()
    {
        if (!user_id())
            redirect(base_url());
        return;
    }
    
    function is_admin()
    {
        if (!admin_id())
            redirect(base_url());
        return;
    }
    /* Main Calendar Functionality Start */
    function showStopSaleCalendar()
    {
        if (!IS_AJAX) {
            $this->load->view('admin/404');
        } else {
            extract($this->input->post());
            $startDate      = DateTime::createFromFormat("d/m/Y", $cal_start);
            $cal_end        = date('d/m/Y', strtotime('+1 day', strtotime(date('Y-m-d', strtotime(str_replace("/", "-", $cal_end))))));
            $endDate        = DateTime::createFromFormat("d/m/Y", $cal_end);
            $periodInterval = new DateInterval("P1D");
            //$endDate->add( $periodInterval );
            $period         = new DatePeriod($startDate, $periodInterval, $endDate);
            $da             = array();
            foreach ($period as $show_dates) {
                $da[] = $show_dates->format("d/m/Y");
            }
            $this->db->order_by('property_id', 'desc');
            $all_room = get_data(TBL_PROPERTY, array(
                'status' => 'Active',
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'droc' => '1'
            ), 'property_id,non_refund,member_count,property_name,pricing_type')->result_array();
            if (count($all_room) != 0) {
                foreach ($all_room as $room) {
                    extract($room);
?>
                   <tr class="p-b-o ss_main" id="<?php
                    echo $property_id;
?>">
                    <td bgcolor="#a6a6a6">SS</td>
                    <?php
                    foreach ($period as $date) {
                        $i     = $date->format("d");
                        $ss_da = $date->format("d/m/Y");
                        if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                            $color = '#d9d9d9';
                        } else {
                            $color = '#FFFFFF';
                        }
                        if (in_array($ss_da, $da)) {
                            $single = get_data(TBL_UPDATE, array(
                                'individual_channel_id' => '0',
                                'owner_id' => current_user_type(),
                                'hotel_id' => hotel_id(),
                                'room_id' => $property_id,
                                'separate_date' => $ss_da
                            ), 'availability,price,stop_sell')->row();
                            if (count($single) != '0') {
                                if ($single->availability == 0) {
                                    $color = '#FF0000';
                                } elseif ($single->stop_sell == 1) {
                                    $color = '#CC99FF';
                                }
?>
                               <td bgcolor="<?php //echo $color
?>">
                                <?php
                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> 
                                        <input id="<?php
                                    echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_M';
?>" type="checkbox"  value="1" <?php
                                    if ($single->stop_sell == '1') {
?> checked="checked" <?php
                                    }
?> name="room[<?php
                                    echo $property_id;
?>][stop_sell][<?php
                                    echo $ss_da;
?>]" class="update_stop_sell_main">
                                        <?php
                                } else {
?>
                                      <input id="<?php
                                    echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_M';
?>" type="checkbox"  name="room[<?php
                                    echo $property_id;
?>][stop_sell][<?php
                                    echo $ss_da;
?>]" value="1" <?php
                                    if ($single->stop_sell == '1') {
?> checked="checked"<?php
                                    }
?> disabled="disabled">
                                        <?php
                                }
?>
                               </td>
                    <?php
                            } else {
?>
                           <td bgcolor="<?php //echo $color
?>">
                            <?php
                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d')) {
?> 
                                    <input id="<?php
                                    echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_M';
?>" type="checkbox"  name="room[<?php
                                    echo $property_id;
?>][stop_sell][<?php
                                    echo $ss_da;
?>]" value="1" class="update_stop_sell_main">
                                    <?php
                                } else {
?>
                                   N/A
                                    <?php
                                }
?>
                               </td>
                    <?php
                            }
                        } else {
?> 
                    <td bgcolor="<?php //echo $color
?>">
                    <?php
                            if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d')) {
?> 
                            <input id="<?php
                                echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_M';
?>" type="checkbox"  name="room[<?php
                                echo $property_id;
?>][stop_sell][<?php
                                echo $ss_da;
?>]" value="1" class="update_stop_sell_main">
                            <?php
                            } else {
?>
                           N/A
                            <?php
                            }
?>
                           
                        </td>
                    <?php
                        }
                    }
?>
                   </tr>
                    <?php
                }
            } else {
                echo "No Rooms Available";
            }
        }
    }
    
    function showCTACalendar()
    {
        if (!IS_AJAX) {
            $this->load->view('admin/404');
        } else {
            extract($this->input->post());
            $startDate      = DateTime::createFromFormat("d/m/Y", $cal_start);
            $cal_end        = date('d/m/Y', strtotime('+1 day', strtotime(date('Y-m-d', strtotime(str_replace("/", "-", $cal_end))))));
            $endDate        = DateTime::createFromFormat("d/m/Y", $cal_end);
            $periodInterval = new DateInterval("P1D");
            //$endDate->add( $periodInterval );
            $period         = new DatePeriod($startDate, $periodInterval, $endDate);
            $da             = array();
            foreach ($period as $show_dates) {
                $da[] = $show_dates->format("d/m/Y");
            }
            $this->db->order_by('property_id', 'desc');
            $all_room = get_data(TBL_PROPERTY, array(
                'status' => 'Active',
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'droc' => '1'
            ), 'property_id,non_refund,member_count,property_name,pricing_type')->result_array();
            if (count($all_room) != 0) {
                foreach ($all_room as $room) {
                    extract($room);
?>
                   <tr class="p-b-o ss_main" id="<?php
                    echo $property_id;
?>">
                    <td bgcolor="#a6a6a6">CTA</td>
                    <?php
                    foreach ($period as $date) {
                        $i     = $date->format("d");
                        $ss_da = $date->format("d/m/Y");
                        if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                            $color = '#d9d9d9';
                        } else {
                            $color = '#FFFFFF';
                        }
                        if (in_array($ss_da, $da)) {
                            $single = get_data(TBL_UPDATE, array(
                                'individual_channel_id' => '0',
                                'owner_id' => current_user_type(),
                                'hotel_id' => hotel_id(),
                                'room_id' => $property_id,
                                'separate_date' => $ss_da
                            ), 'cta')->row();
                            if (count($single) != '0') {
                                if ($single->cta == 0) {
                                    $color = '#FF0000';
                                } elseif ($single->cta == 1) {
                                    $color = '#f9c372';
                                }
?>
                               <td bgcolor="<?php //echo $color
?>">
                                <?php
                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> 
                                        <input id="<?php
                                    echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_M';
?>" type="checkbox"  value="1" <?php
                                    if ($single->cta == '1') {
?> checked="checked" <?php
                                    }
?> name="room[<?php
                                    echo $property_id;
?>][cta][<?php
                                    echo $ss_da;
?>]" class="update_cta_main">
                                        <?php
                                } else {
?>
                                      <input id="<?php
                                    echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_M';
?>" type="checkbox"  name="room[<?php
                                    echo $property_id;
?>][cta][<?php
                                    echo $ss_da;
?>]" value="1" <?php
                                    if ($single->cta == '1') {
?> checked="checked"<?php
                                    }
?> disabled="disabled">
                                        <?php
                                }
?>
                               </td>
                    <?php
                            } else {
?>
                           <td bgcolor="<?php //echo $color
?>">
                            <?php
                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d')) {
?> 
                                    <input id="<?php
                                    echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_M';
?>" type="checkbox"  name="room[<?php
                                    echo $property_id;
?>][cta][<?php
                                    echo $ss_da;
?>]" value="1" class="update_cta_main">
                                    <?php
                                } else {
?>
                                   N/A
                                    <?php
                                }
?>
                               </td>
                    <?php
                            }
                        } else {
?> 
                    <td bgcolor="<?php //echo $color
?>">
                    <?php
                            if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d')) {
?> 
                            <input id="<?php
                                echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_M';
?>" type="checkbox"  name="room[<?php
                                echo $property_id;
?>][cta][<?php
                                echo $ss_da;
?>]" value="1" class="update_cta_main">
                            <?php
                            } else {
?>
                           N/A
                            <?php
                            }
?>
                           
                        </td>
                    <?php
                        }
                    }
?>
                   </tr>
                    <?php
                }
            } else {
                echo "No Rooms Available";
            }
        }
    }
    
    function showCTDCalendar()
    {
        if (!IS_AJAX) {
            $this->load->view('admin/404');
        } else {
            extract($this->input->post());
            $startDate      = DateTime::createFromFormat("d/m/Y", $cal_start);
            $cal_end        = date('d/m/Y', strtotime('+1 day', strtotime(date('Y-m-d', strtotime(str_replace("/", "-", $cal_end))))));
            $endDate        = DateTime::createFromFormat("d/m/Y", $cal_end);
            $periodInterval = new DateInterval("P1D");
            //$endDate->add( $periodInterval );
            $period         = new DatePeriod($startDate, $periodInterval, $endDate);
            $da             = array();
            foreach ($period as $show_dates) {
                $da[] = $show_dates->format("d/m/Y");
            }
            $this->db->order_by('property_id', 'desc');
            $all_room = get_data(TBL_PROPERTY, array(
                'status' => 'Active',
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'droc' => '1'
            ), 'property_id,non_refund,member_count,property_name,pricing_type')->result_array();
            if (count($all_room) != 0) {
                foreach ($all_room as $room) {
                    extract($room);
?>
                   <tr class="p-b-o ss_main" id="<?php
                    echo $property_id;
?>">
                    <td bgcolor="#a6a6a6">CTD</td>
                    <?php
                    foreach ($period as $date) {
                        $i     = $date->format("d");
                        $ss_da = $date->format("d/m/Y");
                        if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                            $color = '#d9d9d9';
                        } else {
                            $color = '#FFFFFF';
                        }
                        if (in_array($ss_da, $da)) {
                            $single = get_data(TBL_UPDATE, array(
                                'individual_channel_id' => '0',
                                'owner_id' => current_user_type(),
                                'hotel_id' => hotel_id(),
                                'room_id' => $property_id,
                                'separate_date' => $ss_da
                            ), 'ctd')->row();
                            if (count($single) != '0') {
                                if ($single->ctd == 0) {
                                    $color = '#FF0000';
                                } elseif ($single->ctd == 1) {
                                    $color = '#93c6ff';
                                }
?>
                               <td bgcolor="<?php //echo $color
?>">
                                <?php
                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> 
                                        <input id="<?php
                                    echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_M';
?>" type="checkbox"  value="1" <?php
                                    if ($single->ctd == '1') {
?> checked="checked" <?php
                                    }
?> name="room[<?php
                                    echo $property_id;
?>][ctd][<?php
                                    echo $ss_da;
?>]" class="update_ctd_main">
                                        <?php
                                } else {
?>
                                      <input id="<?php
                                    echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_M';
?>" type="checkbox"  name="room[<?php
                                    echo $property_id;
?>][ctd][<?php
                                    echo $ss_da;
?>]" value="1" <?php
                                    if ($single->ctd == '1') {
?> checked="checked"<?php
                                    }
?> disabled="disabled">
                                        <?php
                                }
?>
                               </td>
                    <?php
                            } else {
?>
                           <td bgcolor="<?php //echo $color
?>">
                            <?php
                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d')) {
?> 
                                    <input id="<?php
                                    echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_M';
?>" type="checkbox"  name="room[<?php
                                    echo $property_id;
?>][ctd][<?php
                                    echo $ss_da;
?>]" value="1" class="update_ctd_main">
                                    <?php
                                } else {
?>
                                   N/A
                                    <?php
                                }
?>
                               </td>
                    <?php
                            }
                        } else {
?> 
                    <td bgcolor="<?php //echo $color
?>">
                    <?php
                            if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d')) {
?> 
                            <input id="<?php
                                echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_M';
?>" type="checkbox"  name="room[<?php
                                echo $property_id;
?>][ctd][<?php
                                echo $ss_da;
?>]" value="1" class="update_ctd_main">
                            <?php
                            } else {
?>
                           N/A
                            <?php
                            }
?>
                           
                        </td>
                    <?php
                        }
                    }
?>
                   </tr>
                    <?php
                }
            } else {
                echo "No Rooms Available";
            }
        }
    }
    
    function showRatesGuestsCalendar()
    {
        if (!IS_AJAX) {
            $this->load->view('admin/404');
        } else {
            extract($this->input->post());
            $startDate      = DateTime::createFromFormat("d/m/Y", $cal_start);
            $cal_end        = date('d/m/Y', strtotime('+1 day', strtotime(date('Y-m-d', strtotime(str_replace("/", "-", $cal_end))))));
            $endDate        = DateTime::createFromFormat("d/m/Y", $cal_end);
            $periodInterval = new DateInterval("P1D");
            //$endDate->add( $periodInterval );
            $period         = new DatePeriod($startDate, $periodInterval, $endDate);
            $da             = array();
            foreach ($period as $show_dates) {
                $da[] = $show_dates->format("d/m/Y");
            }
            $all_room = get_data(TBL_PROPERTY, array(
                'property_id' => $property_id,
                'status' => 'Active',
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'droc' => '1'
            ), 'property_id,non_refund,member_count,property_name,pricing_type')->result_array();
            if (count($all_room) != 0) {
                foreach ($all_room as $room) {
                    extract($room);
                    
                    if ($non_refund == 1) {
                        $members = $member_count + $member_count;
                    } else {
                        $members = $member_count;
                    }
                    $all_rate_types = get_data(RATE_TYPES, array(
                        'room_id' => $property_id,
                        'user_id' => current_user_type(),
                        'hotel_id' => hotel_id(),
                        'droc' => '1'
                    ))->result_array();
                    if ($pricing_type == 2) {
                        if ($non_refund == 1) {
                            for ($k = 1; $k <= $members; $k++) {
                                
                                if ($member_count < $members) {
                                    if ($k % 2 == 0) {
                                        $name  = 'Guest Non refundable';
                                        $v     = ceil($k / 2);
                                        $refun = '2';
                                    } else {
                                        $name  = 'Guest';
                                        $v     = ceil($k / 2);
                                        $refun = '1';
                                    }
                                } else {
                                    $name  = 'Guest';
                                    $v     = $k;
                                    $refun = '1';
                                }
?>
                               <tr class="p-b-o contents_<?php
                                echo $property_id;
?>" style="display:none;">
                                <td class="ha2"><span class="pull-left"><?php
                                echo ucfirst($property_name);
?> - <?php
                                echo $v . ' ' . $name;
?></span></td>
                                <td bgcolor="#a6a6a6" class="w_bdr">P</td>
                                <?php
                                foreach ($period as $date) {
                                    $j     = $date->format("d");
                                    $ss_da = $date->format("d/m/Y");
                                    if ($refun == '1') {
                                        $sub_rates = get_data(RESERV, array(
                                            'individual_channel_id' => '0',
                                            'owner_id' => current_user_type(),
                                            'hotel_id' => hotel_id(),
                                            'room_id' => $property_id,
                                            'separate_date' => $ss_da,
                                            'guest_count' => $v,
                                            'refun_type' => $refun
                                        ))->row_array();
                                        if (count($sub_rates) != 0) {
                                            $sub_rate = $sub_rates['refund_amount'];
                                        }
                                    } elseif ($refun == '2') {
                                        $sub_rates = get_data(RESERV, array(
                                            'individual_channel_id' => '0',
                                            'owner_id' => current_user_type(),
                                            'hotel_id' => hotel_id(),
                                            'room_id' => $property_id,
                                            'separate_date' => $ss_da,
                                            'guest_count' => $v,
                                            'refun_type' => $refun
                                        ))->row_array();
                                        if (count($sub_rates) != 0) {
                                            $sub_rate = $sub_rates['non_refund_amount'];
                                        }
                                    }
                                    if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                        $color = '#669933';
                                    } else {
                                        $color = '#D9E4C3';
                                    }
                                    if (in_array($ss_da, $da)) {
                                        if (count($sub_rates) != '0') {
                                            if ($sub_rate != 0) {
?>
                               <td bgcolor="<?php
                                                echo $color;
?>" class="w_bdr">
                                <?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                                    echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun;
?>" data-url="<?php
                                                    echo lang_url();
?>inventory/inline_edit_guest" data-title="Change Price"><?php
                                                    echo floatval($sub_rate);
?></a> <?php
                                                } else {
?> <?php
                                                    echo floatval($sub_rate);
?> <?php
                                                }
?></td><?php
                                            } else {
?>
                               <td bgcolor="<?php
                                                echo $color;
?>" class="w_bdr">
                                <?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                                    echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun;
?>" data-url="<?php
                                                    echo lang_url();
?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php
                                                } else {
?> N/A <?php
                                                }
?></td>
                                 <?php
                                            }
                                        } else {
?>
                               <td bgcolor="<?php
                                            echo $color;
?>" class="w_bdr">
                                <?php
                                            if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                                echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun;
?>" data-url="<?php
                                                echo lang_url();
?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php
                                            } else {
?> N/A <?php
                                            }
?></td>
                                <?php
                                        }
                                    } else {
?> 
                                <td bgcolor="<?php
                                        echo $color;
?>" class="w_bdr">
                                <?php
                                        if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                            echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun;
?>" data-url="<?php
                                            echo lang_url();
?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php
                                        } else {
?> N/A <?php
                                        }
?></td>
                                <?php
                                    }
                                }
?>
                               </tr>
                            <?php
                            }
                        } else {
                            for ($k = 1; $k <= $members; $k++) {
                                if ($member_count < $members) {
                                    if ($k % 2 == 0) {
                                        $name  = 'Guest Non refundable';
                                        $v     = ceil($k / 2);
                                        $refun = 2;
                                    } else {
                                        $name  = 'Guest';
                                        $v     = ceil($k / 2);
                                        $refun = 1;
                                    }
                                } else {
                                    $name  = 'Guest';
                                    $v     = $k;
                                    $refun = 1;
                                    
                                }
?>
                               <tr class="p-b-o contents_<?php
                                echo $property_id;
?>" style="display: none;">
                                <td class="ha2"><span class="pull-left"><?php
                                echo ucfirst($property_name);
?> - <?php
                                echo $v . ' ' . $name;
?></span></td>
                                <td bgcolor="#a6a6a6" class="w_bdr">P</td>
                                <?php
                                foreach ($period as $date) {
                                    $j     = $date->format("d");
                                    $ss_da = $date->format("d/m/Y");
                                    if ($refun == '1') {
                                        $sub_rates = get_data(RESERV, array(
                                            'individual_channel_id' => '0',
                                            'owner_id' => current_user_type(),
                                            'hotel_id' => hotel_id(),
                                            'room_id' => $property_id,
                                            'separate_date' => $ss_da,
                                            'guest_count' => $v,
                                            'refun_type' => $refun
                                        ))->row_array();
                                        if (count($sub_rates) != 0) {
                                            $sub_rate = $sub_rates['refund_amount'];
                                        }
                                    } elseif ($refun == '2') {
                                        $sub_rates = get_data(RESERV, array(
                                            'individual_channel_id' => '0',
                                            'owner_id' => current_user_type(),
                                            'hotel_id' => hotel_id(),
                                            'room_id' => $property_id,
                                            'separate_date' => $ss_da,
                                            'guest_count' => $v,
                                            'refun_type' => $refun
                                        ))->row_array();
                                        if (count($sub_rates) != 0) {
                                            $sub_rate = $sub_rates['non_refund_amount'];
                                        }
                                    }
                                    if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                        $color = '#669933';
                                    } else {
                                        $color = '#D9E4C3';
                                    }
                                    if (in_array($ss_da, $da)) {
                                        if (count($sub_rates) != '0') {
                                            if ($sub_rate != 0) {
?>
                               <td bgcolor="<?php
                                                echo $color;
?>" class="w_bdr">
                                <?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                                    echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun;
?>" data-url="<?php
                                                    echo lang_url();
?>inventory/inline_edit_guest" data-title="Change Price"><?php
                                                    echo floatval($sub_rate);
?></a><?php
                                                } else {
?> <?php
                                                    echo floatval($sub_rate);
?> <?php
                                                }
?></td><?php
                                            } else {
?>
                               <td bgcolor="<?php
                                                echo $color;
?>" class="w_bdr">
                                <?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                                    echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun;
?>" data-url="<?php
                                                    echo lang_url();
?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php
                                                } else {
?> N/A <?php
                                                }
?></td>
                                 <?php
                                            }
                                        } else {
?>
                               <td bgcolor="<?php
                                            echo $color;
?>" class="w_bdr">
                                <?php
                                            if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                                echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun;
?>" data-url="<?php
                                                echo lang_url();
?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php
                                            } else {
?> N/A <?php
                                            }
?></td>
                                <?php
                                        }
                                    } else {
?> 
                                <td bgcolor="<?php
                                        echo $color;
?>" class="w_bdr">
                                <?php
                                        if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                            echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun;
?>" data-url="<?php
                                            echo lang_url();
?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php
                                        } else {
?> N/A <?php
                                        }
?></td>
                                <?php
                                    }
                                }
?>  
                                </tr>      
                                <?php
                            }
                        }
                    } else if ($pricing_type == 1 && $non_refund == 1) {
                        $v     = 1;
                        $refun = 2;
?>
                       <tr class="p-b-o contents_<?php
                        echo $property_id;
?>" style="display: none;">
                        <td class="ha2"><span class="pull-left"><?php
                        echo ucfirst($property_name);
?> - Non refundable</span></td>
                        <td bgcolor="#a6a6a6" class="w_bdr">P</td>
                        <?php
                        foreach ($period as $date) {
                            $j     = $date->format("d");
                            $ss_da = $date->format("d/m/Y");
                            if ($refun == '1') {
                                $sub_rates = get_data(RESERV, array(
                                    'individual_channel_id' => '0',
                                    'owner_id' => current_user_type(),
                                    'hotel_id' => hotel_id(),
                                    'room_id' => $property_id,
                                    'separate_date' => $ss_da,
                                    'guest_count' => $v,
                                    'refun_type' => $refun
                                ))->row_array();
                                if (count($sub_rates) != 0) {
                                    $sub_rate = $sub_rates['refund_amount'];
                                }
                            } elseif ($refun == '2') {
                                $sub_rates = get_data(RESERV, array(
                                    'individual_channel_id' => '0',
                                    'owner_id' => current_user_type(),
                                    'hotel_id' => hotel_id(),
                                    'room_id' => $property_id,
                                    'separate_date' => $ss_da,
                                    'guest_count' => $v,
                                    'refun_type' => $refun
                                ))->row_array();
                                if (count($sub_rates) != 0) {
                                    $sub_rate = $sub_rates['non_refund_amount'];
                                }
                            }
                            if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                $color = '#669933';
                            } else {
                                $color = '#D9E4C3';
                            }
                            if (in_array($ss_da, $da)) {
                                if (count($sub_rates) != '0') {
                                    if ($sub_rate != 0) {
?>
                       <td bgcolor="<?php
                                        echo $color;
?>" class="w_bdr">
                        <?php
                                        if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                            echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun;
?>" data-url="<?php
                                            echo lang_url();
?>inventory/inline_edit_guest" data-title="Change Price"><?php
                                            echo floatval($sub_rate);
?></a><?php
                                        } else {
                                            echo floatval($sub_rate);
                                        }
?></td><?php
                                    } else {
?>
                       <td bgcolor="<?php
                                        echo $color;
?>" class="w_bdr">
                        <?php
                                        if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                            echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun;
?>" data-url="<?php
                                            echo lang_url();
?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php
                                        } else {
?> N/A <?php
                                        }
?></td>
                        <?php
                                    }
                                } else {
?>
                       <td bgcolor="<?php
                                    echo $color;
?>" class="w_bdr">
                        <?php
                                    if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                        echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun;
?>" data-url="<?php
                                        echo lang_url();
?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php
                                    } else {
?> N/A <?php
                                    }
?></td>
                        <?php
                                }
                            } else {
?> 
                        <td bgcolor="<?php
                                echo $color;
?>" class="w_bdr">
                        <?php
                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                    echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun;
?>" data-url="<?php
                                    echo lang_url();
?>inventory/inline_edit_guest" data-title="Change Price">N/A </a><?php
                                } else {
?> N/A <?php
                                }
?></td>
                        <?php
                            }
                        }
?>  
                        </tr>
                        <?php
                    }
                    if (count($all_rate_types) != 0) {
                        foreach ($all_rate_types as $rate_types) {
                            extract($rate_types);
                            $all_rates = get_data(RATE_TYPES_REFUN, array(
                                'room_id' => $property_id,
                                'uniq_id' => $uniq_id,
                                'user_id' => current_user_type(),
                                'hotel_id' => hotel_id()
                            ))->result_array();
?>
                           <tr class="p-b-o contents_<?php
                            echo $property_id;
?>" style="display: none;" >
                            
                            <td rowspan="3" class="ha2 ss_main_row_rate" width="400"><a href="javascript:;" class="show_e">
                            <span class="pull-left rate_clr"><strong><?php
                            echo $rate_name ? ucfirst($rate_name) : '#' . $uniq_id;
?></strong></span>
                            <?php
                            if ($pricing_type == 2) {
?> <?php
                            } else if ($pricing_type == 1 && $non_refund == 1) {
?> <?php
                            }
?>
                           </a></td>
                    
                            <td bgcolor="#a6a6a6">P</td>
                            <?php
                            foreach ($period as $date) {
                                $i     = $date->format("d");
                                $ss_da = $date->format("d/m/Y");
                                if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                    $color = '#669933';
                                } else {
                                    $color = '#D9E4C3';
                                }
                                if (in_array($ss_da, $da)) {
                                    $single = get_data(RATE_BASE, array(
                                        'individual_channel_id' => '0',
                                        'owner_id' => current_user_type(),
                                        'hotel_id' => hotel_id(),
                                        'room_id' => $property_id,
                                        'separate_date' => $ss_da,
                                        'rate_types_id' => $rate_type_id
                                    ), 'availability,price,stop_sell')->row();
                                    if (count($single) != '0') {
                                        if ($single->availability == 0 || $single->stop_sell == 1) {
                                            $color = '#FF0000';
                                        }
?>
                           <td>
                            <?php
                                        if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                            echo $ss_da . '-' . $property_id . '~' . $rate_type_id;
?>" data-url="<?php
                                            echo lang_url();
?>inventory/inline_rate_edit_no" data-title="Change Price"><?php
                                            echo floatval($single->price);
?> </a><?php
                                        } else {
?><?php
                                            echo floatval($single->price);
?> <?php
                                        }
?></td>
                            <?php
                                    } else {
?>
                           <td><?php
                                        if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                            echo $ss_da . '-' . $property_id . '~' . $rate_type_id;
?>" data-url="<?php
                                            echo lang_url();
?>inventory/inline_rate_edit_no" data-title="Change Price">N/A </a><?php
                                        } else {
?> N/A <?php
                                        }
?></td>
                            <?php
                                    }
                                } else {
?> 
                            <td><?php
                                    if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                        echo $ss_da . '-' . $property_id . '~' . $rate_type_id;
?>" data-url="<?php
                                        echo lang_url();
?>inventory/inline_rate_edit_no" data-title="Change Price">N/A </a><?php
                                    } else {
?> N/A <?php
                                    }
?></td>
                            <?php
                                }
                            }
?>
                           </tr>
                    
                            <tr class="p-b-o contents_<?php
                            echo $property_id;
?>" style="display: none;" >
                            <td bgcolor="#a6a6a6">A</td>
                            <?php
                            foreach ($period as $date) {
                                $i     = $date->format("d");
                                $ss_da = $date->format("d/m/Y");
                                if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                    $color = '#669933';
                                } else {
                                    $color = '#D9E4C3';
                                }
                                
                                if (in_array($ss_da, $da)) {
                                    $single = get_data(RATE_BASE, array(
                                        'individual_channel_id' => '0',
                                        'owner_id' => current_user_type(),
                                        'hotel_id' => hotel_id(),
                                        'room_id' => $property_id,
                                        'separate_date' => $ss_da,
                                        'rate_types_id' => $rate_type_id
                                    ), 'availability,price,stop_sell')->row();
                                    if (count($single) != '0') {
                                        if ($single->availability == 0) {
                                            $color = '#FF0000';
                                        } elseif ($single->stop_sell == 1) {
                                            $color = '#CC99FF';
                                        } elseif ($single->availability < 0) {
                                            $color = '#F4C327';
                                        }
?>
                               <td width="28" bgcolor="<?php
                                        echo $color;
?>" class="w_bdr <?php
                                        echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_R';
?>"> 
                                <?php
                                        if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability <?php
                                            echo str_replace('/', '_', $ss_da) . '_' . $property_id;
?>" data-type="number" data-name="availability" data-pk="<?php
                                            echo $ss_da . '-' . $property_id . '~' . $rate_type_id;
?>" data-url="<?php
                                            echo lang_url();
?>inventory/inline_rate_edit_no" data-title="Change Availability"><?php
                                            echo ($single->availability);
?> </a> <?php
                                        } else {
?> <?php
                                            echo ($single->availability);
?> <?php
                                        }
?></td>
                                <?php
                                    } else {
?>  
                                <td width="28" bgcolor="<?php
                                        echo $color;
?>" class="w_bdr <?php
                                        echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_R';
?>">
                                <?php
                                        if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability <?php
                                            echo str_replace('/', '_', $ss_da) . '_' . $property_id;
?>" data-type="number" data-name="availability" data-pk="<?php
                                            echo $ss_da . '-' . $property_id . '~' . $rate_type_id;
?>" data-url="<?php
                                            echo lang_url();
?>inventory/inline_rate_edit_no" data-title="Change Availability">N/A </a><?php
                                        } else {
?> N/A <?php
                                        }
?></td>
                                <?php
                                    }
                                } else {
?>  
                                <td width="28" bgcolor="<?php
                                    echo $color;
?>" class="w_bdr <?php
                                    echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_R';
?>">
                                <?php
                                    if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability <?php
                                        echo str_replace('/', '_', $ss_da) . '_' . $property_id;
?>" data-type="number" data-name="availability" data-pk="<?php
                                        echo $ss_da . '-' . $property_id . '~' . $rate_type_id;
?>" data-url="<?php
                                        echo lang_url();
?>inventory/inline_rate_edit_no" data-title="Change Availability">N/A </a><?php
                                    } else {
?> N/A <?php
                                    }
?></td>
                                <?php
                                }
                            }
?>  
                            </tr>
                    
                            <tr class="p-b-o contents_<?php
                            echo $property_id;
?>" style="display: none;" >
                            <td bgcolor="#a6a6a6">M</td>
                            <?php
                            foreach ($period as $date) {
                                $i     = $date->format("d");
                                $ss_da = $date->format("d/m/Y");
                                if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                    $color = '#669933';
                                } else {
                                    $color = '#D9E4C3';
                                }
                                
                                if (in_array($ss_da, $da)) {
                                    $single = get_data(RATE_BASE, array(
                                        'individual_channel_id' => '0',
                                        'owner_id' => current_user_type(),
                                        'hotel_id' => hotel_id(),
                                        'room_id' => $property_id,
                                        'separate_date' => $ss_da,
                                        'rate_types_id' => $rate_type_id
                                    ), 'availability,price,stop_sell,minimum_stay')->row();
                                    if (count($single) != '0') {
                                        if ($single->availability == 0) {
                                            $color = '#FF0000';
                                        } elseif ($single->stop_sell == 1) {
                                            $color = '#CC99FF';
                                        } elseif ($single->availability < 0) {
                                            $color = '#F4C327';
                                        }
?>
                               <td width="28" bgcolor="<?php
                                        echo $color;
?>" class="w_bdr <?php
                                        echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_R';
?>"> 
                                <?php
                                        if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="minimum_stay" data-pk="<?php
                                            echo $ss_da . '-' . $property_id . '~' . $rate_type_id;
?>" data-url="<?php
                                            echo lang_url();
?>inventory/inline_rate_edit_no" data-title="Change Minimum Stay"><?php
                                            echo ($single->minimum_stay);
?> </a> <?php
                                        } else {
?> <?php
                                            echo ($single->minimum_stay);
?> <?php
                                        }
?></td>
                                <?php
                                    } else {
?>  
                                <td width="28" bgcolor="<?php
                                        echo $color;
?>" class="w_bdr <?php
                                        echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_R';
?>">
                                <?php
                                        if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="minimum_stay" data-pk="<?php
                                            echo $ss_da . '-' . $property_id . '~' . $rate_type_id;
?>" data-url="<?php
                                            echo lang_url();
?>inventory/inline_rate_edit_no" data-title="Change Minimum Stay">N/A </a><?php
                                        } else {
?> N/A <?php
                                        }
?></td>
                                <?php
                                    }
                                } else {
?>  
                                <td width="28" bgcolor="<?php
                                    echo $color;
?>" class="w_bdr <?php
                                    echo str_replace('/', '_', $ss_da) . '_' . $property_id . '_R';
?>">
                                <?php
                                    if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_availability" data-type="number" data-name="minimum_stay" data-pk="<?php
                                        echo $ss_da . '-' . $property_id . '~' . $rate_type_id;
?>" data-url="<?php
                                        echo lang_url();
?>inventory/inline_rate_edit_no" data-title="Change Minimum Stay">N/A </a><?php
                                    } else {
?> N/A <?php
                                    }
?></td>
                                <?php
                                }
                            }
?>  
                            </tr>
                
                            <tr class="p-b-o ss_main_rate ss_contents_<?php
                            echo $property_id;
?>">
                            <td bgcolor="#a6a6a6">SS</td>
                                
                            <?php
                            foreach ($period as $date) {
                                $i     = $date->format("d");
                                $ss_da = $date->format("d/m/Y");
                                if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                    $color = '#d9d9d9';
                                } else {
                                    $color = '#FFFFFF';
                                }
                                if (in_array($ss_da, $da)) {
                                    $single = get_data(RATE_BASE, array(
                                        'individual_channel_id' => '0',
                                        'owner_id' => current_user_type(),
                                        'hotel_id' => hotel_id(),
                                        'room_id' => $property_id,
                                        'separate_date' => $ss_da,
                                        'rate_types_id' => $rate_type_id
                                    ), 'availability,price,stop_sell')->row();
                                    if (count($single) != '0') {
                                        if ($single->availability == 0) {
                                            $color = '#FF0000';
                                        } elseif ($single->stop_sell == 1) {
                                            $color = '#CC99FF';
                                        }
?>
                           <td bgcolor="<?php //echo $color
?>">
                           <?php
                                        if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> 
                                    <input type="checkbox"  name="s_rate_room[<?php
                                            echo $property_id;
?>][<?php
                                            echo $rate_type_id;
?>][stop_sell][<?php
                                            echo $ss_da;
?>]" value="1" <?php
                                            if ($single->stop_sell == '1') {
?> checked="checked"<?php
                                            }
?> class="update_stop_sell_main_rate">
                                    <?php
                                        } else {
?>
                                  <input type="checkbox"  name="s_rate_room[<?php
                                            echo $property_id;
?>][<?php
                                            echo $rate_type_id;
?>][stop_sell][<?php
                                            echo $ss_da;
?>]" value="1" <?php
                                            if ($single->stop_sell == '1') {
?> checked="checked"<?php
                                            }
?> disabled="disabled">
                                    <?php
                                        }
?>
                               </td>
                            <?php
                                    } else {
?>
                           <td bgcolor="<?php //echo $color
?>">
                            <?php
                                        if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d')) {
?> 
                                    <input type="checkbox"  name="rate_room[<?php
                                            echo $property_id;
?>][<?php
                                            echo $rate_type_id;
?>][stop_sell][<?php
                                            echo $ss_da;
?>]" value="1" class="update_stop_sell_new_rate">
                                    <?php
                                        } else {
?>
                                   N/A
                                    <?php
                                        }
?>
                               </td>
                            <?php
                                    }
                                } else {
?> 
                            <td bgcolor="<?php //echo $color
?>">
                            <?php
                                    if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d')) {
?> 
                                    <input type="checkbox"  name="rate_room[<?php
                                        echo $property_id;
?>][<?php
                                        echo $rate_type_id;
?>][stop_sell][<?php
                                        echo $ss_da;
?>]" value="1" class="update_stop_sell_new_rate">
                                    <?php
                                    } else {
?>
                                   N/A
                                    <?php
                                    }
?>
                                   
                                </td>
                            <?php
                                }
                            }
?>
                           </tr>

                            <?php
                            if ($pricing_type == 2) {
                                if ($non_refund == 1) {
                                    for ($k = 1; $k <= $members; $k++) {
                                        
                                        if ($member_count < $members) {
                                            if ($k % 2 == 0) {
                                                $name  = 'Guest Non refundable';
                                                $v     = ceil($k / 2);
                                                $refun = '2';
                                                
                                            } else {
                                                $name  = 'Guest';
                                                $v     = ceil($k / 2);
                                                $refun = '1';
                                                
                                            }
                                        } else {
                                            $name  = 'Guest';
                                            $v     = $k;
                                            $refun = '1';
                                            
                                        }
                                        
?>
                       <tr class="p-b-o contents_<?php
                                        echo $property_id;
?>" style="display:none;">
                        <td class="ha2"><span class="pull-left"><?php
                                        echo $rate_name ? ucfirst($rate_name) : '#' . $uniq_id;
?> - <?php
                                        echo $v . ' ' . $name;
?></span></td>
                        <td bgcolor="#a6a6a6" class="w_bdr">P</td>
                        <?php
                                        foreach ($period as $date) {
                                            $j     = $date->format("d");
                                            $ss_da = $date->format("d/m/Y");
                                            if ($refun == '1') {
                                                
                                                $sub_rates = get_data(RATE_ADD, array(
                                                    'individual_channel_id' => '0',
                                                    'owner_id' => current_user_type(),
                                                    'hotel_id' => hotel_id(),
                                                    'room_id' => $property_id,
                                                    'separate_date' => $ss_da,
                                                    'guest_count' => $v,
                                                    'refun_type' => $refun,
                                                    'rate_types_id' => $rate_type_id
                                                ))->row_array();
                                                if (count($sub_rates) != 0) {
                                                    $sub_rate = $sub_rates['refund_amount'];
                                                }
                                            } elseif ($refun == '2') {
                                                $sub_rates = get_data(RATE_ADD, array(
                                                    'individual_channel_id' => '0',
                                                    'owner_id' => current_user_type(),
                                                    'hotel_id' => hotel_id(),
                                                    'room_id' => $property_id,
                                                    'separate_date' => $ss_da,
                                                    'guest_count' => $v,
                                                    'refun_type' => $refun,
                                                    'rate_types_id' => $rate_type_id
                                                ))->row_array();
                                                if (count($sub_rates) != 0) {
                                                    $sub_rate = $sub_rates['non_refund_amount'];
                                                }
                                            }
                                            if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                                $color = '#669933';
                                            } else {
                                                $color = '#D9E4C3';
                                            }
                                            if (in_array($ss_da, $da)) {
                                                if (count($sub_rates) != '0') {
                                                    if ($sub_rate != 0) {
?>
                       <td bgcolor="<?php
                                                        echo $color;
?>" class="w_bdr">
                        <?php
                                                        if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                                            echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '>' . $rate_type_id;
?>" data-url="<?php
                                                            echo lang_url();
?>inventory/inline_rate_edit_guest" data-title="Change Price"><?php
                                                            echo floatval($sub_rate);
?></a> <?php
                                                        } else {
?> <?php
                                                            echo floatval($sub_rate);
?> <?php
                                                        }
?></td>
                        <?php
                                                    } else {
?>
                       <td bgcolor="<?php
                                                        echo $color;
?>" class="w_bdr">
                        <?php
                                                        if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                                            echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '>' . $rate_type_id;
?>" data-url="<?php
                                                            echo lang_url();
?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php
                                                        } else {
?> N/A <?php
                                                        }
?></td>
                         <?php
                                                    }
                                                } else {
?>
                       <td bgcolor="<?php
                                                    echo $color;
?>" class="w_bdr">
                        <?php
                                                    if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                                        echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '>' . $rate_type_id;
?>" data-url="<?php
                                                        echo lang_url();
?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php
                                                    } else {
?> N/A <?php
                                                    }
?></td>
                        <?php
                                                }
                                            } else {
?> 
                        <td bgcolor="<?php
                                                echo $color;
?>" class="w_bdr">
                        <?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                                    echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '>' . $rate_type_id;
?>" data-url="<?php
                                                    echo lang_url();
?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php
                                                } else {
?> N/A <?php
                                                }
?></td>
                        <?php
                                            }
                                        }
?>
                       </tr>
                        <?php
                                    }
                                } else {
                                    for ($k = 1; $k <= $members; $k++) {
                                        
                                        if ($member_count < $members) {
                                            if ($k % 2 == 0) {
                                                $name  = 'Guest Non refundable';
                                                $v     = ceil($k / 2);
                                                $refun = 2;
                                            } else {
                                                $name  = 'Guest';
                                                $v     = ceil($k / 2);
                                                $refun = 1;
                                            }
                                        } else {
                                            $name  = 'Guest';
                                            $v     = $k;
                                            $refun = 1;
                                            
                                        }
?>
                               <tr class="p-b-o contents_<?php
                                        echo $property_id;
?>" style="display: none;">
                                <td class="ha2"><span class="pull-left"><?php
                                        echo $rate_name ? ucfirst($rate_name) : '#' . $uniq_id;
?> - <?php
                                        echo $v . ' ' . $name;
?></span></td>
                                <td bgcolor="#a6a6a6" class="w_bdr">P</td>
                                <?php
                                        foreach ($period as $date) {
                                            $j     = $date->format("d");
                                            $ss_da = $date->format("d/m/Y");
                                            if ($refun == '1') {
                                                $sub_rates = get_data(RATE_ADD, array(
                                                    'individual_channel_id' => '0',
                                                    'owner_id' => current_user_type(),
                                                    'hotel_id' => hotel_id(),
                                                    'room_id' => $property_id,
                                                    'separate_date' => $ss_da,
                                                    'guest_count' => $v,
                                                    'refun_type' => $refun,
                                                    'rate_types_id' => $rate_type_id
                                                ))->row_array();
                                                if (count($sub_rates) != 0) {
                                                    $sub_rate = $sub_rates['refund_amount'];
                                                }
                                            } elseif ($refun == '2') {
                                                $sub_rates = get_data(RATE_ADD, array(
                                                    'individual_channel_id' => '0',
                                                    'owner_id' => current_user_type(),
                                                    'hotel_id' => hotel_id(),
                                                    'room_id' => $property_id,
                                                    'separate_date' => $ss_da,
                                                    'guest_count' => $v,
                                                    'refun_type' => $refun,
                                                    'rate_types_id' => $rate_type_id
                                                ))->row_array();
                                                if (count($sub_rates) != 0) {
                                                    $sub_rate = $sub_rates['non_refund_amount'];
                                                }
                                            }
                                            if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                                $color = '#669933';
                                            } else {
                                                $color = '#D9E4C3';
                                            }
                                            if (in_array($ss_da, $da)) {
                                                if (count($sub_rates) != '0') {
                                                    if ($sub_rate != 0) {
?>
                               <td bgcolor="<?php
                                                        echo $color;
?>" class="w_bdr">
                                <?php
                                                        if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                                            echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '>' . $rate_type_id;
?>" data-url="<?php
                                                            echo lang_url();
?>inventory/inline_rate_edit_guest" data-title="Change Price"><?php
                                                            echo floatval($sub_rate);
?></a><?php
                                                        } else {
?> <?php
                                                            echo floatval($sub_rate);
?> <?php
                                                        }
?></td>
                                <?php
                                                    } else {
?>
                               <td bgcolor="<?php
                                                        echo $color;
?>" class="w_bdr">
                                <?php
                                                        if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                                            echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '>' . $rate_type_id;
?>" data-url="<?php
                                                            echo lang_url();
?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php
                                                        } else {
?> N/A <?php
                                                        }
?></td>
                                 <?php
                                                    }
                                                } else {
?>
                               <td bgcolor="<?php
                                                    echo $color;
?>" class="w_bdr">
                                <?php
                                                    if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                                        echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '>' . $rate_type_id;
?>" data-url="<?php
                                                        echo lang_url();
?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php
                                                    } else {
?> N/A <?php
                                                    }
?></td>
                                <?php
                                                }
                                            } else {
?> 
                                <td bgcolor="<?php
                                                echo $color;
?>" class="w_bdr">
                                <?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                                    echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '>' . $rate_type_id;
?>" data-url="<?php
                                                    echo lang_url();
?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php
                                                } else {
?> N/A <?php
                                                }
?></td>
                                <?php
                                            }
                                        }
?>  
                                </tr>      
                                        <?php
                                    }
                                }
                            } else if ($pricing_type == 1 && $non_refund == 1) {
                                $v     = 1;
                                $refun = 2;
?>
                               <tr class="p-b-o contents_<?php
                                echo $property_id;
?>" style="display: none;">
                                <td class="ha2"><span class="pull-left"><?php
                                echo $rate_name ? ucfirst($rate_name) : '#' . $uniq_id;
?> - Non refundable</span></td>
                                <td bgcolor="#a6a6a6" class="w_bdr">P</td>
                                <?php
                                foreach ($period as $date) {
                                    $j     = $date->format("d");
                                    $ss_da = $date->format("d/m/Y");
                                    if ($refun == '1') {
                                        $sub_rates = get_data(RATE_ADD, array(
                                            'individual_channel_id' => '0',
                                            'owner_id' => user_id(),
                                            'hotel_id' => hotel_id(),
                                            'room_id' => $property_id,
                                            'separate_date' => $ss_da,
                                            'guest_count' => $v,
                                            'refun_type' => $refun,
                                            'rate_types_id' => $rate_type_id
                                        ))->row_array();
                                        if (count($sub_rates) != 0) {
                                            $sub_rate = $sub_rates['refund_amount'];
                                        }
                                    } elseif ($refun == '2') {
                                        $sub_rates = get_data(RATE_ADD, array(
                                            'individual_channel_id' => '0',
                                            'owner_id' => current_user_type(),
                                            'hotel_id' => hotel_id(),
                                            'room_id' => $property_id,
                                            'separate_date' => $ss_da,
                                            'guest_count' => $v,
                                            'refun_type' => $refun,
                                            'rate_types_id' => $rate_type_id
                                        ))->row_array();
                                        if (count($sub_rates) != 0) {
                                            $sub_rate = $sub_rates['non_refund_amount'];
                                        }
                                    }
                                    if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                        $color = '#669933';
                                    } else {
                                        $color = '#D9E4C3';
                                    }
                                    if (in_array($ss_da, $da)) {
                                        if (count($sub_rates) != '0') {
                                            if ($sub_rate != 0) {
?>
                               <td bgcolor="<?php
                                                echo $color;
?>" class="w_bdr">
                                <?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                                    echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '>' . $rate_type_id;
?>" data-url="<?php
                                                    echo lang_url();
?>inventory/inline_rate_edit_guest" data-title="Change Price"><?php
                                                    echo floatval($sub_rate);
?></a><?php
                                                } else {
                                                    echo floatval($sub_rate);
                                                }
?></td>
                                <?php
                                            } else {
?>
                               <td bgcolor="<?php
                                                echo $color;
?>" class="w_bdr">
                                <?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                                    echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '>' . $rate_type_id;
?>" data-url="<?php
                                                    echo lang_url();
?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php
                                                } else {
?> N/A <?php
                                                }
?></td>
                                <?php
                                            }
                                        } else {
?>
                               <td bgcolor="<?php
                                            echo $color;
?>" class="w_bdr">
                                <?php
                                            if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                                echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '>' . $rate_type_id;
?>" data-url="<?php
                                                echo lang_url();
?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php
                                            } else {
?> N/A <?php
                                            }
?></td>
                                <?php
                                        }
                                    } else {
?> 
                                <td bgcolor="<?php
                                        echo $color;
?>" class="w_bdr">
                                <?php
                                        if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit())) {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_price" data-type="number" data-name="price" data-pk="<?php
                                            echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '>' . $rate_type_id;
?>" data-url="<?php
                                            echo lang_url();
?>inventory/inline_rate_edit_guest" data-title="Change Price">N/A </a><?php
                                        } else {
?> N/A <?php
                                        }
?></td>
                                <?php
                                    }
                                }
?>  
                                </tr>
                                <?php
                            }
                        }
                    }
                }
            }
        }
    }
    
    function showReservations()
    {
        
        if (!IS_AJAX) {
            $this->load->view('admin/404');
        } else {
            /* $currency = get_data(TBL_CUR,array('currency_id'=>get_data(TBL_USERS,array('user_id'=>current_user_type()))->row()->currency))->row()->symbol; */
            
            $hotel_detail = get_data(HOTEL, array(
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id()
            ))->row()->currency;
            
            /* echo $hotel_detail; die; */
            
            $currency = get_data(TBL_CUR, array(
                'currency_id' => $hotel_detail
            ))->row()->symbol;
            
            extract($this->input->post());
            $startDate      = DateTime::createFromFormat("d/m/Y", $cal_start);
            $cal_end        = date('d/m/Y', strtotime('+1 day', strtotime(date('Y-m-d', strtotime(str_replace("/", "-", $cal_end))))));
            $endDate        = DateTime::createFromFormat("d/m/Y", $cal_end);
            $periodInterval = new DateInterval("P1D");
            //$endDate->add( $periodInterval );
            $period         = new DatePeriod($startDate, $periodInterval, $endDate);
            $da             = array();
            foreach ($period as $show_dates) {
                $da[] = $show_dates->format("d/m/Y");
            }
            $current_start_date = date('Y-m-d', strtotime(str_replace('/', '-', $cal_start)));
            $current_end_date   = date('Y-m-d', strtotime(str_replace('/', '-', $cal_end)));
            $this->db->order_by('property_id', 'desc');
            $all_room = get_data(TBL_PROPERTY, array(
                'status' => 'Active',
                'owner_id' => current_user_type(),
                'hotel_id' => hotel_id(),
                'droc' => '1'
            ), 'property_id,non_refund,member_count,property_name,pricing_type,existing_room_number')->result_array();
            
            if (count($all_room) != 0) {
                foreach ($all_room as $room) {
                    extract($room);
                    
                    $reservation_count = $this->inventory_model->reservation_count(insep_encode($property_id), $current_start_date, $current_end_date);
                    
                    $channel_reservation_count = $this->reservation_model->channel_reservation_count(insep_encode($property_id), $current_start_date, $current_end_date);
                    
                    if ($reservation_count != 0 || $channel_reservation_count != 0) {
                        if ($reservation_count != 0) {
                            $manualreservation = manual_reservation(insep_encode($property_id), $current_start_date, $current_end_date);
                            
                            if ($manualreservation) {
                                $manualreservation = $manualreservation;
                            } else {
                                $manualreservation = array();
                            }
                        } else {
                            $manualreservation = array();
                        }
                        /* echo '<pre>';
                        print_r($manualreservation); */
                        if ($channel_reservation_count != 0) {
                            $channelreservation = $this->reservation_model->channel_reservation_result(insep_encode($property_id), $current_start_date, $current_end_date);
                        } else {
                            $channelreservation = array();
                        }
                        
                        //echo '<pre>'; print_r($channelreservation);
                        $reservation = $this->inventory_model->cleanArray(array_merge($manualreservation, $channelreservation));
                        $arr_pu      = array();
                        $reser       = array();
                        $date_list   = 0;
                        /* echo '<pre>'; print_r($reservation);     */
                        if ($reservation) {
                            foreach ($reservation as $value) {
                                $reser_id         = $value->reservation_id;
                                $reser_channel_id = $value->channel_id;
                                $booking_number   = $value->booking_number;
                                $var              = $value->start_date;
                                $datea            = str_replace('/', '-', $var);
                                $start_dates      = date('Y-m-d', strtotime($datea));
                                
                                $var1      = $value->end_date;
                                $datea1    = str_replace('/', '-', $var1);
                                $end_dates = date('Y-m-d', strtotime($datea1));
                                
                                //$startDate = DateTime::createFromFormat("d/m/Y",$value->start_date);
                                
                                //if (date('Y/m/d', strtotime(str_replace('/', '-', $value->start_date))) >= date('Y/m/d')) {
                                    $startDate = DateTime::createFromFormat("d/m/Y", $value->start_date);
                                //} else if (date('Y/m/d', strtotime(str_replace('/', '-', $value->start_date))) < date('Y/m/d')) {
                                 //   $startDate = DateTime::createFromFormat("d/m/Y", date('d/m/Y'));
                               // }
                                $endDate = date('d/m/Y', strtotime('+1 day', strtotime(date('Y-m-d', strtotime(str_replace("/", "-", $value->end_date))))));
                                
                                $endDate        = DateTime::createFromFormat("d/m/Y", $endDate);
                                $periodInterval = new DateInterval("P1D");
                                //$endDate->add( $periodInterval );
                                $periods        = new DatePeriod($startDate, $periodInterval, $endDate);
                                $re             = array();
                                $same           = 1;
                                //print "periods=>";print_r($periods);
                                foreach ($periods as $dates) {
                                    //echo $dates->format("d/m/Y").'<br>';
                                    array_push($arr_pu, $dates->format("d/m/Y"));
                                    if ($reser_channel_id != 15) {
                                        $reser[$dates->format("d/m/Y")][$date_list] = $reser_id . '~' . $reser_channel_id . '~~' . $booking_number;
                                    } else {
                                        $reser[$dates->format("d/m/Y")][$date_list] = $reser_id . '~' . $reser_channel_id . '~~' . $booking_number . '~' . $value->position;
                                    }
                                    $same++;
                                }
                                $date_list++;
                            }
                        }
                        /*       echo '<pre>';
                        print_r($reser);*/
                        $fillTo = array(
                            'key' => '',
                            'count' => 0
                        );
                        foreach ($reser as $k => $data) {
                            if (count($data) > $fillTo['count']) {
                                $fillTo['key']   = $k;
                                $fillTo['count'] = count($data);
                            }
                        }
                        // echo 'test'.count($reser[$fillTo['key']]);
                        if (@$reser[$fillTo['key']]) {
                            $fillData = $reser[$fillTo['key']];
                            foreach ($fillData as $k => &$data) {
                                @$data['numberof'] = ''; //0
                            }
                        }
                        
                        foreach ($reser as $k => &$data) {
                            if ($k === $fillTo['key'])
                                continue;
                            
                            $data = $data + $fillData;
                        }
                        
                        //$reser = array_map('array_values', $reser);
                        /*echo '<pre>';
                        
                        print_r($reser);
                        
                        
                        print_r($arr_pu);*/
                        
                        
                        if (count($reservation) != 0) {
                            $a = $da;
                            $b = $arr_pu;
                            $c = array_intersect($a, $b);
                            $c = $this->inventory_model->cleanArray($c);
                            if (count($c) != 0) {
                                $arr_name    = array();
                                $rer_name    = array();
                                $rer_cha_id  = array();
                                $rer_date_id = array();
                                if (empty($arr_unq)) {
                                    $arr_unq = array();
                                }
                                $o_loop = 1;
                                /* echo '<pre>';
                                print_r($reser); */
                                //print "reser2222=>";print_r($reser);
                                //print_r(array_keys($reser));
                                //echo count($reser).'<br>';
                                for ($s = 0; $s < count($reser); $s++) {
                                    //print "hiiiiiiiii\n";
                                    //echo $s.'<br>';
                                    /* foreach($reser as $t_key=>$t_val)
                                    {
                                    foreach($t_val as $s_key=>$s_val)
                                    {
                                    $s = $s_key; */
                                    /*        
                                    }
                                    } */
                                    
                                    $t_array   = array();
                                    $t_array[] = $reser;
                                    // echo $s.'<br>';
                                    /*if($s%2 == 0)
                                    {
                                    $book_color='show5';
                                    $a_color   ='contents6';
                                    $btn_color ='booked';
                                    }
                                    
                                    else    
                                    {
                                    $book_color='show6';
                                    $a_color   ='contents5';
                                    $btn_color ='booked1'; 
                                    }*/
?>
                         <?php
                                    //print "period=>";print_r($period);
                                    foreach ($period as $date) {
                                        //print "date=>";print_r($date);
                                        $j     = $date->format("d");
                                        $ss_rr = $date->format("d/m/Y");
                                        //echo $ss_rr.' = '.$s.'<br>';
                                        //print "ss_rr=>$ss_rr\n";
                                        
                                        // print "reser11=>";print_r($reser);
                                        if (@array_key_exists($ss_rr, $reser)) {
                                            //print "ss=>".$reser[$ss_rr][$s];
                                            $all_reservation_id = explode('~', @$reser[$ss_rr][$s]);
                                            $get_reser          = @$all_reservation_id[0];
                                            $all_channel_id     = @$all_reservation_id[1];
                                            $all_booking        = explode('~~', @$reser[$ss_rr][$s]);
                                            $cha_booking        = @$all_booking[1];
                                            // print "get_reser=>$get_reser\n";
                                            // print "all_channel_id=>$all_channel_id\n";
                                            // print "cha_booking=>$cha_booking\n";
                                            
                                            // echo $all_channel_id.'sdsjg';die;
                                            if (@$all_channel_id == 15) {
                                                $position = @$all_reservation_id[4];
                                            } else {
                                                //echo '<pre>';
                                                //echo ' all_channel_id = '.$all_channel_id; //CURDATE()
                                                if ($all_channel_id == 0) {
                                                    $reservation = single_manual_reservation(insep_encode($property_id), $current_start_date, $current_end_date, $get_reser);
                                                    
                                                    if ($reservation) {
                                                        $reservation = $reservation;
                                                    } else {
                                                        $reservation = array();
                                                    }
                                                    
                                                } elseif ($all_channel_id == 11) {
                                                    //echo $all_channel_id;
                                                    $room_id = $this->reservation_model->getRoomRelation(insep_encode($property_id), $all_channel_id);
                                                    //print_r($room_id);
                                                    if (count($room_id) != 0) {
                                                        $reservation = array();
                                                        foreach ($room_id as $room_code) {
                                                            extract($room_code);
                                                            $reservation = array_merge($reservation, $this->reservation_model->getSingleReservationReconline($get_reser, $CODE, $cha_booking));
                                                        }
                                                    } else {
                                                        $reservation = array();
                                                    }
                                                    //print_r($reservation);
                                                } elseif ($all_channel_id == 1) {
                                                    //echo $get_reser.'<br>';
                                                    /*$property_ids[]='';
                                                    if(!in_array($property_id , $property_ids))
                                                    {*/
                                                    //$room_id        = $this->reservation_model->getRoomRelation(insep_encode($property_id),$all_channel_id);
                                                    /* echo '<pre>';
                                                    print_r($room_id); */
                                                    /* if(count($room_id)!=0)
                                                    {
                                                    $reservation = array();
                                                    foreach($room_id as $room_code)
                                                    {
                                                    extract($room_code);
                                                    $reservation    = array_merge($reservation,$this->reservation_model->getSingleReservationExpedia($get_reser,$roomtype_id,$rate_type_id,$rateplan_id,$cha_booking));
                                                    }
                                                    }
                                                    else
                                                    {
                                                    $reservation = array();
                                                    } */
                                                    $reservation = array_merge($reservation, $this->reservation_model->getSingleReservationExpedia($get_reser, $roomtype_id = '', $rate_type_id = '', $rateplan_id = '', $cha_booking = ''));
                                                    /*$property_ids[] =$property_id;
                                                    }*/
                                                    /* print_r($reservation); die; */
                                                } elseif ($all_channel_id == 8) {
                                                    $room_id = $this->reservation_model->getRoomRelation(insep_encode($property_id), $all_channel_id);
                                                    //print_r($room_id);
                                                    if (count($room_id) != 0) {
                                                        $reservation = array();
                                                        foreach ($room_id as $room_code) {
                                                            extract($room_code);
                                                            $reservation = array_merge($reservation, $this->reservation_model->getSingleReservationGta($get_reser, $ID, $rateplan_id, $cha_booking));
                                                        }
                                                    } else {
                                                        $reservation = array();
                                                    }
                                                    //print_r($reservation);
                                                } elseif ($all_channel_id == 2) {
                                                    $room_id = $this->reservation_model->getRoomRelation(insep_encode($property_id), $all_channel_id);
                                                    //print_r($room_id);
                                                    if (count($room_id) != 0) {
                                                        $reservation = array();
                                                        foreach ($room_id as $room_code) {
                                                            extract($room_code);
                                                            $reservation = array_merge($reservation, $this->reservation_model->getSingleReservationBooking($get_reser, $B_room_id, $B_rate_id, $cha_booking));
                                                        }
                                                    } else {
                                                        $reservation = array();
                                                    }
                                                    //print_r($reservation);
                                                } elseif ($all_channel_id == 17) {
                                                    $reservation = array_merge($reservation, $this->bnow_model->getSingleReservationBnow($get_reser));
                                                    
                                                    //print_r($reservation);
                                                } elseif ($all_channel_id == 15) {
                                                    
                                                    //print_r($reservation);
                                                    $room_id = $this->reservation_model->getRoomRelation(insep_encode($property_id), $all_channel_id);
                                                    //print_r($room_id);
                                                    if (count($room_id) != 0) {
                                                        $reservation = array();
                                                        foreach ($room_id as $room_code) {
                                                            extract($room_code);
                                                            $reservation = array_merge($reservation, $this->reservation_model->getSingleReservationTravelrepublic($get_reser, $RoomTypeId, $position, $cha_booking));
                                                        }
                                                    } else {
                                                        $reservation = array();
                                                    }
                                                    //print_r($reservation);
                                                } elseif ($all_channel_id == 5) {
                                                    $reservation = array_merge($reservation, $this->hotelbeds_model->getSingleReservationHotelbeds($get_reser));
                                                    
                                                    //print_r($reservation);
                                                }
                                                
                                                if (count($reservation) != 0) {
                                                    if(array_key_exists('reservation_id',$reservation) && array_key_exists('channel_id',$reservation)){
                                                        if (!in_array($reservation['reservation_id'], $rer_name) || !in_array($reservation['channel_id'], $rer_cha_id)) {
                                                            $startDate = str_replace('/', '-', $reservation['start_date']);
                                                            $startDate = date('d/m/Y', strtotime($startDate));
                                                            $roomNum   = $reservation['RoomNumber'];
                                                            if ($reservation['RoomNumber'] == '' || $reservation['RoomNumber'] == '0') {
                                                                $roomNum = 'Unassigned number';
                                                            }
															if($reservation['status'] != 'No Show'){
																$this->resvArr[$property_id][$roomNum][$startDate] = $reservation;
															}
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        @array_push($rer_name, $reservation['reservation_id']);
                                        @array_push($rer_cha_id, $reservation['channel_id']);
                                        @array_push($rer_date_id, $reservation['start_date']);
                                        $o_loop++;
                                    }
                                }
                                
                            }
                        }
                    }
                }
            }
            
            if (count($this->resvArr) > 0) {
                $s = 0;
                if (count($all_room) != 0) {
                    foreach ($all_room as $room) {
                        extract($room);
                        if ($existing_room_number != '') {
                            $roomsArr = explode(',', $existing_room_number);
                            foreach ($roomsArr as $key => $roomNum) {
                                if (!(array_key_exists($property_id, $this->resvArr) && array_key_exists($roomNum, $this->resvArr[$property_id]))) {
                                    $this->resvArr[$property_id][$roomNum][0] = array();
                                }
                            }
                        }
                    }
                }
                
                foreach ($this->resvArr as $property_id => $roomArr) {
                    //echo "Room Arr=>";print_r($roomArr);
                    $tempArr = $roomArr;
                    if(!array_key_exists('Unassigned number',$roomArr)){
                        $roomArr = array();
                        $roomArr['Unassigned number'][0] = array();
                        foreach ($tempArr as $roomNumber => $valueArr) {
                            $roomArr[$roomNumber] = $valueArr;
                        }
                    }elseif(array_key_exists('Unassigned number',$roomArr)){
                        $roomArr = array();
                        $unassigned = $tempArr['Unassigned number'];
                        unset($tempArr['Unassigned number']);                       
                        $roomArr['Unassigned number'] = $unassigned;
                        foreach ($tempArr as $roomNumber => $valueArr) {
                            $roomArr[$roomNumber] = $valueArr;
                        }
                    }
                    //echo "Room Arr 1 =>";print_r($roomArr);
                    foreach ($roomArr as $roomNumber => $valueArr) {
                        if($roomNumber == '' || $roomNumber == '0'){
                            $roomNumber = 'Unassigned number';
                        }
                        echo '<tr class="p-b-o contents4_' . $property_id . '" id="' . $property_id . '_' . $s . '" custom="' . $property_id . '" show_value="' . $s . '">
                                <td class="ha2">' . $roomNumber . '</td>
                                <td class="ha2">&nbsp;</td>';
                        $skipDate = '';
                        foreach ($period as $date) {
                            $ss_rr = $date->format("d/m/Y");
                            if ($skipDate != '') {
                                if (dateCompare(date('Y-m-d', strtotime(preg_replace('/\//is', '-', $ss_rr))), date('Y-m-d', strtotime(preg_replace('/\//is', '-', $skipDate)))) < 0) {
                                    continue;
                                } else {
                                    $skipDate = '';
                                }
                            }
                            if (array_key_exists($ss_rr, $valueArr)) {
                                $reservation = $valueArr[$ss_rr];
                                $var         = $reservation['start_date'];
                                $datea       = str_replace('/', '-', $var);
                                $start_dates = date('Y-m-d', strtotime($datea));
                                
                                $var1      = $reservation['end_date'];
                                $datea1    = str_replace('/', '-', $var1);
                                $end_dates = date('Y-m-d', strtotime($datea1));
                                
                                $datetime1 = new DateTime($start_dates);
                                $datetime2 = new DateTime($end_dates);
                                $interval  = $datetime1->diff($datetime2);
                                
                                $newend_dates = $current_start_date;
                                $datetime1    = new DateTime($start_dates);
                                $datetime2    = new DateTime($newend_dates);
                                $interval2    = $datetime1->diff($datetime2);
                                
                                //echo $interval2->format('%a%');
                                // date('Y-m-d') = $current_start_date;
                                
                                if ($start_dates == $current_start_date || $start_dates > $current_start_date) {
                                    $colspn = $interval->format('%a%'); //+1;
                                    //echo 'colspn = '.$colspn;
                                } else if ($start_dates <= $current_start_date) {
                                    $colspn = $interval->format('%a%') - $interval2->format('%a%'); //+1;
                                    //echo 'colspn = '.$colspn;
                                } else {
                                    //$colspn = $interval->format('%a%');
                                }
                                $date->add($interval);
                                $skipDate = $date->format("d/m/Y");
                                $Rstatus  = $reservation['status'];
                                
                                $start_date = date('Y/m/d', strtotime(str_replace('/', '-', $reservation['start_date'])));
                                
                                /* $end_date    =    date('Y/m/d',strtotime(str_replace('/','-',$reservation['end_date']))); */
                                
                                $end_date = date('Y/m/d', strtotime('-1 day', strtotime(date('Y-m-d', strtotime(str_replace("/", "-", $reservation['end_date']))))));
                                
                                
                                if ($Rstatus == "new" || $Rstatus == 11 || $Rstatus == "Book" || $Rstatus == "Reserved") {
                                    $book_color = 'show5';
                                    $a_color    = 'contents6';
                                    $btn_color  = 'booked3';
                                } else if ($Rstatus == 'pending' || $Rstatus == "Modify" || $Rstatus == 12 || $Rstatus == 'modified') {
                                    $book_color = 'show6';
                                    $a_color    = 'contents5';
                                    $btn_color  = 'booked';
                                } else if ($Rstatus == "Confirmed") {
                                    $book_color = 'show5';
                                    $a_color    = 'contents6';
                                    $btn_color  = 'booked1';
                                } else {
                                    $book_color = 'show6';
                                    $a_color    = 'contents5';
                                    $btn_color  = 'booked';
                                }
                                if ($start_date == date('Y/m/d')) {
                                    $book_color = 'show5';
                                    $a_color    = 'contents6';
                                    $btn_color  = 'booked2';
                                }
                                if ($end_date == date('Y/m/d')) {
                                    $book_color = 'show6';
                                    $a_color    = 'contents5';
                                    $btn_color  = 'booked4';
                                }
?>
   <td style="position: relative;" class="<?php if($reservation['channel_id'] == '0'){echo 'drag_int';}else{echo 'drag';}?>" ResId="<?php
                                echo $reservation['reservation_id'] . '_' . $reservation['channel_id'];
?>" colspan="<?php
                                echo $colspn;
?>" onmouseover="show_detais_reser('<?php
                                echo $reservation['reservation_id'] . '_' . $reservation['channel_id'];
?>');" onmouseout="show_detais_reser('<?php
                                echo $reservation['reservation_id'] . '_' . $reservation['channel_id'];
?>');">
                                    
                                                <?php
                                if ($reservation['start_date'] == $reservation['end_date']) {
?>
                                              <?php
                                    if ($reservation['channel_id'] == 0) {
?>
                                              <a target="_blank" href="<?php
                                        echo lang_url();
?>reservation/reservation_order/<?php
                                        echo insep_encode($reservation['reservation_id']);
?>">
                                                <?php
                                    } else {
?>
                                              <a target="_blank" href="<?php
                                        echo lang_url();
?>reservation/reservation_channel/<?php
                                        echo secure($reservation['channel_id']) . '/' . insep_encode($reservation['reservation_id']);
?>">
                                                <?php
                                    }
?>
                                       <i class="fa fa-arrows-alt dragHandles" style="left:0;position: absolute;color: transparent;padding: 4px <?php echo $reservation['num_nights']*9 ?>px;"></i>              <button style="display:block" class="<?php
                                    echo $btn_color . ' ' . $book_color . ' ' . $reservation['reservation_id'];
?> " type="button" target="_blank">      
                                                    <?php
                                    if (!in_array($reservation['reservation_id'], $rer_name) || !in_array($reservation['channel_id'], $rer_cha_id)) {
                                        if ($reservation['channel_id'] == 2) {
                                            if ($reservation['guest_name'] != '') {
                                                $reservation_name = $reservation['guest_name'];
                                            } else {
                                                $reservation_name = get_data(BOOK_RESERV, array(
                                                    'id' => get_data(BOOK_ROOMS, array(
                                                        'roomreservation_id' => $reservation['reservation_code']
                                                    ))->row()->reservation_id
                                                ), 'first_name,last_name')->row();
                                                $reservation_name = $reservation_name->first_name . ' ' . $reservation_name->last_name;
                                            }
                                        } else if ($reservation['channel_id'] == 15) {
                                            if ($reservation['guest_name'] != '') {
                                                $reservation_name = $reservation['guest_name'];
                                            } else {
                                                $reservation_name = get_data(IM_TRAVEL_RESER, array(
                                                    'BookingId' => $reservation['id']
                                                ), 'CustomerFirstName,CustomerSurname,CustomerTitle')->row();
                                                
                                                $reservation_name = $reservation_name->CustomerTitle . ' ' . $reservation_name->CustomerFirstName . ' ' . $reservation_name->CustomerSurname;
                                            }
                                        } else {
                                            $reservation_name = $reservation['guest_name'];
                                        }
                                        //echo $reservation_name;
                                    } else {
                                        echo '';
                                    }
?> 
                                                    <?php
                                    if ($reservation['channel_id'] == '0') {
                                        $book_logo = get_data(CONFIG, array(
                                            'id' => '1'
                                        ))->row()->logo_book;
                                        $path      = "uploads/logo/small/";
                                    } else {
                                        $book_logo = get_data(TBL_CHANNEL, array(
                                            'channel_id' => $reservation['channel_id']
                                        ))->row()->logo_book;
                                        $path      = "uploads/small/";
                                    }
?>
                                                  <img src="data:image/png;base64,<?php
                                    echo base64_encode(file_get_contents($path . $book_logo));
?>" class="pull-right ">
                                                    </button>
                                                    </a>
                                                    <div class="<?php
                                    echo $a_color . ' ' . $reservation['reservation_id'];
?>" id="<?php
                                    echo $reservation['reservation_id'] . '_' . $reservation['channel_id'];
?>" style="width:160px">
                                                    <ul>
                                                    <li>Room Name : <span class="pull-right">
                                                    <?php
                                    if ($reservation['channel_id'] == 0) {
                                        $room_details = get_data(TBL_PROPERTY, array(
                                            'property_id' => $reservation['room_id']
                                        ), 'property_name')->row_array();
                                        if (count($room_details) != '0') {
                                            echo ucfirst($room_details['property_name']);
                                        } else {
                                            echo '"No Room Set"';
                                        }
                                    } elseif ($reservation['channel_id'] == 11) {
                                        $roomName = @get_data(TBL_PROPERTY, array(
                                            'property_id' => get_data(MAP, array(
                                                'channel_id' => $reservation['channel_id'],
                                                'import_mapping_id' => get_data(IM_RECO, array(
                                                    'CODE' => $reservation['ROOMCODE']
                                                ))->row()->re_id
                                            ))->row()->property_id
                                        ))->row()->property_name;
                                        if ($roomName) {
                                            echo ucfirst($roomName);
                                        } else {
                                            echo '"No Room Set"';
                                        }
                                    } else if ($reservation['channel_id'] == 1) {
                                        $import_mapping_id = get_data('import_mapping', array(
                                            'user_id' => current_user_type(),
                                            'hotel_id' => hotel_id(),
                                            'roomtype_id' => $reservation['roomTypeID'],
                                            'rate_type_id' => $reservation['ratePlanID']
                                        ), 'map_id,rateAcquisitionType,rateplan_id')->row();
                                        if ($import_mapping_id->rateAcquisitionType == 'SellRate') {
                                            $import_mapping_ids = $import_mapping_id->map_id;
                                        } else {
                                            $import_mapping_ids = get_data('import_mapping', array(
                                                'user_id' => current_user_type(),
                                                'hotel_id' => hotel_id(),
                                                'rate_type_id' => $import_mapping_id->rateplan_id
                                            ), 'map_id,rateAcquisitionType,rateplan_id')->row();
                                            
                                            $import_mapping_ids = $import_mapping_ids->map_id;
                                        }
                                        $room_ids = get_data(MAP, array(
                                            'owner_id' => current_user_type(),
                                            'hotel_id' => hotel_id(),
                                            'channel_id' => $reservation['channel_id'],
                                            'import_mapping_id' => $import_mapping_ids
                                        ))->row();
                                        if ($room_ids->property_id != '' && $room_ids->rate_id == 0) {
                                            $roomName = @get_data(TBL_PROPERTY, array(
                                                'property_id' => $room_ids->property_id
                                            ))->row()->property_name;
                                        } else {
                                            $rateRoomName = @get_data(RATE_TYPES, array(
                                                'room_id' => $room_ids->property_id,
                                                'rate_type_id' => $room_ids->rate_id
                                            ))->row();
                                            if ($rateRoomName->rate_name != '') {
                                                $roomName = $rateRoomName->rate_name;
                                            } else {
                                                $roomName = '#' . $rateRoomName->uniq_id;
                                            }
                                        }
                                        if ($roomName) {
                                            echo ucfirst($roomName);
                                        } else {
                                            echo '"No Room Set"';
                                        }
                                    } elseif ($reservation['channel_id'] == 2) {
                                        $roomName = @get_data(TBL_PROPERTY, array(
                                            'property_id' => get_data(MAP, array(
                                                'channel_id' => $reservation['channel_id'],
                                                'import_mapping_id' => get_data(BOOKING, array(
                                                    'B_room_id' => $reservation['id'],
                                                    'B_rate_id' => $reservation['rate_id']
                                                ))->row()->import_mapping_id
                                            ))->row()->property_id
                                        ))->row()->property_name;
                                        if ($roomName) {
                                            echo ucfirst($roomName);
                                        } else {
                                            echo '"No Room Set"';
                                        }
                                    } elseif ($reservation['channel_id'] == 17) {
                                        $roomName = @get_data(TBL_PROPERTY, array(
                                            'property_id' => get_data(MAP, array(
                                                'channel_id' => $reservation['channel_id'],
                                                'import_mapping_id' => get_data(IM_BNOW, array(
                                                    'InvTypeCode' => $reservation['RoomTypeCode'],
                                                    'RatePlanCode' => $reservation['RatePlanCode']
                                                ))->row()->import_mapping_id
                                            ))->row()->property_id
                                        ))->row()->property_name;
                                        if ($roomName) {
                                            echo ucfirst($roomName);
                                        } else {
                                            echo '"No Room Set"';
                                        }
                                    } elseif ($reservation['channel_id'] == 15) {
                                        $roomName = @get_data(TBL_PROPERTY, array(
                                            'property_id' => get_data(MAP, array(
                                                'channel_id' => $reservation['channel_id'],
                                                'import_mapping_id' => get_data(IM_TRAVELREPUBLIC, array(
                                                    'RoomTypeId' => $reservation['room_id']
                                                ))->row()->map_id
                                            ))->row()->property_id
                                        ))->row()->property_name;
                                        if ($roomName) {
                                            echo ucfirst($roomName);
                                        } else {
                                            echo '"No Room Set"';
                                        }
                                    } elseif ($reservation['channel_id'] == 5) {
                                        $htb_id = $this->db->query("SELECT map_id, TRIM(TRAILING '-' FROM  REPLACE(roomname,SUBSTRING_INDEX(roomname,'-',-1),'') ) as roomnames, TRIM(TRAILING '-' FROM  REPLACE(characterstics,SUBSTRING_INDEX(characterstics,'-',-1),'') ) as charactersticss FROM `" . IM_HOTELBEDS_ROOMS . "` where sequence='" . $reservation['Contract_Code'] . "' and contract_name='" . $reservation['Contract_Name'] . "' and contract_code='" . $reservation['IncomingOffice'] . "' and user_id='" . current_user_type() . "' and hotel_id='" . hotel_id() . "' having roomnames ='" . $reservation['Room_code'] . "' AND charactersticss ='" . $reservation['CharacteristicCode'] . "'");
                                        
                                        if ($htb_id->num_rows != 0) {
                                            $htb_id = $htb_id->row()->map_id;
                                            $htbid  = get_data(MAP, array(
                                                'channel_id' => $reservation['channel_id'],
                                                'import_mapping_id' => $htb_id
                                            ));
                                            if ($htbid->num_rows != 0) {
                                                $room_id  = $htbid->row()->property_id;
                                                $roomName = @get_data(TBL_PROPERTY, array(
                                                    'property_id' => $room_id
                                                ))->row()->property_name;
                                            }
                                        }
                                        
                                        if ($roomName) {
                                            echo ucfirst($roomName);
                                        } else {
                                            echo '"No Room Set"';
                                        }
                                    }
                                    
?></span></li>
                                                    <li>Name : <span class="pull-right"><?php
                                    echo $reservation_name;
?></span></li>
                                                    <li>Booking ID : <span class="pull-right"><?php
                                    echo $reservation['reservation_code'];
?></span></li>
                                                    <li>Arrival : <span class="pull-right"><?php
                                    echo $reservation['start_date'];
?></span></li> 
                                                    <li>Departure : <span class="pull-right"><?php
                                    echo $reservation['end_date'];
?></span></li>
                                                    <li>Days : <span class="pull-right"><?php
                                    echo $reservation['num_nights'];
?></span></li>
                                                    <li>Guest : <span class="pull-right"><?php
                                    echo $reservation['members_count'];
?></span></li>
                                                    <li>Price : <span class="pull-right"><?php
                                    if ($reservation['channel_id'] == 0) {
                                        echo $reservation['num_rooms'] * $reservation['price'];
                                    } else {
                                        echo $reservation['price'];
                                    }
?>
                                                       <?php
                                    if ($reservation['channel_id'] != 15) {
                                        echo $currency;
                                    } else {
                                        $cur = get_data(IM_TRAVEL_RESER, array(
                                            'BookingId' => $reservation['id']
                                        ), 'CurrencyCode')->row()->CurrencyCode;
                                        echo $cur;
                                    }
?></span></li>
                                                    </ul>
                                                    </div>
                                                <?php
                                } else {
?>
                                              <?php
                                    if ($reservation['channel_id'] == 0) {
?>
                                              <a target="_blank" href="<?php
                                        echo lang_url();
?>reservation/reservation_order/<?php
                                        echo insep_encode($reservation['reservation_id']);
?>">
                                                <?php
                                    } else {
?>
                                              <a target="_blank" href="<?php
                                        echo lang_url();
?>reservation/reservation_channel/<?php
                                        echo secure($reservation['channel_id']) . '/' . insep_encode($reservation['reservation_id']);
?>">
                                                <?php
                                    }
?>
                                           <i class="fa fa-arrows-alt dragHandles" style="left:0;position: absolute;color: transparent;padding: 4px <?php echo $reservation['num_nights']*9 ?>px;"></i>   
                                           <button style="display:block" class="<?php
                                    echo $btn_color . ' ' . $book_color . ' ' . $reservation['reservation_id'];
?>" type="button">     <?php
                                    $reservation_name  = '';
                                    if (!in_array($reservation['reservation_id'], $rer_name) || !in_array($reservation['channel_id'], $rer_cha_id)) {
                                        if ($reservation['channel_id'] == 2) {
                                            if ($reservation['guest_name'] != '') {
                                                $reservation_name = $reservation['guest_name'];
                                            } else {
                                                $reservation_name = get_data(BOOK_RESERV, array(
                                                    'id' => get_data(BOOK_ROOMS, array(
                                                        'roomreservation_id' => $reservation['reservation_code']
                                                    ))->row()->reservation_id
                                                ), 'first_name,last_name')->row();
                                                $reservation_name = $reservation_name->first_name . ' ' . $reservation_name->last_name;
                                            }
                                        } else if ($reservation['channel_id'] == 15) {
                                            if ($reservation['guest_name'] != '') {
                                                $reservation_name = $reservation['guest_name'];
                                            } else {
                                                $reservation_name = get_data(IM_TRAVEL_RESER, array(
                                                    'BookingId' => $reservation['id']
                                                ), 'CustomerFirstName,CustomerSurname,CustomerTitle')->row();
                                                $reservation_name = $reservation_name->CustomerTitle . ' ' . $reservation_name->CustomerFirstName . ' ' . $reservation_name->CustomerSurname;
                                            }
                                        } else {
                                            $reservation_name = $reservation['guest_name'];
                                        }
                                        //echo $reservation_name;
                                    } else {
                                       $reservation_name = $reservation['guest_name'];
                                    }
?> 
                                                    <?php
                                    if ($reservation['channel_id'] == '0') {
                                        $book_logo = get_data(CONFIG, array(
                                            'id' => '1'
                                        ))->row()->logo_book;
                                        $path      = "uploads/logo/small/";
                                    } else {
                                        $book_logo = get_data(TBL_CHANNEL, array(
                                            'channel_id' => $reservation['channel_id']
                                        ))->row()->logo_book;
                                        $path      = "uploads/small/";
                                    }
?>
                                                  <img src="data:image/png;base64,<?php
                                    echo base64_encode(file_get_contents($path . $book_logo));
?>" class="pull-right ">
                                                    </button>
                                                    </a>
                                                    <div class="<?php
                                    echo $a_color . ' ' . $reservation['reservation_id'];
?>" id="<?php
                                    echo $reservation['reservation_id'] . '_' . $reservation['channel_id'];
?>" style="width:160px">
                                                    <ul>
                                                    <li>Room Name : <span class="pull-right">
                                                    <?php
                                    if ($reservation['channel_id'] == 0) {
                                        $room_details = get_data(TBL_PROPERTY, array(
                                            'property_id' => $reservation['room_id']
                                        ), 'property_name')->row_array();
                                        if (count($room_details) != '0') {
                                            echo ucfirst($room_details['property_name']);
                                        } else {
                                            echo '"No Room Set"';
                                        }
                                    } elseif ($reservation['channel_id'] == 11) {
                                        $roomName = @get_data(TBL_PROPERTY, array(
                                            'property_id' => get_data(MAP, array(
                                                'channel_id' => $reservation['channel_id'],
                                                'import_mapping_id' => get_data(IM_RECO, array(
                                                    'CODE' => $reservation['ROOMCODE']
                                                ))->row()->re_id
                                            ))->row()->property_id
                                        ))->row()->property_name;
                                        if ($roomName) {
                                            echo ucfirst($roomName);
                                        } else {
                                            echo '"No Room Set"';
                                        }
                                    } else if ($reservation['channel_id'] == 1) {
                                        
                                        $import_mapping_id = get_data('import_mapping', array(
                                            'user_id' => current_user_type(),
                                            'hotel_id' => hotel_id(),
                                            'roomtype_id' => $reservation['roomTypeID'],
                                            'rate_type_id' => $reservation['ratePlanID']
                                        ), 'map_id,rateAcquisitionType,rateplan_id')->row();
                                        if ($import_mapping_id->rateAcquisitionType == 'SellRate') {
                                            $import_mapping_ids = $import_mapping_id->map_id;
                                        } else {
                                            $import_mapping_ids = get_data('import_mapping', array(
                                                'user_id' => current_user_type(),
                                                'hotel_id' => hotel_id(),
                                                'rate_type_id' => $import_mapping_id->rateplan_id
                                            ), 'map_id,rateAcquisitionType,rateplan_id')->row();
                                            
                                            $import_mapping_ids = $import_mapping_ids->map_id;
                                        }
                                        $room_ids = get_data(MAP, array(
                                            'owner_id' => current_user_type(),
                                            'hotel_id' => hotel_id(),
                                            'channel_id' => $reservation['channel_id'],
                                            'import_mapping_id' => $import_mapping_ids
                                        ))->row();
                                        if ($room_ids->property_id != '' && $room_ids->rate_id == 0) {
                                            $roomName = @get_data(TBL_PROPERTY, array(
                                                'property_id' => $room_ids->property_id
                                            ))->row()->property_name;
                                        } else {
                                            $rateRoomName = @get_data(RATE_TYPES, array(
                                                'room_id' => $room_ids->property_id,
                                                'rate_type_id' => $room_ids->rate_id
                                            ))->row();
                                            if ($rateRoomName->rate_name != '') {
                                                $roomName = $rateRoomName->rate_name;
                                            } else {
                                                $roomName = '#' . $rateRoomName->uniq_id;
                                            }
                                        }
                                        if ($roomName) {
                                            echo ucfirst($roomName);
                                        } else {
                                            echo '"No Room Set"';
                                        }
                                    } elseif ($reservation['channel_id'] == 2) {
                                        $roomName = @get_data(TBL_PROPERTY, array(
                                            'property_id' => get_data(MAP, array(
                                                'channel_id' => $reservation['channel_id'],
                                                'import_mapping_id' => get_data(BOOKING, array(
                                                    'B_room_id' => $reservation['id'],
                                                    'B_rate_id' => $reservation['rate_id']
                                                ))->row()->import_mapping_id
                                            ))->row()->property_id
                                        ))->row()->property_name;
                                        if ($roomName) {
                                            echo ucfirst($roomName);
                                        } else {
                                            echo '"No Room Set"';
                                        }
                                    } elseif ($reservation['channel_id'] == 17) {
                                        $roomName = @get_data(TBL_PROPERTY, array(
                                            'property_id' => get_data(MAP, array(
                                                'channel_id' => $reservation['channel_id'],
                                                'import_mapping_id' => get_data(IM_BNOW, array(
                                                    'InvTypeCode' => $reservation['RoomTypeCode'],
                                                    'RatePlanCode' => $reservation['RatePlanCode']
                                                ))->row()->import_mapping_id
                                            ))->row()->property_id
                                        ))->row()->property_name;
                                        if ($roomName) {
                                            echo ucfirst($roomName);
                                        } else {
                                            echo '"No Room Set"';
                                        }
                                    } elseif ($reservation['channel_id'] == 5) {
                                        $htb_id = $this->db->query("SELECT map_id, TRIM(TRAILING '-' FROM  REPLACE(roomname,SUBSTRING_INDEX(roomname,'-',-1),'') ) as roomnames, TRIM(TRAILING '-' FROM  REPLACE(characterstics,SUBSTRING_INDEX(characterstics,'-',-1),'') ) as charactersticss FROM `" . IM_HOTELBEDS_ROOMS . "` where sequence='" . $reservation['Contract_Code'] . "' and contract_name='" . $reservation['Contract_Name'] . "' and contract_code='" . $reservation['IncomingOffice'] . "' and user_id='" . current_user_type() . "' and hotel_id='" . hotel_id() . "' having roomnames ='" . $reservation['Room_code'] . "' AND charactersticss ='" . $reservation['CharacteristicCode'] . "'");
                                        
                                        if ($htb_id->num_rows != 0) {
                                            $htb_id = $htb_id->row()->map_id;
                                            $htbid  = get_data(MAP, array(
                                                'channel_id' => $reservation['channel_id'],
                                                'import_mapping_id' => $htb_id
                                            ));
                                            if ($htbid->num_rows != 0) {
                                                $room_id  = $htbid->row()->property_id;
                                                $roomName = @get_data(TBL_PROPERTY, array(
                                                    'property_id' => $room_id
                                                ))->row()->property_name;
                                            }
                                        }
                                        
                                        if ($roomName) {
                                            echo ucfirst($roomName);
                                        } else {
                                            echo '"No Room Set"';
                                        }
                                    } elseif ($reservation['channel_id'] == 15) {
                                        $roomName = @get_data(TBL_PROPERTY, array(
                                            'property_id' => get_data(MAP, array(
                                                'channel_id' => $reservation['channel_id'],
                                                'import_mapping_id' => get_data(IM_TRAVELREPUBLIC, array(
                                                    'RoomTypeId' => $reservation['room_id']
                                                ))->row()->map_id
                                            ))->row()->property_id
                                        ))->row()->property_name;
                                        if ($roomName) {
                                            echo ucfirst($roomName);
                                        } else {
                                            echo '"No Room Set"';
                                        }
                                    }
?></span></li>
                                                    <li>Room Number : <span class="pull-right"><?php
                                    echo (isset($reservation['RoomNumber']) ? $reservation['RoomNumber'] : 'Unassigned number');
?></span></li>
                                                    <li>Name : <span class="pull-right"><?php
                                    echo $reservation_name;
?></span></li>
                                                    <li>Booking ID : <span class="pull-right"><?php
                                    echo $reservation['reservation_code'];
?></span></li>
                                                    <li>Arrival : <span class="pull-right"><?php
                                    echo $reservation['start_date'];
?></span></li> 
                                                    <li>Departure : <span class="pull-right"><?php
                                    echo $reservation['end_date'];
?></span></li>
                                                    <li>Days : <span class="pull-right"><?php
                                    echo $reservation['num_nights'];
?></span></li>
                                                    <li>Guest : <span class="pull-right"><?php
                                    echo $reservation['members_count'];
?></span></li>
                                                    <li>Price : <span class="pull-right"><?php
                                    if ($reservation['channel_id'] == 0) {
                                        echo $reservation['num_rooms'] * $reservation['price'];
                                    } else {
                                        echo $reservation['price'];
                                    }
?> 
                                                        <?php
                                    if ($reservation['channel_id'] != 15) {
                                        echo $currency;
                                    } else {
                                        $cur = get_data(IM_TRAVEL_RESER, array(
                                            'BookingId' => $reservation['id']
                                        ), 'CurrencyCode')->row()->CurrencyCode;
                                        echo $cur;
                                    }
?></span></li>
                                                    </ul>
                                                    </div>
                                                <?php
                                }
?>
                                      </td> <?php
                            } else {
                                $single = get_data(TBL_UPDATE, array(
                                    'individual_channel_id' => 0,
                                    'owner_id' => current_user_type(),
                                    'hotel_id' => hotel_id(),
                                    'room_id' => $property_id,
                                    'separate_date' => $date->format("d/m/Y")
                                ), 'availability,stop_sell')->row();
                                if (count($single) != '0') {
                                    if ($single->availability == 0) {
                                        echo '<td class="droppable" roomId="' . $property_id . '_' . $roomNumber . '" date="' . $ss_rr . '"> </td>';
                                    } elseif ($single->stop_sell == 1) {
                                        echo '<td class="droppable" roomId="' . $property_id . '_' . $roomNumber . '" date="' . $ss_rr . '"> </td>';
                                    } else {
                                        if ($roomNumber != 'Unassigned number') {
                                            echo '<td class="droppable" roomId="' . $property_id . '_' . $roomNumber . '" date="' . $ss_rr . '"> </td>';
                                        } else {
                                            echo '<td class="droppable" roomId="' . $property_id .'_0" date="' . $ss_rr . '"> </td>';
                                        }
                                    }
                                } else {
                                    if ($roomNumber != 'Unassigned number') {
                                        echo '<td class="droppable" date="' . $ss_rr . '" roomId="' . $property_id . '_' . $roomNumber . '"> </td>';
                                    } else {
                                        echo '<td class="droppable" roomId="' . $property_id .'_0" date="' . $ss_rr . '"> </td>';
                                    }
                                }
                            }
                        }
                        echo '</tr>';
                        $s++;
                    }
                }
            } else {
                $s = 0;
                if (count($all_room) != 0) {
                    foreach ($all_room as $room) {
                        extract($room);
                        if ($existing_room_number != '') {
                            $roomsArr = explode(',', $existing_room_number);
                            foreach ($roomsArr as $key => $roomNum) {
                                echo '<tr class="p-b-o contents4_' . $property_id . '" id="' . $property_id . '_' . $s . '" custom="' . $property_id . '" show_value="' . $s . '">
                                            <td class="ha2">' . $roomNum . '</td>
                                            <td class="ha2">&nbsp;</td>';
                                
                                foreach ($period as $date) {
                                    echo '<td> </td>';
                                }
                                
                                echo '</tr>';
                                $s++;
                            }
                        }
                    }
                }
            }
        }
    }

    function changeResRoom()
    {
        extract($this->input->post());
        list($reservation_id, $channel_id) = explode('_', $ResId);
        list($room_id, $room_num) = explode('_', $RoomId);
        if ($channel_id == 0) {
            $manualreservation = $this->db->query("SELECT * FROM `" . RESERVATION . "` WHERE user_id ='" . current_user_type() . "' AND hotel_id = '" . hotel_id() . "' AND reservation_id ='" . $reservation_id . "'");
            if ($manualreservation) {
                $resArr = $manualreservation->row();
                if ($resArr) {
                    if ($resArr->status == 'Canceled') {
                        echo 'Reservation is cancelled.You can\'t modify Room.';
                        exit;
                    } else {
                        $chkResv = $this->db->query("SELECT * FROM `reservation_reassign` WHERE user_id ='" . current_user_type() . "' AND hotel_id = '" . hotel_id() . "' AND reservation_id ='" . $reservation_id . "'");
                        if ($chkResv) {
                            if ($chkResv->num_rows != 0) {                              
                                $startDate = DateTime::createFromFormat("d/m/Y", $resArr->start_date);
                                $dateChanged = 0;
                                if($newdate != $resArr->start_date){
                                    $dateChanged  = 1;
                                    list($day1,$mon1,$yr1)  = explode('/',$resArr->start_date);
                                    list($day2,$mon2,$yr2)  = explode('/',$resArr->end_date);
                                    list($nd,$nm,$ny)       = explode('/',$newdate);
                                    $datetime1              = new DateTime($yr1.'-'.$mon1.'-'.$day1);
                                    $datetime2              = new DateTime($yr2.'-'.$mon2.'-'.$day2);
                                    $interval               = $datetime1->diff($datetime2);
                                    $tempdate               = new DateTime($ny.'-'.$nm.'-'.$nd);
                                    $startDate              = new DateTime($ny.'-'.$nm.'-'.$nd);
                                    $tempdate->add($interval);
                                    $tempend                = $tempdate->format("d/m/Y");
                                    $endDate                = DateTime::createFromFormat("d/m/Y", $tempend);
                                }else{
                                    $endDate                = DateTime::createFromFormat("d/m/Y",$resArr->end_date);
                                }
                                
                                $periodInterval = new DateInterval("P1D");                          
                                $period = new DatePeriod($startDate, $periodInterval, $endDate);
                                $notAvailable = 0;
                                foreach ($period as $date) {
                                    $get_available = $this->db->query("SELECT * FROM `" . TBL_UPDATE . "` WHERE owner_id ='" . current_user_type() . "' AND hotel_id = '" . hotel_id() . "' AND `individual_channel_id`='0' AND `room_id`='$room_id' AND `separate_date`='".$date->format("d/m/Y")."'");
                                    if($get_available){
                                        $resRow = $get_available->row();
                                        if($resRow->availability == 0){
                                            $notAvailable = 1;
                                        } else if ($resRow->stop_sell == 1) {
                                            $notAvailable = 1;
                                        }
                                    }
                                }
                                if($notAvailable == 0){
                                    $chkResvArr                 = $chkResv->row();
                                    $chkResvdata['property_id'] = $room_id;
                                    $chkResvdata['RoomNumber']  = $room_num;
                                    
                                    $chkResvwhere['user_id']        = current_user_type();
                                    $chkResvwhere['hotel_id']       = hotel_id();
                                    $chkResvwhere['channel_id']     = $channel_id;
                                    $chkResvwhere['reservation_id'] = $reservation_id;
                                    update_data('reservation_reassign', $chkResvdata, $chkResvwhere);
                                    //echo $this->db->last_query();
                                    if($chkResvArr->property_id != $room_id){
                                        $this->increment(0,$chkResvArr->property_id,$resArr->start_date,$resArr->end_date,$resArr->num_rooms);

                                        if($dateChanged == 1){
                                            $this->db->query("UPDATE `" . RESERVATION . "` SET `start_date`='".$startDate->format("d/m/Y")."',`end_date`='".$endDate->format("d/m/Y")."' WHERE user_id ='" . current_user_type() . "' AND hotel_id = '" . hotel_id() . "' AND reservation_id ='" . $reservation_id . "'");
                                            $this->decrement(0,$room_id,$startDate->format("d/m/Y"),$endDate->format("d/m/Y"),$resArr->num_rooms);
                                        }else{
                                            $this->decrement(0,$room_id,$resArr->start_date,$resArr->end_date,$resArr->num_rooms);
                                        }
                                    }elseif($dateChanged == 1){
                                        $this->increment(0,$chkResvArr->property_id,$resArr->start_date,$resArr->end_date,$resArr->num_rooms);
                                        $this->db->query("UPDATE `" . RESERVATION . "` SET `start_date`='".$startDate->format("d/m/Y")."',`end_date`='".$endDate->format("d/m/Y")."' WHERE user_id ='" . current_user_type() . "' AND hotel_id = '" . hotel_id() . "' AND reservation_id ='" . $reservation_id . "'");
                                        $this->decrement(0,$room_id,$startDate->format("d/m/Y"),$endDate->format("d/m/Y"),$resArr->num_rooms);
                                    }
                                    echo 'Room Changed';
                                }else{
                                    echo 'Not changed';
                                }
                            } else {
                                if (($resArr->room_id != $room_id) || ($newdate != $resArr->start_date)) {
                                    $startDate = DateTime::createFromFormat("d/m/Y", $resArr->start_date); 
                                    $dateChanged = 0;
                                    if($newdate != $resArr->start_date){
                                        $dateChanged  = 1;
                                        list($day1,$mon1,$yr1)  = explode('/',$resArr->start_date);
                                        list($day2,$mon2,$yr2)  = explode('/',$resArr->end_date);
                                        list($nd,$nm,$ny)       = explode('/',$newdate);
                                        $datetime1              = new DateTime($yr1.'-'.$mon1.'-'.$day1);
                                        $datetime2              = new DateTime($yr2.'-'.$mon2.'-'.$day2);
                                        $interval               = $datetime1->diff($datetime2);
                                        $tempdate               = new DateTime($ny.'-'.$nm.'-'.$nd);
                                        
                                        $startDate              = new DateTime($ny.'-'.$nm.'-'.$nd);
                                        $tempdate->add($interval);
                                        $tempend                = $tempdate->format("d/m/Y");
                                        $endDate                = DateTime::createFromFormat("d/m/Y", $tempend);
                                    }else{
                                        $endDate                = DateTime::createFromFormat("d/m/Y",$resArr->end_date);
                                    }
                                    $periodInterval = new DateInterval("P1D");                          
                                    $period = new DatePeriod($startDate, $periodInterval, $endDate);
                                    $notAvailable = 0;
                                    foreach ($period as $date) {
                                        $get_available = $this->db->query("SELECT * FROM `" . TBL_UPDATE . "` WHERE owner_id ='" . current_user_type() . "' AND hotel_id = '" . hotel_id() . "' AND `individual_channel_id`='0' AND `room_id`='$room_id' AND `separate_date`='".$date->format("d/m/Y")."'");
                                        if($get_available){
                                            $resRow = $get_available->row();
                                            if($resRow->availability == 0){
                                                $notAvailable = 1;
                                            } else if ($resRow->stop_sell == 1) {
                                                $notAvailable = 1;
                                            }
                                        }
                                    }
                                    if($notAvailable == 0){
                                        $chkResv = $this->db->query("SELECT * FROM `reservation_reassign` WHERE user_id ='" . current_user_type() . "' AND hotel_id = '" . hotel_id() . "' AND reservation_id ='" . $reservation_id . "'");
                                        if ($chkResv) {
                                            if ($chkResv->num_rows != 0) {
                                                $chkResvArr                 = $chkResv->row();
                                                $chkResvdata['property_id'] = $room_id;
                                                $chkResvdata['RoomNumber']  = $room_num;
                                                
                                                $chkResvwhere['user_id']        = current_user_type();
                                                $chkResvwhere['hotel_id']       = hotel_id();
                                                $chkResvwhere['channel_id']     = $channel_id;
                                                $chkResvwhere['reservation_id'] = $reservation_id;
                                                update_data('reservation_reassign', $chkResvdata, $chkResvwhere);
                                                echo $this->db->last_query();
                                                if($chkResvArr->property_id != $room_id){
                                                    $this->increment(0,$chkResvArr->property_id,$resArr->start_date,$resArr->end_date,$resArr->num_rooms);

                                                    if($dateChanged == 1){
                                                        $this->db->query("UPDATE `" . RESERVATION . "` SET `start_date`='".$startDate->format("d/m/Y")."',`end_date`='".$endDate->format("d/m/Y")."' WHERE user_id ='" . current_user_type() . "' AND hotel_id = '" . hotel_id() . "' AND reservation_id ='" . $reservation_id . "'");
                                                        $this->decrement(0,$room_id,$startDate->format("d/m/Y"),$endDate->format("d/m/Y"),$resArr->num_rooms);
                                                    }else{
                                                        $this->decrement(0,$room_id,$resArr->start_date,$resArr->end_date,$resArr->num_rooms);
                                                    }
                                                }elseif($dateChanged == 1){
                                                    $this->increment(0,$chkResvArr->property_id,$resArr->start_date,$resArr->end_date,$resArr->num_rooms);
                                                    $this->db->query("UPDATE `" . RESERVATION . "` SET `start_date`='".$startDate->format("d/m/Y")."',`end_date`='".$endDate->format("d/m/Y")."' WHERE user_id ='" . current_user_type() . "' AND hotel_id = '" . hotel_id() . "' AND reservation_id ='" . $reservation_id . "'");
                                                    $this->decrement(0,$room_id,$startDate->format("d/m/Y"),$endDate->format("d/m/Y"),$resArr->num_rooms);
                                                }
                                                echo 'Room Changed';
                                            } else {
                                                $chkResvdata['user_id']        = current_user_type();
                                                $chkResvdata['hotel_id']       = hotel_id();
                                                $chkResvdata['channel_id']     = $channel_id;
                                                $chkResvdata['reservation_id'] = $reservation_id;
                                                $chkResvdata['property_id']    = $room_id;
                                                $chkResvdata['RoomNumber']     = $room_num;
                                                insert_data('reservation_reassign', $chkResvdata);
                                                $this->increment(0,$resArr->room_id,$resArr->start_date,$resArr->end_date,$resArr->num_rooms);

                                                if($dateChanged == 1){
                                                    $this->db->query("UPDATE `" . RESERVATION . "` SET `start_date`='".$startDate->format("d/m/Y")."',`end_date`='".$endDate->format("d/m/Y")."' WHERE user_id ='" . current_user_type() . "' AND hotel_id = '" . hotel_id() . "' AND reservation_id ='" . $reservation_id . "'");
                                                    $this->decrement(0,$room_id,$startDate->format("d/m/Y"),$endDate->format("d/m/Y"),$resArr->num_rooms);
                                                }else{
                                                    $this->decrement(0,$room_id,$resArr->start_date,$resArr->end_date,$resArr->num_rooms);
                                                }
                                                
                                                echo 'Room Changed';
                                            }
                                        }else{
                                            echo 'Not changed';
                                        }
                                    }else{
                                        echo 'Not changed';
                                    }
                                } else {
                                    $updata['RoomNumber'] = $room_num;
                                    $where['reservation_id'] = $reservation_id;

                                    update_data(RESERVATION, $updata, $where);
                                    echo $this->db->last_query();

                                    echo 'Number changed';
                                }
                            }
                        }
                    }
                } else {
                    echo "Some Error Occurred.";
                }
            } else {
                echo 'Reservation Not Found.';
                exit;
            }
        } else if ($channel_id == 2) {
            $channelReservation = $this->db->query("SELECT id, rate_id, status, arrival_date, departure_date FROM `" . BOOK_ROOMS . "` WHERE user_id ='" . current_user_type() . "' AND hotel_hotel_id = '" . hotel_id() . "' AND room_res_id ='" . $reservation_id . "'");
            //echo $this->db->last_query();
            if ($channelReservation) {
                $resArr = $channelReservation->row();
                if ($resArr) {
                    if ($resArr->status == 'cancelled') {
                        echo 'Reservation is cancelled.You can\'t modify Room.';
                        exit;
                    } else {
                        $chkmapquery = $this->db->query("SELECT property_id FROM `" . MAP . "` WHERE owner_id ='" . current_user_type() . "' AND hotel_id = '" . hotel_id() . "' AND `channel_id`='2' AND `import_mapping_id` = (SELECT `import_mapping_id` FROM `" . BOOKING . "` WHERE `B_room_id`='$resArr->id' AND `B_rate_id`='$resArr->rate_id')");
                        //echo $this->db->last_query();
                        if ($chkmapquery) {
                            $roomMap = $chkmapquery->row();
                            $chkResv = $this->db->query("SELECT * FROM `reservation_reassign` WHERE user_id ='" . current_user_type() . "' AND hotel_id = '" . hotel_id() . "' AND reservation_id ='" . $reservation_id . "'");
                            if ($chkResv) {
                                if ($chkResv->num_rows != 0) {
                                    $startDate = DateTime::createFromFormat("d/m/Y", date('d/m/Y',strtotime($resArr->arrival_date))); 
                                    $endDate = DateTime::createFromFormat("d/m/Y", date('d/m/Y',strtotime($resArr->departure_date)));
                                    $periodInterval = new DateInterval("P1D");                          
                                    $period = new DatePeriod($startDate, $periodInterval, $endDate);
                                    $notAvailable = 0;
                                    foreach ($period as $date) {
                                        $get_available = $this->db->query("SELECT * FROM `" . TBL_UPDATE . "` WHERE owner_id ='" . current_user_type() . "' AND hotel_id = '" . hotel_id() . "' AND `individual_channel_id`='0' AND `room_id`='$room_id' AND `separate_date`='".$date->format("d/m/Y")."'");
                                        if($get_available){
                                            $resRow = $get_available->row();
                                            if($resRow->availability == 0){
                                                $notAvailable = 1;
                                            } else if ($resRow->stop_sell == 1) {
                                                $notAvailable = 1;
                                            }
                                        }
                                    }
                                    if($notAvailable == 0){
                                        $chkResvArr                 = $chkResv->row();
                                        $chkResvdata['property_id'] = $room_id;
                                        $chkResvdata['RoomNumber']  = $room_num;
                                        
                                        $chkResvwhere['user_id']        = current_user_type();
                                        $chkResvwhere['hotel_id']       = hotel_id();
                                        $chkResvwhere['channel_id']     = $channel_id;
                                        $chkResvwhere['reservation_id'] = $reservation_id;
                                        update_data('reservation_reassign', $chkResvdata, $chkResvwhere);
                                        if($chkResvArr->property_id != $room_id){
                                            $this->increment(0,$chkResvArr->property_id,date('d/m/Y',strtotime($resArr->arrival_date)),date('d/m/Y',strtotime($resArr->departure_date)),1);
                                            $this->decrement(0,$room_id,date('d/m/Y',strtotime($resArr->arrival_date)),date('d/m/Y',strtotime($resArr->departure_date)),1);
                                        }
                                    }
                                } else {
                                    if ($roomMap->property_id != $room_id) {
                                        $startDate = DateTime::createFromFormat("d/m/Y",date('d/m/Y',strtotime($resArr->arrival_date))); 
                                        $endDate = DateTime::createFromFormat("d/m/Y",date('d/m/Y',strtotime($resArr->departure_date)));
                                        $periodInterval = new DateInterval("P1D");                          
                                        $period = new DatePeriod($startDate, $periodInterval, $endDate);
                                        $notAvailable = 0;
                                        foreach ($period as $date) {
                                            $get_available = $this->db->query("SELECT * FROM `" . TBL_UPDATE . "` WHERE owner_id ='" . current_user_type() . "' AND hotel_id = '" . hotel_id() . "' AND `individual_channel_id`='0' AND `room_id`='$room_id' AND `separate_date`='".$date->format("d/m/Y")."'");
                                            if($get_available){
                                                $resRow = $get_available->row();
                                                if($resRow->availability == 0){
                                                    $notAvailable = 1;
                                                } else if ($resRow->stop_sell == 1) {
                                                    $notAvailable = 1;
                                                }
                                            }
                                        }
                                        if($notAvailable == 0){
                                            $chkResv = $this->db->query("SELECT * FROM `reservation_reassign` WHERE user_id ='" . current_user_type() . "' AND hotel_id = '" . hotel_id() . "' AND reservation_id ='" . $reservation_id . "'");
                                            if ($chkResv) {
                                                if ($chkResv->num_rows != 0) {
                                                    $chkResvArr                 = $chkResv->row();
                                                    
                                                    $chkResvdata['property_id'] = $room_id;
                                                    $chkResvdata['RoomNumber']  = $room_num;
                                                    
                                                    $chkResvwhere['user_id']        = current_user_type();
                                                    $chkResvwhere['hotel_id']       = hotel_id();
                                                    $chkResvwhere['channel_id']     = $channel_id;
                                                    $chkResvwhere['reservation_id'] = $reservation_id;
                                                    update_data('reservation_reassign', $chkResvdata, $chkResvwhere);
                                                    if($chkResvArr->property_id != $room_id){
                                                        $this->increment(0,$chkResvArr->property_id,date('d/m/Y',strtotime($resArr->arrival_date)),date('d/m/Y',strtotime($resArr->departure_date)),1);
                                                        $this->decrement(0,$room_id,date('d/m/Y',strtotime($resArr->arrival_date)),date('d/m/Y',strtotime($resArr->departure_date)),1);
                                                    }
                                                } else {
                                                    $chkResvdata['user_id']        = current_user_type();
                                                    $chkResvdata['hotel_id']       = hotel_id();
                                                    $chkResvdata['channel_id']     = $channel_id;
                                                    $chkResvdata['reservation_id'] = $reservation_id;
                                                    $chkResvdata['property_id']    = $room_id;
                                                    $chkResvdata['RoomNumber']     = $room_num;
                                                    insert_data('reservation_reassign', $chkResvdata);

                                                    $this->increment(0,$roomMap->property_id,date('d/m/Y',strtotime($resArr->arrival_date)),date('d/m/Y',strtotime($resArr->departure_date)),1);
                                                    $this->decrement(0,$room_id,date('d/m/Y',strtotime($resArr->arrival_date)),date('d/m/Y',strtotime($resArr->departure_date)),1);
                                                }
                                            }
                                        }
                                    } else {
                                        $updata['RoomNumber'] = $room_num;
                                        $where['room_res_id'] = $reservation_id;
                                        update_data(BOOK_ROOMS, $updata, $where);
                                        //echo $this->db->last_query();
                                    }
                                }
                            }
                        }
                    }
                } else {
                    echo "Some Error Occurred.";
                }
            } else {
                echo 'Reservation Not Found.';
                exit;
            }
        }
    }
    
    /* Main Calendar Functionality End */
    
    /* Channel Calendar Functionality Start */
    
    function showRestictions()
    {
        if (!IS_AJAX) {
            $this->load->view('admin/404');
        } else {
            extract($this->input->post());
            
            $con_ch = $channel;
            
            $startDate = DateTime::createFromFormat("d/m/Y", $cal_start);
            
            $cal_end = date('d/m/Y', strtotime('+1 day', strtotime(date('Y-m-d', strtotime(str_replace("/", "-", $cal_end))))));
            
            $endDate = DateTime::createFromFormat("d/m/Y", $cal_end);
            
            $periodInterval = new DateInterval("P1D");
            
            //$endDate->add( $periodInterval );
            
            $period = new DatePeriod($startDate, $periodInterval, $endDate);
            
            $da = array();
            
            foreach ($period as $show_dates) {
                $da[] = $show_dates->format("d/m/Y");
            }
            
            $all_propertyid = $this->channel_model->getMappedRoom_Rate($channel);
            
            if (count($all_propertyid != 0)) {
                foreach ($all_propertyid as $propertyid) {
                    $pid = $propertyid->property_id;
                    
                    $rate_id = $propertyid->rate_id;
                    
                    if ($rate_id != "0") {
                        $ratename = get_data(RATE_TYPES, array(
                            'rate_type_id' => $rate_id
                        ))->row();
                        if ($ratename->rate_name != '') {
                            $rate_name = $ratename->rate_name;
                        } else {
                            $rate_name = '#' . $ratename->uniq_id;
                        }
                    }
                    
                    $all_room = get_data(TBL_PROPERTY, array(
                        'property_id' => $pid,
                        'status' => 'Active',
                        'owner_id' => current_user_type(),
                        'droc' => '1'
                    ), 'property_id,non_refund,member_count,property_name,pricing_type')->result_array();
                    
                    foreach ($all_room as $room) {
                        extract($room);
                        
                        if ($non_refund == 1) {
                            $members = $member_count + $member_count;
                        } else {
                            $members = $member_count;
                        }
                        if ($rate_id == "0") {
                            $main_available = get_data(MAP, array(
                                'property_id' => $property_id,
                                'rate_id' => '0',
                                'guest_count' => '0',
                                'refun_type' => '0',
                                'channel_id' => $con_ch
                            ))->row_array();
                            if (count($main_available) != 0) {
                                if ($source == 'm') {
?>
                                   <tr class="p-b-o msccc m_<?php
                                    echo $con_ch;
?> mm_main_room" room_id="<?php
                                    echo $property_id;
?>">
                                    <td bgcolor="#a6a6a6">M</td>
                                    <?php
                                    foreach ($period as $date) {
                                        $i = $date->format("d");
                                        
                                        $ss_da = $date->format("d/m/Y");
                                        
                                        if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                            $color = '#d9d9d9';
                                        } else {
                                            $color = '#FFFFFF';
                                        }
                                        if (in_array($ss_da, $da)) {
                                            $single = get_data(TBL_UPDATE, array(
                                                'individual_channel_id' => $con_ch,
                                                'owner_id' => current_user_type(),
                                                'hotel_id' => hotel_id(),
                                                'room_id' => $property_id,
                                                'separate_date' => $ss_da
                                            ), 'availability,minimum_stay,stop_sell')->row();
                                            if (count($single) != '0') {
                                                if ($single->availability == 0) {
                                                    $color = '#FF0000';
                                                } elseif ($single->stop_sell == 1) {
                                                    $color = '#CC99FF';
                                                }
?>
                                                   <td bgcolor="<?php //echo $color;
?>"><?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php
                                                    echo $ss_da . '-' . $property_id . '~' . $con_ch;
?>" data-url="<?php
                                                    echo lang_url();
?>inventory/inline_edit_channel" data-title="Change Minimum Stay"><?php
                                                    echo ceil($single->minimum_stay);
?> </a><?php
                                                } else {
?><?php
                                                    echo ceil($single->minimum_stay);
?> <?php
                                                }
?></td>
                            
                                            <?php
                                            } else {
?>
                                                   <td bgcolor="<?php //echo $color;
?>"> <?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php
                                                    echo $ss_da . '-' . $property_id . '~' . $con_ch;
?>" data-url="<?php
                                                    echo lang_url();
?>inventory/inline_edit_channel" data-title="Change Minimum Stay">N/A </a><?php
                                                } else {
?> N/A <?php
                                                }
?></td>
                                            <?php
                                            }
                                        } else {
?> 
                                                    <td bgcolor="<?php //echo $color;
?>"><?php
                                            if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php
                                                echo $ss_da . '-' . $property_id . '~' . $con_ch;
?>" data-url="<?php
                                                echo lang_url();
?>inventory/inline_edit_channel" data-title="Change Minimum Stay">N/A </a><?php
                                            } else {
?> N/A <?php
                                            }
?></td>
                                            <?php
                                        }
                                    }
?>
                                   </tr>
                                <?php
                                }
                                if ($source == 's') {
?>
                                   <tr class="p-b-o msccc s_<?php
                                    echo $con_ch;
?> ss_main_room" room_id="<?php
                                    echo $property_id;
?>">
                                    <td bgcolor="#a6a6a6">SS</td>
                                    <?php
                                    foreach ($period as $date) {
                                        $i = $date->format("d");
                                        
                                        $ss_da = $date->format("d/m/Y");
                                        
                                        if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                            $color = '#d9d9d9';
                                        } else {
                                            $color = '#FFFFFF';
                                        }
                                        if (in_array($ss_da, $da)) {
                                            $single = get_data(TBL_UPDATE, array(
                                                'individual_channel_id' => $con_ch,
                                                'owner_id' => current_user_type(),
                                                'hotel_id' => hotel_id(),
                                                'room_id' => $property_id,
                                                'separate_date' => $ss_da
                                            ), 'availability,price,stop_sell')->row();
                                            if (count($single) != '0') {
                                                if ($single->availability == 0) {
                                                    $color = '#FF0000';
                                                } elseif ($single->stop_sell == 1) {
                                                    $color = '#CC99FF';
                                                }
?>
                                                   <td bgcolor="<?php //echo $color
?>"><?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><input type="checkbox" value="1" <?php
                                                    if ($single->stop_sell == '1') {
?> checked="checked" name="room[<?php
                                                        echo $property_id;
?>][stop_sell][<?php
                                                        echo $ss_da;
?>]" class="update_stop_sell" <?php
                                                    } else {
?> name="new_room[<?php
                                                        echo $property_id;
?>][stop_sell][<?php
                                                        echo $ss_da;
?>]" <?php
                                                    }
?> custom="<?php
                                                    echo $con_ch;
?>"><?php
                                                } else {
?><input type="checkbox"   value="1" <?php
                                                    if ($single->stop_sell == '1') {
?> checked="checked" name="room[<?php
                                                        echo $property_id;
?>][stop_sell][<?php
                                                        echo $ss_da;
?>]" <?php
                                                    } else {
?> name="new_room[<?php
                                                        echo $property_id;
?>][stop_sell][<?php
                                                        echo $ss_da;
?>]" <?php
                                                    }
?> disabled="disabled"><?php
                                                }
?></td>
                                            <?php
                                            } else {
?>
                                                   <td bgcolor="<?php //echo $color
?>"><?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d')) {
?><input type="checkbox"  name="new_room[<?php
                                                    echo $property_id;
?>][stop_sell][<?php
                                                    echo $ss_da;
?>]" value="1"><?php
                                                } else {
?> N/A<?php
                                                }
?></td>
                                            <?php
                                            }
                                        } else {
?> 
                                                <td bgcolor="<?php //echo $color
?>"><?php
                                            if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d')) {
?><input type="checkbox"  name="new_room[<?php
                                                echo $property_id;
?>][stop_sell][<?php
                                                echo $ss_da;
?>]" value="1"><?php
                                            } else {
?>N/A<?php
                                            }
?></td>
                                        <?php
                                        }
                                    }
?>
                                   </tr>
                                <?php
                                }
                                if ($source == 'c1') {
?>
                                   <tr class="p-b-o msccc c1_<?php
                                    echo $con_ch;
?> cta_main_room" room_id="<?php
                                    echo $property_id;
?>">
                                    <td bgcolor="#a6a6a6">CTA</td>
                                    <?php
                                    foreach ($period as $date) {
                                        $i     = $date->format("d");
                                        $ss_da = $date->format("d/m/Y");
                                        
                                        if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                            $color = '#d9d9d9';
                                        } else {
                                            $color = '#FFFFFF';
                                        }
                                        if (in_array($ss_da, $da)) {
                                            $single = get_data(TBL_UPDATE, array(
                                                'individual_channel_id' => $con_ch,
                                                'owner_id' => current_user_type(),
                                                'hotel_id' => hotel_id(),
                                                'room_id' => $property_id,
                                                'separate_date' => $ss_da
                                            ), 'availability,cta,stop_sell')->row();
                                            if (count($single) != '0') {
                                                if ($single->availability == 0) {
                                                    $color = '#FF0000';
                                                } elseif ($single->stop_sell == 1) {
                                                    $color = '#CC99FF';
                                                }
?>
                                                   <td bgcolor="<?php //echo $color
?>"><?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><input type="checkbox"  <?php
                                                    if ($single->cta == '1') {
?> checked="checked" name="room[<?php
                                                        echo $property_id;
?>][cta][<?php
                                                        echo $ss_da;
?>]" class="update_stop_sell" <?php
                                                    } else {
?> name="new_room[<?php
                                                        echo $property_id;
?>][cta][<?php
                                                        echo $ss_da;
?>]" <?php
                                                    }
?> value="1"  custom="<?php
                                                    echo $con_ch;
?>"><?php
                                                } else {
?><input type="checkbox"  <?php
                                                    if ($single->cta == '1') {
?> checked="checked" name="room[<?php
                                                        echo $property_id;
?>][cta][<?php
                                                        echo $ss_da;
?>]" <?php
                                                    } else {
?> name="new_room[<?php
                                                        echo $property_id;
?>][cta][<?php
                                                        echo $ss_da;
?>]" <?php
                                                    }
?> value="1" disabled="disabled"><?php
                                                }
?></td>
                            
                                            <?php
                                            } else {
?>
                                                   <td bgcolor="<?php //echo $color
?>"><input type="checkbox"  name="new_room[<?php
                                                echo $property_id;
?>][cta][<?php
                                                echo $ss_da;
?>]" value="1" ></td>                
                                            <?php
                                            }
                                        } else {
?> 
                                                    <td bgcolor="<?php //echo $color
?>"><input type="checkbox"  name="new_room[<?php
                                            echo $property_id;
?>][cta][<?php
                                            echo $ss_da;
?>]" value="1" ></td>
                            
                                            <?php
                                        }
                                    }
?>
                                   </tr>    
                                    <?php
                                }
                                if ($source == 'c2') {
?>
                                   <tr class="p-b-o msccc c2_<?php
                                    echo $con_ch;
?> ctd_main_room" room_id="<?php
                                    echo $property_id;
?>">
                                    <td bgcolor="#a6a6a6">CTD</td>
                                    <?php
                                    foreach ($period as $date) {
                                        $i = $date->format("d");
                                        
                                        $ss_da = $date->format("d/m/Y");
                                        
                                        if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                            $color = '#d9d9d9';
                                        } else {
                                            $color = '#FFFFFF';
                                        }
                                        
                                        if (in_array($ss_da, $da)) {
                                            $single = get_data(TBL_UPDATE, array(
                                                'individual_channel_id' => $con_ch,
                                                'owner_id' => current_user_type(),
                                                'hotel_id' => hotel_id(),
                                                'room_id' => $property_id,
                                                'separate_date' => $ss_da
                                            ), 'availability,ctd,stop_sell')->row();
                                            if (count($single) != '0') {
                                                if ($single->availability == 0) {
                                                    $color = '#FF0000';
                                                } elseif ($single->stop_sell == 1) {
                                                    $color = '#CC99FF';
                                                }
                                                
?>
                                                   <td bgcolor="<?php //echo $color
?>"><?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit())) {
?><input type="checkbox"  <?php
                                                    if ($single->ctd == '1') {
?> checked="checked" name="room[<?php
                                                        echo $property_id;
?>][ctd][<?php
                                                        echo $ss_da;
?>]" class="update_stop_sell" <?php
                                                    } else {
?> name="new_room[<?php
                                                        echo $property_id;
?>][ctd][<?php
                                                        echo $ss_da;
?>]" <?php
                                                    }
?> value="1"  custom="<?php
                                                    echo $con_ch;
?>"><?php
                                                } else {
?><input type="checkbox"  <?php
                                                    if ($single->ctd == '1') {
?> checked="checked" name="room[<?php
                                                        echo $property_id;
?>][ctd][<?php
                                                        echo $ss_da;
?>]" <?php
                                                    } else {
?> name="new_room[<?php
                                                        echo $property_id;
?>][ctd][<?php
                                                        echo $ss_da;
?>]" <?php
                                                    }
?> value="1" disabled="disabled"><?php
                                                }
?></td>
                            
                                            <?php
                                            } else {
?>
                                                   <td bgcolor="<?php //echo $color
?>"><input type="checkbox"  name="new_room[<?php
                                                echo $property_id;
?>][ctd][<?php
                                                echo $ss_da;
?>]" value="1"></td>
                                            <?php
                                            }
                                        } else {
?>
                                                   <td bgcolor="<?php //echo $color
?>"><input type="checkbox"  name="new_room[<?php
                                            echo $property_id;
?>][ctd][<?php
                                            echo $ss_da;
?>]" value="1"  ></td>
                            
                                            <?php
                                        }
                                    }
?>
                                   </tr>
                                    <?php
                                }
                            }
                            if ($pricing_type == 1 && $non_refund == 1) {
                                $v             = 1;
                                $refun         = 2;
                                $sub_available = get_data(MAP, array(
                                    'property_id' => $property_id,
                                    'rate_id' => '0',
                                    'guest_count' => $v,
                                    'refun_type' => $refun,
                                    'channel_id' => $con_ch
                                ))->row_array();
                                if (count($sub_available) != 0) {
                                    if ($source == 'm') {
?>
                                       <tr class="p-b-o msccc m_<?php
                                        echo $con_ch;
?>">
                                        <td bgcolor="#a6a6a6">M</td>
                                        <?php
                                        foreach ($period as $date) {
                                            $i = $date->format("d");
                                            
                                            $ss_da = $date->format("d/m/Y");
                                            
                                            if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                                $color = '#d9d9d9';
                                            } else {
                                                $color = '#FFFFFF';
                                            }
                                            if (in_array($ss_da, $da)) {
                                                if (user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
                                                    $single = get_data('room_rate_types_additional', array(
                                                        'individual_channel_id' => $con_ch,
                                                        'owner_id' => current_user_type(),
                                                        'hotel_id' => hotel_id(),
                                                        'rate_types_id' => $rate_id,
                                                        'room_id' => $property_id,
                                                        'separate_date' => $ss_da,
                                                        'guest_count' => $v,
                                                        'refun_type' => $refun
                                                    ), 'availability,minimum_stay,stop_sell')->row();
                                                }
                                                if (count($single) != '0') {
                                                    if ($single->availability == 0) {
                                                        $color = '#FF0000';
                                                    } elseif ($single->stop_sell == 1) {
                                                        $color = '#CC99FF';
                                                        
                                                    }
?>
                                                   <td bgcolor="<?php //echo $color;
?>"><?php
                                                    if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php
                                                        echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '|' . $con_ch . '|' . $rate_id;
?>" data-url="<?php
                                                        echo lang_url();
?>inventory/inline_edit_guest_channel_type" data-title="Change Minimum Stay"><?php
                                                        echo ceil($single->minimum_stay);
?> </a><?php
                                                    } else {
?><?php
                                                        echo ceil($single->minimum_stay);
?> <?php
                                                    }
?></td>
                            
                                                <?php
                                                } else {
?>
                                                       <td bgcolor="<?php //echo $color;
?>"> <?php
                                                    if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php
                                                        echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '|' . $con_ch . '|' . $rate_id;
?>" data-url="<?php
                                                        echo lang_url();
?>inventory/inline_edit_guest_channel_type" data-title="Change Minimum Stay">N/A </a><?php
                                                    } else {
?> N/A <?php
                                                    }
?></td>
                            
                                                <?php
                                                }
                                            } else {
?> 
                                                    <td bgcolor="<?php //echo $color;
?>"><?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php
                                                    echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '|' . $con_ch . '|' . $rate_id;
?>" data-url="<?php
                                                    echo lang_url();
?>inventory/inline_edit_guest_channel_type" data-title="Change Minimum Stay">N/A </a><?php
                                                } else {
?> N/A <?php
                                                }
?></td>
                            
                                                <?php
                                            }
                                        }
?>
                                       </tr>
                                        <?php
                                    }
                                    if ($source == 's') {
?>
                                       
                                        <?php
                                    }
                                    if ($source == 'c1') {
?>
                                           
                                        <?php
                                    }
                                    if ($source == 'c2') {
?>
                                       
                                        <?php
                                    }
                                    
                                }
                            }
                        }
                        if ($rate_id != "0") {
                            $main_rate_available = get_data(MAP, array(
                                'property_id' => $property_id,
                                'rate_id' => $rate_id,
                                'guest_count' => '0',
                                'refun_type' => '0',
                                'channel_id' => $con_ch
                            ))->row_array();
                            if (count($main_rate_available) != '0') {
                                if ($source == 'm') {
?>
                                   <tr class="p-b-o msccc m_<?php
                                    echo $con_ch;
?> mm_main_rate" room_id="<?php
                                    echo $property_id;
?>" rate_id="<?php
                                    echo $rate_id;
?>">
                                    <td bgcolor="#a6a6a6">M</td>
                                    <?php
                                    foreach ($period as $date) {
                                        
                                        $i = $date->format("d");
                                        
                                        $ss_da = $date->format("d/m/Y");
                                        
                                        if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                            $color = '#d9d9d9';
                                        } else {
                                            $color = '#FFFFFF';
                                        }
                                        if (in_array($ss_da, $da)) {
                                            $single = get_data('room_rate_types_base', array(
                                                'individual_channel_id' => $con_ch,
                                                'owner_id' => current_user_type(),
                                                'hotel_id' => hotel_id(),
                                                'room_id' => $property_id,
                                                'rate_types_id' => $rate_id,
                                                'separate_date' => $ss_da
                                            ), 'availability,minimum_stay,stop_sell')->row();
                                            if (count($single) != '0') {
                                                if ($single->availability == 0) {
                                                    $color = '#FF0000';
                                                } elseif ($single->stop_sell == 1) {
                                                    $color = '#CC99FF';
                                                }
?>
                                                   <td bgcolor="<?php //echo $color;
?>"><?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php
                                                    echo $ss_da . '-' . $property_id . '~' . $con_ch . '~' . $rate_id;
?>" data-url="<?php
                                                    echo lang_url();
?>inventory/inline_edit_channel_type" data-title="Change Price"><?php
                                                    echo ceil($single->minimum_stay);
?> </a><?php
                                                } else {
?><?php
                                                    echo ceil($single->minimum_stay);
?> <?php
                                                }
?></td>
                                            <?php
                                            } else {
?>
                                                   <td bgcolor="<?php //echo $color;
?>"> <?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php
                                                    echo $ss_da . '-' . $property_id . '~' . $con_ch . '~' . $rate_id;
?>" data-url="<?php
                                                    echo lang_url();
?>inventory/inline_edit_channel_type" data-title="Change Minimum Stay">N/A </a><?php
                                                } else {
?> N/A <?php
                                                }
?></td>
                        
                                        <?php
                                            }
                                        } else {
?> 
                                                    <td bgcolor="<?php //echo $color;
?>"><?php
                                            if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php
                                                echo $ss_da . '-' . $property_id . '~' . $con_ch . '~' . $rate_id;
?>" data-url="<?php
                                                echo lang_url();
?>inventory/inline_edit_channel_type" data-title="Change Minimum Stay">N/A </a><?php
                                            } else {
?> N/A <?php
                                            }
?></td>
                                        <?php
                                        }
                                    }
?>
                                   </tr>
                                <?php
                                }
                                if ($source == 's') {
?>
                                   <tr class="p-b-o msccc s_<?php
                                    echo $con_ch;
?> ss_main_rate" room_id="<?php
                                    echo $property_id;
?>" rate_id="<?php
                                    echo $rate_id;
?>">
                                    <td bgcolor="#a6a6a6">SS</td>
                                    <?php
                                    foreach ($period as $date) {
                                        $i     = $date->format("d");
                                        $ss_da = $date->format("d/m/Y");
                                        if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                            $color = '#d9d9d9';
                                        } else {
                                            $color = '#FFFFFF';
                                        }
                                        if (in_array($ss_da, $da)) {
                                            $single = get_data('room_rate_types_base', array(
                                                'individual_channel_id' => $con_ch,
                                                'owner_id' => current_user_type(),
                                                'hotel_id' => hotel_id(),
                                                'room_id' => $property_id,
                                                'rate_types_id' => $rate_id,
                                                'separate_date' => $ss_da
                                            ), 'availability,stop_sell,stop_sell')->row();
                                            if (count($single) != '0') {
                                                if ($single->availability == 0) {
                                                    $color = '#FF0000';
                                                } elseif ($single->stop_sell == 1) {
                                                    $color = '#CC99FF';
                                                }
?>
                                                   <td bgcolor="<?php //echo $color
?>"><?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><input type="checkbox"  <?php
                                                    if ($single->stop_sell == '1') {
?> checked="checked" name="room_rate[<?php
                                                        echo $property_id;
?>][<?php
                                                        echo $rate_id;
?>][stop_sell][<?php
                                                        echo $ss_da;
?>]"  class="update_stop_sell_rate" <?php
                                                    } else {
?> name="new_room_rate[<?php
                                                        echo $property_id;
?>][<?php
                                                        echo $rate_id;
?>][stop_sell][<?php
                                                        echo $ss_da;
?>]"  <?php
                                                    }
?> value="1"  custom="<?php
                                                    echo $con_ch;
?>"><?php
                                                } else {
?><input type="checkbox"  <?php
                                                    if ($single->stop_sell == '1') {
?> checked="checked" name="room_rate[<?php
                                                        echo $property_id;
?>][<?php
                                                        echo $rate_id;
?>][stop_sell][<?php
                                                        echo $ss_da;
?>]"  <?php
                                                    } else {
?> name="new_room_rate[<?php
                                                        echo $property_id;
?>][<?php
                                                        echo $rate_id;
?>][stop_sell][<?php
                                                        echo $ss_da;
?>]"  <?php
                                                    }
?> value="1"  disabled="disabled"><?php
                                                }
?></td>
                            
                                            <?php
                                            } else {
?>
                                                   <td bgcolor="<?php //echo $color
?>"><?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><input type="checkbox"  name="new_room_rate[<?php
                                                    echo $property_id;
?>][<?php
                                                    echo $rate_id;
?>][stop_sell][<?php
                                                    echo $ss_da;
?>]" value="1"><?php
                                                } else {
?>N/A<?php
                                                }
?></td>
                            
                                            <?php
                                            }
                                        } else {
?> 
                                                <td bgcolor="<?php //echo $color
?>"><?php
                                            if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><input type="checkbox"  name="new_room_rate[<?php
                                                echo $property_id;
?>][<?php
                                                echo $rate_id;
?>][stop_sell][<?php
                                                echo $ss_da;
?>]" value="1"><?php
                                            } else {
?>N/A<?php
                                            }
?></td>
                                            <?php
                                        }
                                    }
?>
                                   </tr>
                                    <?php
                                }
                                if ($source == 'c1') {
?>
                                   <tr class="p-b-o msccc c1_<?php
                                    echo $con_ch;
?> cta_main_rate" room_id="<?php
                                    echo $property_id;
?>" rate_id="<?php
                                    echo $rate_id;
?>">
                                    <td bgcolor="#a6a6a6">CTA</td>
                                    <?php
                                    foreach ($period as $date) {
                                        $i     = $date->format("d");
                                        $ss_da = $date->format("d/m/Y");
                                        if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                            $color = '#d9d9d9';
                                        } else {
                                            $color = '#FFFFFF';
                                        }
                                        if (in_array($ss_da, $da)) {
                                            $single = get_data('room_rate_types_base', array(
                                                'individual_channel_id' => $con_ch,
                                                'owner_id' => current_user_type(),
                                                'hotel_id' => hotel_id(),
                                                'room_id' => $property_id,
                                                'rate_types_id' => $rate_id,
                                                'separate_date' => $ss_da
                                            ), 'availability,cta,stop_sell')->row();
                                            if (count($single) != '0') {
                                                if ($single->availability == 0) {
                                                    $color = '#FF0000';
                                                } elseif ($single->stop_sell == 1) {
                                                    $color = '#CC99FF';
                                                }
?>
                                                   <td bgcolor="<?php //echo $color
?>"><?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><input type="checkbox"  <?php
                                                    if ($single->cta == '1') {
?> checked="checked" name="room_rate[<?php
                                                        echo $property_id;
?>][<?php
                                                        echo $rate_id;
?>][cta][<?php
                                                        echo $ss_da;
?>]"  class="update_stop_sell_rate" <?php
                                                    } else {
?> name="new_room_rate[<?php
                                                        echo $property_id;
?>][<?php
                                                        echo $rate_id;
?>][cta][<?php
                                                        echo $ss_da;
?>]"  <?php
                                                    }
?> value="1"  custom="<?php
                                                    echo $con_ch;
?>"><?php
                                                } else {
?><input type="checkbox"  <?php
                                                    if ($single->cta == '1') {
?> checked="checked" name="room_rate[<?php
                                                        echo $property_id;
?>][<?php
                                                        echo $rate_id;
?>][cta][<?php
                                                        echo $ss_da;
?>]"  <?php
                                                    } else {
?> name="new_room_rate[<?php
                                                        echo $property_id;
?>][<?php
                                                        echo $rate_id;
?>][cta][<?php
                                                        echo $ss_da;
?>]"  <?php
                                                    }
?> value="1" disabled="disabled"><?php
                                                }
?></td>
                            
                                            <?php
                                            } else {
?>
                                                   <td bgcolor="<?php //echo $color
?>"><input type="checkbox"  name="new_room_rate[<?php
                                                echo $property_id;
?>][<?php
                                                echo $rate_id;
?>][cta][<?php
                                                echo $ss_da;
?>]" value="1" ></td>
                            
                                            <?php
                                            }
                                        } else {
?> 
                                                    <td bgcolor="<?php //echo $color
?>"><input type="checkbox"  name="new_room_rate[<?php
                                            echo $property_id;
?>][<?php
                                            echo $rate_id;
?>][cta][<?php
                                            echo $ss_da;
?>]" value="1" ></td>
                                            <?php
                                        }
                                    }
?>
                                   </tr>    
                                    <?php
                                }
                                if ($source == 'c2') {
?>
                                   <tr class="p-b-o msccc c2_<?php
                                    echo $con_ch;
?> ctd_main_rate" room_id="<?php
                                    echo $property_id;
?>" rate_id="<?php
                                    echo $rate_id;
?>">
                                    <td bgcolor="#a6a6a6">CTD</td>
                                    <?php
                                    foreach ($period as $date) {
                                        $i = $date->format("d");
                                        
                                        $ss_da = $date->format("d/m/Y");
                                        
                                        if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                            $color = '#d9d9d9';
                                        } else {
                                            $color = '#FFFFFF';
                                        }
                                        if (in_array($ss_da, $da)) {
                                            $single = get_data('room_rate_types_base', array(
                                                'individual_channel_id' => $con_ch,
                                                'owner_id' => current_user_type(),
                                                'hotel_id' => hotel_id(),
                                                'room_id' => $property_id,
                                                'rate_types_id' => $rate_id,
                                                'separate_date' => $ss_da
                                            ), 'availability,ctd,stop_sell')->row();
                                            if (count($single) != '0') {
                                                if ($single->availability == 0) {
                                                    $color = '#FF0000';
                                                } elseif ($single->stop_sell == 1) {
                                                    $color = '#CC99FF';
                                                }
                                                
?>
                                                   <td bgcolor="<?php //echo $color
?>"><?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><input type="checkbox" <?php
                                                    if ($single->ctd == '1') {
?> checked="checked" name="room_rate[<?php
                                                        echo $property_id;
?>][<?php
                                                        echo $rate_id;
?>][ctd][<?php
                                                        echo $ss_da;
?>]"  class="update_stop_sell_rate" <?php
                                                    } else {
?> name="new_room_rate[<?php
                                                        echo $property_id;
?>][<?php
                                                        echo $rate_id;
?>][ctd][<?php
                                                        echo $ss_da;
?>]"  <?php
                                                    }
?> value="1"  custom="<?php
                                                    echo $con_ch;
?>"><?php
                                                } else {
?><input type="checkbox"  <?php
                                                    if ($single->ctd == '1') {
?> checked="checked" name="room_rate[<?php
                                                        echo $property_id;
?>][<?php
                                                        echo $rate_id;
?>][ctd][<?php
                                                        echo $ss_da;
?>]"  <?php
                                                    } else {
?> name="new_room_rate[<?php
                                                        echo $property_id;
?>][<?php
                                                        echo $rate_id;
?>][ctd][<?php
                                                        echo $ss_da;
?>]"  <?php
                                                    }
?>  value="1"  disabled="disabled"><?php
                                                }
?></td>
                            
                                            <?php
                                            } else {
?>
                                               <td bgcolor="<?php //echo $color
?>"><input type="checkbox"  name="new_room_rate[<?php
                                                echo $property_id;
?>][<?php
                                                echo $rate_id;
?>][ctd][<?php
                                                echo $ss_da;
?>]" value="1"></td>
                                            <?php
                                            }
                                        } else {
?> 
                                                <td bgcolor="<?php //echo $color
?>"><input type="checkbox"  name="new_room_rate[<?php
                                            echo $property_id;
?>][<?php
                                            echo $rate_id;
?>][ctd][<?php
                                            echo $ss_da;
?>]" value="1"></td>
                            
                                            <?php
                                        }
                                    }
?>
                                   </tr>
                                    <?php
                                }
                            }
                            if ($pricing_type == 1 && $non_refund == 1) {
                                $v                  = 1;
                                $refun              = 2;
                                $sub_rate_available = get_data(MAP, array(
                                    'property_id' => $property_id,
                                    'rate_id' => $rate_id,
                                    'guest_count' => $v,
                                    'refun_type' => $refun,
                                    'channel_id' => $con_ch
                                ))->row_array();
                                if (count($sub_rate_available) != '0') {
                                    if ($source == 'm') {
?>
                                       <tr class="p-b-o msccc m_<?php
                                        echo $con_ch;
?>">
                                        <td bgcolor="#a6a6a6">M</td>
                                    <?php
                                        foreach ($period as $date) {
                                            $i = $date->format("d");
                                            
                                            $ss_da = $date->format("d/m/Y");
                                            
                                            if ($date->format("D") == 'Sat' || $date->format("D") == 'Sun') {
                                                $color = '#d9d9d9';
                                            } else {
                                                $color = '#FFFFFF';
                                            }
                                            if (in_array($ss_da, $da)) {
                                                if (user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
                                                    $single = get_data('room_rate_types_additional', array(
                                                        'individual_channel_id' => $con_ch,
                                                        'owner_id' => current_user_type(),
                                                        'hotel_id' => hotel_id(),
                                                        'rate_types_id' => $rate_id,
                                                        'room_id' => $property_id,
                                                        'separate_date' => $ss_da,
                                                        'guest_count' => $v,
                                                        'refun_type' => $refun
                                                    ), 'availability,minimum_stay,stop_sell')->row();
                                                }
                                                if (count($single) != '0') {
                                                    if ($single->availability == 0) {
                                                        $color = '#FF0000';
                                                    } elseif ($single->stop_sell == 1) {
                                                        $color = '#CC99FF';
                                                    }
?>
                                                   <td bgcolor="<?php //echo $color;
?>"><?php
                                                    if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?><a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php
                                                        echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '|' . $con_ch . '|' . $rate_id;
?>" data-url="<?php
                                                        echo lang_url();
?>inventory/inline_edit_guest_channel_type" data-title="Change Minimum Stay"><?php
                                                        echo ceil($single->minimum_stay);
?> </a><?php
                                                    } else {
?><?php
                                                        echo ceil($single->minimum_stay);
?> <?php
                                                    }
?></td>
                                
                                                <?php
                                                } else {
?>
                                                   <td bgcolor="<?php //echo $color;
?>"> <?php
                                                    if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php
                                                        echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '|' . $con_ch . '|' . $rate_id;
?>" data-url="<?php
                                                        echo lang_url();
?>inventory/inline_edit_guest_channel_type" data-title="Change Minimum Stay">N/A </a><?php
                                                    } else {
?> N/A <?php
                                                    }
?></td>
                                
                                                <?php
                                                }
                                            } else {
?> 
                                                    <td bgcolor="<?php //echo $color;
?>"><?php
                                                if (date('Y/m/d', strtotime(str_replace('/', '-', $ss_da))) >= date('Y/m/d') && user_type() == '1' || user_type() == '2' && in_array('3', user_edit()) || admin_id() != '' && admin_type() == '1') {
?> <a href="javascript:;" id="inline_username" class="inline_username inline_minimum" data-type="number" data-name="minimum_stay" data-pk="<?php
                                                    echo $ss_da . '-' . $property_id . ',' . $v . '~' . $refun . '|' . $con_ch . '|' . $rate_id;
?>" data-url="<?php
                                                    echo lang_url();
?>inventory/inline_edit_guest_channel_type" data-title="Change Minimum Stay">N/A </a><?php
                                                } else {
?> N/A <?php
                                                }
?></td>
                                            <?php
                                            }
                                        }
?>
                                       </tr>
                                    <?php
                                    }
                                    if ($source == 'c1') {
?>
                                           
                                        <?php
                                    }
                                    if ($source == 'c2') {
?>
                                       
                                        <?php
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    /* Channel Calendar Functionality End */
    
    function getChannelCalebdar()
    {
        extract($this->input->post());
        
        $data['all_room'] = get_data(TBL_PROPERTY, array(
            'status' => 'Active',
            'owner_id' => current_user_type(),
            'hotel_id' => hotel_id(),
            'droc' => '1'
        ), 'property_id,non_refund,member_count,property_name,pricing_type')->result_array();
        
        $data['all_channel'] = get_data(TBL_CHANNEL, array(
            'channel_id' => insep_decode($channel_id)
        ))->result_array();
        
        $this->load->view('channel/channel_cal', $data);
    }
    
    function singleTable()
    {
        $file   = '/ICAL/mytable.sql';
        $result = mysql_query("SELECT * INTO OUTFILE '$file' FROM `##faq##`");
    }
    
    function ExportCSV()
    {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline   = "\r\n";
        $filename  = "filename_you_wish.csv";
        $query     = "SELECT * FROM faq";
        $result    = $this->db->query($query);
        $data      = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }
    
    function downsp()
    {
        /*     $this->load->dbutil();        
        $backup =& $this->dbutil->backup();        
        $this->load->helper('file');
        write_file('/images/mybackup.gz', $backup);        
        $this->load->helper('download');
        force_download('mybackup.gz', $backup); */
        
        /* $dbhost = 'localhost:3036';
        $dbuser = 'root';
        $dbpass = 'Osiz@123';
        
        $conn = mysql_connect($dbhost, $dbuser, $dbpass);
        
        if(! $conn ) {
        die('Could not connect: ' . mysql_error());
        } */
        //echo base_url();
        $table_name  = "faq";
        $backup_file = base_url() . "ICAL/employee.sql";
        $sql         = "SELECT * INTO OUTFILE '$backup_file' FROM $table_name";
        
        //mysql_select_db('test_db');
        $retval = mysql_query($sql);
        
        if (!$retval) {
            die('Could not take data backup: ' . mysql_error());
        }
        
        echo "Backedup data successfully\n";
        
        mysql_close($conn);
        
    }
    
    function get_recoreds()
    {
        $this->is_login();
        $all_data = get_data(TBL_UPDATE)->result();
        $i        = 0;
        foreach ($all_data as $all_data) {
            $data['random_data'] = $i++;
            $data['php_call']    = current_user_type();
            insert_data('test', $data);
            echo '<pre>';
            print_r($all_data) . '<br>';
        }
    }
    
    
    
}
?> 