<?php 

//ini_set('memory_limit', '-1');

//ini_set('display_errors','1');

class Inventory_advance_update_model extends CI_Model
{
	private $today;
	
	private $startDate;
	
	private $endDate;
	
	private $period;
	
	private $curr_date;
	
	private $add_date;
	
	private $todayMonth;
		
	private $startMonthDate;
		
	private $endMonthDate;
		
	private $prevMonthDate;
		
	private $nextMonthDate;
		
	private $monthPeriod;
		
	private $months;

	public function __construct()
	{	   
		$this->today = new DateTime();

		parent::__construct();
	}

	public function setStartDate(DateTime $startDate)
	{
		$this->startDate = $startDate;
		
		return $this;
	}

	public function setEndDate(DateTime $endDate)
	{
		$this->endDate = $endDate;
		
		return $this;
	}

	private function initDates()
	{
		$interval = new DateInterval('P1D');
		
		$this->period = new DatePeriod($this->startDate, $interval ,$this->endDate);

		//
		
		$this->curr_date = $this->startDate->format('d/m/Y');
		
		$add_date = clone $this->startDate;
		
		$interval = new DateInterval('P7D');
		
		$add_date->add($interval);
		
		$this->add_date = $add_date->format('d/m/Y');
		
		//
		
		$todayMonth = new DateTime();		
		
		$todayMonth->setDate($todayMonth->format('Y') , $todayMonth->format('m') , 1);

		$startMonthDate = clone($this->startDate);
		
		$startMonthDate->setDate($startMonthDate->format('Y') , $startMonthDate->format('m') , 1);
		
		$interval = new DateInterval('P7M');
		
		$startMonthDate->sub($interval);
		
		$interval = new DateInterval('P1M');
		
		while( $startMonthDate < $todayMonth )
		{
			$startMonthDate->add($interval);
		}
		
		$endMonthDate = clone($startMonthDate);

		$interval = new DateInterval('P14M');

		$endMonthDate->add($interval);

		$interval = new DateInterval('P1M');

		$prevMonthDate = clone($this->startDate);

		$nextMonthDate = clone($this->startDate);

		$prevMonthDate->setDate($prevMonthDate->format('Y') , $prevMonthDate->format('m') , 1);

		$nextMonthDate->setDate($nextMonthDate->format('Y') , $nextMonthDate->format('m') , 1);

		$prevMonthDate->sub($interval);

		$nextMonthDate->add($interval);
		
		$this->todayMonth = $todayMonth;
		
		$this->startMonthDate = $startMonthDate;
		
		$this->endMonthDate = $endMonthDate;
		
		$this->prevMonthDate = $prevMonthDate;
		
		$this->nextMonthDate = $nextMonthDate;
		
		$this->monthPeriod = new DatePeriod($startMonthDate, $interval ,$endMonthDate);
		
		//

		$months = [];

		foreach( $this->period as $date ) 
		{
			$month = $date->format('F Y');

			$day = $date->format('d');

			$months[$month][] = $day;
		}
		
		foreach( $months as $month => $days )
		{
			$months[$month] = count(array_unique($days));
		}
		
		foreach( $months as $month => $days )
		{
			$months[$month]++;
			
			break;
		}
		
		$this->months = $months;
	}

	private function getUpdates()
	{
		$sql = "
			SELECT 
				`".TBL_UPDATE."`.`room_id`,
				`".TBL_UPDATE."`.`separate_date`,
				`".TBL_UPDATE."`.`stop_sell`,
				`".TBL_UPDATE."`.`availability`,
				`".TBL_UPDATE."`.`price`,
				`".TBL_UPDATE."`.`minimum_stay`,
				`".TBL_UPDATE."`.`cta`,
				`".TBL_UPDATE."`.`ctd`

			FROM 
				`".TBL_UPDATE."`

			WHERE
				`".TBL_UPDATE."`.`individual_channel_id`='0' AND 
				`".TBL_UPDATE."`.`owner_id`='".current_user_type()."' AND
				`".TBL_UPDATE."`.`hotel_id`='".hotel_id()."' AND 
				str_to_date(`".TBL_UPDATE."`.`separate_date`, '%d/%m/%Y') >= str_to_date('".$this->startDate->format('d/m/Y')."', '%d/%m/%Y') AND 
				str_to_date(`".TBL_UPDATE."`.`separate_date`, '%d/%m/%Y') <= str_to_date('".$this->endDate->format('d/m/Y')."', '%d/%m/%Y')

			ORDER BY
				str_to_date(`".TBL_UPDATE."`.`separate_date`, '%d/%m/%Y')
				
		";

		return $this->db->query($sql)->result();
	}

	private function getRateTypes($properties)
	{
		$property_ids = [];
		
		foreach( $properties as $property )
		{
			$property_ids[] = $property->property_id;
		}
		if(count($property_ids)!=0)
		{
			$sql = "
				SELECT 
					*

				FROM 
					`".RATE_TYPES."`

				WHERE
					`".RATE_TYPES."`.`user_id`='".current_user_type()."' AND
					`".RATE_TYPES."`.`hotel_id`='".hotel_id()."' AND
					`".RATE_TYPES."`.`droc`='1' AND
					`".RATE_TYPES."`.`room_id` IN (".implode(',', $property_ids).")  
			";

			return $this->db->query($sql)->result();
		}
		else
		{
			return false;
		}
	}

	private function getProperties($property_id = null)
	{
		$where = "
			`".TBL_PROPERTY."`.`status`='Active' AND 
			`".TBL_PROPERTY."`.`owner_id`='".current_user_type()."' AND
			`".TBL_PROPERTY."`.`hotel_id`='".hotel_id()."' AND
			`".TBL_PROPERTY."`.`droc`='1'
		";
		
		if( $property_id )
		{
			$where .= "AND
				`".TBL_PROPERTY."`.`property_id`='$property_id'
			";
		}
		
		$sql = "
			SELECT 
				`".TBL_PROPERTY."`.`property_id`,
				`".TBL_PROPERTY."`.`property_name`,
				`".TBL_PROPERTY."`.`non_refund`,
				`".TBL_PROPERTY."`.`pricing_type`,
				`".TBL_PROPERTY."`.`member_count`,
+				`".TBL_PROPERTY."`.`existing_room_number`

			FROM 
				`".TBL_PROPERTY."`

			WHERE $where

			ORDER BY
				`".TBL_PROPERTY."`.`property_id` DESC
		";

		$properties = $this->db->query($sql)->result();

		$updates = $this->getUpdates();

		$rate_types = $this->getRateTypes($properties);
		
		//
		
		foreach( $properties as $property )
		{
			$property->updates = [];
			
			foreach( $updates as $update )
			{
				if( $property->property_id === $update->room_id )
				{
					$property->updates[$update->separate_date] = $update;
				}
			}
		}
		
		//
		
		foreach( $properties as $property )
		{
			$property->rate_types = [];
			
			foreach( $rate_types as $rate_type )
			{
				if( $property->property_id === $rate_type->room_id )
				{
					$property->rate_types[] = $rate_type;
				}
			}
		}
		
		return $properties;
	}

	public function getCalendarData()
	{
		$this->initDates();
		
		$ed = clone $this->endDate;
		$ed->sub(new DateInterval('P1D'));


		return [
			'today' => $this->today,
			'startDate' => $this->startDate,
			'endDate' => $ed,
			'period' => $this->period,
			'curr_date' => $this->curr_date,
			'add_date' => $this->add_date,
			'todayMonth' => $this->todayMonth,
			'startMonthDate' => $this->startMonthDate,
			'endMonthDate' => $this->endMonthDate,
			'prevMonthDate' => $this->prevMonthDate,
			'nextMonthDate' => $this->nextMonthDate,
			'monthPeriod' => $this->monthPeriod,
			'months' => $this->months,
			'properties' => $this->getProperties(),
			'con_cha' => $this->channel_model->user_channel(),
		];
	}   

	private function getReservations($properties)
	{
		$property_ids = [];
		
		foreach( $properties as $property )
		{
			$property_ids[] = $property->property_id;
		}
		
		$sql = "
			SELECT 
				*

			FROM 
				`".RESERV."`

			WHERE
				`".RESERV."`.`individual_channel_id`='0' AND
				`".RESERV."`.`owner_id`='".current_user_type()."' AND
				`".RESERV."`.`hotel_id`='".hotel_id()."' AND
				`".RESERV."`.`room_id` IN (".implode(',', $property_ids).")"/* AND
				str_to_date(`".RESERV."`.`separate_date`, '%d/%m/%Y') >= str_to_date('".$this->startDate->format('d/m/Y')."', '%d/%m/%Y') AND 
				str_to_date(`".RESERV."`.`separate_date`, '%d/%m/%Y') <= str_to_date('".$this->endDate->format('d/m/Y')."', '%d/%m/%Y')
		"*/;

		return $this->db->query($sql)->result();
	}

	private function getRateTypesRefun($properties)
	{
		$property_ids = [];
		
		foreach( $properties as $property )
		{
			$property_ids[] = $property->property_id;
		}
		
		$sql = "
			SELECT 
				*

			FROM 
				`".RATE_TYPES_REFUN."`

			WHERE
				`".RATE_TYPES_REFUN."`.`user_id`='".current_user_type()."' AND
				`".RATE_TYPES_REFUN."`.`hotel_id`='".hotel_id()."' AND
				`".RATE_TYPES_REFUN."`.`room_id` IN (".implode(',', $property_ids).")  
		";

		return $this->db->query($sql)->result();
	}

	function getRatesGuestsCalendar($property_id)
	{
		$this->initDates();
		
		$properties = $this->getProperties($property_id);
		
		//$reservations = $this->getReservations($properties);
		
		$rate_types_refun = $this->getRateTypesRefun($properties);
		
		foreach( $properties as $property )
		{
			$property->rate_types_refun = [];
			
			foreach( $rate_types_refun as $rate_type_refun )
			{
				if( $property->property_id === $rate_type_refun->room_id )
				{
					$property->rate_types_refun[] = $rate_type_refun;
				}
			}
		}

		return [
			'property_id' => $property_id,
			'today' => $this->today,
			'startDate' => $this->startDate,
			'endDate' => $this->endDate,
			'period' => $this->period,
			'properties' => $properties,
		];
	}
}