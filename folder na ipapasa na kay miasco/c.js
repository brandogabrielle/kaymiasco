document.addEventListener('DOMContentLoaded', function() {
    const calendar = document.getElementById('calendar');
    const agendaPopup = document.getElementById('agenda-popup');
    const modal = document.getElementById('modal');
    const closeModal = document.querySelector('.close');
    const agendaForm = document.getElementById('agenda-form');
    const agendaText = document.getElementById('agenda-text');
    const agendaDate = document.getElementById('agenda-date');
    let selectedDay = null;

    const daysTag = document.querySelector(".days"),
    currentDate = document.querySelector(".current-date"),
    prevNextIcon = document.querySelectorAll(".icons span");

    let date = new Date(),
    currYear = date.getFullYear(),
    currMonth = date.getMonth();

    const months = ["January", "February", "March", "April", "May", "June", "July",
                    "August", "September", "October", "November", "December"];

    const renderCalendar = () => {
        let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(),
        lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(),
        lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(),
        lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate();
        let liTag = "";

        for (let i = firstDayofMonth; i > 0; i--) {
            liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
        }

        for (let i = 1; i <= lastDateofMonth; i++) {
            let isToday = i === date.getDate() && currMonth === new Date().getMonth() 
                         && currYear === new Date().getFullYear() ? "active" : "";
            liTag += `<li class="day ${isToday}" data-date="${i}">${i}</li>`;
        }

        for (let i = lastDayofMonth; i < 6; i++) {
            liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`
        }
        currentDate.innerText = `${months[currMonth]} ${currYear}`;
        daysTag.innerHTML = liTag;

        document.querySelectorAll('.day').forEach(day => {
            day.addEventListener('click', openModal);
            day.addEventListener('mouseenter', showAgenda);
            day.addEventListener('mouseleave', hideAgenda);
        });
    };

    renderCalendar();

    prevNextIcon.forEach(icon => {
        icon.addEventListener("click", () => {
            currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;

            if (currMonth < 0 || currMonth > 11) {
                date = new Date(currYear, currMonth, new Date().getDate());
                currYear = date.getFullYear();
                currMonth = date.getMonth();
            } else {
                date = new Date();
            }
            renderCalendar();
        });
    });

    function openModal(event) {
        selectedDay = event.target;
        agendaDate.value = `${currYear}-${String(currMonth + 1).padStart(2, '0')}-${String(selectedDay.dataset.date).padStart(2, '0')}`;
        modal.classList.remove('hidden');
        modal.style.display = "block";
    }

    closeModal.onclick = function() {
        modal.classList.add('hidden');
        modal.style.display = "none";
    };

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.classList.add('hidden');
            modal.style.display = "none";
        }
    };

    agendaForm.addEventListener('submit', function(event) {
        event.preventDefault();
        if (selectedDay) {
            selectedDay.classList.add('agenda');
            selectedDay.dataset.agenda = agendaText.value;
            agendaText.value = '';
            modal.classList.add('hidden');
            modal.style.display = "none";

            // Send the agenda data to the backend
            const formData = new FormData();
            formData.append('agenda_date', agendaDate.value);
            formData.append('agenda_text', selectedDay.dataset.agenda);

            fetch('add_agenda.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text()).then(data => {
                console.log(data);
            }).catch(error => {
                console.error('Error:', error);
            });
        }
    });

    function showAgenda(event) {
        const day = event.target;
        if (day.dataset.agenda) {
            agendaPopup.textContent = day.dataset.agenda;
            agendaPopup.style.display = 'block';
            agendaPopup.style.top = event.clientY + 'px';
            agendaPopup.style.left = event.clientX + 'px';
        }
    }

    function hideAgenda() {
        agendaPopup.style.display = 'none';
    }
});
