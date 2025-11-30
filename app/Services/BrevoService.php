<?php

namespace App\Services;

use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Configuration;
use Brevo\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class BrevoService
{
  protected $apiInstance;
  protected $fromEmail;
  protected $fromName;

  public function __construct()
  {
    $apiKey = config('services.brevo.api_key');

    if (!$apiKey) {
      throw new \Exception('Brevo API key is not configured. Please set BREVO_API_KEY in your .env file.');
    }

    $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);
    $this->apiInstance = new TransactionalEmailsApi(new Client(), $config);

    $this->fromEmail = config('services.brevo.from_email', config('mail.from.address'));
    $this->fromName = config('services.brevo.from_name', config('mail.from.name'));
  }

  /**
   * Send an email using Brevo API
   *
   * @param string $toEmail
   * @param string $toName
   * @param string $subject
   * @param string $htmlContent
   * @return array
   * @throws \Exception
   */
  public function sendEmail(string $toEmail, string $toName, string $subject, string $htmlContent): array
  {
    try {
      $sendSmtpEmail = new SendSmtpEmail([
        'to' => [['email' => $toEmail, 'name' => $toName]],
        'sender' => ['email' => $this->fromEmail, 'name' => $this->fromName],
        'subject' => $subject,
        'htmlContent' => $htmlContent,
      ]);

      $result = $this->apiInstance->sendTransacEmail($sendSmtpEmail);

      return [
        'success' => true,
        'message_id' => $result->getMessageId(),
      ];
    } catch (\Exception $e) {
      Log::error('Brevo API Error: ' . $e->getMessage());
      throw new \Exception('Failed to send email: ' . $e->getMessage());
    }
  }
}
