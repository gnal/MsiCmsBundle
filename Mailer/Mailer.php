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
            ['a.name' => $name]
        );

        foreach ($emails as $email) {
            if ($data) {
                $body = preg_replace_callback(
                    '@#[a-zA-z0-9]+#@',
                    function ($matches) use ($data) {
                        $code = str_replace('#', '', $matches[0]);
                        $getter = 'get'.ucfirst(strtolower($code));

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

            $this->send(
                $email->getFromWho(),
                $toWho ?: $email->getToWho(),
                $email->getTranslation()->getSubject(),
                $rendered,
                $attachments
            );
        }
    }

    protected function send($fromWho, $toWho, $subject, $body, $attachments = [])
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromWho)
            ->setTo($toWho)
            ->setBody($body, 'text/html')
        ;

        foreach ($attachments as $attachment) {
            $message->attach(\Swift_Attachment::fromPath($attachment));
        }

        $this->mailer->send($message);
    }
}
