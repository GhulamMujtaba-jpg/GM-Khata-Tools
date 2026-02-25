<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>

<h2>Login</h2>

<input type="email" id="email" placeholder="Enter Email"><br><br>
<input type="password" id="password" placeholder="Enter Password"><br><br>

<button onclick="login()">Login</button>

<script>
function login(){
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;

    // Login credentials
    if(email === "admin@gmail.com" && password === "1234"){
        window.location.href = "dashboard.html"; // Redirect to dashboard
    } else {
        alert("Wrong Email or Password");
    }
}
</script>

</body>
</html>
