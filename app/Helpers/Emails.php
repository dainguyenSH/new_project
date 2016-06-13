<?php

function sendEmailFromMandrill($subject, $toEmail, $toEmailName, $emailTemplate, $dataEmailTemplate)
{
	$mandrill = new Mandrill("HgXtBW8StI34Ifbw37qhLQ");
    $message = [
		'subject' => $subject,
        'html' => (string)view($emailTemplate, $dataEmailTemplate),
        'from_email' => 'donotreply@abbycard.com',
        'from_name' => 'AbbyCard',
        'to' => [
        	[
        		'email' => $toEmail,
                'name' => $toEmailName,
                'type' => 'to'
        	]
        ]
    ];

    return $mandrill->messages->send($message);
}
