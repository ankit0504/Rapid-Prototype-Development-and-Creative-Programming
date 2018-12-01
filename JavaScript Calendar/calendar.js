// For our purposes, we can keep the current month in a variable in the global scope

fetch("is_loggedin.php", {
	method: 'POST',
	body: JSON.stringify(),
	headers: { 'content-type': 'application/json' }
})
.then(response => response.json())
.then (data => is_loggedin(data))
.catch(error => console.error('Error:',error));


let currentMonth = new Month(2018, 9); // October 2018
let focused_id = 0;
let high_priority = false;

updateCalendar();

// Change the month when the "next" button is pressed
document.getElementById("next_month_btn").addEventListener("click", function(event) {
	currentMonth = currentMonth.nextMonth(); // Previous month would be currentMonth.prevMonth()
	updateCalendar(); // Whenever the month is updated, we'll need to re-render the calendar in HTML
	//alert("The new month is " + currentMonth.month + " " + currentMonth.year);
}, false);

document.getElementById("prev_month_btn").addEventListener("click", function(event){
	currentMonth = currentMonth.prevMonth(); // Previous month would be currentMonth.prevMonth()
	updateCalendar(); // Whenever the month is updated, we'll need to re-render the calendar in HTML
	//alert("The new month is " + currentMonth.month + " " + currentMonth.year);
}, false);


document.getElementById("month-selector").addEventListener("change", function(event){
	let vals = document.getElementById("month-selector").value.split('-');
	currentMonth = new Month(Number(vals[0]), Number(vals[1]) - 1);
	updateCalendar();
}, false)


document.getElementById("high_priority").addEventListener("change", function(event){
	updateCalendar();
}, false)


function is_loggedin (data) {

	var logged_in = false;

	if (data.success) {
		logged_in = true;
	}

	$('.sidebar').toggle(logged_in);
	$('.login_page').toggle(!logged_in);

}


function updateCalendar() {
	getEventArray(currentMonth.month + 1, currentMonth.year);
}

function finishUpdatingCalendar (event_array) {
	let weeks = currentMonth.getWeeks();
	$('#current-month').empty();
	$('#current-month').append(currentMonth.month_name[currentMonth.month] + " ");
	$('#current-month').append(currentMonth.year);
	let days = document.getElementsByClassName('calendar__day');

	let starting_day = (currentMonth.getDateObject(1).getDay());

	let day = 1;
	let box = 0;

	$('.calendar__day').empty();
	$('.calendar__day').each(function() {

		if (day <= currentMonth.days_in_month && box >= starting_day) {

			$(this).append(day);

			for (i = 0; i < event_array.length; i++) {

				high_priority = document.getElementById("high_priority").checked;

				if ((event_array[i].day) == day && ((event_array[i].is_priority == "true" && high_priority == true) || high_priority == false)) {

					let minute = event_array[i].minute;
					if (minute < 10) {
						minute = 0 + "" + minute;
					}

					$(this).append("<br>");
					$(this).append(
						"<a role=\"button\" \
						tabindex=\"0\" \
						class=\"event_buttons popover-dismiss btn btn-outline-" + event_array[i].color + "\" \
						id=\"" + event_array[i].id + "\"" + " \
						data-container=\"body\" \
						data-toggle=\"popover\" \
						data-trigger=\"focus\" \
						data-placement=\"bottom\" \
						title=\"" + event_array[i].name+ "\"" +" \
						data-content=\"" +
						currentMonth.year + "/" + (currentMonth.month+1) + "/" + event_array[i].day + " " + event_array[i].hour + ":"+ minute +
						"\">" + event_array[i].name + "</a>"
					);
					$('.popover-dismiss').popover({
						trigger: 'focus'
					})
					$(this).popover({selector:'[data-toggle=popover]'});

					let html_obj = document.getElementById(event_array[i].id);
					let name = event_array[i].name;
					let color = event_array[i].color;

					let year = currentMonth.year;
					let month = currentMonth.month + 1;
					let day = event_array[i].day;
					let hour = event_array[i].hour;

					if (hour < 10) {
						hour = "0" + hour;
					}

					if (month < 10) {
						month = "0" + month;
					}

					if (day < 10) {
						day = "0" + day;
					}

					if (year < 1000) {
						year = "0" + year;
					}

					else if (year < 100) {
						year = "00" + year;
					}

					let high_priority = false;

					if (event_array[i].is_priority == "true") {
						high_priority = true;
					}


					document.getElementById(event_array[i].id).addEventListener("focus", function(event) {
						console.log("HENLO");
						focused_id = html_obj.id;

						$('#event_name').val(name); //THIS THREE LINES ARE WHERE THE VALUES ARE FILLED IN!!!!
						$('#' + color).prop('checked', true);
						$('#priority_level').prop('checked', high_priority);
						console.log(year);
						$('#event_date').val(year + "-" + month + "-" + day + "T" + hour + ":" + minute);


						document.getElementById("edit").addEventListener("click", function(event) {

							const event_name = document.getElementById("event_name").value; // Get the event name from the form
							let date_time = document.getElementById("event_date").value;
							let color = $("input[name='color']:checked").val();
							let priority = document.getElementById('priority_level').checked;

   							let adding_event = new new_event (event_name, date_time, color, priority);

							const data = {
								'event_id': focused_id,
								'new_name': event_name,
								'new_year': adding_event.year,
								'new_month': adding_event.month,
								'new_day': adding_event.day,
								'new_hour': adding_event.hour,
								'new_minute': adding_event.minute,
								'new_color': color,
								'new_priority': priority,
								'token': sessionCookie
							};

							fetch("edit_event.php", {
									method: 'POST',
									body: JSON.stringify(data),
									headers: { 'content-type': 'application/json' }
								})
								.then(response => response.json())
								.then(data => {
									alert(data.success ? "Event has been edited!" : `Error: event has not been edited.`);
									updateCalendar ();
								})
								.catch(error => console.error('Error:',error))

						}, false);

						document.getElementById("delete").addEventListener("click", function(event) {

							const data = {
								'event_id': focused_id,
								'token': sessionCookie
							};

							fetch("delete_event.php", {
									method: 'POST',
									body: JSON.stringify(data),
									headers: { 'content-type': 'application/json' }
								})
								.then(response => response.json())
								.then(data => {
									alert(data.success ? "Event has been deleted!" : `Error: event has not been deleted.`);
									updateCalendar ();
								})
								.catch(error => console.error('Error:',error))

						}, false);

					}, false);
				}

			}

			day++;
			$(this).css("background-color","white");
		}
		else {
			$(this).css("background-color","#f0f0f0");
		}
		box++;

	});
}


function getEventArray (month, year) {

	const data = {'year': year, 'month': month};

	fetch("get_events.php", {
		method: 'POST',
		body: JSON.stringify(data),
		headers: { 'content-type': 'application/json' }
	})
	.then(response => response.json())
	.then(data => {
		let event_array = helpEventArray (data);
		finishUpdatingCalendar (event_array);
	})
	.catch(error => console.error('Error:',error))

}


function helpEventArray (data) {

	var events = [];

	if (data.success == true) {

		let ids = data.ids;
		let names = data.names;
		let days = data.days;
		let hours = data.hours;
		let minutes = data.minutes;
		let priorities = data.priorities;
		let colors = data.colors;

		for (i = 0; i < names.length; i++) {

			let new_event = new event (ids[i], names[i], days[i], hours[i], minutes[i], colors[i], priorities[i]);
			events.push (new_event);

		}
	}

	return events;

}

(function () {
	"use strict";

	/* Date.prototype.deltaDays(n)
	 *
	 * Returns a Date object n days in the future.
	 */
	Date.prototype.deltaDays = function (n) {
		// relies on the Date object to automatically wrap between months for us
		return new Date(this.getFullYear(), this.getMonth(), this.getDate() + n);
	};

	/* Date.prototype.getSunday()
	 *
	 * Returns the Sunday nearest in the past to this date (inclusive)
	 */
	Date.prototype.getSunday = function () {
		return this.deltaDays(-1 * this.getDay());
	};
}());

/** Week
 *
 * Represents a week.
 *
 * Functions (Methods):
 *	.nextWeek() returns a Week object sequentially in the future
 *	.prevWeek() returns a Week object sequentially in the past
 *	.contains(date) returns true if this week's sunday is the same
 *		as date's sunday; false otherwise
 *	.getDates() returns an Array containing 7 Date objects, each representing
 *		one of the seven days in this month
 */
function Week(initial_d) {
	"use strict";

	this.sunday = initial_d.getSunday();

	this.nextWeek = function () {
		return new Week(this.sunday.deltaDays(7));
	};

	this.prevWeek = function () {
		return new Week(this.sunday.deltaDays(-7));
	};

	this.contains = function (d) {
		return (this.sunday.valueOf() === d.getSunday().valueOf());
	};

	this.getDates = function () {
		let dates = [];
		for(let i=0; i<7; i++){
			dates.push(this.sunday.deltaDays(i));
		}
		return dates;
	};
}

/** Month
 *
 * Represents a month.
 *
 * Properties:
 *	.year == the year associated with the month
 *	.month == the month number (January = 0)
 *
 * Functions (Methods):
 *	.nextMonth() returns a Month object sequentially in the future
 *	.prevMonth() returns a Month object sequentially in the past
 *	.getDateObject(d) returns a Date object representing the date
 *		d in the month
 *	.getWeeks() returns an Array containing all weeks spanned by the
 *		month; the weeks are represented as Week objects
 */
function Month(year, month) {
	"use strict";

	this.year = year;
	this.month = month;
	this.month_name = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

	this.is_leap_year = (year % 4) || ((year % 100 === 0) && (year % 400)) ? 0 : 1;
	this.days_in_month = (month + 1 === 2) ? (28 + this.is_leap_year) : 31 - (month) % 7 % 2;

	this.nextMonth = function () {
		return new Month( year + Math.floor((month+1)/12), (month+1) % 12);
	};

	this.prevMonth = function () {
		return new Month( year + Math.floor((month-1)/12), (month+11) % 12);
	};

	this.getDateObject = function(d) {
		return new Date(this.year, this.month, d);
	};

	this.getWeeks = function () {
		let firstDay = this.getDateObject(1);
		let lastDay = this.nextMonth().getDateObject(0);

		let weeks = [];
		let currweek = new Week(firstDay);
		weeks.push(currweek);
		while(!currweek.contains(lastDay)){
			currweek = currweek.nextWeek();
			weeks.push(currweek);
		}

		return weeks;
	};
}

