<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message.
     *
     * @param string|null $to The destination phone number
     * @param string $message The message body
     * @return bool
     */
    public function sendMessage(?string $to, string $message): bool
    {
        if (empty($to)) {
            Log::warning("WhatsApp send skipped: Recipient phone number is empty.", ['message' => $message]);
            return false;
        }

        $provider = config('services.whatsapp.provider', 'log');

        if ($provider === 'twilio') {
            return $this->sendViaTwilio($to, $message);
        }

        // Default: Log provider
        $this->logMessage($to, $message);
        return true;
    }

    /**
     * Send WhatsApp message via Twilio.
     */
    protected function sendViaTwilio(string $to, string $message): bool
    {
        $sid = config('services.whatsapp.twilio.sid');
        $authToken = config('services.whatsapp.twilio.token');
        $from = config('services.whatsapp.twilio.from');

        if (empty($sid) || empty($authToken) || empty($from)) {
            Log::error("Twilio WhatsApp sending failed: Missing configuration (SID, Token, or From number). Falling back to logging.");
            $this->logMessage($to, $message);
            return false;
        }

        // Format number for WhatsApp
        $formattedTo = 'whatsapp:' . $this->formatPhoneNumber($to);
        $formattedFrom = 'whatsapp:' . $this->formatPhoneNumber($from);

        try {
            $response = Http::withBasicAuth($sid, $authToken)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                    'To' => $formattedTo,
                    'From' => $formattedFrom,
                    'Body' => $message,
                ]);

            if ($response->successful()) {
                Log::info("WhatsApp message successfully sent via Twilio to {$to}", ['sid' => $response->json('sid')]);
                return true;
            }

            Log::error("Twilio WhatsApp sending returned error status: " . $response->status() . ". Falling back to simulated log.", [
                'response' => $response->body(),
                'to' => $to,
            ]);
            $this->logMessage($to, $message);
            return false;
        } catch (\Exception $e) {
            Log::error("Exception occurred while sending Twilio WhatsApp message: " . $e->getMessage() . ". Falling back to simulated log.", [
                'exception' => $e,
                'to' => $to,
            ]);
            $this->logMessage($to, $message);
            return false;
        }
    }

    /**
     * Log the WhatsApp message to a custom log file and laravel.log.
     */
    protected function logMessage(string $to, string $message): void
    {
        Log::channel('whatsapp')->info("TO: {$to} | MESSAGE: {$message}");
        Log::info("WhatsApp Simulated Notification | TO: {$to} | MESSAGE: {$message}");
    }

    /**
     * Format phone number to E.164 format.
     */
    protected function formatPhoneNumber(string $number): string
    {
        // Strip out non-digit characters except plus sign
        $clean = preg_replace('/[^\d+]/', '', $number);
        
        // Handle local Pakistani numbers starting with 03xx (e.g. 03377089909)
        if (str_starts_with($clean, '03') && strlen($clean) === 11) {
            $clean = '+92' . substr($clean, 1);
        }
        
        // Handle Pakistani numbers starting with 3xx (e.g. 3377089909)
        if (str_starts_with($clean, '3') && strlen($clean) === 10) {
            $clean = '+92' . $clean;
        }

        // If it doesn't start with '+', prepend it (assuming it's a full international number with country code)
        if (!str_starts_with($clean, '+')) {
            $clean = '+' . $clean;
        }
        return $clean;
    }
}
