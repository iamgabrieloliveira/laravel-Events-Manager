<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Confirmed</title>
</head>
<body>
<header>
       <nav>
           @auth
                <li>
                    <form action="/logout" method="POST">
                        @csrf
                        <a href="/logout"8000
                            onclick="event.preventDefault()
                            this.closest('form').submit();"
                        >Logout</a>
                    </form>
                    <a href="{{ route('myEvents', ['name' =>  $currentUser->name]) }}">My Events</a>
                    <a href="/">Home</a>
                    <a href="/">{{ $currentUser->name }}</a>
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
    <div class="events-wrapper">
    @foreach($events as $event)
    <div class="event-container">
        <div class="event-img-container">
            <img src="/img/events/{{$event->image}}" alt="{{$event->title}}">
        </div>
        <div class="description-event">
        <h1 id="event-title">Title: {{ $event->title }}</h1>
        <h2>Description: {{ $event->description }}</h2>
        <h2>City: {{ $event->city }}</h2>
        @if($event->private === 1)
        <h2>Is private: Yes</h2>
        @else
        <h2>Is private: No</h2>
        @endif
        <h2>Event date: {{ date('d/m/y', strtotime($event->date))}}</h2>
        <h2>Event owner: {{ $users[$event->user_id - 1]->name }}</h2>
        <form action="{{ route('cancelPresence', ['id' => $event->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <button>Cancel presence</button>
        </form>
        </div>
    </div>
    @endforeach
</div>

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
        font-size: 17px;
    }

    li {
        display: flex;
        justify-content: space-around;
    }

    header {
        text-align: center;
        padding: 20px;

        margin-bottom: 20px;

    }

    a {
        text-decoration: none;
        color: black;

        transition: .5s;
    }

    .events-wrapper {

        display: grid;
        grid-template-columns: auto auto auto;
        justify-content: space-evenly;
        row-gap: 20px;

        margin-top: 20px;

    }

    .event-container {
        background: white;

        border: none;
        border-radius: 2px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 7px;
        margin: 10px 0px;
        width: 350px;
        box-shadow: 0px 0px 5px 5px rgba(0, 0, 0, 0.2);

        transition: .5s;
        padding-bottom: 14px;
    }

    .event-img-container {
        width: 350px;
        height: 350px;
    }

    .event-img-container img {
        width: 350px;
        height: 350px;
    }

    button {
        padding: 4px 25px;
        color: black;
        background: none;
        font-weight: bolder;
        border: 1px solid rgba(0, 0, 0, .5);
        border-radius: 4px;
        margin-top: 10px;
        cursor: pointer;
        transition: .5s;
    }

    button:hover {
        opacity: .8;
        transform: translateY(5px);
    }
    .description-event{
        width: 100%;

        padding: 0px 12px;

        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .description-event h1{
        font-size: 19px;
    }

</style>
