<?php

require_once('al-includes/core.php');
require 'al-includes/emails/clientEmail.php';
require 'al-includes/emails/paymentEmail.php';
require 'vendor/autoload.php';

if(!empty($_GET['id'])){
    checkRecordPayment($conn, $_GET['id']);
}

function checkRecordPayment($conn, $id){
    $client = new \GuzzleHttp\Client();
    $body = $client->request('GET', 'https://app.payku.cl/api/transaction/'.$id, [
        'headers' => [
            'Authorization' => 'Bearer tkpuaa19341fe10942f825f247c13843'
        ]
    ])->getBody();
    $response = json_decode($body);
    createTicket($conn, $response->order, $response->subject);
    $ticketId = getTicketId($conn, $response->order);
    echo "El Id del Ticket es: ".$ticketId."<br/>";
    recordPayment($conn, $response->order, $id, $response->subject, $response->amount, $response->created_at, $response->status, $ticketId);
}

function createTicket($conn, $personId, $subject){
    $sql = "INSERT INTO `ticket`(`eventId`, `personId`, `ticketState`) VALUES (".$subject.",".$personId.",'NONE')";
    echo "Cree el Ticket: ".$sql."<br/>";
}

function getTicketId($conn, $personId){
    $stmt = $conn->query("SELECT ticketId FROM ticket WHERE personId=".$personId);
    while ($row = $stmt->fetch()) {
        $value=$row['ticketId'];
    }
    return $value;
}

function recordPayment($conn, $personId, $transactionId, $subject, $amount, $created_at, $status, $ticketId){
    $sql = "INSERT INTO `transaction` (`transactionId`, `personId`, `ticketId`, `transactionSubject`, `transactionAmount`, `transactionDate`, `transactionStatus`) VALUES (".$transactionId.", ".$personId.",".$ticketId.",'".$subject."',".$amount.",'".$created_at."','".$status."')";
    echo "Cree la Transacción: ".$sql."<br/>";
    updateTicket($conn, $personId);
    updateCapacity($conn, $subject);
}

function updateTicket($conn, $personId){
    $sql = "UPDATE ticket SET ticketState='VALID' WHERE personId=".$personId." AND ticketId=".$theId;
    echo "Actualicé el estado del Ticket: ".$sql."<br/>";
    return true;
}

function updateCapacity($conn, $eventId){
    $sql="SELECT * FROM event WHERE eventId=".$eventId);
    echo "El Id del Evento es: ".$sql."<br/>";
    while ($row = $stmt->fetch()) {
        $value=$row['eventCapacity'];
    }
    echo "Tiene una capacidad de: ".$value."<br/>";
    $theValue=$value-1;
    echo "Y Ahora Tiene una capacidad de: ".$theValue."<br/>";
    $sql = "UPDATE event SET eventCapacity=".$theValue." WHERE eventId=".$eventId;
    echo "Y lo actualicé con: ".$sql."<br/>";
    return true;
}

?>