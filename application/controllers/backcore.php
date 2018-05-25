<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
* Cron calls
* Spyros Ziogas 26/11/2016
*/
class Backcore extends Front_Controller
{
	public function __construct()
	{       
		parent::__construct();
		
		$this->load->model('channel/backcore_model');

		log_message('debug', 'Backcore Controller Initialized');
	}

	/*
	* Entity: Room, ReservationTable, RoomRateTypesBase, RoomRateTypesAdditional
	* Table: 'manage_property', 'reservation_table', 'room_rate_types_base', 'room_rate_types_additional'
	* Spyros Ziogas 26/11/2016
	*/
	public function delete_duplicate_record($step = 1)
	{
		$rnd = rand(1, 4);
		
		switch($rnd)
		{
			case 1:
				$duplicates = $this->backcore_model->deleteRoomUpdateDuplicates($step);
		
				var_dump($duplicates);
		
				echo "backcore::delete_duplicate_record_room_update successfully terminated\n";
				
				break;
			case 2:
				$duplicates = $this->backcore_model->deleteReservationTableDuplicates($step);
					
				var_dump($duplicates);
					
				echo "backcore::delete_duplicate_record_reservation_table successfully terminated";

				break;
			case 3:
				$duplicates = $this->backcore_model->deleteRoomRateTypesBaseDuplicates($step);
				
				var_dump($duplicates);
				
				echo "backcore::delete_duplicate_record_room_rate_types_base successfully terminated";
				
				break;
			case 4:
				$duplicates = $this->backcore_model->deleteRoomRateTypesAdditionalDuplicates($step);
				
				var_dump($duplicates);
				
				echo "backcore::delete_duplicate_record_room_rate_types_additional successfully terminated";
				
				break;
		}
	}
}