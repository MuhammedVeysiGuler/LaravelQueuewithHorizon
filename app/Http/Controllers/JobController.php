<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class JobController extends Controller
{
//    public $id;
    /**
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */

    public function enqueue(Request $request)
    {
        $users = User::all();

        ini_set('max_execution_time', 1000000);
        foreach ($users as $user) {
            if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                $details = ['email' => $user->email];
                $id = ['id' => $user->id];
                $emailJob = (new SendEmail($details, $id))->delay(Carbon::now()->addSeconds(3));
                dispatch($emailJob);
            }
        }



    }
}
