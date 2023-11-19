<?php

namespace App\Helpers;

use App\Jobs\NotificationJob;
use App\Models\MailCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Laravel\Horizon\Contracts\JobRepository;

class QueueHelper
{
    public static function sendJob($user_id_array, $message, $data_id)
    {
        //user_id_array => array olarak iletilecek kullanıcıların id değerlerini içerir. type->array
        //message => iletilecek olan mesajı içerir. type->string
        //data_id => oluşturulan kaydın id değeri, Ör: Duyuru için oluşturulduğunda ilgili duyurunun id değeri

        $totalMailsSent = new MailCount();
        $totalMailsSent->total_count = count($user_id_array);
        $totalMailsSent->send_success = 0;
        $totalMailsSent->job = $data_id;
        $totalMailsSent->save();

        foreach ($user_id_array as $userId) {
            NotificationJob::dispatch($userId, $message, $data_id);
        }
    }


    public function mailCount(Request $request)
    {
        $status = 0;
        $job = MailCount::where('job', $request->id)->first();
        $total_count = $job->total_count;
        $send_success = $job->send_success;


        //failed olanlar için
        $uniqueIdMatches = $this->getFailedJobs($request->id);

        //failed ve success olanları topluyorum, toplama eşitse tüm gönderme işlemi bitti demek
        if ($total_count == ($send_success + count($uniqueIdMatches))) {
            $status = 1;
        }
        return response()->json(['success' => $send_success, 'total' => $total_count, 'failed' => count($uniqueIdMatches), 'status' => $status]);
    }

    public function getFailedJobs($job_id = null)
    {
        $failedJobs = app(JobRepository::class)->getFailed();
        $faileds = [];

        foreach ($failedJobs as $failedJob) {
            $failedData = unserialize(json_decode($failedJob->payload)->data->command);
            $jobId = $failedJob->id;
            $connection = $failedJob->connection;
            $queue = $failedJob->queue;
            $name = $failedJob->name;
            $status = $failedJob->status;
            $exception = $failedJob->exception;

            $faileds[] = [
                'jobId' => $jobId,
                'connection' => $connection,
                'queue' => $queue,
                'name' => $name,
                'status' => $status,
                'command' => $failedData->toArray(),
                'exception' => $exception,
            ];
        }

        if ($job_id === null) {
            return $faileds;
        }

        $uniqueIdMatches = [];
        $uniqueIds = [];

        foreach ($faileds as $failed) {
            if ($failed['command']['unique_id'] == $job_id) {
                if (!in_array($failed['command']['userId'], $uniqueIds)) {
                    $uniqueIds[] = $failed['command']['userId'];
                    $uniqueIdMatches[] = $failed;
                }
            }
        }
        return $uniqueIdMatches;
    }

    public function sendFailedJobs(Request $request)
    {
        $job_id = $request->id;
        $failed_jobs = $this->getFailedJobs($job_id);
        Session::put("old_failed_count_{$job_id}", count($failed_jobs));
        $old_failed_count = Session::get("old_failed_count_{$job_id}");

        foreach ($failed_jobs as $failed_job) {
            $failedJobId = $failed_job['jobId'];
            DB::table('failed_jobs')->where('uuid', $failedJobId)->delete();
        }

        $job = MailCount::where('job', $request->id)->first();
        $uniqueIdMatches = $this->getFailedJobs($request->id);
        $new_failed_count = count($uniqueIdMatches);
        $send_success = $job->send_success;

        if ($new_failed_count < $old_failed_count) {
            $difference = $old_failed_count - $new_failed_count;
            $send_success += $difference;
            $job->send_success = $send_success;
            $job->save();
        }
        return response()->json(['success', 'Tekrar gönderme başarılı']);
    }
}
