<?php

namespace mail\service;

use Silex\Application;

class MailService
{
    private $mailServerConfig;

    public function __construct($mailServerConfig)
    {
        $this->mailServerConfig = $mailServerConfig;
    }

    public function sendMail($recipient, $subject, $body, $attachments = array())
    {
        $mail = new \PHPMailer;
        $mail->From = $this->mailServerConfig->settings->sender->fromMailAddress;
        $mail->AddAddress($recipient);
        $mail->SetFrom(
            $this->mailServerConfig->settings->sender->fromMailAddress,
            $this->mailServerConfig->settings->sender->fromName
        );

        $mail->IsSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = $this->mailServerConfig->settings->server[0]->host;
        $mail->Port = $this->mailServerConfig->settings->server[0]->port;
        $mail->IsHTML(true);
        $mail->Username = $this->mailServerConfig->settings->server[0]->username;
        $mail->Password = $this->mailServerConfig->settings->server[0]->password;

        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                $mail->addAttachment($attachment, '', 'base64', 'application/octet-stream', 'attachment');
            }
        }

        $mail->Subject  = $subject;
        $mail->Body     = $body;
        $mail->WordWrap = 50;

        if (!$mail->Send()) {

        }
    }
}
