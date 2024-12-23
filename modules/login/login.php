<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registration - Vehicle Rental System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            overflow: hidden;
            position: relative;
        }

        .container {
            position: relative;
            width: 800px;
            height: 600px;
            background: #fff;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border-radius: 15px;
            animation: fadeInUp 0.8s ease forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        .form-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .signin-signup, .signup-signup {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            transition: transform 0.6s ease-in-out;
            background: #fff;
        }

        .signup-signup {
            left: 100%;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }

        h2 {
            color: #2c3e50;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .input-field {
            position: relative;
            margin-bottom: 25px;
            width: 100%;
        }

        .input-field i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #3498db;
            font-size: 1.2rem;
        }

        .input-field input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: none;
            border-radius: 50px;
            background: #f8f9fa;
            outline: none;
            font-size: 16px;
            transition: 0.3s;
        }

        .input-field input:focus {
            background: #fff;
            box-shadow: 0 0 10px rgba(52, 152, 219, 0.2);
        }

        .btn {
            width: 100%;
            height: 45px;
            border: none;
            border-radius: 50px;
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: #fff;
            font-size: 16px;
            margin: 10px 0;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        .toggle-text {
            text-align: center;
            color: #666;
            margin-top: 1rem;
            width: 100%;
        }

        .toggle-text a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .toggle-text a:hover {
            color: #2c3e50;
        }

        .error-message {
            color: #e74c3c;
            margin-bottom: 15px;
            text-align: center;
            font-size: 0.9rem;
            width: 100%;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            from {
                transform: translateY(0) rotate(0);
            }
            to {
                transform: translateY(-100vh) rotate(360deg);
            }
        }

        @media (max-width: 768px) {
            .container {
                width: 90%;
                margin: 20px;
            }
            
            h2 {
                font-size: 2rem;
            }

            form {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="form-container">
            <!-- Sign In Form -->
            <div class="signin-signup">
                <form action="login_process.php" method="POST" id="loginForm">
                    <h2>Welcome Back</h2>
                    <div class="error-message" id="loginError"></div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email Address" required>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn">Sign In</button>
                    <p class="toggle-text">Don't have an account? <a href="#" onclick="toggleForms()">Sign Up</a></p>
                </form>
            </div>

            <!-- Sign Up Form -->
            <div class="signup-signup">
                <form action="register_process.php" method="POST" id="registerForm">
                    <h2>Create Account</h2>
                    <div class="error-message" id="registerError"></div>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" placeholder="Full Name" required>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email Address" required>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn">Sign Up</button>
                    <p class="toggle-text">Already have an account? <a href="#" onclick="toggleForms()">Sign In</a></p>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.animation = `float ${Math.random() * 8 + 4}s linear infinite`;
                document.body.appendChild(particle);
            }
        });

        function toggleForms() {
            const signinForm = document.querySelector('.signin-signup');
            const signupForm = document.querySelector('.signup-signup');

            signinForm.style.transform = signinForm.style.transform === 'translateX(-100%)' 
                ? 'translateX(0)' 
                : 'translateX(-100%)';
            
            signupForm.style.transform = signupForm.style.transform === 'translateX(-100%)' 
                ? 'translateX(0)' 
                : 'translateX(-100%)';
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('login_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '../../index.php';
                } else {
                    document.getElementById('loginError').textContent = data.message;
                }
            })
            .catch(error => {
                document.getElementById('loginError').textContent = 'An error occurred. Please try again.';
            });
        });

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('register_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('registerError').textContent = '';
                    alert('Registration successful! Please sign in.');
                    toggleForms();
                } else {
                    document.getElementById('registerError').textContent = data.message;
                }
            })
            .catch(error => {
                document.getElementById('registerError').textContent = 'An error occurred. Please try again.';
            });
        });
    </script>
</body>
</html>