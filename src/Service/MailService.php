<?php

namespace App\Service;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Message;

class MailService {

    public function sendMail($destinaire,$messageSubject, $messageBody,):void{
        
        $tgv = Transport::fromDsn('smtp://anthony.vouin76120@gmail.com:dxvqrgrvmmsuoenh@smtp.gmail.com:587');
        $mailer = new Mailer($tgv);
        $email = (new Email())->from('anthony.vouin76120@gmail.com')->to($destinaire)->subject($messageSubject)->html($messageBody);
        $mailer->send($email);
    }

}