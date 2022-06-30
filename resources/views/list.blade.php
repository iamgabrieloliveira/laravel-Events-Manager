<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/solid.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>List</title>
</head>

<body>
    <header>
        <nav>
            @auth
                <li>
                    <form action="/logout" method="POST">
                        @csrf
                        <a href="/logout" 8000
                            onclick="event.preventDefault()
                            this.closest('form').submit();">Logout</a>
                    </form>
                    <a href="{{ route('myEvents', ['name' => $currentUser->name]) }}">My Events</a>
                    <a href="/">Home</a>
                    <a href="/">{{ $currentUser->name }}</a>
                    </a>
                </li>
            @endauth
            @guest
                <li>
                    <a href="/login">Enter</a>
                </li>
                <li>
                    <a href="/register">Register</a>8000
                </li>
            @endguest

        </nav>
    </header>
    <div class="about-event">
        <h2>Confirmed List of: {{ $event->title }}</h2>
        <h3>Description: {{ $event->description }}</h3>
        <h3>City: {{ $event->city }}</h3>
        <h3>Date: {{ date('d/m/y', strtotime($event->date)) }}</h3>
    </div>
    @if ($userList)
        <ul class="confirmeds">Confirmed List:
            @foreach ($userList as $user)
                <li>{{ $user->name }} </li>
            @endforeach
        </ul>
    @else
        <h2 class="message">Nobody was confirmed</h2>
    @endif
</body>

</html>

<style>
     * {
        margin: 0;
        padding: 0;

        box-sizing: border-box;

        font-family: 'Montserrat', sans-serif;
    }

    body {
        background: #EFF0F2;
        margin-bottom: 10px;
    }

    a {
        font-weight: bolder;
    }


    h2 {
        font-size: 18px;
    }

    li {
        display: flex;
        justify-content: space-around;
    }

    header {
        padding: 20px;

        margin-bottom: 20px;

    }

    a {
        text-decoration: none;
        color: black;

        transition: .5s;
    }



    .about-event {
        display: flex;
        flex-direction: column;
        gap: 12px;

        margin: 120px auto 80px auto;


        border: 1px solid rgba(0, 0, 0, 0.525);
        border-radius: 5px;

        width: 320px;

        padding: 20px 25px;

        box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.246);




    }
    .message{
        background: red;
        width: 300px;
        margin: 0 auto;
        padding: 5px 9px;
        border-radius: 5px;
    }

    .confirmeds{
        line-height: 20px;
        text-align: center;
        font-weight: bolder;
        list-style: dot;
    }
    .description-event{
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

</style>
