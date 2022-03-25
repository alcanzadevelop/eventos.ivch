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
    recordPayment($conn, $response->order, $id, $response->subject, $response->amount, $response->created_at);
}

function recordPayment($conn, $userId, $paymentId, $subject, $amount, $created_at){
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO `transaction`(`paymentId`, `userId`, `paymentSubject`, `paymentAmount`, `paymentDate`) VALUES ('".$paymentId."','".$userId."','".$subject."',".$amount.",'".$created_at."')";
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

?>