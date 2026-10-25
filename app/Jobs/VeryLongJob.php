<?php

namespace App\Jobs;

use App\Mail\NewArticleMail;
use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class VeryLongJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Article $article
    ) {
    }

    public function handle(): void
    {
        $moderator = User::whereHas('role', function ($query) {
            $query->where('name', 'moderator');
        })->first();

        if ($moderator && $moderator->email) {
            Mail::to($moderator->email)->send(
                new NewArticleMail($this->article)
            );
        }
    }
}
