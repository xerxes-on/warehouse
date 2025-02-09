<?php

declare(strict_types=1);

namespace App\Http\Traits;

trait CanSetFlashMessageTrait
{
    public function setMessage(string $message): void
    {
        session()->flash('message', $message);
    }
}
