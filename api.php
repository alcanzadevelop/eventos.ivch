<?php

require_once('al-includes/core.php');
require_once('al-includes/functions.php');
require 'al-includes/emails/welcomeEmail.php';
require 'al-includes/emails/welcomeInvoiceEmail.php';
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
    createTicket($conn, $response->order);
    $ticketId = getTicketId($conn, $response->order);
    recordPayment($conn, $response->order, $id, $response->subject, $response->amount, $response->created_at, $response->status, $ticketId);
}

function recordPayment($conn, $personId, $transactionId, $subject, $amount, $created_at, $status, $ticketId){
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO `transaction`(`transactionId`, `personId`, `ticketId`, `transactionSubject`, `transactionAmount`, `transactionDate`, `transactionStatus`) VALUES (".$transactionId.", ".$personId.",".$ticketId.",'".$subject."','".$amount."','".$created_at."','".$status."')";
    $conn->exec($sql);
    sendEmail($conn, $userId, $subject, $amount);
}

function sendEmail($conn, $memberId, $subject, $amount)
{
    $stmt = $conn->query("SELECT * FROM member WHERE memberId=".$memberId);
    while ($row = $stmt->fetch()) {
        welcomeEmail($row['memberEmail']);
        welcomeInvoiceEmail($row['memberEmail'], $row['memberName'], $row['memberLastName'], $row['memberRut'], $row['memberBirth'], $row['memberPhone'], $row['memberAddress'], $row['memberLocal'], $row['memberChurch'], $row['memberGender'], $row['memberYear']);
        header('location: https://matriculas.institutovc.cl/bienvenida.php');
    }
}

function createTicket($conn, $personId){
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO `ticket`(`eventId`, `personId`, `ticketState`) VALUES ('0',".$personId.",'VIGENTE')";
    $conn->exec($sql);
    return true;
}

function getTicketId($conn, $personId){
    $stmt = $conn->query("SELECT * FROM ticket WHERE personId=".$personId);
    while ($row = $stmt->fetch()) {
        $value=$row['ticketId']);
    }
    return $value;
}

?>