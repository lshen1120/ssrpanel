<?php

namespace App\Http\Controllers\Mu;
use App\Http\Controllers\Controller;
use App\Http\Models\SsNode;
use App\Http\Models\User;
use App\Http\Models\UserTrafficLog;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // User List
    public function index()
    {
        $users= User::query()->select(
            "id","username","passwd","t","u","d","transfer_enable",
            "port","protocol","obfs","enable","expire_time as expire_time_d","method")
            ->get();
        foreach($users as $user){
            //datetime 转timestamp
            $user['switch']=1;
            $user['email']=$user['username'];
            $user['expire_time']=strval((new \DateTime($user['expire_time_d']))->getTimestamp());
        }
        $res = [
            "msg" => "ok",
            "data" => $users
        ];
        return response()->json($res);
    }

    //   Update Traffic
    public function addTraffic(Request $request, $response, $args)
    {
        $id = $request->route('id');
        $u = $request->get('u');
        $d = $request->get('d');
        $nodeId = $request->get('node_id');
        $node = SsNode::query()->find($nodeId)->get();
        $rate = $node->traffic_rate;
        $user = User::query()->find($id)->get();

        $user->t = time();
        $user->u = $user->u + ($u * $rate);
        $user->d = $user->d + ($d * $rate);
        if (!$user->save()) {
            $res = [
                "ret" => 0,
                "msg" => "update failed",
            ];
            return response()->json($res);
        }
        // log
        $totalTraffic = self::flowAutoShow(($u + $d) * $rate);
        $traffic = new UserTrafficLog();
        $traffic->user_id = $id;
        $traffic->u = $u;
        $traffic->d = $d;
        $traffic->node_id = $nodeId;
        $traffic->rate = $rate;
        $traffic->traffic = $totalTraffic;
        $traffic->log_time = time();
        $traffic->save();

        $res = [
            "ret" => 1,
            "msg" => "ok",
        ];
        return response()->json($res);
    }

    /**
     * 根据流量值自动转换单位输出
     * @param int $value
     * @return string
     */
    public static function flowAutoShow($value = 0)
    {
        $kb = 1024;
        $mb = 1048576;
        $gb = 1073741824;
        if (abs($value) > $gb) {
            return round($value / $gb, 2) . "GB";
        } else if (abs($value) > $mb) {
            return round($value / $mb, 2) . "MB";
        } else if (abs($value) > $kb) {
            return round($value / $kb, 2) . "KB";
        } else {
            return round($value, 2);
        }
    }
}