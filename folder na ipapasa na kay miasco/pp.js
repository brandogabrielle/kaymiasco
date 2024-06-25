// Variables
let workTittle;
let breakTittle;

let workTime = 25;
let breakTime = 5;
let seconds = "00";

let timerInterval;
let sessionStartTime;
let breakCount = 0;

// Display setup after DOM is loaded
window.onload = () => {
    workTittle = document.getElementById('work');
    breakTittle = document.getElementById('break');

    document.getElementById('minutes').innerHTML = workTime;
    document.getElementById('seconds').innerHTML = seconds;

    if (workTittle && breakTittle) {
        workTittle.classList.add('active');
    }
}

// Start timer function
function start() {
    sessionStartTime = new Date();

    document.getElementById('start').style.display = "none";
    document.getElementById('reset').style.display = "block";

    seconds = 59;
    let workMinutes = workTime - 1;
    let breakMinutes = breakTime - 1;
    breakCount = 0;

    // Countdown function
    let timerFunction = () => {
        document.getElementById('minutes').innerHTML = workMinutes;
        document.getElementById('seconds').innerHTML = seconds;

        seconds--;

        if (seconds === 0) {
            workMinutes--;
            if (workMinutes === -1) {
                if (breakCount % 2 === 0) {
                    workMinutes = breakMinutes;
                    breakCount++;

                    workTittle.classList.remove('active');
                    breakTittle.classList.add('active');
                } else {
                    workMinutes = workTime;
                    breakCount++;

                    breakTittle.classList.remove('active');
                    workTittle.classList.add('active');
                }
            }
            seconds = 59;
        }
    }

    // Start interval
    timerInterval = setInterval(timerFunction, 1000);
}

// Reset timer function
function resetTimer() {
    clearInterval(timerInterval);

    document.getElementById('minutes').innerHTML = workTime;
    document.getElementById('seconds').innerHTML = "00";
    seconds = "00";
    breakCount = 0;

    document.getElementById('start').style.display = "block";
    document.getElementById('reset').style.display = "none";

    if (workTittle && breakTittle) {
        workTittle.classList.add('active');
        breakTittle.classList.remove('active');
    }

    // Prepare session data to send to server
    let sessionData = {
        user_id: userId, // Ensure userId is defined and passed correctly
        session_start: sessionStartTime.toISOString(), // Convert date to ISO string
        session_end: new Date().toISOString(), // Current time as session end
        breaks_taken: breakCount
    };

    // Send session data to server using AJAX
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "save_pomodoro.php", true);
    xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log("Server Response:", xhr.responseText);
        } else {
            console.error("Error:", xhr.status, xhr.statusText);
        }
    };
    xhr.send(JSON.stringify(sessionData));
}

