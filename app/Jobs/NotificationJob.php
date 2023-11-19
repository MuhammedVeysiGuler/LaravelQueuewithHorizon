<?php

namespace App\Jobs;

use App\Models\MailCount;
use App\Models\User;
use App\Notifications\MailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $message;
    protected $unique_id;

    public function getUserId()
    {
        return $this->userId;
    }

    public function getUniqueId()
    {
        return $this->unique_id;
    }

    public function toArray()
    {
        return [
            'userId' => $this->userId,
            'message' => $this->message,
            'unique_id' => $this->unique_id,
            'job' => $this->job,
            'connection' => $this->connection,
            'queue' => $this->queue,
            'chainConnection' => $this->chainConnection,
            'chainQueue' => $this->chainQueue,
            'chainCatchCallbacks' => $this->chainCatchCallbacks,
            'delay' => $this->delay,
            'afterCommit' => $this->afterCommit,
            'middleware' => $this->middleware,
            'chained' => $this->chained,
        ];
    }

    public function __construct($userId, $message, $unique_id)
    {
        $this->userId = $userId;
        $this->message = $message;
        $this->unique_id = $unique_id;
    }

    public function handle(Request $request)
    {
        try {
            $user = User::find($this->userId);
            $user->notify(new MailNotification($this->message));
            $totalMailsSent = MailCount::where('job', $this->unique_id)->first();
            $totalMailsSent->send_success = ($totalMailsSent->send_success) + 1;
            $totalMailsSent->save();
        } catch (\Exception $e) {
            dd("hata oluştu => ", $e->getMessage());
        }
    }

}
