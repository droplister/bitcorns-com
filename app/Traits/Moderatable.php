<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait Moderatable {

    /**
     * Accept
     */
    public function accept()
    {
        return $this->update([
            'accepted_at' => \Carbon\Carbon::now(),
            'rejected_at' => null,
        ]);
    }

    /**
     * Reject
     */
    public function reject()
    {
        return $this->update([
            'accepted_at' => null,
            'rejected_at' => \Carbon\Carbon::now(),
        ]);
    }
}