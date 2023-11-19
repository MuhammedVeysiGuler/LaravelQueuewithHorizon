<?php

namespace App\Http\Controllers;

use App\Helpers\QueueHelper;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AnnouncementController extends Controller
{
    public function index()
    {
        return view('index');
    }


    public function create(Request $request)
    {
        $announcement = new Announcement();
        $announcement->announcement_name = $request->name;
        $announcement->message = $request->message;
        if ($announcement->save()) {
            $userIds = User::query()->pluck('id');  // <-- gönderilecek user_id değerleri
            // bizim senaryomuzda tüm userlara göndereceğimiz için hepsini pluck->('id') ile alıyoruz.
            QueueHelper::sendJob($userIds, $announcement->message, $announcement->id);
        }
        return response()->json(['success' => 'Success']);
    }


    public function fetch()
    {
        $announcement = Announcement::query();

        return DataTables::of($announcement)
            ->addColumn('detail', function ($data) {
                return "<button onclick='detail(" . $data->id . ")' class='btn btn-info'>Detay</button>";
            })
            ->rawColumns(['detail'])
            ->make(true);
    }


    public function detail(Request $request)
    {
        $announcement = Announcement::find($request->id);
        return response()->json(['Success' => 'success', 'data' => $announcement]);
    }

}
