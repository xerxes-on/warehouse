<?php

namespace App\Http\Traits;

trait CanSetFlashMessageTrait
{
    public function sendMessage(string $message): void
    {
        session()->flash('message', $message);
    }
}
