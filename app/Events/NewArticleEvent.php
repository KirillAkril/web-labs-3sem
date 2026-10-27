<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewArticleEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public int $id;
    public string $title;

    public function __construct(int $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('test'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'NewArticleEvent';
    }

    public function broadcastWith(): array
    {
        return [
            'article' => [
                'id' => $this->id,
                'title' => $this->title,
            ],
        ];
    }
}
