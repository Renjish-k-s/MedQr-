<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<style>
    :root {
  --qr-primary: #7C3AED;
  --qr-primary-dark: #6366F1;
  --qr-accent-light: #10B981;
  --qr-bg: #020617;
  --qr-panel: rgba(15, 23, 42, 0.85);
  --qr-border: rgba(148, 163, 184, 0.25);
  --shadow-xl: 0 25px 45px rgba(15, 23, 42, 0.3);
  --transition: 280ms cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background: linear-gradient(135deg, var(--qr-primary), var(--qr-primary-dark));
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 60px;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            width: 350px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            font-weight: 600;
            color: #555;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
            transition: 0.3s;
        }
        input:focus {
            border-color: #007bff;
        }
        button {
            width: 100%;
            padding: 10px;
            background: var(--qr-primary);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            box-shadow: 0 18px 32px rgba(124, 58, 237, 0.35);

        }
        .message {
            text-align: center;
            margin-top: 15px;
            font-weight: 600;
        }
        .success {
            color: #28a745;
        }
        .error {
            color: #dc3545;
        }
        .back {
            display: inline-block;
            margin-bottom: 15px;
            color: #007bff;
            text-decoration: none;
            transition: 0.3s;
            font-weight: 600;
            font-size: 16px;
            margin-left: 10px;
            margin-bottom: 15px;
            
        }
        .back:hover {
            color: var(--qr-primary);
        }
    </style>
</head>
<body>

<div class="container">
    <a href="./" class="back"  style="color: var(--qr-primary);">Go back</a>
    <h2>Change Password</h2>
    <form id="changePasswordForm">
        <div class="form-group">
            <label>Old Password</label>
            <input type="password" id="old_password" required>
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" id="new_password" required>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" id="confirm_password" required>
        </div>
        <button type="submit" id="submitBtn">Change Password</button>
        <div id="message" class="message"></div>
    </form>
</div>

<script>
document.getElementById("changePasswordForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const old_password = document.getElementById("old_password").value.trim();
    const new_password = document.getElementById("new_password").value.trim();
    const confirm_password = document.getElementById("confirm_password").value.trim();
    const messageEl = document.getElementById("message");
    const btn = document.getElementById("submitBtn");

    messageEl.textContent = "";
    messageEl.className = "message";

    if (new_password !== confirm_password) {
        messageEl.textContent = "New and Confirm Password do not match!";
        messageEl.classList.add("error");
        return;
    }

    btn.disabled = true;
    btn.textContent = "Updating...";

    try {
        const res = await fetch("process_change_password.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({
                old_password,
                new_password
            })
        });

        const data = await res.json();
        if (data.status === "success") {
            messageEl.textContent = data.message;
            messageEl.classList.add("success");
            document.getElementById("changePasswordForm").reset();
        } else {
            messageEl.textContent = data.message;
            messageEl.classList.add("error");
        }
    } catch (error) {
        messageEl.textContent = "An error occurred. Please try again.";
        messageEl.classList.add("error");
    } finally {
        btn.disabled = false;
        btn.textContent = "Change Password";
    }
});
</script>

</body>
</html>
