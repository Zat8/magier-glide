<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magier Glide - Login & Register</title>
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box; 
        }

        body {
            font-family: 'Georgia', serif; 
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%); 
            min-height: 100vh; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            padding: 20px; 
        }

        .container {
            text-align: center; 
            max-width: 450px; 
            width: 100%; 
        }

        .logo {
            color: #f1f5f9; 
            font-size: 24px; 
            margin-bottom: 30px; 
            letter-spacing: 2px; 
        }

        h1 {
            color: #f1f5f9; 
            font-family: 'Cinzel Decorative', serif; 
            font-size: 18px; 
            font-weight: 400; 
            margin-bottom: 10px; 
            letter-spacing: 3px; 
        }

        h2 {
            color: #f1f5f9; 
            font-family: 'Cinzel Decorative', serif; 
            font-size: 32px; 
            font-weight: 700; 
            margin-bottom: 50px; 
            letter-spacing: 2px; 
        }

        .form-container {
            position: relative; 
        }

        .form {
            display: none; 
        }

        .form.active {
            display: block; 
        }

        .form-group {
            margin-bottom: 25px; 
            text-align: center; 
        }

        label {
            display: inline-block; 
            width: 65%; 
            color: #f1f5f9; 
            font-size: 14px; 
            margin-bottom: 8px; 
            font-weight: 500; 
            text-align: left; 
        }

        input, select {
            width: 65%; 
            padding: 15px 20px; 
            border: none; 
            border-radius: 12px; 
            background: #f1f5f9; 
            font-size: 16px; 
            color: #334155; 
            outline: none; 
            transition: all 0.3s ease; 
            display: inline-block; 
        }

        input:focus, select:focus {
            background: #ffffff; 
            box-shadow: 0 0 0 3px rgba(241, 245, 249, 0.3); 
        }

        select {
            cursor: pointer; 
            appearance: none; 

            background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L6 6L11 1' stroke='%23334155' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat; 
            background-position: right 20px center; 
            padding-right: 45px; 
        }

        .link {
            display: block; 
            color: #f1f5f9; 
            font-size: 14px; 
            margin-bottom: 25px; 
            text-align: center; 
            text-decoration: none; 
            opacity: 0.9; 
            transition: opacity 0.3s ease; 
            cursor: pointer; 
        }

        .link:hover {
            opacity: 1; 
            text-decoration: underline; 
        }

        .submit-btn {
            width: 65%; 
            padding: 16px; 
            background: #f1f5f9; 
            border: none; 
            border-radius: 25px; 
            font-size: 16px; 
            font-weight: 700; 
            color: #334155; 
            cursor: pointer; 
            transition: all 0.3s ease; 
            letter-spacing: 1px; 
        }

        .submit-btn:hover {
            background: #ffffff; 
            transform: translateY(-2px); 
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); 
        }

        .submit-btn:active {
            transform: translateY(0); 
        }
    </style>
</head>
<body>
    <div class="container">

        <div class="logo">
            <img src="./asset/foto/logo.png" alt="Frieren Logo">
        </div>

        <h1>SELAMAT DATANG DI</h1>
        <h2>MAGIER GLIDE</h2>

        <div class="form-container">

            <form id="loginForm" class="form active" action="api/login.php">

                <div class="form-group">
                    <label for="loginUsername">Username</label>
                    <input type="text" id="loginUsername" placeholder="Username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <input type="password" id="loginPassword" placeholder="Password" name="password" required>
                </div>

                <a class="link" onclick="toggleForms()">Belum Punya Akun</a>

                <button type="submit" class="submit-btn">LOGIN</button>
            </form>

            <form id="registerForm" class="form">

                <div class="form-group">
                    <label for="registerUsername">Username</label>
                    <input type="text" id="registerUsername" placeholder="Username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="element">Element Sihir</label>
                    <select id="element" required name="element" >
                        <option value="">Element Sihir</option>
                        <option value="api">Api</option>
                        <option value="air">Air</option>
                        <option value="angin">Angin</option>
                        <option value="tanah">Tanah</option>
                        <option value="alam">Alam</option>
                    </select>
				</div>
 	 			<div class="form-group">
                    <label for="ras">Ras</label>
                    <select id="ras" required name="ras">
                        <option value="">Ras</option>
                        <option value="elf">Elf</option>
                        <option value="manusia">Manusia</option>
                        <option value="dwarf">dwarf</option>
                        <option value="half-demon">Half Demon</option>
                        <option value="half-monster">Half Monster</option>
                        <option value="spirits">Spirits</option>
                        <option value="herioc-spirits">Heroic Spirits</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="registerPassword">Password</label>
                    <input type="password" id="registerPassword" placeholder="Password" name="password" required>
                </div>

                <a class="link" onclick="toggleForms()">Sudah Punya Akun</a>

                <button type="submit" class="submit-btn">REGISTER</button>
            </form>
        </div>
    </div>

    <script>
        function toggleForms() {
            const loginForm = document.getElementById('loginForm'); 
            const registerForm = document.getElementById('registerForm'); 

            loginForm.classList.toggle('active');
            registerForm.classList.toggle('active');
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault(); 

            const username = document.getElementById('loginUsername').value;
            const password = document.getElementById('loginPassword').value;

            if (username && password) {

                sessionStorage.setItem('username', username);

                alert(`Login berhasil!\nSelamat datang, ${username}!`);

                setTimeout(function() {
                    window.location.href = 'index.html';
                }, 500);
            }
        });

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault(); 

            const username = document.getElementById('registerUsername').value;
            const element = document.getElementById('element').value;
            const password = document.getElementById('registerPassword').value;

            if (username && element && password) {
                alert(`Registrasi berhasil!\nUsername: ${username}\nElement: ${element}`);
            }
        });
    </script>
</body>
</html>


