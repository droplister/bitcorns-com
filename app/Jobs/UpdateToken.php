<?php

namespace App\Jobs;

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
        $this->counterparty = new \JsonRPC\Client(env('CP_API'));
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
        try
        {
            // Get Token Issuances
            $issuances = $this->getIssuances();

            // Get Latest Issuance
            $issuance = end($issuances);

            // Crunch Total Issued
            $total_issued = array_sum(array_column($issuances,'quantity'));

            // Update Token
            $this->updateToken($issuance, $total_issued);
        }
        catch(\Exception $e)
        {
            // API 404
        }
    }

    /**
     * Counterparty API
     * https://counterparty.io/docs/api/#get_table
     */
    private function getIssuances()
    {
        return $this->counterparty->execute('get_issuances', [
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
    }

    /**
     * Update Token
     */
    private function updateToken($issuance, $total_issued)
    {
        return $this->token->update([
            'long_name' => $issuance['asset_longname'],
            'issuer' => $issuance['issuer'],
            'description' => $issuance['description'],
            'total_issued' => $total_issued,
            'divisible' => $issuance['divisible'],
            'locked' => $issuance['locked'],
        ]);
    }
}