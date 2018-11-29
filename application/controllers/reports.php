 <?php
ini_set('memory_limit', '-1');
ini_set('display_errors', '1');
defined('BASEPATH') OR exit('No direct script access allowed');

class reports extends Front_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function guestinhouse()
	{
		$date1=date('Y-m-d',strtotime($_POST['startdate']));
		$date2=date('Y-m-d',strtotime($_POST['enddate']));

		$reservas=$this->reservation_model->AllReservationListReport($date1,$date2,'',5);
		$status[0]="Canceled";
		$status[1]="Reserved";
		$status[2]="Modified";
		$status[3]="No Show";
		$status[4]="Confirmed";
		$status[5]="Check-in";
		$status[6]="Check-out";
		$status[7]="Unchecked";
		if(count($reservas['info'])>0)
				{
					$html='<div><div style="float: right;" class="buttons-ui">
					            <a onclick="Export()" class="btn green">Export</a>
					        </div> <div class="clearfix"></div><center><h1><span class="label label-primary">Registered Guests (In-House)</span></h1></center></div>';
					$html.='<div class="graph">
							<div class="table-responsive">
									<div class="clearfix"></div>
									<table id="myTable" class="table table-bordered">
											<thead>
													<tr>	<th>#</th>
															<th style="text-align:center;">Number</th>
															<th style="text-align:center;">Status</th>
															<th style="text-align:center;">Name</th>
															<th style="text-align:center;">Check-in</th>
															<th style="text-align:center;">Check-out</th>
															<th style="text-align:center;">Room Type</th>
															<th style="text-align:center;">Room Number</th>
															<th style="text-align:center;">Total Amount</th>
													</tr>
																		 </thead>
											<tbody>';
					$i=0;
					$total=0;
					foreach ($reservas['info'] as  $value) {
						$i++;
						$total++;
						$html.=' <tr  class="'.($i%2?'active':'success').'">
								<th scope="row">'.$i.' </th>
								<td style="text-align:left;">'.$value['reservation_code'].'</td>
								<td style="text-align:center;">'.$status[$value['status']].'</td>
								<td style="text-align:left;">'.$value['Full_Name'].'</td>
								<td style="text-align:center;">'.date('m/d/Y',strtotime($value['start_date'])).'</td>
								<td style="text-align:center;">'.date('m/d/Y',strtotime($value['end_date'])).'</td>
								<td style="text-align:left;">'.$value['roomName'].'</td>
								<td style="text-align:center;">'.$value['RoomNumber'].'</td>
								<td style="text-align:right;">'.number_format($value['price'], 2, '.', ',').'</td>
								 </tr>';


					}

					$html.='</tbody>
									</table>
									</div>
									</div>';
					$html.='<div><center><h1><strong class="label label-primary">Total In-House:'.$total.'<strong></h1></center></div>';
				}
				else
				{
					$html='<center><h1><span class="label label-danger">No Record Found</span></h1></center>';
				}
			echo json_encode(array('html'=>$html,'title'=>"Registered Guests(In-House)"));
	}
	public function allreservation()
	{
		$date1=date('Y-m-d',strtotime($_POST['startdate']));
		$date2=date('Y-m-d',strtotime($_POST['enddate']));

		$reservas=$this->reservation_model->AllReservationListReport($date1,$date2,'','');
		$status[0]="Canceled";
		$status[1]="Reserved";
		$status[2]="Modified";
		$status[3]="No Show";
		$status[4]="Confirmed";
		$status[5]="Check-in";
		$status[6]="Check-out";
		$status[7]="Unchecked";
		if(count($reservas['info'])>0)
				{
					$html='<div><div style="float: right;" class="buttons-ui">
					            <a onclick="Export()" class="btn green">Export</a>
					        </div> <div class="clearfix"></div><center><h1><span class="label label-primary">All Reservations</span></h1></center></div>';
					$html.='<div class="graph">
							<div class="table-responsive">
									<div class="clearfix"></div>
									<table id="myTable" class="table table-bordered">
											<thead>
													<tr>	<th>#</th>
															<th style="text-align:center;">Number</th>
															<th style="text-align:center;">Status</th>
															<th style="text-align:center;">Name</th>
															<th style="text-align:center;">Check-in</th>
															<th style="text-align:center;">Check-out</th>
															<th style="text-align:center;">Room Type</th>
															<th style="text-align:center;">Room Number</th>
															<th style="text-align:center;">Total Amount</th>
													</tr>
																		 </thead>
											<tbody>';
					$i=0;
					$total=0;
					foreach ($reservas['info'] as  $value) {
						$i++;
						$total++;
						$html.=' <tr  class="'.($i%2?'active':'success').'">
								<th scope="row">'.$i.' </th>
								<td style="text-align:left;">'.$value['reservation_code'].'</td>
								<td style="text-align:center;">'.$status[$value['status']].'</td>
								<td style="text-align:left;">'.$value['Full_Name'].'</td>
								<td style="text-align:center;">'.date('m/d/Y',strtotime($value['start_date'])).'</td>
								<td style="text-align:center;">'.date('m/d/Y',strtotime($value['end_date'])).'</td>
								<td style="text-align:left;">'.$value['roomName'].'</td>
								<td style="text-align:center;">'.$value['RoomNumber'].'</td>
								<td style="text-align:right;">'.number_format($value['price'], 2, '.', ',').'</td>
								 </tr>';


					}

					$html.='</tbody>
									</table>
									</div>
									</div>';
					$html.='<div><center><h1><strong class="label label-primary">Total :'.$total.'<strong></h1></center></div>';
				}
				else
				{
					$html='<center><h1><span class="label label-danger">No Record Found</span></h1></center>';
				}
			echo json_encode(array('html'=>$html,'title'=>"All Reservations"));
	}
	public function guestdepatures()
	{
		$date1=date('Y-m-d',strtotime($_POST['startdate']));
		$date2=date('Y-m-d',strtotime($_POST['enddate']));

		$reservas=$this->reservation_model->AllReservationListReport($date1,$date2,'','','depature');
		$status[0]="Canceled";
		$status[1]="Reserved";
		$status[2]="Modified";
		$status[3]="No Show";
		$status[4]="Confirmed";
		$status[5]="Check-in";
		$status[6]="Check-out";
		$status[7]="Unchecked";
		if(count($reservas['info'])>0)
				{
					$html='<div><div style="float: right;" class="buttons-ui">
					            <a onclick="Export()" class="btn green">Export</a>
					        </div> <div class="clearfix"></div><center><h1><span class="label label-primary">Depatures</span></h1></center></div>';
					$html.='<div class="graph">
							<div class="table-responsive">
									<div class="clearfix"></div>
									<table id="myTable" class="table table-bordered">
											<thead>
													<tr>	<th>#</th>
															<th style="text-align:center;">Number</th>
															<th style="text-align:center;">Status</th>
															<th style="text-align:center;">Name</th>
															<th style="text-align:center;">Check-in</th>
															<th style="text-align:center;">Check-out</th>
															<th style="text-align:center;">Room Type</th>
															<th style="text-align:center;">Room Number</th>
															<th style="text-align:center;">Total Amount</th>
													</tr>
																		 </thead>
											<tbody>';
					$i=0;
					$total=0;
					foreach ($reservas['info'] as  $value) {
						$i++;
						$total++;
						$html.=' <tr  class="'.($i%2?'active':'success').'">
								<th scope="row">'.$i.' </th>
								<td style="text-align:left;">'.$value['reservation_code'].'</td>
								<td style="text-align:center;">'.$status[$value['status']].'</td>
								<td style="text-align:left;">'.$value['Full_Name'].'</td>
								<td style="text-align:center;">'.date('m/d/Y',strtotime($value['start_date'])).'</td>
								<td style="text-align:center;">'.date('m/d/Y',strtotime($value['end_date'])).'</td>
								<td style="text-align:left;">'.$value['roomName'].'</td>
								<td style="text-align:center;">'.$value['RoomNumber'].'</td>
								<td style="text-align:right;">'.number_format($value['price'], 2, '.', ',').'</td>
								 </tr>';


					}

					$html.='</tbody>
									</table>
									</div>
									</div>';
					$html.='<div><center><h1><strong class="label label-primary">Total :'.$total.'<strong></h1></center></div>';
				}
				else
				{
					$html='<center><h1><span class="label label-danger">No Record Found</span></h1></center>';
				}
			echo json_encode(array('html'=>$html,'title'=>"Depatures"));
	}
	public function guestarrivals()
	{
		$date1=date('Y-m-d',strtotime($_POST['startdate']));
		$date2=date('Y-m-d',strtotime($_POST['enddate']));

		$reservas=$this->reservation_model->AllReservationListReport($date1,$date2,'','','arrivals');
		$status[0]="Canceled";
		$status[1]="Reserved";
		$status[2]="Modified";
		$status[3]="No Show";
		$status[4]="Confirmed";
		$status[5]="Check-in";
		$status[6]="Check-out";
		$status[7]="Unchecked";

		if(count($reservas['info'])>0)
				{
					$html='<div><div style="float: right;" class="buttons-ui">
					            <a onclick="Export()" class="btn green">Export</a>
					        </div> <div class="clearfix"></div><center><h1><span class="label label-primary">Arrivals</span></h1></center></div>';
					$html.='<div class="graph">
							<div class="table-responsive">
									<div class="clearfix"></div>
									<table id="myTable" class="table table-bordered">
											<thead>
													<tr>	<th>#</th>
															<th style="text-align:center;">Number</th>
															<th style="text-align:center;">Status</th>
															<th style="text-align:center;">Name</th>
															<th style="text-align:center;">Check-in</th>
															<th style="text-align:center;">Check-out</th>
															<th style="text-align:center;">Room Type</th>
															<th style="text-align:center;">Room Number</th>
															<th style="text-align:center;">Total Amount</th>
													</tr>
																		 </thead>
											<tbody>';
					$i=0;
					$total=0;
					foreach ($reservas['info'] as  $value) {
						$i++;
						$total++;
						$html.=' <tr  class="'.($i%2?'active':'success').'">
								<th scope="row">'.$i.' </th>
								<td style="text-align:left;">'.$value['reservation_code'].'</td>
								<td style="text-align:center;">'.$status[$value['status']].'</td>
								<td style="text-align:left;">'.$value['Full_Name'].'</td>
								<td style="text-align:center;">'.date('m/d/Y',strtotime($value['start_date'])).'</td>
								<td style="text-align:center;">'.date('m/d/Y',strtotime($value['end_date'])).'</td>
								<td style="text-align:left;">'.$value['roomName'].'</td>
								<td style="text-align:center;">'.$value['RoomNumber'].'</td>
								<td style="text-align:right;">'.number_format($value['price'], 2, '.', ',').'</td>
								 </tr>';


					}

					$html.='</tbody>
									</table>
									</div>
									</div>';
					$html.='<div><center><h1><strong class="label label-primary">Total :'.$total.'<strong></h1></center></div>';
				}
				else
				{
					$html='<center><h1><span class="label label-danger">No Record Found</span></h1></center>';
				}
			echo json_encode(array('html'=>$html,'title'=>"Arrivals"));
	}
	public function cancelations()
	{
		$date1=date('Y-m-d',strtotime($_POST['startdate']));
		$date2=date('Y-m-d',strtotime($_POST['enddate']));

		$reservas=$this->reservation_model->AllReservationListReport($date1,$date2,'',0);
		$status[0]="Canceled";
		$status[1]="Reserved";
		$status[2]="Modified";
		$status[3]="No Show";
		$status[4]="Confirmed";
		$status[5]="Check-in";
		$status[6]="Check-out";
		$status[7]="Unchecked";

		if(count($reservas['info'])>0)
				{
					$html='<div><div style="float: right;" class="buttons-ui">
					            <a onclick="Export()" class="btn green">Export</a>
					        </div> <div class="clearfix"></div><center><h1><span class="label label-primary">Cancelations</span></h1></center></div>';
					$html.='<div class="graph">
							<div class="table-responsive">
									<div class="clearfix"></div>
									<table id="myTable" class="table table-bordered">
											<thead>
													<tr>	<th>#</th>
															<th style="text-align:center;">Number</th>
															<th style="text-align:center;">Status</th>
															<th style="text-align:center;">Name</th>
															<th style="text-align:center;">Check-in</th>
															<th style="text-align:center;">Check-out</th>
															<th style="text-align:center;">Room Type</th>
															<th style="text-align:center;">Room Number</th>
															<th style="text-align:center;">Total Amount</th>
													</tr>
																		 </thead>
											<tbody>';
					$i=0;
					$total=0;
					foreach ($reservas['info'] as  $value) {
						$i++;
						$total++;
						$html.=' <tr  class="'.($i%2?'active':'success').'">
								<th scope="row">'.$i.' </th>
								<td style="text-align:left;">'.$value['reservation_code'].'</td>
								<td style="text-align:center;">'.$status[$value['status']].'</td>
								<td style="text-align:left;">'.$value['Full_Name'].'</td>
								<td style="text-align:center;">'.date('m/d/Y',strtotime($value['start_date'])).'</td>
								<td style="text-align:center;">'.date('m/d/Y',strtotime($value['end_date'])).'</td>
								<td style="text-align:left;">'.$value['roomName'].'</td>
								<td style="text-align:center;">'.$value['RoomNumber'].'</td>
								<td style="text-align:right;">'.number_format($value['price'], 2, '.', ',').'</td>
								 </tr>';


					}

					$html.='</tbody>
									</table>
									</div>
									</div>';
					$html.='<div><center><h1><strong class="label label-primary">Total :'.$total.'<strong></h1></center></div>';
				}
				else
				{
					$html='<center><h1><span class="label label-danger">No Record Found</span></h1></center>';
				}
			echo json_encode(array('html'=>$html,'title'=>"Cancelations"));
	}
  public function noshow()
	{
		$date1=date('Y-m-d',strtotime($_POST['startdate']));
		$date2=date('Y-m-d',strtotime($_POST['enddate']));

		$reservas=$this->reservation_model->AllReservationListReport($date1,$date2,'',3);
		$status[0]="Canceled";
		$status[1]="Reserved";
		$status[2]="Modified";
		$status[3]="No Show";
		$status[4]="Confirmed";
		$status[5]="Check-in";
		$status[6]="Check-out";
		$status[7]="Unchecked";

		if(count($reservas['info'])>0)
			{
					$html='<div><div style="float: right;" class="buttons-ui">
					            <a onclick="Export()" class="btn green">Export</a>
					        </div> <div class="clearfix"></div><center><h1><span class="label label-primary">Cancelations</span></h1></center></div>';
					$html.='<div class="graph">
							<div class="table-responsive">
									<div class="clearfix"></div>
									<table id="myTable" class="table table-bordered">
											<thead>
													<tr>	<th>#</th>
															<th style="text-align:center;">Number</th>
															<th style="text-align:center;">Status</th>
															<th style="text-align:center;">Name</th>
															<th style="text-align:center;">Check-in</th>
															<th style="text-align:center;">Check-out</th>
															<th style="text-align:center;">Room Type</th>
															<th style="text-align:center;">Room Number</th>
															<th style="text-align:center;">Total Amount</th>
													</tr>
																		 </thead>
											<tbody>';
					$i=0;
					$total=0;
					foreach ($reservas['info'] as  $value) {
						$i++;
						$total++;
						$html.=' <tr  class="'.($i%2?'active':'success').'">
								<th scope="row">'.$i.' </th>
								<td style="text-align:left;">'.$value['reservation_code'].'</td>
								<td style="text-align:center;">'.$status[$value['status']].'</td>
								<td style="text-align:left;">'.$value['Full_Name'].'</td>
								<td style="text-align:center;">'.date('m/d/Y',strtotime($value['start_date'])).'</td>
								<td style="text-align:center;">'.date('m/d/Y',strtotime($value['end_date'])).'</td>
								<td style="text-align:left;">'.$value['roomName'].'</td>
								<td style="text-align:center;">'.$value['RoomNumber'].'</td>
								<td style="text-align:right;">'.number_format($value['price'], 2, '.', ',').'</td>
								 </tr>';


					}

					$html.='</tbody>
									</table>
									</div>
									</div>';
					$html.='<div><center><h1><strong class="label label-primary">Total :'.$total.'<strong></h1></center></div>';
				}
				else
				{
					$html='<center><h1><span class="label label-danger">No Record Found</span></h1></center>';
				}
			echo json_encode(array('html'=>$html,'title'=>"Cancelations"));
		
	}

 
	/*$date1=date('Y-m-d',strtotime($_POST['startdate']));
	$date2=date('Y-m-d',strtotime($_POST['enddate']));*/
  public function reporttype()
{
		switch ($_POST['reportid']) {
			case '1':
				$this->guestinhouse();
				break;
			case '2':
				$this->allreservation();
				break;
			case '3':
				$this->guestarrivals();
				break;
			case '4':
				$this->guestdepatures();
			break;
			case '5':
				$this->cancelations();
			break;
      case '6':
				$this->noshow();
			break;
      case '7':
				$this->occupancy();
			break;
			default:
				# code...
				break;
		}
	}
}
