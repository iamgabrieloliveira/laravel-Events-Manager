<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/solid.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
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

                    <a class="current-user" onclick="dropMenu()">
                        <div class="menu-hamburguer">
                            <div class="line1"></div>
                            <div class="line2"></div>
                            <div class="line3"></div>
                        </div>
                        <div class="user-dropdown">
                            <i class="uis uis-triangle"></i>
                            <nav>
                                <p onclick="showAddEventForm()">Create Event</p>
                                <form action="{{ route('myConfirmedEvents', ['name' => $currentUser->id]) }}"
                                    method="GET">
                                    <button class="myConfirmedsBtn">My confirmeds</button>
                                </form>
                            </nav>
                        </div>{{ $currentUser->name }}
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
    <main>
        @if (session('message'))
            <h2 class="messages">{{ session('message') }}</h2>
        @endif
        <form action="{{ route('store') }}" method="POST" enctype="multipart/form-data" class="new-event-form">
            @csrf
            <div class="form-group">
                <label id="img-input-label">
                    <input id="img-input" type="file" id="image" name="image" required>
                    Select event image
                </label>
            </div>
            <div class="form-group">
                <label for="date">Event Date</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" placeholder="Title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" placeholder="Description" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" placeholder="City" required>
            </div>
            <div class="form-group-option">
                <label for="private">is Private</label>
                <select name="private" id="private">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
            <button type="submit">Send</button>
        </form>

        <form action="/" method="GET" class="search-event-form">
            <label for="search">Search a Event</label>
            <input type="text" id="search" name="search" placeholder="Write event title" value="{{ request('search') }}">
            <button type="submit">Search</button>
        </form>
        @if ($search)
            <h2 class="messages">Looking for events by: {{ $search }}</h2>
        @else
            <h2 style="margin-top: 100px; font-size: 25px; text-align: center;">Events</h2>
        @endif
        <div class="events-wrapper">

            @foreach ($events as $event)
                <div class="event-container">
                    <div class="event-img-container">
                        <img src="img/events/{{ $event->image }}" alt="{{ $event->title }}">
                    </div>
                    <div class="description-event">
                        <h1 id="event-title">Title: {{ $event->title }}</h1>
                        <h2>Description: {{ $event->description }}</h2>
                        {{-- <h2>City: {{ $event->city }}</h2> --}}
                        {{-- @if ($event->private == 1)
                            <h2>Is Private: Yes</h2>
                        @elseif ($event->private == 0)
                            <h2>Is Private: No</h2>
                        @endif --}}
                        <h2>Event Date: {{ date('d/m/y', strtotime($event->date)) }}</h2>
                        {{-- <h2>Event Owner: {{ $users[$event->user_id - 1]->name }}</h2> --}}
                        @if ($currentUser->name === $users[$event->user_id - 1]->name)
                            <div class="management-buttons-event">
                                <form action="{{ route('delete', ['id' => $event->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button>Delete</button>
                                </form>
                                <button onclick="showEditEventModal({{ $event->id }})">Edit</button>

                            </div>


                        @elseif(in_array($event->id, $eventPerUserIDs))
                            <form action="{{ route('cancelPresence', ['id' => $event->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button>Cancel presence</button>
                            </form>
                        @else
                            <form action="{{ route('confirmPresence', ['id' => $event->id]) }}" method="POST">
                                @csrf
                                <button>Confirm presence</button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="modal-overlay" name="overlay-{{ $event->id }}"
                    onclick="showEditEventModal({{ $event->id }})"></div>
                <div name="modal-{{ $event->id }}" class="modal-content">
                    <form action="{{ route('atualizar', ['id' => $event->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" placeholder="Title" id="title" name="title"
                                value="{{ $event->title }}">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" placeholder="Description" id="description"
                                value="{{ $event->description }}">

                        </div>
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" placeholder="City" id="city" name="city"
                                value="{{ $event->city }}">
                        </div>
                        <div class="form-group">
                            <label for="private">Is Private</label>
                            <select name="private" id="private" value="{{ $event->private }}">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date">Data</label>
                            <input type="date" id="date" name="date"
                                value="{{ date('Y-m-d', strtotime($event->date)) }}">
                        </div>
                        <button>Save</button>
                    </form>
                </div>
            @endforeach


            @if (count($events) === 0 && $search != '')
                <h2>Não há eventos disponíveis que correspondem a {{ $search }}</h2>
            @endif
        </div>
    </main>

</body>

</html>

<script>
    const page = document.querySelector('html');


    function showEditEventModal(eventoId) {



        const modalContent = document.querySelector(`[name=modal-${eventoId}]`);
        const modalOverlay = document.querySelector(`[name=overlay-${eventoId}]`);


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


    const UserDropDown = document.querySelector('.menu-hamburguer');
    const dropDown = document.querySelector('.user-dropdown');

    function dropMenu() {
        if (UserDropDown.classList.contains("active")) {
            UserDropDown.classList.remove('active')
            dropDown.classList.remove('active')

        } else {
            UserDropDown.classList.add('active')
            dropDown.classList.add('active')
        }
    }

    const addEventForm = document.querySelector('.new-event-form')

    function showAddEventForm() {
        if (addEventForm.classList.contains("active")) {
            addEventForm.classList.remove('active')
            dropDown.classList.remove('active')

        } else {
            addEventForm.classList.add('active')
            dropDown.classList.add('active')
        }
    }
</script>

<style>
    * {
        margin: 0;
        padding: 0;

        box-sizing: border-box;

        font-family: 'Inter', sans-serif;
    }

    body {

        margin-bottom: 10px;
    }

    a {
        font-weight: bolder;
    }


    h2 {
        font-size: 18px;
    }

    .messages {
        text-align: center;
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


    .new-event-form {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: space-around;
        gap: 15px;
        border: none;
        width: 400px;
        margin: 20px auto;

        padding: 25px;

        border-radius: 4px;

        border: 1px solid rgba(0, 0, 0, 0.299);

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
        border: 1px solid rgba(0, 0, 0, 0.335);
        width: 400px;
        margin: 20px auto;

        padding: 25px;
        background: white;

        border-radius: 4px;
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

        border: 1px solid rgba(0, 0, 0, 0.26);
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        gap: 7px;
        margin: 10px 0px;
        width: 400px;
        height: 400px;

        transition: .5s;
        padding-bottom: 14px;
    }

    .event-img-container {
        width: 400px;
        height: 250px;

        border-bottom: 1px solid black;
    }

    .event-img-container img {
        width: 400px;
        height: 250px;
    }


    input {
        width: 300px;
        height: 35px;
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

    #img-input-label {
        border: 1px solid #ccc;
        display: flex;
        flex-direction: column;
        padding: 6px 25px;
        cursor: pointer;
        text-align: center
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
        height: 85px;
    }

    .uis.uis-triangle {
        position: absolute;
        margin-top: -23px;
        margin-left: -20px;
        font-size: white;
        color: white;
    }

    .myConfirmedsBtn {
        border: none;
        transition: 0s;
        white-space: nowrap;
        font-size: 16px;
    }

    .myConfirmedsBtn:hover {
        transform: none;
    }

    .description-event {
        padding: 5px 24px;

        display: flex;
        flex-direction: column;
        gap: 5px;

    }

    .description-event h2 {
        font-weight: lighter;
        font-size: 15px;
    }
</style>
