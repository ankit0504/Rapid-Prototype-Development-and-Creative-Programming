function new_event (name, date_time, color, priority) {

    this.name = name;
    this.date_time = date_time; //yyyy-mm-ddThh:mm

    let split1 = this.date_time.split('-'); //split1 = [yyyy, mm, ddThh:mm]
    let split2 = split1[2].split('T'); //split2 = [dd, hh:mm]
    let split3 = split2[1].split(':'); //split3 = [hh, mm]

    this.year = Number(split1[0]);
    this.month = Number(split1[1]);
    this.day = Number(split2[0]);
    this.hour = Number(split3[0]);
    this.minute = Number(split3[1]);
    this.color = color;
    this.priority = priority;

}

function event (id, name, day, hour, minute, color, is_priority) {

    this.name = name;
    this.id = id;
    this.hour = hour;
    this.day = day;
    this.minute = minute;
    this.color = color;
    this.is_priority = is_priority;

}

function add_event(event) {

    const event_name = document.getElementById("event_name").value; // Get the event name from the form
    let date_time = document.getElementById("event_date").value; // Get the event date from the form

    let color = $("input[name='color']:checked").val();
    let priority = document.getElementById('priority_level').checked;


    let adding_event = new new_event (event_name, date_time, color, priority);

    // Make a URL-encoded string for passing POST data:
    const data = {
        'event_name': event_name,
        'year': adding_event.year,
        'month': adding_event.month,
        'day': adding_event.day,
        'hour': adding_event.hour,
        'minute': adding_event.minute,
        'color': color,
        'priority': priority
    };

    fetch("new_event.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => alert(data.success ? "Event has been added!" : `Error: event has not been added.`))
        .catch(error => console.error('Error:',error));
    updateCalendar();

}

document.getElementById("add").addEventListener("click", add_event, false);
