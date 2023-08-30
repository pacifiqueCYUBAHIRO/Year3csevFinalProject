<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/03d0a7c683.js" crossorigin="anonymous"></script>
    <!-- <script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script> -->
    <title>Forgot password</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        body{
            margin: 0;
            padding: 0;
            
        }
      
        nav{
            position: sticky;
            top: 0;
            display: flex;
            padding: 25px;
            background: rgb(1, 13, 44);
            
            background-size: cover;
         }
         .checkButton{
            font-size: 30px;
            color: rgb(255, 255, 255);
            cursor: pointer;
            display: none;
          }
          
          .myCheckBox{
            display: none;
          }

         nav h2{
          color: white;
          font-size: 30px;
         }
         
         nav li{
          display: flex;
          position: absolute;
          right: 30px;
          top: 30px;
          list-style: none;
    
         }

         nav a{
          font-size: 20px;
          color: rgb(255, 255, 255);
          text-decoration: none;
          padding: 10px;
          margin: 0 15px;
         }
              
              nav a:hover {
             transition: 0.4s ease-in-out;
                color: yellowgreen;
              }
              button:hover{
                font-weight: 400;
                color: yellowgreen;
              }

              .container{
                display: flex;
                justify-content: center;
                padding: 40px;
              }

              
      .login-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        max-width: 400px;
        width: 100%;
    }



    .login-container h2 {
        text-align: center;
        margin-bottom: 20px;
        padding: 10px;
        width: 100%;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input[type="email"] {
        width: 95%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        
    }

    .form-group button {
      background: #0b5885;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
    }

              @media screen and (max-width: 912px) {
            
        
            .moving-text{
                
                color: #fff;
                text-align: center;
                margin: 10px;
                margin-top: -150px;
                
            }
            .checkButton{
                display: block;
               margin-left: 160px;
            }
  ul{
    position: fixed;
    width: 100%;
    height: 100vh;
    background-color: rgb(10, 10, 10);
    top: 80px;
    color: #000000;
    left: -130%;
    text-align: center;
    transition: all .5s;
}

nav ul li a{
    display: block;
    font-size: 20px;
    
}
.myCheckBox:checked ~ ul{
    left: 0;
} 
 nav li{
                display: block;
                margin-right: 80px;
                
            } 
            button{
                position: static;
            }
        }

              </style>
</head>
          
<body>
<nav>
            <a href="index.html"><h2> O E F R <span style="color: yellowgreen;">A </span>S</h2></a>
            <input type="checkbox" class="myCheckBox" id="myCheck" />
            <label for="myCheck" class="checkButton">
                <i class="fa fa-bars"></i>
            </label>
            <ul>
              <li>
                <a href="index.html" class="active1">Home</a>
                <a href="#about">Who we are</a>
                <a href="login.html">Attendance</a>
               <a href="adminlog.html" style="background-color: #0b5885; text-align: center; border-radius: 10px; padding: 10px;">Admin</a>
              </li>
            </ul>
          </nav> 


<div class="container">
        <div class="login-container">
            <h2>Forgot Password</h2>
            <form action="send-reset-link.php" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="enter your email" required>
                </div>
                <div class="form-group">
                    <button type="submit">Send Reset Link</button>
                </div>
            </form>
        </div>
    </div>


</body>
</html>