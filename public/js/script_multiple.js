//読み込み時処理
$(function(){
    controlExample();
    $( '#yearCheck' ).change( function() {
        controlExample();
    });

    $( '#minuteCheck' ).change( function() {
        controlExample();
    });

    $('#listResetBtn').click(function(){
        $('#resultArea').empty();
    });
    
    $('#inputResetBtn').click(function(){
        $('#schedule').val('');
    });

    $('#addNotificationArea').hide();
    $('#addNotificationButton').click(function(){
        $('#addNotificationArea').toggle();
    });

    $('#calendarInputArea').hide();
    $('#showCalendar').click(function(){
        $('#calendarInputArea').toggle();
    });

    $('#nameOptionArea').hide();
    $('#showNameOption').click(function(){
        $('#nameOptionArea').toggle();
    });

    $('#color').addClass("optionBlack");
    $('#color').change( function() {
        controlOptionBackground();
    });

    $('#datepicker').datepicker({
        dateFormat: "mmdd",
        showOn: "button", // inputは非表示にしているのでボタンを表示させる
        buttonText: "カレンダー表示",
        onSelect: function (dateText, inst) {
            let time = $('#inputTime').val();
            let inputText = $('#schedule').val();
            $('#schedule').val(inputText + dateText + time + "\n");
            inst.inline = true;
        },
        onClose: function (dateText, inst) {
            inst.inline = false;
        }
    });

    $('#execBtn').click(function(){
        let eventName = escapeHTML($('#eventName').val());
        if (eventName === ''){
            alert('イベント名を入力してください！');
            return false;
        }

        const color = $('#color').val();
    
        let inputText = escapeHTML($('#schedule').val());
        if (inputText === ''){
            alert('日時を入力してください！');
            return false;
        }
            
    });

    $('#replaceBtn').click(function(){
        let replaceChar = $('#replaceChar').val();
        let schedule = $('#schedule').val();
        $('#schedule').val(schedule.replaceAll(replaceChar,'' ));
    });

});

function judgeYear(inputMonth){
    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth() + 1;
    let setYear = '';
    if (month<= inputMonth){
        setYear = year;
    }else{
        setYear = year + 1;
    }
    return setYear;
}

function controlExample(){
    let minuteCheck = $('#minuteCheck').prop("checked");
    let yearCheck = $('#yearCheck').prop("checked");
    if (yearCheck && !minuteCheck){
        $('#inputExample').html("(例：120118002200説明 ・改行で複数入力)");
    }else if(yearCheck){
        $('#inputExample').html("(例：12011822説明 ・改行で複数入力)");
    }else if(!minuteCheck){
        $('#inputExample').html("(例：2023120118002200説明 ・改行で複数入力)");
    }else{
        $('#inputExample').html("(例：202312011822説明 ・改行で複数入力)");
    }
}

function controlOptionBackground(){
    let selectedClass = $('[name=color] option:selected').attr("class");
    $('#color').attr('class', selectedClass);
}

function notifyJudge(str, num){
    if (str === ''){
        return ' ';
    }else{
        return ' notify_' + num + ':' + str;
    }
}

function escapeHTML(string){
    return string.replace(/&/g, '&lt;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, "&#x27;");
}



