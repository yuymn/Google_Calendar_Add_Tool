//読み込み時処理
$(function(){
    controlExample();
    $( '#yearCheck' ).change( function() {
        controlExample();
    });

    $( '#minuteCheck' ).change( function() {
        controlExample();
    });

    $( '#allDayCheck' ).change( function() {
        controlExample();
    });

    $('#inputResetBtn').click(function(){
        $('#oneEventDateInput').val('');
    });

    $('#addNotificationArea').hide();
    $('#addNotificationButton').click(function(){
        $('#addNotificationArea').toggle();
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
            let inputText = $('#oneEventDateInput').val();
            $('#oneEventDateInput').val(inputText + dateText );
            inst.inline = true;
        },
        onClose: function (dateText, inst) {
            inst.inline = false;
        }
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
    let allDayCheck = $('#allDayCheck').prop("checked");

    if (allDayCheck){
        if(yearCheck){
            $('#inputExample').text('(例：1201飲み会)');
        }else{
            $('#inputExample').text('(例：20241201飲み会)');
        }
    }else if(yearCheck){
        if(minuteCheck){
            $('#inputExample').text('(例：12011822飲み会)');
        }else{
            $('#inputExample').text('(例：120118002200飲み会)');
        }
    }else if(minuteCheck){
        $('#inputExample').text('(例：202412011822飲み会)');
    }else{
        $('#inputExample').text('(例：2024120118002200飲み会)');
    }
}

function controlOptionBackground(){
    let selectedClass = $('[name=color] option:selected').attr("class");
    $('#color').attr('class', selectedClass);
}



