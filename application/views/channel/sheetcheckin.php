
<?php


$this->load->library('pdf/fpdf');

class PDF extends FPDF
{
	private $header;

	function __construct($orientation='P', $unit='mm', $size='A4',$datos='')
	{

		parent::__construct($orientation, $unit, $size);
		$this->header=$datos['header'];
	}
	
	// Cabecera de página
	function Header()
	{
		 
	    // Logo
	    $this->Image(base_url()."uploads/room_photos/noimage.jpg",10,1,33);
	    // Arial bold 15
	    $this->SetFont('Arial','B',15);
	     $this->SetFillColor(200,220,255);
	    // Movernos a la derecha
	    $this->Cell(80);

	    // Título
	    $this->Cell(40,10,$this->header,0,0,'C');
	    // Salto de línea
	    $this->Ln(25);
	}
	 
	// Pie de página
	function Footer()
	{
	    // Posición: a 1,5 cm del final
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Número de página
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}

	function ChapterTitle($label)
	{
	    // Arial 12
	    $this->SetFont('Arial','',12);
	    // Color de fondo
	    $this->SetFillColor(200,220,255);
	    // Título
	    $this->Cell(0,10,"$label",0,1,'C',true);
	    // Salto de línea
	    $this->Ln(4);
	}

	function Columna1($header,$dato,$size1=27,$size2=60)
	{
		$this->SetFont('','B',10);
		$this->Cell(40,6,"$header",'',0,'L');
		$this->SetFont('');
		$this->Cell(60,6,$dato,'',0,'L');
	}
	function Columna2($header,$dato,$size=50)
	{
		$this->SetFont('','B',10);
		$this->Cell(45,6,"$header",'',0,'L');
		$this->SetFont('');
		$this->Cell(60,6,$dato,'',0,'L');
		$this->Ln();
	}
}
$idioma=$language;
$headers['english']['header']="Checkin Form";
$headers['english']['reservation']="RESERVATION";
$headers['english']['channelname']="Channel Name:";
$headers['english']['confirmation']="Confirmation Number:";
$headers['english']['checkin']="Check-In Date:";
$headers['english']['checkout']="Check-Out Date:";
$headers['english']['adult']="Adult:";
$headers['english']['children']="Children:";
$headers['english']['subtotal']="Sub Total:";
$headers['english']['grandtotal']="Grand Total:";
$headers['english']['status']="Status:";
$headers['english']['booked']="Booked date:";

$headers['english']['guestdetails']="Guest Details";
$headers['english']['name']="Name:";
$headers['english']['email']="E-mail:";
$headers['english']['address']="Street address:";
$headers['english']['country']="Country:";
$headers['english']['phone']="Phone:";

$headers['english']['additional']="Additional Channel Details";
$headers['english']['roomname']="Channel Room Name:";

$headers['english']['roomdetails']="Room Details";
$headers['english']['guestcount']="Guest Count:";
$headers['english']['childrencount']="Child Count:";
$headers['english']['mealplan']="Meal Plan:";
$headers['english']['total']="Total:";

$headers['english']['notes']="Notes";
$headers['english']['policies']="Policies";
$headers['english']['guest']="Guest";

$headers['spanish']['header']="Formulario de Llegada";
$headers['spanish']['reservation']="RESERVACION";
$headers['spanish']['channelname']="Nombre del Canal:";
$headers['spanish']['confirmation']="Numero de Confirmacion:";
$headers['spanish']['checkin']="Fecha de Llegada:";
$headers['spanish']['checkout']="Fecha de Salida:";
$headers['spanish']['adult']="Adultos:";
$headers['spanish']['children']="Niños:";
$headers['spanish']['subtotal']="Sub Total:";
$headers['spanish']['grandtotal']="Grand Total";
$headers['spanish']['status']="Estatus";
$headers['spanish']['booked']="Fecha de Creación";

$headers['spanish']['guestdetails']="Detalles del huésped";
$headers['spanish']['name']="Nombre:";
$headers['spanish']['email']="E-mail:";
$headers['spanish']['address']="Dirección:";
$headers['spanish']['country']="Ciudad:";
$headers['spanish']['phone']="Telefono:";

$headers['spanish']['additional']="Detalles adicionales del canal";
$headers['spanish']['roomname']="Nombre de la Habitacion:";

$headers['spanish']['roomdetails']="Detalles de la habitación";
$headers['spanish']['guestcount']="Cantidad de Adultos:";
$headers['spanish']['childrencount']="Cantidad de Niños:";
$headers['spanish']['mealplan']="Plan de Comida:";
$headers['spanish']['total']="Total:";

$headers['spanish']['notes']="Notas";
$headers['spanish']['policies']="Politicas";
$headers['spanish']['guest']="Huésped";

$datos['header']=$headers[$idioma]['header'];
// Creación del objeto de la clase heredada
$pdf = new PDF($orientation='P', $unit='mm', $size='A4',$datos);

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$pdf->ChapterTitle($headers[$idioma]['reservation'].' '.$Reservation['reservationNumber']);

$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('');
//Datos

$pdf->Columna1($headers[$idioma]['channelname'],$Reservation['ChannelName']);
$pdf->Columna2($headers[$idioma]['confirmation'],$Reservation['reservationNumber']);

$pdf->Columna1($headers[$idioma]['checkin'],date('M d,Y',strtotime($Reservation['checkin'])));
$pdf->Columna2($headers[$idioma]['checkout'],date('M d,Y',strtotime($Reservation['checkout'])));

$pdf->Columna1($headers[$idioma]['adult'],$Reservation['numberAdults']);
$pdf->Columna2(utf8_decode($headers[$idioma]['children']),$Reservation['numberChilds']);

$pdf->Columna1($headers[$idioma]['subtotal'],$Reservation['currency'].number_format((float)$Reservation['totalStay']),2,'.','');
$pdf->Columna2($headers[$idioma]['grandtotal'],$Reservation['currency'].number_format((float)$Reservation['grandtotal']),2,'.','');

$pdf->Columna1($headers[$idioma]['status'],$Reservation['status']);
if(isset($Reservation['bookeddate']))
{
	$pdf->Columna2(utf8_decode($headers[$idioma]['booked']),date('M d,Y:h:m:s',strtotime($Reservation['bookeddate'])));
}


$pdf->Ln();

$pdf->ChapterTitle(utf8_decode($headers[$idioma]['guestdetails']));

$pdf->Columna1(utf8_decode($headers[$idioma]['name']),$Reservation['guestFullName']);
$pdf->Columna2(utf8_decode($headers[$idioma]['phone']),$Reservation['mobiler']);
$pdf->Columna1(utf8_decode($headers[$idioma]['email']),$Reservation['email']);
$pdf->Columna2(utf8_decode($headers[$idioma]['address']),$Reservation['address']);
$pdf->Columna1(utf8_decode($headers[$idioma]['country']),$Reservation['country']);
$pdf->Ln();
$pdf->Ln();



$pdf->ChapterTitle(utf8_decode($headers[$idioma]['additional']));

$pdf->SetFont('','B',10);
$pdf->Cell(60,6,utf8_decode($headers[$idioma]['roomname']),'',0,'L');
$pdf->SetFont('');
$pdf->Cell(60,6,$Reservation['roomTypeName'],'',0,'L');
$pdf->Ln();
$pdf->Ln();

$pdf->ChapterTitle(utf8_decode($headers[$idioma]['roomdetails']));

$pdf->Columna1(utf8_decode($headers[$idioma]['guestcount']),$roominfo['member_count']);
$pdf->Columna2(utf8_decode($headers[$idioma]['childrencount']),$roominfo['children']);
$pdf->Columna1(utf8_decode($headers[$idioma]['mealplan']),$roominfo['meal_name']);
$pdf->Columna2(utf8_decode($headers[$idioma]['checkin']),date('M d,Y',strtotime($Reservation['checkin'])));
$pdf->Columna1(utf8_decode($headers[$idioma]['checkout']),date('M d,Y',strtotime($Reservation['checkout'])));
$pdf->Columna2(utf8_decode($headers[$idioma]['total']),$Reservation['currency'].number_format((float)$Reservation['grandtotal']),2,'.','');
$pdf->Ln();
$pdf->Ln();



$pdf->ChapterTitle(utf8_decode($headers[$idioma]['notes']));

$pdf->SetFont('','',10);
$pdf->MultiCell(0,5,$Reservation['notes']);
$pdf->Ln();
$pdf->Ln();

$pdf->ChapterTitle(utf8_decode($headers[$idioma]['policies']));

$pdf->SetFont('','',10);
foreach ($policies as $value) {
	$pdf->SetFont('','B',10);
	$pdf->Cell(40,6,$value['Name'],'',0,'L');
	$pdf->SetFont('');
	$pdf->MultiCell(0,6,$value['description']);
}

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();


$pdf->Columna1(utf8_decode($headers[$idioma]['guest'])." 1:",$Reservation['guestFullName']);

if(isset($Reservation['allguest']))
{
	$guest=explode(',',  $Reservation['allguest']);
	$i=2;
	foreach ($guest as $value) {
		if(strlen($value)>0)
		{
			if($i%2)
			{
				
				$pdf->SetFont('','B',10);
				$pdf->Cell(20,6,utf8_decode($headers[$idioma]['guest'])." $i:",'',0,'L');
				$pdf->SetFont('');
				$pdf->Cell(60,6,$value,'',0,'L');
			}
			else
			{
				$pdf->SetFont('','B',10);
				$pdf->Cell(20,6,utf8_decode($headers[$idioma]['guest'])." $i:",'',0,'L');
				$pdf->SetFont('');
				$pdf->Cell(60,6,$value,'',0,'L');
				$pdf->Ln();
			}

			$i++;
		}
		
	}
}




$pdf->Output();


?>