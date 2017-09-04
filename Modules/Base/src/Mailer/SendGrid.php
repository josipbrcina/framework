<?php

namespace Framework\Base\Mailer;

use SendGrid as SendGridSender;
use SendGrid\Content;
use SendGrid\Email as EmailAddress;
use SendGrid\Mail as SendgridEmail;

/**
 * Class SendGrid
 * @package Framework\Base\Src\Mailer
 */
class SendGrid extends Mailer
{
    public function send()
    {
        $apiKey = getenv('SENDGRID_API_KEY');
        $sg = new SendGridSender($apiKey);

        $options = $this->getOptions();
        $emailFrom = $this->getFrom();
        $emailTo = $this->getTo();

        $from = new EmailAddress($emailFrom, $emailFrom);
        $subject = $this->getSubject();
        $to = new EmailAddress($emailTo, $emailTo);
        $content = new Content("text/plain", $this->getTextBody());

        $mail = new SendGridEmail($from, $subject, $to, $content);
        $contentHtml = new Content("text/html", $this->getHtmlBody());
        $mail->addContent($contentHtml);

        if (array_key_exists('cc', $options) && !empty($options['cc'])) {
            $mail->personalization[0]->addCc(['email' => $options['cc']]);
        }
        if (array_key_exists('bcc', $options) && !empty($options['bcc'])) {
            $mail->personalization[0]->addBcc(['email' => $options['bcc']]);
        }

        $response = $sg->client->mail()->send()->post($mail);

        $responseMsg = 'Email was successfully sent!';
        $errors = json_decode($response->body());

        if ($errors) {
            $responseMsg = $errors->errors[0]->message;
        }

        return $responseMsg;
    }
}
