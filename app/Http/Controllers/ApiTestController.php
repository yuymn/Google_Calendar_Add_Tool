<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class ApiTestController extends Controller
{
    public function test()
    {
        $client = $this->getClient();
        $service = new Google_Service_Calendar($client);

        $calendarId = env('GOOGLE_CALENDAR_ID');

        $event = new Google_Service_Calendar_Event(array(
            //タイトル
            'summary' => 'テスト',
            'description' => '説明',
            'colorId' => 1, 
            'start' => array(
                // 開始日時
                'date' => '2024-08-28',
                //dateTime' => '2024-08-26T11:00:00+09:00',
                'timeZone' => 'Asia/Tokyo',
            ),
            'end' => array(
                // 終了日時
                'date' => '2024-08-30',
                //'dateTime' => '2024-08-26T12:00:00+09:00',
                'timeZone' => 'Asia/Tokyo',
            ),
        ));

        $event = $service->events->insert($calendarId, $event);
        echo "イベントを追加しました";
    }

    private function getClient()
    {
        $client = new Google_Client();

        //アプリケーション名
        $client->setApplicationName('GoogleCalendarAPIのテスト');
        //権限の指定
        $client->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);
        //JSONファイルの指定
        $client->setAuthConfig(storage_path('app/api-key/astute-heaven-433913-u2-14f17d7dc95a.json'));

        return $client;
    }
}
