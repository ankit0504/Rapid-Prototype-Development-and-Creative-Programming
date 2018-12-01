//Login stuff
document.getElementById("login").addEventListener("click", loginAjax, false); // Bind the AJAX call to button click
let sessionCookie = 0;

fetch("csrf.php", {
    method: 'POST',
    body: JSON.stringify(),
    headers: { 'content-type': 'application/json' }
})
.then(response => response.json())
.then (data => setCSRF(data))
.catch(error => console.error('Error:',error));


function setCSRF (data) {
    sessionCookie = data.token;
}

function loginAjax(event) {
    const username = document.getElementById("username").value; // Get the username from the form
    const password = document.getElementById("password").value; // Get the password from the form

    // Make a URL-encoded string for passing POST data:
    const data = { 'username': username, 'password': password };

    fetch("login.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then (data => update_appearance(data))
        .catch(error => console.error('Error:',error));
}

function update_appearance (data) {
    $('.sidebar').toggle(data.success);
    $('.login_page').toggle(!data.success);
    updateCalendar();
    sessionCookie = data.token;
}

 //Register stuff
document.getElementById("register").addEventListener("click", registerAjax, false); // Bind the AJAX call to button click

function registerAjax(event) {
    const new_username = document.getElementById("new_username").value; // Get the username from the form
    const new_password = document.getElementById("new_password").value; // Get the password from the form

    // Make a URL-encoded string for passing POST data:
    const data = {
        'new_username': new_username,
        'new_password': new_password
    };

    fetch("register.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => alert(data.success ? "You've been registered!" : `Error: ${data.message}`))
        .catch(error => console.error('Error:',error));
}

document.getElementById("logout").addEventListener("click", logoutAjax, false);

function logoutAjax (event) {

    fetch("logout.php", {
        method: 'POST',
        body: JSON.stringify(),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then (data => {
        return update_appearance(data);
    })
    .catch(error => console.error('Error:',error))

}



