<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::get();
        return view('user.index', compact('users'));
    }

    public function form($id = null)
    {
        if(!is_null($id)){
            if($id){
                $user = User::find($id);
                session()->flashInput(array_merge($user->toArray(), old()));
            }else{
                session()->flashInput(old());
            }

            $action = route('user.update', $id);
            $method = 'PUT';
        }else{
            $action = route('user.store');
            $method = 'POST';
        }

        return view('user.form', compact('action', 'method'));
    }

    public function create()
    {
        return $this->form();
    }

    public function edit($id)
    {
        return $this->form($id);
    }

    public function save($id = null)
    {
        if($id){
            $user = User::find($id);
        }else{
            $user = new User;
        }

        $user->name     = request()->input('name');
        $user->email    = request()->input('email');
        $user->password = bcrypt(request()->input('password'));

        $user->save();

        return redirect()->route('user.index');
    }

    public function store()
    {
        return $this->save();
    }

    public function update($id)
    {
        return $this->save($id);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if($user){
            $user->delete();
            return redirect()->route('user.index');;
        }
    }
}
