<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\confirmedLists;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

use function GuzzleHttp\Promise\all;

class EventController extends Controller
{

    public function store(Request $request){

        //Função que adiciona eventos

        $event = new Event;

        $event->title = $request->title;
        $event->description = $request->description;
        $event->date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;

        //Adicionou os dados do Request a um novo evento

        if($request->hasFile('image') && $request->file('image')->isValid()){ //Verifica o input de imagem
            $requestImage = $request->image;


            $request->hasFile('image');
            $extension = $requestImage->extension();


            $imageName = md5($requestImage->getClientOriginalName().strtotime("now")).'.'.$extension;

            $requestImage->move(public_path('img/events'), $imageName);

            $event->image = $imageName;

        }

        $user = auth()->user();
        $event->user_id = $user->id;

        //Adiciona o nome do criador do evento ao usuário logado

        $event->save();

        return redirect('/')->with('message', 'Event created successfully '); //Redireciona a homepage com um aviso de sucessso

    }

    public function index(){

        //Rota raiz que lista todos os dados da página inicial

        $currentUser = auth()->user();

        $users = User::all();
        $list = confirmedLists::where('user', '=', $currentUser->id )->get();
        $eventsPerUser = [];



        foreach($list as $arr){
            $eventsPerUser[] = $arr->event;
        }

        $search = request('search');

        //Funcionalidade de busca na pagina principal
        if($search){

          $events = Event::
          where([ ['title', 'like', '%'.$search.'%']])
          ->orWhere('description', 'like', '%'.$search.'%')
          ->get();
            //Verifica se há eventos com o título ou com a descrição preenchida no campo de busca
        }
        else{

            $events = Event::all();
            //Se não for buscado nada ele lista todos os eventos
        }


        return view ('welcome', [
            'events' => $events,
            'search' => $search,
            'currentUser' => $currentUser,
            'users' => $users,
            'list' => $list,
            'eventsPerUser' => $eventsPerUser,
        ]);

    }

    public function destroy($id){
        //Função que deleta evento


        //Recebendo o id, ele deleta o evento na tabela que tenha o id recebido na URL
        confirmedLists::where('event', '=', (int) $id)->delete();

        Event::findOrFail($id)->delete();

        return redirect('/')->with('message', 'Event deleted successfully');

        //Redireciona para a homepage com a mensagem de sucesso
    }

    public function update($id, Request $request){

        //Atualiza os dados do evento resgatado pelo id delete


        $event = Event::findOrFail($id);

        $event->title = ($request->title ?? $event->title);
        $event->description = ($reqloginuest->description ?? $event->description);
        $event->city = ($request->city ?? $event->city);
        $event->private = $request->private;

        //Substitui os dados do evento pelos do Request, se o campo não for preenchido ele mantém o mesmo

        $event->date = ($request->date ?? $event->date);

        $event->save();

        return redirect('/')->with('message', 'event edited successfully');

        //Salve e redireciona



    }

    public function myEvents($id){

        //Lista os eventos criados pelo usuário

        $currentUser = auth()->user();


        $events = Event::where('user_id', '=', $currentUser->id)->get();

        if(count($events) == 0){
            return redirect("/")->with('message', 'you do not have events');
        }


        $users = User::all();

        return view('myEvents', [
            'events' => $events,
            'users' => $users,
            'currentUser' => $currentUser,
        ]);
    }

    public function  editInMyEvents($user, $id){
        $currentUser = auth()->user();
        $events = Event::where('user_id', '=', $currentUser->id)->get();
        $users = User::all();

        return view('myEvents', [
            'events' => $events,
            'users' => $users,
            'currentUser' => $currentUser,
        ]);
    }

    public function confirm($id){

        $confirmed_list = new confirmedLists;

        $currentUser = auth()->user();

        $confirmed_list->user = $currentUser->id;
        $confirmed_list->event = $id;

        $confirmed_list->save();


        return redirect('/')->with('message', 'Presence confirmed');


    }

    public function cancel($id){

        $currentUser = auth()->user();

        confirmedLists::where('event', '=', $id, 'and', 'user', $currentUser->id)->delete();

        return redirect()->back();


    }


    public function list($id){

        $event = Event::findOrFail($id);

        $currentUser = auth()->user();

        $list = confirmedLists::where('event', '=', $id)->get('user');

        $users = User::whereIn('id', $list)->get();

        return view('list', [
            'userList' => $users,
            'event' => $event,
            'currentUser' => $currentUser,
        ]);


    }

    public function myConfirmedEvents($user){

        $myConfirmedEvents = confirmedLists::where('user', '=', $user)->get();

        $currentUser = auth()->user();


        if(count($myConfirmedEvents) !== 0){

            foreach ($myConfirmedEvents as $eventsIds)
            {
                $IDs[] = $eventsIds->event;
            }


            $events =  Event::whereIn('id', $IDs)->get();


        $users = User::all();

        return view('myConfirmedsEvents', [
            'events' => $events, 'currentUser' => $currentUser, 'users' => $users]);
        }else{
            return redirect('/')->with('message', 'You are not confirmed in any events');
        }


    }



}
