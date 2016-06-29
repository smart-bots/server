<?php

namespace SmartBots\Console\Commands;

use Illuminate\Console\Command;

use SmartBots\TwilioNumber;
use GuzzleHttp\Client;

class TwilioSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twilio:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure twilio to verify account';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This command will access to Twilio API, get all of your phone number and save it to database
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info('Configuring Twilio...');

        $client = new Client([
            'base_uri' => sprintf('https://api.twilio.com/2010-04-01/Accounts/%s/', env('TWILIO_SID')),
            'auth'     => [env('TWILIO_SID'), env('TWILIO_TOKEN')],
            'defaults' => ['verify' => false]
        ]);

        $body = json_decode($client->request('GET', 'IncomingPhoneNumbers.json')->getBody());

        TwilioNumber::truncate();

        foreach ($body->incoming_phone_numbers as $number) {

            TwilioNumber::create([
                'sid'    => $number->sid,
                'number' => $number->phone_number
            ]);

            $response = $client->request('POST', sprintf('IncomingPhoneNumbers/%s.json', $number->sid), [
                    'form_params' => [
                        'VoiceUrl'               => route('twilioVoiceRequest'),
                        'VoiceMethod'            => 'GET',
                        // 'VoiceFallbackUrl'    => route('twilioVoiceFallbackRequest'),
                        // 'VoiceFallbackMethod' => 'GET',
                        'StatusCallback'         => route('twilioVoiceStatusCallBack'),
                        'StatusCallbackMethod'   => 'POST'
                    ]
                ]);
        }

        $this->info('Successully!');
    }
}
