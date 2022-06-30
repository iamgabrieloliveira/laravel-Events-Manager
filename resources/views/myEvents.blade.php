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
    <title>My Events</title>
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
                    <a href="">My Events</a>
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
    @if(session('message'))
        <h2>{{ session('message') }}</h2>
    @endif
    <div class="events-wrapper">
        @foreach($events as $event)
        <div class="event-container">
            <div class="event-img-container">
                <img src="/img/events/{{$event->image}}" alt="{{$event->title}}">
            </div>
            <div class="description-event">
            <h1>Title: {{ $event->title }}</h1>
            <h2>Description: {{ $event->description }}</h2>
            <h2>City: {{ $event->city }}</h2>
            @if($event->private === 1)
            <h2>Is Private: Yes</h2>
            @else
            <h2>Is Private: No</h2>
            @endif
            <h2>Event Date: {{ date('d/m/y'), strtotime($event->date)}}</h2>
            <h2>Event Owner: {{ $users[$event->user_id -1]->name }}</h2>
            @if($currentUser->name === $users[$event->user_id -1]->name)
            <div class="management-buttons-event">
                <form action="/{{$event->id}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button>Delete</button>
                </form>
                <div class="edit-modal">
                    <button onclick="showEditEventModal()">Editar</button>
                    <div class="modal-overlay" onclick="showEditEventModal()"></div>
                    <form action="/{{ $event->id }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="modal-content">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" placeholder="Title" id="title" name="title">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" placeholder="Description" id="description" name="description">
                            </div>
                            <div class="form-group">
                                <label for="city"></label>
                                <input type="text" placeholder="City" id="city" name="city">
                            </div>
                            <div class="form-group">
                                <label for="private">Is Private</label>
                                <select name="private" id="private">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="date"></label>
                                <input type="date" id="date" name="date">
                            </div>
                            <button type="submit">Save</button>
                        </div>
                    </form>
                </div>
                <form action="{{ route('presenceList', ['event' => $event->id]) }}"  method="GET">
                    @csrf
                    <button>List</button>
                </form>
            </div>
            </div>
            @endif
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
        font-size: 18px;
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


    .new-event-form {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: space-around;
        gap: 15px;
        border: none;
        width: 300px;
        margin: 20px auto;

        padding: 25px;

        border-radius: 4px;
        box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.2);

    }

    .new-event-form.active {
        display: flex;
        background: white;
    }

    .search-event-form {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-around;
        gap: 15px;
        border: none;
        width: 300px;
        margin: 20px auto;

        padding: 25px;
        background: white;

        border-radius: 4px;
        box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.2);
    }

    .form-group {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }

    .form-group-option {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 30px;
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
    }

    li {
        list-style: none;
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


    input {
        width: 200px;
        transition: .2s;
        border-radius: 5px;
        border: 1px solid rgba(0, 0, 0, 0.2);
        padding: 6px 8px;
        color: black;
    }

    input::placeholder {
        color: black;
    }

    input:focus {
        outline: 0;
        box-shadow: 0px 0px 5px 5px rgba(0, 0, 0, 0.2);
    }



    .event-container h1 {
        font-size: 17px;
    }

    img {
        width: 140px;
        margin: 0 auto;
    }

    .management-buttons-event {
        display: flex;
        gap: 5px;
    }

    .modal-content {
        display: none;
    }

    .modal-content.active {
        display: flex;
        position: fixed;
        z-index: 2;
        top: 50%;
        left: 50%;

        transform: translate(-50%, -50%);

        flex-direction: column;
        padding: 25px;
        gap: 5px;

        background: white;

        border: 1px solid black;

        width: 380px;
        height: 350px;

        border-radius: 6px;

    }

    .modal-content input {
        width: 100%;
    }

    .modal-overlay {
        display: none;
    }

    .modal-overlay.active {
        display: block;
        position: fixed;
        z-index: 1;
        inset: 0;
        background: rgba(0, 0, 0, .5);
    }

    input[type="file"] {
        display: none;
    }

    #img-input-label {
        border: 1px solid #ccc;
        display: inline-block;
        padding: 6px 25px;
        cursor: pointer;
    }

    select {
        background: none;
        border: 1px solid rgba(0, 0, 0, .2);
        padding: 4px 8px;
    }

    .current-user {
        background: white;
        border: none;
        border-radius: 15px;
        padding: 6px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }

    .menu-hamburguer {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .line1,
    .line2,
    .line3 {
        width: 12px;
        height: 2px;
        background: black;
        border: none;
        transition: .6s;
    }

    .line2 {
        transition: .2s;
    }

    .menu-hamburguer.active .line2 {
        visibility: hidden;
    }

    .menu-hamburguer.active .line1 {
        transform: rotate(135deg) translate(4px, -6px);
    }

    .menu-hamburguer.active .line3 {
        transform: rotate(-135deg) translate(3px, 4px);
    }

    .user-dropdown {
        text-align: center;
        visibility: hidden;
        background: white;
        position: absolute;
        margin-top: 120px;
        padding: 12px 24px;
        height: 0px;
    }

    .user-dropdown nav {
        display: flex;
        flex-direction: column;
        gap: 5px;
        text-align: center;
    }

    .user-dropdown.active {
        visibility: visible;
        height: 45px;
    }

    .uis.uis-triangle {
        position: absolute;
        margin-top: -23px;
        margin-left: -60px;
        font-size: white;
        color: white;
    }
    .description-event{
        display: flex;
        flex-direction: column;
        gap: 5px;
        padding: 5px 12px;
    }
</style>

<script>
    const modalContent = document.querySelector('.modal-content');
    const modalOverlay = document.querySelector('.modal-overlay');
    const page = document.querySelector('html');

    function showEditEventModal() {
        if (modalContent.classList.contains("active")) {
            modalContent.classList.remove('active')
            modalOverlay.classList.remove('active')
            page.style.overflow = "auto"

        } else {
            modalContent.classList.add('active')
            modalOverlay.classList.add('active')
            page.style.overflow = "hidden"


        }
    }
</script>
