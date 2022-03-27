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
    if($response->status=="success"){
        createTicket($conn, $response->order, $response->subject);
        $ticketId = getTicketId($conn, $response->order);
        recordPayment($conn, $response->order, $id, $response->subject, $response->amount, $response->created_at, $response->status, $ticketId);
    }
    else{
        header('location: http://eventos.ivch.cl/');
    }
}

function createTicket($conn, $personId, $subject){
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO `ticket`(`eventId`, `personId`, `ticketState`) VALUES (".$subject.",".$personId.",'NONE')";
    $conn->exec($sql);
}

function getTicketId($conn, $personId){
    $stmt = $conn->query("SELECT ticketId FROM ticket WHERE personId=".$personId);
    while ($row = $stmt->fetch()) {
        $value=$row['ticketId'];
    }
    return $value;
}

function recordPayment($conn, $personId, $transactionId, $subject, $amount, $created_at, $status, $ticketId){
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO `transaction` (`transactionId`, `personId`, `ticketId`, `transactionSubject`, `transactionAmount`, `transactionDate`, `transactionStatus`) VALUES (".$transactionId.", ".$personId.",".$ticketId.",'".$subject."',".$amount.",'".$created_at."','".$status."')";
    $conn->exec($sql);
    updateTicket($conn, $personId, $ticketId);
    updateCapacity($conn, $subject);
    sendEmail($conn, $personId, $subject);
}

function updateTicket($conn, $personId, $ticketId){
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE ticket SET ticketState='VALID' WHERE personId=".$personId." AND ticketId=".$ticketId;
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return true;
}

function updateCapacity($conn, $eventId){
    $stmt = $conn->query("SELECT * FROM event WHERE eventId=".$eventId);
    while ($row = $stmt->fetch()) {
        $value=$row['eventCapacity'];
    }
    $theValue=$value-1;
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE event SET eventCapacity=".$theValue." WHERE eventId=".$eventId;
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return true;
}

function sendEmail($conn, $personId, $subject)
{
    $stmt = $conn->query("SELECT * FROM person WHERE personId=".$personId);
    while ($row = $stmt->fetch()) {
        clientEmail($conn, $row['personEmail'], $subject, $personId);
        paymentEmail($conn, $personId, $subject);
        header('location: http://eventos.ivch.cl/exito.php');
    }
}

?>