// Calendar JavaScript
const calendar = document.getElementById("calendar");
const monthYear = document.getElementById("monthYear");
const prevMonth = document.getElementById("prevMonth");
const nextMonth = document.getElementById("nextMonth");
const selectedDateSpan = document.getElementById("selectedDate");
const eventInput = document.getElementById("eventInput");
const eventColor = document.getElementById("eventColor");
const addEventBtn = document.getElementById("addEvent");

let currentDate = new Date();
let selectedDate = null;
let events = {};
let selectedDates = new Set(); // Store multiple selected dates

// Load events from database
function loadEventsFromDB() {
    fetch("load_events.php")
        .then(response => response.json())
        .then(data => {
            if (data && typeof data === 'object') {
                events = data;
            } else {
                events = {};
            }
            renderCalendar();
        })
        .catch(error => {
            console.error("Error loading events:", error);
            events = {};
            renderCalendar();
        });
}

// Render the calendar grid with days and events
function renderCalendar() {
    calendar.innerHTML = "";

    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDay = firstDay.getDay();
    const daysInMonth = lastDay.getDate();

    monthYear.textContent = currentDate.toLocaleDateString("en-US", {
        month: "long",
        year: "numeric",
    });

    // Empty cells before the first day
    for (let i = 0; i < startDay; i++) {
        const emptyCell = document.createElement("div");
        emptyCell.classList.add("calendar-day", "other-month");
        calendar.appendChild(emptyCell);
    }

    // Render days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayCell = document.createElement("div");
        dayCell.classList.add("calendar-day");

        // Add today class if it's the current day
        if (day === new Date().getDate() && 
            month === new Date().getMonth() && 
            year === new Date().getFullYear()) {
            dayCell.classList.add("today");
        }

        const dayNumberDiv = document.createElement("div");
        dayNumberDiv.classList.add("calendar-day-number");
        dayNumberDiv.textContent = day;
        dayCell.appendChild(dayNumberDiv);

        // Format date for event lookup
        const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

        // Create container for events
        const eventContainer = document.createElement("div");
        eventContainer.classList.add("event-list");
        dayCell.appendChild(eventContainer);

        // Load events for this day
        if (events && events[dateKey] && Array.isArray(events[dateKey])) {
            events[dateKey].forEach((event) => {
                if (event && typeof event === 'object') {
                    const eventDiv = createEventElement(event, dateKey);
                    eventContainer.appendChild(eventDiv);
                }
            });
        }

        // Add click event for selecting date
        dayCell.addEventListener("click", () => {
            if (selectedDates.has(dateKey)) {
                selectedDates.delete(dateKey);
                dayCell.classList.remove("selected");
            } else {
                selectedDates.add(dateKey);
                dayCell.classList.add("selected");
            }
            updateSelectedDatesDisplay();
            eventInput.focus();
        });

        // Add selected class if date is in selectedDates
        if (selectedDates.has(dateKey)) {
            dayCell.classList.add("selected");
        }

        calendar.appendChild(dayCell);
    }
}

function createEventElement(event, dateKey) {
    const eventDiv = document.createElement("div");
    eventDiv.classList.add("event");
    eventDiv.style.backgroundColor = event.event_color || event.color || "#241e62";

    const eventContent = document.createElement("div");
    eventContent.classList.add("event-content");
    
    const eventTitle = document.createElement("span");
    eventTitle.textContent = event.event_name || event.name || "Untitled Event";
    eventTitle.title = eventTitle.textContent;
    
    const eventDetails = document.createElement("small");
    eventDetails.textContent = event.details || "";
    
    eventContent.appendChild(eventTitle);
    if (event.details) {
        eventContent.appendChild(eventDetails);
    }

    const deleteBtn = document.createElement("button");
    deleteBtn.innerHTML = "&times;";
    deleteBtn.classList.add("delete-btn");
    deleteBtn.title = "Delete event";
    deleteBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        if (confirm("Are you sure you want to delete this event?")) {
            deleteEvent(dateKey, event.event_name || event.name);
        }
    });

    // Add click event to show event details
    eventDiv.addEventListener("click", (e) => {
        if (e.target !== deleteBtn) {
            e.stopPropagation(); // Prevent day selection
            const formattedDate = new Date(dateKey).toLocaleDateString("en-US", {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            // Show event details
            const detailsSection = document.getElementById("eventDetailsSection");
            const eventDateElement = document.getElementById("eventDate");
            const eventTitleElement = document.getElementById("eventTitle");
            const eventDetailsElement = document.getElementById("eventDetailsText");
            const eventColorElement = document.getElementById("eventColorText");

            if (eventDateElement) eventDateElement.textContent = formattedDate;
            if (eventTitleElement) eventTitleElement.textContent = event.event_name || event.name;
            if (eventDetailsElement) eventDetailsElement.textContent = event.details || "No details provided";
            if (eventColorElement) eventColorElement.textContent = event.event_color || event.color || "#241e62";
            
            if (detailsSection) {
                detailsSection.classList.add("active");
                // Scroll to the details section
                detailsSection.scrollIntoView({ behavior: "smooth" });
            }
        }
    });

    eventDiv.appendChild(eventContent);
    eventDiv.appendChild(deleteBtn);
    return eventDiv;
}

// Update the selected dates display
function updateSelectedDatesDisplay() {
    if (selectedDates.size === 0) {
        selectedDateSpan.textContent = "Select dates";
        return;
    }

    const dates = Array.from(selectedDates).map(date => {
        return new Date(date).toLocaleDateString("en-US", {
            month: "short",
            day: "numeric"
        });
    });

    selectedDateSpan.textContent = dates.join(", ");
}

// Delete event from database
function deleteEvent(date, eventName) {
    fetch("delete_calendar.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            date: date,
            eventName: eventName
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the event from the local events object
            if (events[date]) {
                events[date] = events[date].filter(event => 
                    (event.event_name !== eventName && event.name !== eventName)
                );
                if (events[date].length === 0) {
                    delete events[date];
                }
            }
            // Re-render the calendar to show the updated events
            renderCalendar();
        } else {
            alert("Error deleting event: " + data.error);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Error deleting event");
    });
}

// Navigation buttons
prevMonth.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
});

nextMonth.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
});

// Close event details
function closeEventDetails() {
    const detailsSection = document.getElementById("eventDetailsSection");
    if (detailsSection) {
        detailsSection.classList.remove("active");
    }
}

// Add event to selected dates
addEventBtn.addEventListener("click", () => {
    const eventName = eventInput.value.trim();
    const eventDetails = document.getElementById("eventDetails").value.trim();
    const eventColorValue = eventColor.value;

    if (eventName && selectedDates.size > 0) {
        selectedDates.forEach(date => {
            const eventData = {
                name: eventName,
                date: date,
                color: eventColorValue,
                details: eventDetails
            };

            fetch("save_calendar.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(eventData),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadEventsFromDB();
                    eventInput.value = "";
                    document.getElementById("eventDetails").value = "";
                    selectedDates.clear();
                    updateSelectedDatesDisplay();
                } else {
                    alert("Error saving event: " + (data.error || "Unknown error"));
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Error saving event. Please try again.");
            });
        });
    } else {
        alert("Please enter an event name and select at least one date.");
    }
});

// Initialize calendar on page load
document.addEventListener("DOMContentLoaded", () => {
    loadEventsFromDB();
    updateSelectedDatesDisplay();
});
