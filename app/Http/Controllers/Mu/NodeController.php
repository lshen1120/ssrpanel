<?php

namespace  App\Http\Controllers\Mu;

use App\Http\Controllers\Controller;
use App\Http\Models\SsNodeInfo;
use App\Http\Models\SsNodeOnlineLog;
use Illuminate\Http\Request;

class NodeController extends Controller
{
    public function onlineUserLog(Request $request)
    {
        $node_id =$request->route('id');
        $count = $request->get('count');
        $log = new SsNodeOnlineLog();
        $log->node_id = $node_id;
        $log->online_user = $count;
        $log->log_time = time();
        if (!$log->save()) {
            $res = [
                "ret" => 0,
                "msg" => "update failed",
            ];
            return response()->json($res);
        }
        $res = [
            "ret" => 1,
            "msg" => "ok",
        ];
        return response()->json($res);
    }

    public function info(Request $request)
    {
        $node_id = $request->route('id');
        $load = $request->get('load');
        $uptime = $request->get('uptime');

        $log = new SsNodeInfo();
        $log->node_id = $node_id;
        $log->load = $load;
        $log->uptime = $uptime;
        $log->log_time = time();
        if (!$log->save()) {
            $res = [
                "ret" => 0,
                "msg" => "update failed",
            ];
            return response()->json($res);
        }
        $res = [
            "ret" => 1,
            "msg" => "ok",
        ];
        return response()->json($res);
    }

}