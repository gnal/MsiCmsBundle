<?php

namespace Msi\CmsBundle\Mailer;

class Mailer
{
    protected $mailer;
    protected $emailManager;
    protected $templating;

    public function __construct($mailer, $emailManager, $templating)
    {
        $this->mailer = $mailer;
        $this->emailManager = $emailManager;
        $this->templating = $templating;
    }

    public function sendEmail($name, $data = null, $toWho = null, $attachments = [])
    {
        $emails = $this->emailManager->findAll(
            [
                'a.name' => $name,
                'translations.published' => true,
            ],
            [
                'a.translations' => 'translations',
            ]
        );

        foreach ($emails as $email) {
            if ($data) {
                $body = preg_replace_callback(
                    '@#[a-zA-z0-9]+#@',
                    function ($matches) use ($data) {
                        $code = str_replace('#', '', $matches[0]);
                        $getter = 'get'.ucfirst($code);

                        return is_array($data) ? $data[$code] : $data->$getter();
                    },
                    $email->getTranslation()->getBody()
                );
            } else {
                $body = $email->getTranslation()->getBody();
            }

            $rendered = $this->templating->render('MsiCmsBundle:Email:base.html.twig', [
                'subject' => $email->getTranslation()->getSubject(),
                'body' => $body,
            ]);

            // fix "to" to merge it with entity "to"

            if ($email->getToWho() && $toWho) {
                $toWho = ', '.$toWho;
            }

            // fix stuff to be array

            if ($email->getToWho().$toWho) {
                $to = explode(', ', $email->getToWho().$toWho);
            } else {
                $to = [];
            }

            if ($email->getCc()) {
                $cc = explode(', ', $email->getCc());
            } else {
                $cc = [];
            }

            if ($email->getBcc()) {
                $bcc = explode(', ', $email->getBcc());
            } else {
                $bcc = [];
            }

            // send email

            $this->send(
                $email->getFromWho(),
                $to,
                $cc,
                $bcc,
                $email->getTranslation()->getSubject(),
                $rendered,
                $attachments
            );
        }
    }

    protected function send($fromWho, $toWho, $cc, $bcc, $subject, $body, $attachments = [])
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromWho)
            ->setTo($toWho)
            ->setCc($cc)
            ->setBcc($bcc)
            ->setBody($body, 'text/html')
        ;

        foreach ($attachments as $attachment) {
            $message->attach(\Swift_Attachment::fromPath($attachment));
        }

        $this->mailer->send($message);
    }
}
