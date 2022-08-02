<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>

body  
{  
    margin: 0;  
    padding: 0;  
    font-family: 'Arial';  
}  
.login{  
        width: 350px;
        height :180px; 
        position: absolute;
      top:35%;
      bottom: 35%;
      left: 35%;
      right: 35%;
        border :2px solid black;  
        border-radius: 15px ;  
          
}  
/* Full-width input fields */
input[type=number]{
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

/* Set a style for all buttons */
button {
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}
.container {
  padding: 16px;
}


</style>
</head>
<body>

<div class="login">
<form method="post">
  <div class="container">
    <label for="uname"><b>OTP</b></label>
    <input type="number" name="otp_send" placeholder="Enter Verification Code" required>

    <button type="submit" class="btn">Verify</button>
  </div>
</form>
</div> 