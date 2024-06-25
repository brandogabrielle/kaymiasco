let workTittle = document.getElementById('work');
let breakTittle = document.getElementById('break');

let workTime = 25;
let breakTime = 5;
let seconds = "00";

let timerInterval;
let sessionStart;
let breakCount = 0;

window.onload = () => {
    document.getElementById('minutes').innerHTML = workTime;
    document.getElementById('seconds').innerHTML = seconds;

    workTittle.classList.add('active');
}

function start() {
    document.getElementById('start').style.display = "none";
    document.getElementById('reset').style.display = "block";

    sessionStart = new Date(); // Capture session start time
    seconds = 59;

    let workMinutes = workTime - 1;
    let breakMinutes = breakTime - 1;

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

    timerInterval = setInterval(timerFunction, 1000); // 1000 = 1s
}

function resetTimer() {
    clearInterval(timerInterval);

    let sessionEnd = new Date(); // Capture session end time
    saveSession(sessionStart, sessionEnd, breakCount);

    document.getElementById('minutes').innerHTML = workTime;
    document.getElementById('seconds').innerHTML = "00";
    seconds = "00";
    breakCount = 0;

    document.getElementById('start').style.display = "block";
    document.getElementById('reset').style.display = "none";

    workTittle.classList.add('active');
    breakTittle.classList.remove('active');
}

function saveSession(start, end, breaks) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "save_session.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    let userId = 1; // Replace with the actual user ID from your session management
    let username = "username"; // Replace with the actual username from your session management

    let params = `user_id=${userId}&username=${username}&session_start=${start.toISOString()}&session_end=${end.toISOString()}&breaks_taken=${breaks}`;
    xhr.send(params);
}
