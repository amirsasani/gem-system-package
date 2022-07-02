<?php

namespace AmirSasani\GemSystem;

use AmirSasani\GemSystem\Models\Gem;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class GemService
{
    private $user;

    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function increment(int $amount = 1)
    {
        $this->changeAmount($amount);

        return $this;
    }

    public function decrement(int $amount = -1)
    {
        // decrement amount should be negative
        $amount = $amount > 0 ? $amount * -1 : $amount;
        $this->changeAmount($amount);

        return $this;
    }

    public function getGem()
    {
        return $this->user->gem;
    }

    private function changeAmount(int $amount = 1)
    {
        DB::transaction(function () use($amount) {
            try {
                $userGem = Gem::lockForUpdate()->firstOrCreate(['user_id' => $this->user->id]);
            }catch (QueryException $e) {
                $errorCode = $e->errorInfo[1];
                if ($errorCode == 1062) {
                    $userGem = $this->user->gem()->create();
                }
            }

            $userGem->update(['gem' => DB::raw(sprintf('gem + %d', $amount))]);

            $userGem->transactions()->create(['amount' => $amount]);
        }, 3);

        return true;
    }
}
