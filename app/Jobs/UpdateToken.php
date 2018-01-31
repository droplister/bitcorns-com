<?php

namespace App\Jobs;

use JsonRPC\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $counterparty;
    protected $token;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Token $token)
    {
        $this->counterparty = new Client(env('CP_API'));
        $this->counterparty->authentication(env('CP_USER'), env('CP_PASS'));
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $issuances = $this->counterparty->execute('get_issuances', [
            'filters' => [
                [
                    'field' => 'asset',
                    'op'    => '==',
                    'value' => $this->token->name,
                ],
                [
                    'field' => 'status',
                    'op'    => '==',
                    'value' => 'valid',
                ],
            ],
        ]);

        $issuance = end($issuances);
        $total_issued = array_sum(array_column($issuances,'quantity'));

        $this->token->update([
            'long_name' => $issuance['asset_longname'],
            'issuer' => $issuance['issuer'],
            'description' => $issuance['description'],
            'total_issued' => $total_issued,
            'divisible' => $issuance['divisible'],
            'locked' => $issuance['locked'],
        ]);
    }
}