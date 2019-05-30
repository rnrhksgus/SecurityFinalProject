<?php
    session_start();
    session_destroy();
?>
<meta http-equiv='refresh' content='0;url=index.php'>


var plusEle = document.querySelector('#plus');
var minusEle = document.querySelector('#minus');
var isPressed = false;

plusEle.addEventListener('mouseup', function(event) {
    isPressed = false;
    playAlert = setInterval(function() {
        count.value = count.value - 5;
        if (count.value < 0)
            count.value = 0;
        sendScaleMsg();
    }, 1000);
});
plusEle.addEventListener('mousedown', function(event) {
    clearInterval(playAlert);
    isPressed = true;
    doInterval('10');
});

minusEle.addEventListener('mouseup', function(event) {
    isPressed = false;
});

minusEle.addEventListener('mousedown', function(event) {
    isPressed = true;
    doInterval('-10');
});
function doInterval(action) {
    if (isPressed) {
        var countEle = document.querySelector('#count');
        count.value = parseInt(count.value) + parseInt(action);

        setTimeout(function() {
            doInterval(action);
            if(count.value>100){
                count.value = 100
            }
            if(count.value<0){
                count.value = 0
            }
            sendScaleMsg();
        }, 200);
    }
};
