<?php

namespace App\Console\Commands;

use App\Models\ArticleView;
use App\Models\Comment;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('app:send-daily-statistics')]
#[Description('Send daily website statistics to moderators')]
class SendDailyStatistics extends Command
{
    public function handle(): int
    {
        $today = today();
        $sentKey = 'daily_statistics_sent_' . $today->toDateString();

        if (\Illuminate\Support\Facades\Cache::has($sentKey)) {
            $this->info('Статистика за сегодня уже отправлена.');

            return self::SUCCESS;
        }
        $viewsCount = ArticleView::whereDate('created_at', $today)
            ->count();

        $commentsCount = Comment::whereDate('created_at', $today)
            ->count();

        $moderatorEmail = env('MODERATOR_EMAIL');

        if (!$moderatorEmail) {
            $this->error('Не задан MODERATOR_EMAIL в файле .env');

            return self::FAILURE;
        }

        Mail::raw(
            "Статистика сайта за {$today->format('d.m.Y')}\n\n"
                . "Количество просмотров статей: {$viewsCount}\n"
                . "Количество новых комментариев: {$commentsCount}",
            function ($message) use ($moderatorEmail, $today) {
                $message->to($moderatorEmail)
                    ->subject('Статистика сайта за ' . $today->format('d.m.Y'));
            }
        );
        \Illuminate\Support\Facades\Cache::put($sentKey, true, now()->endOfDay());
        $this->info("Статистика отправлена на {$moderatorEmail}.");

        return self::SUCCESS;
    }
}
