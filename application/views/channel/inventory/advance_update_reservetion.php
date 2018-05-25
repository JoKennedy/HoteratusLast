<?php

	/**
	* Modify: 10/11/2016 Spyros Ziogas
	*
	* Controller: inventory		(/application/controllers/inventory.php) :: advance_update
	*
	* Models: inventory_advance_update_model		(/application/models/inventory_advance_update_model.php)
	*
	* Views: channel/inventory/advance_update		(/application/views/channel/inventory/advance_update.php)
	*
	* Tables: TBL_USERS (manage_users) :: TBL_PROPERTY (manage_property) :: TBL_UPDATE (room_update) :: RATE_TYPES (room_rate_types)
	*
	*/

	if( count($properties) )
	{
		require "advance_update_reservetion/have_rooms.php";
	}
	else
	{
		require "advance_update_reservetion/have_not_rooms.php";
	}

	require "advance_update_reservetion/finilize.php";
?>
