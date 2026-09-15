<?php

namespace App\Jobs;

use App\Models\Post;
use App\Services\SocialPublisher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PublicarPostRedesSociais implements ShouldQueue
{
    use Queueable;

    public function __construct(public Post $post)
    {
    }

    public function handle(SocialPublisher $publisher): void
    {
        $publisher->publicarPendentes($this->post);
    }
}
