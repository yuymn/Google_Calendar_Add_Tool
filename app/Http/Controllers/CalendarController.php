<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;


class CalendarController extends Controller
{
    private $client;

    // public function __construct()
    // {
    //     // $this->client = new Google_Client();
    //     // $this->client->setAuthConfig('path/to/your-credentials.json');
    //     // $this->client->addScope(Google_Service_Calendar::CALENDAR);
    // }

    public function index(){
        return view('add_event');
    }

    public function indexMulti(){
        return view('add_events');
    }

    public function addEvent(Request $request)
    {
        $yearCheck = $request->get('yearCheck') === '1' ? true:false;
        $minuteCheck = $request->get('minuteCheck') === '1' ? true:false;
        $allDayCheck = $request->get('allDayCheck') === '1' ? true:false;
        $color = $request->get('color');
        $inputText = $request->get('oneEventDateInput');

        $client = $this->getClient();
        $service = new Google_Service_Calendar($client);
        $calendarId = env('GOOGLE_CALENDAR_ID');

        $scheduleText = str_replace(array("\r\n", "\r", "\n"), "\n", $inputText);
        $scheduleArr = explode("\n", $scheduleText);

        foreach($scheduleArr as $input){
            $result = $this->assembleRequest($input,$yearCheck,$minuteCheck,$allDayCheck);

            $result['yearCheck'] = $yearCheck;
            $result['minuteCheck'] = $minuteCheck;
            $result['allDayCheck'] = $allDayCheck;
            $result['color'] = $color;

            if($allDayCheck){
                $event = new Google_Service_Calendar_Event(array(
                    //タイトル
                    'summary' => $result['event'],
                    //'summary' => 'test',
                    //'description' => $result['description'],
                    //'description' => 'setsumei',
                    //'colorId' => $result['color'], 
                    'colorId' => $result['color'],  
                    'start' => array(
                        // 開始日時
                        'date' => $result['startDate'], 
                        'timeZone' => 'Asia/Tokyo',
                    ),
                    'end' => array(
                        // 終了日時
                        'date' => $result['endDate'], 
                        'timeZone' => 'Asia/Tokyo',
                    ),
                    'reminders' => array(
                        'useDefault'=> true,
                        'overrides'=> array(
                            'method'=> 'popup',
                            'minutes'=> '1440',
                        )
                    ),
                ));
            } else {
                $event = new Google_Service_Calendar_Event(array(
                    //タイトル
                    'summary' => $result['event'],
                    //'summary' => 'test',
                    //'description' => $result['description'],
                    //'description' => 'setsumei',
                    //'colorId' => $result['color'], 
                    'colorId' => $result['color'], 
                    'start' => array(
                        // 開始日時
                        //'date' => '2024-08-28',
                        'dateTime' => $result['startDateTime'], 
                        //'dateTime' => '2024-08-26T11:00:00+09:00',
                        'timeZone' => 'Asia/Tokyo',
                    ),
                    'end' => array(
                        // 終了日時
                        //'date' => '2024-08-30',
                        'dateTime' => $result['endDateTime'], 
                        //'dateTime' => '2024-08-26T12:00:00+09:00',
                        'timeZone' => 'Asia/Tokyo',
                    ),
                    'reminders' => array(
                        'useDefault'=> true,
                        'overrides'=> array(
                            'method'=> 'popup',
                            'minutes'=> '1440',
                        )
                    ),
                ));
            }
            

            $event = $service->events->insert($calendarId, $event);
        }
        
        return redirect('/')->with('success','Event created:'.$event->htmlLink);
    }

    public function addEvents(Request $request) {
        $yearCheck = $request->get('yearCheck') === '1' ? true:false;
        $minuteCheck = $request->get('minuteCheck') === '1' ? true:false;
        $allDayCheck = $request->get('allDayCheck') === '1' ? true:false;

        $scheduleText = $request->get('schedule');

        $scheduleText = str_replace(array("\r\n", "\r", "\n"), "\n", $scheduleText);
        $scheduleArr = explode("\n", $scheduleText);

        $eventName = $request->get('eventName');
        
        $color = $request->get('color');
        
        $client = $this->getClient();
        $service = new Google_Service_Calendar($client);
        $calendarId = env('GOOGLE_CALENDAR_ID');

        foreach($scheduleArr as $input){
            $result = $this->assembleRequest($input,$yearCheck,$minuteCheck,$allDayCheck);
            $result['eventName'] = $eventName;
            $result['color'] = $color;
            $event = new Google_Service_Calendar_Event(array(
                //タイトル
                'summary' => $result['eventName'],
                //'summary' => 'test',
                'location' => $result['event'],
                //'description' => 'setsumei',
                //'colorId' => $result['color'], 
                'colorId' => $result['color'], 
                'start' => array(
                    // 開始日時
                    //'date' => '2024-08-28',
                    'dateTime' => $result['startDateTime'], 
                    //'dateTime' => '2024-08-26T11:00:00+09:00',
                    'timeZone' => 'Asia/Tokyo',
                ),
                'end' => array(
                    // 終了日時
                    //'date' => '2024-08-30',
                    'dateTime' => $result['endDateTime'], 
                    //'dateTime' => '2024-08-26T12:00:00+09:00',
                    'timeZone' => 'Asia/Tokyo',
                ),
                'reminders' => array(
                    'useDefault'=> true,
                    'overrides'=> array(
                        'method'=> 'popup',
                        'minutes'=> '1440',
                    )
                ),
            ));
            $event = $service->events->insert($calendarId, $event);
        }

        return redirect('/multi')->with('success','Event created');
        
    }

    private function getClient()
    {
        $client = new Google_Client();

        //アプリケーション名
        $client->setApplicationName('GoogleCalendarAPIのテスト');
        //権限の指定
        $client->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);
        //JSONファイルの指定
        $client->setAuthConfig(storage_path('app/api-key/'.env('GOOGLE_API_JSON_FILE')));

        return $client;
    }

    public function assembleRequest($input,$yearCheck,$minuteCheck,$allDayCheck){
        //データ整理
        $result = [];
        
        $year = '';
        $month = '';
        $day = '';
        $startHour = '';
        $endHour = '';
        $startMinute = '';
        $endMinute = '';

        if($yearCheck){
            $month = mb_substr($input,0,2);
            $year = $this->judgeYear($month);
            $day = mb_substr($input,2,2);
            if($allDayCheck){
                $event = mb_substr($input,4);
            }elseif($minuteCheck){
                $startHour = mb_substr($input,4,2);
                $startMinute = '00';
                $endHour = mb_substr($input,6,2);
                $endMinute = '00';
                $event = mb_substr($input,8);
            }else{
                $startHour = mb_substr($input,4,2);
                $startMinute = mb_substr($input,6,2);
                $endHour = mb_substr($input,8,2);                
                $endMinute = mb_substr($input,10,2);
                $event = mb_substr($input,12);
            }

        }else{
            $year = mb_substr($input,0,4);
            $month = mb_substr($input,4,2);
            $day = mb_substr($input,6,2);
            if($allDayCheck){
                $event = mb_substr($input,8);
            }elseif($minuteCheck){
                $startHour = mb_substr($input,8,2);
                $startMinute = '00';
                $endHour = mb_substr($input,10,2);
                $endMinute = '00';
                $event = mb_substr($input,12);
            }else{
                $startHour = mb_substr($input,8,2);
                $startMinute = mb_substr($input,10,2);
                $endHour = mb_substr($input,12,2);
                $endMinute = mb_substr($input,14,2);
                $event = mb_substr($input,16);
            }
        }
        $startDate = $year.'-'.$month.'-'.$day;
        $startDateTime = $year.'-'.$month.'-'.$day.'T'.$startHour.':'.$startMinute.':00+09:00';
        $endDate = $year.'-'.$month.'-'.$day;
        $endDateTime = $year.'-'.$month.'-'.$day.'T'.$endHour.':'.$endMinute.':00+09:00';

        $result['startDate'] = $startDate;
        $result['startDateTime'] = $startDateTime;
        $result['endDate'] = $endDate;
        $result['endDateTime'] = $endDateTime;
        $result['event'] = $event;

        return $result;
    }
    public function judgeYear($inputMonth){
        $now = Carbon::now()->timezone('Asia/Tokyo'); 
        $nowYear = $now->year;
        $nowMonth = $now->month;
        $nextYear = $now->addYear()->year;
        if ($nowMonth <= $inputMonth){
            $setYear = $nowYear;
        }else{
            $setYear = $nextYear;
        }
        return $setYear;
    }

}
