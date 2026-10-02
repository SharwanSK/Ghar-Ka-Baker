// A small static calendar for August 2026. It displays dummy delivery dates.
document.addEventListener("DOMContentLoaded", function () {
  var calendar = document.getElementById("calendarGrid");
  if (!calendar) return;

  var events = {
    14: ["Cake - Sana", "pink"],
    16: ["Cupcakes - Hira", "blue"],
    18: ["Wedding Cake", ""],
    20: ["Lunch Catering", "pink"],
    23: ["Brownies - Zara", "blue"],
    27: ["Birthday Cake", ""],
  };
  var weekdays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
  weekdays.forEach(function (day) {
    calendar.innerHTML += '<div class="calendar-weekday">' + day + "</div>";
  });

  // August 2026 starts on Saturday, so six empty spaces appear first.
  for (var empty = 0; empty < 6; empty++) {
    calendar.innerHTML += '<div class="calendar-day muted"></div>';
  }
  for (var date = 1; date <= 31; date++) {
    var eventHtml = "";
    if (events[date]) {
      eventHtml =
        '<span class="calendar-event ' +
        events[date][1] +
        '">' +
        events[date][0] +
        "</span>";
    }
    var todayClass = date === 13 ? "today" : "";
    calendar.innerHTML +=
      '<div class="calendar-day ' +
      todayClass +
      '"><strong>' +
      date +
      "</strong>" +
      eventHtml +
      "</div>";
  }
});
