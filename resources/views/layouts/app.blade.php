<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Мой сайт</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            <a href="{{ route('articles.index') }}">Новости из БД</a>
            @auth
            @php
            $unreadNotifications = auth()->user()
            ->unreadNotifications()
            ->latest()
            ->get();
            @endphp

            <details style="position: relative;" id="notifications-menu">
                <summary id="notifications-summary"
                    style="color: white; cursor: pointer; font-size: 17px;">
                    Уведомления ({{ $unreadNotifications->count() }})
                </summary>

                <div id="notifications-list"
                    style="position: absolute; top: 28px; right: 0; z-index: 10;
                    width: 320px; max-height: 350px; overflow-y: auto;
                    background: white; color: #263238; padding: 12px;
                    border-radius: 6px; box-shadow: 0 4px 12px #0003;">

                    @forelse ($unreadNotifications as $notification)
                    <div style="padding: 10px; border-bottom: 1px solid #ddd;">
                        <a href="{{ route('notifications.show', $notification->id) }}"
                            style="color: #243447; font-size: 15px;">
                            {{ $notification->data['title'] ?? 'Новая статья' }}
                        </a>

                        <div style="font-size: 12px; margin-top: 4px;">
                            {{ $notification->created_at->format('d.m.Y H:i') }}
                        </div>
                    </div>
                    @empty
                    <p>Непрочитанных уведомлений нет.</p>
                    @endforelse
                </div>
            </details>
            @endauth

            @auth
            @if (auth()->user()->role?->name === 'moderator')
            <a href="{{ route('comments.moderation') }}">
                Модерация комментариев
            </a>
            @endif
            @endauth
            @auth
            <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                @csrf
                <button type="submit" style="background: none; border: none; padding: 0; color: white; font-size: 17px; font-family: inherit; cursor: pointer;">
                    Выйти
                </button>
            </form>
            @else
            <a href="{{ route('login') }}">Войти</a>
            <a href="{{ route('signin.form') }}">Регистрация</a>
            @endauth
        </nav>



    </header>
    <div id="app"></div>
    <main>
        @yield('content')
    </main>

    <footer>
        <p>Демченко Кирилл Максимович</p>
        <p>Группа 251-3210</p>
    </footer>
    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const summary = document.getElementById('notifications-summary');
            const list = document.getElementById('notifications-list');

            if (!summary || !list) {
                return;
            }

            async function refreshNotifications() {
                try {
                    const response = await fetch(
                        '/notifications/unread', {
                            headers: {
                                'Accept': 'application/json'
                            }
                        }
                    );

                    if (!response.ok) {
                        return;
                    }

                    const data = await response.json();

                    summary.textContent = `Уведомления (${data.count})`;
                    list.replaceChildren();

                    if (data.notifications.length === 0) {
                        const emptyMessage = document.createElement('p');
                        emptyMessage.textContent = 'Непрочитанных уведомлений нет.';
                        list.appendChild(emptyMessage);
                        return;
                    }

                    data.notifications.forEach(function(notification) {
                        const item = document.createElement('div');
                        item.style.cssText =
                            'padding: 10px; border-bottom: 1px solid #ddd;';

                        const link = document.createElement('a');
                        link.href = notification.url;
                        link.textContent = notification.title;
                        link.style.cssText =
                            'color: #243447; font-size: 15px;';

                        const date = document.createElement('div');
                        date.textContent = notification.created_at;
                        date.style.cssText =
                            'font-size: 12px; margin-top: 4px;';

                        item.appendChild(link);
                        item.appendChild(date);
                        list.appendChild(item);
                    });
                } catch (error) {
                    console.error('Ошибка обновления уведомлений:', error);
                }
            }

            refreshNotifications();
            setInterval(refreshNotifications, 3000);
        });
    </script>
    @endauth
</body>

</html>