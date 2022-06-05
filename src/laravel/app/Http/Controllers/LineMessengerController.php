<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use App\Models\User;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class LineMessengerController extends Controller
{
    public function webhook(Request $request)
    {

        // LINEから送られた内容を$inputsに代入
        $inputs = $request->all();
        Log::debug($inputs);

        // そこからtypeをとりだし、$message_typeに代入
        $message_type = $inputs['events'][0]['type'];
        Log::debug(dd($request));

        // メッセージが送られた場合、$message_typeは'message'となる。その場合処理実行。
        if ($message_type == 'message') {

            // replyTokenを取得
            $reply_token = $inputs['events'][0]['replyToken'];

            // LINEBOTSDKの設定
            $http_client = new CurlHTTPClient(config('services.line.channel_token'));
            $bot = new LINEBot($http_client, ['channelSecret' => config('services.line.messenger_secret')]);

            // 送信するメッセージの設定
            $reply_message = 'メッセージのテストです。';

            // ユーザーにメッセージを返す
            $reply = $bot->replyText($reply_token, $reply_message);

            return 'ok';
        }
    }

    public function push_frend_all()
    {
        $api_url = 'https://api.line.me/v2/bot/message/broadcast';
        $data = [
            "messages" =>
            array(
                array(
                    "type" => "text",
                    "text" => "sample data"
                )
            )
        ];
        $data = json_encode($data);

        $api_key = config('services.line.channel_token');
        $headers = ["Content-Type: application/json", "Authorization: Bearer " . $api_key];

        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl_handle, CURLOPT_URL, $api_url);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);

        $json_response = curl_exec($curl_handle);

        return $json_response;
        
    }
}
