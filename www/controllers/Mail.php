<?php

namespace Controllers;

require_once(ROOT . '/libs/PHPMailer/Exception.php');
require_once(ROOT . '/libs/PHPMailer/PHPMailer.php');
require_once(ROOT . '/libs/PHPMailer/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    private $mail;
    private $to;
    private $subject;
    private $content;
    private $link;
    private $linkName;
    private $attachmentFilePath;

    public function __construct(string $to, string $subject, string $content, string $link = null, string $linkName = 'Click here', string $attachmentFilePath = null)
    {
        if (empty($to)) {
            throw new \Exception('Error: mail recipient cannot be empty');
        }
        if (empty($subject)) {
            throw new \Exception('Error: mail subject cannot be empty');
        }
        if (empty($content)) {
            throw new \Exception('Error: mail message cannot be empty');
        }

        /**
         *  if there is a , in the $to string, it means there are multiple recipients
         */
        if (strpos($to, ',') !== false) {
            $to = explode(',', $to);
        }






        
 
        /**
         *  HTML message template
         *  Powered by MJML
         */
        ob_start();
        include(ROOT . '/templates/mail/mail.template.html.php');
        $template = ob_get_clean();

        /**
         *  PHPMailer
         */
        $mail = new PHPMailer(true);

        try {
            /**
             *  From and ReplyTo
             */
            $mail->setFrom('noreply@' . WWW_HOSTNAME, PROJECT_NAME);
            $mail->addReplyTo('noreply@' . WWW_HOSTNAME, PROJECT_NAME);

            /**
             *  Case a SMTP server is used
             */
            if (MAIL_SMTP_ENABLE) {
                /**
                 *  SMTP server settings
                 */
                $mail->isSMTP();                                 // Send using SMTP
                $mail->Host       = MAIL_SMTP_HOST;              // Set the SMTP server to send through
                $mail->Port       = MAIL_SMTP_PORT;              // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above   
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable implicit TLS encryption

                if (MAIL_SMTP_AUTH) {
                    $mail->SMTPAuth   = true;               // Enable SMTP authentication
                    $mail->Username   = MAIL_SMTP_USERNAME; // SMTP username
                    $mail->Password   = MAIL_SMTP_PASSWORD; // SMTP password
                }

                /**
                 *  If use SMTP, change From and ReplyTo
                 */
                $mail->setFrom(MAIL_SMTP_FROM, PROJECT_NAME);
                $mail->addReplyTo(MAIL_SMTP_FROM, PROJECT_NAME);
            }

            /**
             *  Recipients
             *  If there are multiple recipients (array)
             */
            if (is_array($to)) {
                foreach ($to as $recipient) {
                    $mail->addAddress($recipient);
                }
            /**
             *  If single recipient (string)
             */
            } else {
                $mail->addAddress($to);
            }
            
            /**
             *  Attachments
             */
            if (!empty($attachmentFilePath)) {
                $mail->addAttachment($attachmentFilePath);
            }

            /**
             *  Content
             */
            $mail->isHTML(true);    // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $template;

            /**
             *  Charset and encoding
             */
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            /**
             * Send mail
             */
            $mail->send();
        } catch (Exception $e) {
            throw new Exception('Mail could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        }
    }
}
