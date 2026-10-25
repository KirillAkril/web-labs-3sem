<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Мой сайт</title>

    <style>
        * {
            box-sizing: border-box;
        }


        html {
            min-height: 100%;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
            background: #f2f4f7;
            color: #263238;
        }

        header {
            background: #243447;
            padding: 22px;
        }

        nav {
            max-width: 1000px;
            margin: auto;
            display: flex;
            gap: 25px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 17px;
        }

        nav a:hover {
            color: #80cbc4;
        }


        main {
            width: calc(100% - 40px);
            max-width: 1000px;
            min-height: 400px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            flex: 1;
        }



        footer {
            margin-top: auto;
            padding: 20px;
            background: #243447;
            color: white;
            text-align: center;
        }
    </style>
</head>

<body>

    <header>


        <nav>
            <a href="{{ route('home') }}">Главная</a>
            <a href="{{ route('about') }}">О нас</a>
            <a href="{{ route('contacts') }}">Контакты</a>
            <a href="{{ route('signin.form') }}">Регистрация</a>
        </nav>


    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>Демченко Кирилл Максимович</p>
        <p>Группа 251-3210</p>
    </footer>

</body>

</html>