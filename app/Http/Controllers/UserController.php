<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    //创建用户的页面
    public function create(){
        return view('users.create');
    }

    //创建用户
    public function store(Request $request){

        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');

        return redirect()->route('users.show', [$user]);

    }

    //编辑用户个人资料的页面
    public function edit(){

    }

    //更新用户
    public function update(){

    }

    //显示所有用户列表的页面
    public function index(){

    }

    //显示用户个人信息的页面
    public function show(User $user){
        return view('users.show', compact('user'));
    }

    //删除用户
    public function destroy(){

    }


}