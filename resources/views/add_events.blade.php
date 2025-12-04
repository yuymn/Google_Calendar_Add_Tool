<!DOCTYPE html>
<html>
	<head>
		<title>Google Calendar Add Tool</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
		<script src='js/bootstrap.bundle.js'></script>
		<script src='js/script_multiple.js'></script>
		<link rel='stylesheet' href='css/bootstrap.css'>
		<link rel='stylesheet' href='css/style.css'>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
	</head>
	<body>
		<div class="container">
			<header>
                @if(session('success'))
                    <p>{{session('success')}}</p>
                @endif
			</header>
			<main>
				<div class="my-3">
					<a class="btn btn-outline-primary" href="{{url('/')}}" role="button">単イベント追加</a>
					<a class="btn btn-primary" href="{{url('/multi')}}" role="button">複数イベント追加</a>
				</div>
                <form action="add-event-multi" method="POST">
                    @csrf
                    <div class="multipleEventMode">
                        <h5>【日時入力設定】</h5>
                        <div class="dateinputSettings mb-3 ml-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="yearCheck" name="yearCheck" checked>
                                <label class="form-check-label" for="yearCheck">
                                  年を自動判別する
                                </label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="minuteCheck" name="minuteCheck" checked>
                                <label class="form-check-label" for="minuteCheck">
                                    分を入力しない
                                </label>
                              </div>
                        </div>
                        <h5>【予定入力】</h5>
                    </div>
                    
                    <div id="eventNameArea" class=" mx-3">
                        <div>
                            <span class="input-label">イベント名</span>
                            <div><input type="text" name="eventName" id="eventName"></div>
                        </div>
                    </div>
    
                    <div class="form-check ml-3 mt-2">
                        <input class="form-check-input" type="checkbox" value="1" id="showNameOption">
                        <label class="form-check-label" for="showNameOption">
                            <span class="input-label">イベント名オプション</span>
                        </label>
                    </div>
                    <div id="nameOptionArea" class=" ml-4">
                        <div class="form-check ml-4 mt-2">
                            <input class="form-check-input" type="checkbox" value="1" id="orderNo" name="orderNo">
                            <label class="form-check-label" for="orderNo">
                                頭に第○回をつける
                            </label>
                        </div>
                        <div class="form-check ml-4 mt-2">
                            <input class="form-check-input" type="checkbox" value="1" id="lastNo" name="lastNo">
                            <label class="form-check-label" for="lastNo">
                                末尾に数字をつける
                            </label>
                        </div>
                        <!-- <div class="form-check ml-4 mt-2">
                            <input class="form-check-input" type="checkbox" value="1" id="LastDate">
                            <label class="form-check-label" for="LastDate">
                                末尾に日付をつける
                            </label>
                        </div> -->
                    </div>
    
                    <div class="mt-3 mx-3">
                        <div><span class="input-label">色</span></div>
                        <select name="color" id="color" class="">
                            <option class="optionShirakaba" value="0">シラカバ</option>
                            <option class="optionLavendar" value="1">ラベンダー</option>
                            <option class="optionSage" value="2">セージ</option>
                            <option class="optionBudo" value="3">ブドウ</option>
                            <option class="optionFlamingo" value="4">フラミンゴ</option>
                            <option class="optionBanana" value="5">バナナ</option> 
                            <option class="optionMikan" value="6">ミカン</option>
                            <option class="optionPeacock" value="7">ピーコック</option>
                            <option class="optionGraphite" value="8">グラファイト</option>
                            <option class="optionBlueBerry" value="9">ブルーベリー</option>
                            <option class="optionBasil" value="10">バジル</option>
                            <option class="optionTomato" value="11">トマト</option>
                        </select>
                    </div>
                    
                    <div class=" ml-3 mt-3"><span class="input-label">日時＋説明</span></div>
                    <div class="ml-3">	
                        <div id="inputExample" class="text-secondary"></div>
                        <textarea rows="8" cols="30" name="schedule" id="schedule" placeholder=""></textarea>
                        <div class="form-check ml-4 mt-2">
                            <input class="form-check-input" type="checkbox" value="1" id="showCalendar">
                            <label class="form-check-label" for="showCalendar">
                                カレンダーから入力
                            </label>
                        </div>
                        <div id="calendarInputArea" class="ml-5">
                            <div><input id="datepicker" type="hidden"></div>
                            <div>
                                <div><span class="input-label">時刻</span></div>
                                <input type="text" class="" id="inputTime">
                            </div>
                        </div>
                    </div>

                    <div id="" class=" mx-3">
                        <div>
                            <span class="input-label">文字削除</span>
                            <div><input type="text" name="replaceChar" id="replaceChar"></div>
                            <button type="button" id="replaceBtn" class="mt-1 btn btn-outline-secondary" >文字削除</button>
                        </div>
                    </div>

                    <div class="form-check ml-3 mt-3">
                        <input class="form-check-input" type="checkbox" value="1" id="addNotificationButton">
                        <label class="form-check-label" for="addNotificationButton">
                            <span class="input-label">通知を追加する</span>
                        </label>
                    </div>
                        
                    <div class="addNotificationArea ml-4" id="addNotificationArea">
                        <div class="notification1 my-1">通知１：
                            <select name="notificationTime1" id="notificationTime1" class="mr-3">
                                <option value=""></option>
                                <option value="5分前">5分</option>
                                <option value="10分前">10分</option>
                                <option value="15分前">15分</option>
                                <option value="30分前">30分</option>
                                <option value="1時間前">1時間</option>
                                <option value="2時間前">2時間</option>
                                <option value="3時間前">3時間</option>
                                <option value="6時間前">6時間</option>
                                <option value="12時間前">12時間</option>
                                <option value="1日前">1日</option>
                                <option value="2日前">2日</option>
                                <option value="3日前">3日</option>
                                <option value="7日前">7日</option>
                            </select>前
                        </div>
                        <div class="notification2 my-1">通知２：
                            <select name="notificationTime2" id="notificationTime2" class="mr-3">
                                <option value=""></option>
                                <option value="5分前">5分</option>
                                <option value="10分前">10分</option>
                                <option value="15分前">15分</option>
                                <option value="30分前">30分</option>
                                <option value="1時間前">1時間</option>
                                <option value="2時間前">2時間</option>
                                <option value="3時間前">3時間</option>
                                <option value="6時間前">6時間</option>
                                <option value="12時間前">12時間</option>
                                <option value="1日前">1日</option>
                                <option value="2日前">2日</option>
                                <option value="3日前">3日</option>
                                <option value="7日前">7日</option>
                            </select>前
                        </div>
                        <div class="notification3 my-1">通知３：
                            <select name="notificationTime3" id="notificationTime3" class="mr-3">
                                <option value=""></option>
                                <option value="5分前">5分</option>
                                <option value="10分前">10分</option>
                                <option value="15分前">15分</option>
                                <option value="30分前">30分</option>
                                <option value="1時間前">1時間</option>
                                <option value="2時間前">2時間</option>
                                <option value="3時間前">3時間</option>
                                <option value="6時間前">6時間</option>
                                <option value="12時間前">12時間</option>
                                <option value="1日前">1日</option>
                                <option value="2日前">2日</option>
                                <option value="3日前">3日</option>
                                <option value="7日前">7日</option>
                            </select>前
                        </div>
                        <div class="notification4 my-1">通知４：
                            <select name="notificationTime4" id="notificationTime4" class="mr-3">
                                <option value=""></option>
                                <option value="5分前">5分</option>
                                <option value="10分前">10分</option>
                                <option value="15分前">15分</option>
                                <option value="30分前">30分</option>
                                <option value="1時間前">1時間</option>
                                <option value="2時間前">2時間</option>
                                <option value="3時間前">3時間</option>
                                <option value="6時間前">6時間</option>
                                <option value="12時間前">12時間</option>
                                <option value="1日前">1日</option>
                                <option value="2日前">2日</option>
                                <option value="3日前">3日</option>
                                <option value="7日前">7日</option>
                            </select>前
                        </div>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-outline-primary" name="execBtn" id="execBtn" type="submit">登録</button>
                        <button class="btn btn-outline-secondary" name="inputResetBtn" id="inputResetBtn">入力リセット</button>
                        <button class="btn btn-outline-danger" name="listResetBtn" id="listResetBtn">コマンドリストリセット</button>
                    </div>
                    <hr>
                    <div id="resultArea">
                        
                    </div>
                </form>
			</main>
			<footer>
			</footer>
		</div>

	</body>
</html>	
