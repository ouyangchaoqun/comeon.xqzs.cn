<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @var Response
     */
    var $response;
    private $API_URL;
    private $COOKIE_OPEN_ID = "xqzs_openId";

    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
        $this->API_URL = env("API_URL_HOST") . "/" . env("API_VERSION");
    }


    public function guest(Request $request)
    {
        if (env("APP_ENV") == "production") {
            return view('index_production')->with("guest", true);
        }
        if (env("APP_ENV") == "testing") {
            return view('index_testing')->with("guest", true);
        }
        return view('index')->with("guest", true);
    }
    public function errorLog(Request $request){
        $log = storage_path('logs').'/web_js_log.txt';
        $url = $request->input("url");
        if($url==''){
            return ;
        }
        $msg = $request->input("msg");
        $headArray = getallheaders();
        $content =  date("Y_m_d h:i:s") . "【msg:$msg ，User-Agent:". $headArray['User-Agent']  ."】\r\n\r\n";
        file_put_contents($log, $content, FILE_APPEND);

    }
    public function index(Request $request)
    {

         $userId = $this->getUserId($request);
        if ($userId == 0&&false) {
            $fullurl = $request->fullUrl();
             return "<script>window.location.href = '/wx/index?reurl='+encodeURIComponent(window.location.href);</script>";
 //            return redirect("/wx/index?reurl=".urlencode($fullurl));
        }else{
            $this->apiService->login($userId);
        }

        if (env("APP_ENV") == "production") {
            return view('index_production');
        }
        if (env("APP_ENV") == "testing") {
            return view('index_testing');
        }
        return view('index');
    }

    /**好友分享页面
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function befriend(Request $request)
    {

        $userId = $this->getUserId($request);
        if ($userId == 0) {
            $fullurl = $request->fullUrl();
            return redirect("/wx/index?reurl=" . urlencode($fullurl));
        }
        $friendid = $request->input('userid');
        if ($userId == $friendid) {
            return redirect("/#/myCenter/myIndex");
        }
        $apiurl = "/user/be/friend/width/ids/{$friendid}/_userId_";
        $this->apiService->execFull($request, $userId, $apiurl, 'POST');
        return redirect("/#/friendCenter/{$friendid}");


    }


    protected function getUserId(Request $request)
    {
        $openId = $request->cookie($this->COOKIE_OPEN_ID);
        if ($openId == "") {
            return false;
        } else {
            if (isset($_SESSION['userId'])) {
                $userId = $_SESSION['userId'];
            } else {
                $header = $this->apiService->getTokenHeader();
                $user = $this->apiService->geturl($this->API_URL . "/user/find/by/open/Id/" . $openId, $header);
                $user = json_decode($user, true);
                $userId = null;
                if (is_array($user)) {
                    $userId = $user["data"]['id'];
                    $_SESSION['userId'] = $userId;
                }
            }
            return $userId;
        }
    }

    protected function setUserInfo($user)
    {
        $openId = $user['openId'] . '';
        setcookie($this->COOKIE_OPEN_ID, $openId, null, '/');
        //$response->withCookie(new Cookie("openId",$user['openId']));
        $userId = $user['id'];
        $_SESSION['userId'] = $userId;
    }
}