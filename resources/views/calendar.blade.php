@include ('layouts.navbar')
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Pawtopia Calendar</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #fff;
        margin: 0;
        padding: 0;
        color: #5a3b2e;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
.section-title {
    text-align: center;
    margin: 40px auto 20px;
    padding-bottom: 5px;
    border-bottom: 3px solid #F6A892;
    display: block;
    width: fit-content;
    font-size: 2.5rem;
    color: #5a3b2e;
}


@media (max-width: 768px) {
    h2 {
        margin-top: 25px; /* Lebih dekat di layar kecil */
    }
}

/* Wrapper untuk Calendar + Tutorial */
.calendar-wrapper {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    gap: 40px;
    margin: 0 auto 60px;
    flex-wrap: wrap;
    max-width: 1200px;
    width: 90%;
    padding: 0 20px;
}

/* Calendar Container */
.calendar-container {
    margin: 0;
}

.calendar-box {
    background: #fcd8b6;
    padding: 30px;
    border-radius: 20px;
    text-align: center;
    width: 500px; /* diperbesar dari 320px */
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: bold;
    font-size: 22px; /* lebih besar */
    margin-bottom: 20px;
}

.calendar-header button {
    background: #f8a07d;
    border: none;
    color: white;
    padding: 8px 12px; /* lebih besar */
    border-radius: 50%;
    cursor: pointer;
    font-size: 16px; /* lebih besar */
}

table {
    width: 100%;
    border-collapse: separate; 
    border-spacing: 4px; 
    table-layout: fixed; /* biar semua kolom sama besar */
}

th {
    color: white;
    background-color: #f47b60;
    padding: 12px;
    border-radius: 8px;
    font-size: 16px;
    width: 14.28%; /* total 7 kolom */
    text-align: center;
}

td {
    width: 14.28%; /* total 7 kolom */
    height: 65px;
    text-align: center;
    cursor: pointer;
    border-radius: 8px;
    position: relative;
    font-size: 16px;
    background: white;
    border: 2px solid transparent;
    transition: all 0.2s ease;
}

td:not(.full-book):hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(244, 123, 96, 0.2);
}

td.selected {
    background-color: #f47b60 !important;
    color: white;
    font-weight: bold;
    border: 2px solid #d23c5a !important;
}

td.selected::after {
    display: none;
}

/* Tooltip for slot availability */
td[data-slots]::before {
    content: attr(data-slots);
    position: absolute;
    top: -35px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.85);
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s;
    z-index: 10;
}

td[data-slots]:hover::before {
    opacity: 1;
}

/* Availability indicators */
td.availability-high {
    background: linear-gradient(135deg, #d4f4dd 0%, #e8f9ed 100%);
    border: 2px solid #4caf50;
}

td.availability-high::after {
    content: "✓";
    position: absolute;
    bottom: 3px;
    right: 6px;
    font-size: 16px;
    color: #4caf50;
    font-weight: bold;
}

td.availability-medium {
    background: linear-gradient(135deg, #fff8e1 0%, #fffbf0 100%);
    border: 2px solid #ffa726;
}

td.availability-medium::after {
    content: "◐";
    position: absolute;
    bottom: 3px;
    right: 6px;
    font-size: 16px;
    color: #ffa726;
}

td.availability-low {
    background: linear-gradient(135deg, #ffe5e5 0%, #fff0f0 100%);
    border: 2px solid #ff6b6b;
}

td.availability-low::after {
    content: "⚠";
    position: absolute;
    bottom: 3px;
    right: 6px;
    font-size: 14px;
    color: #ff6b6b;
}

td.full-book {
    cursor: not-allowed;
    background: linear-gradient(135deg, #e0e0e0 0%, #f0f0f0 100%);
    border: 2px solid #999;
    color: #999;
}

td.full-book::after {
    content: "✕";
    position: absolute;
    bottom: 3px;
    right: 6px;
    font-size: 18px;
    color: #999;
    font-weight: bold;
}

        .booking-tutorial {
            max-width: 400px;
            margin: 0;
            flex: 1;
            background: linear-gradient(135deg, #ffffff 0%, #fefefe 100%);
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.06);
            border: 1px solid rgba(232, 180, 160, 0.15);
            position: relative;
            overflow: hidden;
        }

        .booking-tutorial::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #f47b60, #f8a07d, #e8b4a0);
        }

        .booking-tutorial h3 {
            font-size: 20px;
            margin-bottom: 20px;
            text-align: center;
            color: #5a3b2e;
            font-weight: 600;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .booking-tutorial h3::before {
            
            font-size: 22px;
        }

        .booking-tutorial-content {
            position: relative;
        }

        .booking-tutorial ol {
            list-style: none;
            counter-reset: step-counter;
            padding: 0;
            margin: 0;
        }

        .booking-tutorial li {
            counter-increment: step-counter;
            margin-bottom: 16px;
            background: #f8fafb;
            padding: 18px 20px 18px 55px;
            border-radius: 12px;
            border-left: 4px solid #e8b4a0;
            position: relative;
            transition: all 0.3s ease;
            color: #9C6F4B;
            font-size: 14px;
            line-height: 1.5;
        }

        .booking-tutorial li:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(232, 180, 160, 0.15);
        }

        .booking-tutorial li::before {
            content: counter(step-counter);
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            width: 26px;
            height: 26px;
            background: linear-gradient(135deg, #e8b4a0, #f8a07d);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(232, 180, 160, 0.3);
        }

        .booking-tutorial li:last-child {
            margin-bottom: 0;
        }

        .booking-tutorial .tutorial-footer {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid rgba(232, 180, 160, 0.2);
            text-align: center;
        }



    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.4);
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        max-width: 300px;
    }
    .modal-content h2 {
        font-size: 16px;
        margin-bottom: 20px;
    }
    .modal-content a {
        background: #d23c5a;
        border: none;
        color: #F6A892;
        padding: 8px 20px;
        border-radius: 20px;
        cursor: pointer;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .features-container {
            grid-template-columns: 1fr;
        }
    }
</style>
</head>
<body>
   

<h2 class="section-title">Choose Stay Dates</h2>

<!-- Calendar & Tutorial Side by Side -->
<div class="calendar-wrapper">
    <!-- Calendar -->
    <div class="calendar-container">
        <div class="calendar-box">
            <div class="calendar-header">
                <span>July 2025</span>
                <div>
                    <button id="prevMonthBtn" aria-label="Previous month">&lt;</button>
                    <button id="nextMonthBtn" aria-label="Next month">&gt;</button>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th>
                        <th>Thu</th><th>Fri</th><th>Sat</th>
                    </tr>
                </thead>
                <tbody id="calendar-body"></tbody>
            </table>
        </div>
    </div>

    <!-- Booking Tutorial -->
    <div class="booking-tutorial">
        <h3>How to Book Your Paw-some Day 🐾</h3>
        <ol>
            <li>Select a date in the calendar.</li>
            <li>Check the availability indicator on each date.</li>
            <li>Click and drag if you want to select more than one day.</li>
            <li>Release the mouse to open the booking form.</li>
            <li>Fill in the details and click <b>Submit Booking</b>.</li>
        </ol>
        
        <!-- Availability Legend -->
        <div style="margin-top: 24px; padding: 16px; background: #f8fafb; border-radius: 12px; border: 1px solid rgba(232, 180, 160, 0.2);">
            <h4 style="margin: 0 0 12px 0; font-size: 14px; color: #5a3b2e; font-weight: 600;">📊 Availability Guide</h4>
            <div style="display: flex; flex-direction: column; gap: 8px; font-size: 13px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="display: inline-block; width: 24px; height: 24px; background: linear-gradient(135deg, #d4f4dd, #e8f9ed); border: 2px solid #4caf50; border-radius: 4px; position: relative;">
                        <span style="position: absolute; bottom: 0; right: 2px; font-size: 12px; color: #4caf50;">✓</span>
                    </span>
                    <span style="color: #5a3b2e;"><b>Many slots</b> (0-49% booked)</span>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="display: inline-block; width: 24px; height: 24px; background: linear-gradient(135deg, #fff8e1, #fffbf0); border: 2px solid #ffa726; border-radius: 4px; position: relative;">
                        <span style="position: absolute; bottom: 0; right: 2px; font-size: 12px; color: #ffa726;">◐</span>
                    </span>
                    <span style="color: #5a3b2e;"><b>Some slots</b> (50-74% booked)</span>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="display: inline-block; width: 24px; height: 24px; background: linear-gradient(135deg, #ffe5e5, #fff0f0); border: 2px solid #ff6b6b; border-radius: 4px; position: relative;">
                        <span style="position: absolute; bottom: 0; right: 2px; font-size: 11px; color: #ff6b6b;">⚠</span>
                    </span>
                    <span style="color: #5a3b2e;"><b>Few slots</b> (75-99% booked)</span>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="display: inline-block; width: 24px; height: 24px; background: linear-gradient(135deg, #e0e0e0, #f0f0f0); border: 2px solid #999; border-radius: 4px; position: relative;">
                        <span style="position: absolute; bottom: 0; right: 2px; font-size: 14px; color: #999;">✕</span>
                    </span>
                    <span style="color: #999;"><b>Fully booked</b> (100%)</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="booking-modal">
    <div class="modal-content">
        <h2>Book Your Appointment Now!</h2>
        <a href="#" id="bookingLink" style=" border: none; padding: 8px 20px; border-radius: 20px; cursor: pointer; text-decoration: none; display: inline-block;">Book</a>
    </div>
</div>

<script>
    const calendarBody = document.getElementById('calendar-body');
const monthYearLabel = document.querySelector('.calendar-header span');

let currentMonth = new Date().getMonth(); // Current month
let currentYear = new Date().getFullYear(); // Current year
let selecting = false;
let selectedDays = [];
let fullBookedDates = [];
let availabilityLevels = {};
let bookingCounts = {};
let maxCapacity = 20;

// Fetch calendar data from API
async function fetchCalendarData(month, year) {
    try {
        const response = await fetch(`/calendar/data?month=${month + 1}&year=${year}`);
        const data = await response.json();
        
        if (data.success) {
            fullBookedDates = data.full_dates;
            availabilityLevels = data.availability_levels || {};
            bookingCounts = data.booking_counts || {};
            maxCapacity = data.max_capacity || 20;
            return data;
        }
    } catch (error) {
        console.error('Failed to fetch calendar data:', error);
    }
    return { full_dates: [] };
}

async function generateCalendar(month, year) {
    // Fetch data from backend
    await fetchCalendarData(month, year);
    
    calendarBody.innerHTML = "";

    const firstDay = new Date(year, month, 1).getDay(); // 0 = Minggu
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    monthYearLabel.textContent = `${new Date(year, month).toLocaleString('default', { month: 'long' })} ${year}`;

    let day = 1;
    for (let i = 0; i < 6; i++) {
        let row = document.createElement('tr');

        for (let j = 0; j < 7; j++) {
            let cell = document.createElement('td');

            if (i === 0 && j < firstDay) {
                cell.innerHTML = '';
            } else if (day <= daysInMonth) {
                cell.innerHTML = day;

                let dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                
                // Calculate available slots
                const booked = bookingCounts[dateString] || 0;
                const available = maxCapacity - booked;
                
                // Add tooltip with slot info
                if (booked > 0) {
                    cell.setAttribute('data-slots', `${available} slots available (${booked}/${maxCapacity} booked)`);
                } else {
                    cell.setAttribute('data-slots', `${available} slots available`);
                }
                
                // Add availability class
                if (fullBookedDates.includes(dateString)) {
                    cell.classList.add('full-book');
                    cell.setAttribute('data-slots', 'Fully booked');
                } else {
                    // Add availability level indicator
                    const availLevel = availabilityLevels[dateString];
                    if (availLevel) {
                        cell.classList.add(`availability-${availLevel}`);
                    }
                    
                    cell.addEventListener('mousedown', () => {
                        selecting = true;
                        selectedDays = [];
                        selectDay(cell);
                    });
                    cell.addEventListener('mouseover', () => {
                        if (selecting) selectDay(cell);
                    });
                    cell.addEventListener('mouseup', () => {
                        selecting = false;
                        if (selectedDays.length) {
                            // Sort selected days
                            selectedDays.sort((a, b) => a - b);
                            
                            // Format dates for booking
                            const startDay = selectedDays[0];
                            const startDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(startDay).padStart(2, '0')}`;
                            
                            // Calculate duration (number of days selected)
                            const duration = selectedDays.length;
                            
                            // Update booking link with date and duration parameters
                            const bookingLink = document.getElementById('bookingLink');
                            bookingLink.href = `{{ route('booking') }}?date=${startDate}&duration=${duration}`;
                            
                            // Show modal
                            document.getElementById('booking-modal').style.display = 'flex';
                        }
                    });
                }

                day++;
            }

            row.appendChild(cell);
        }

        calendarBody.appendChild(row);
    }
}

function selectDay(cell) {
    let dayNum = parseInt(cell.innerText);
    if (!cell.classList.contains('full-book') && !isNaN(dayNum)) {
        cell.classList.add('selected');
        if (!selectedDays.includes(dayNum)) {
            selectedDays.push(dayNum);
        }
    }
}

function closeModal() {
    document.getElementById('booking-modal').style.display = 'none';
    document.querySelectorAll('td.selected').forEach(td => td.classList.remove('selected'));
    selectedDays = [];
}

// Tombol Prev / Next
document.getElementById('prevMonthBtn').addEventListener('click', () => {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    generateCalendar(currentMonth, currentYear);
});

document.getElementById('nextMonthBtn').addEventListener('click', () => {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    generateCalendar(currentMonth, currentYear);
});

document.addEventListener('mouseup', () => selecting = false);

// Inisialisasi awal
generateCalendar(currentMonth, currentYear);
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookNowBtn = document.getElementById('bookNowBtn');
    
    bookNowBtn.addEventListener('click', function() {
        // Show loading state
        bookNowBtn.disabled = true;
        bookNowBtn.textContent = 'Checking...';
        
        // Check authentication status
        fetch('{{ route("check.auth") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.authenticated) {
                // If logged in, go to booking page
                window.location.href = '{{ route("booking") }}';
            } else {
                // If not logged in, go to login page
                window.location.href = '{{ route("register") }}';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            bookNowBtn.disabled = false;
            bookNowBtn.textContent = 'Book';
        });
    });
});
</script>

@include ('layouts.footer')
</body>
</html>
