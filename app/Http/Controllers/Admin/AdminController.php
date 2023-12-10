<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminController extends Controller
{
    use ApiResponser;

    public function dashboard(): View
    {
        return view('admin.dashboard');
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->input();

            if (Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
                return redirect('admin/dashboard');
            } else {
                return redirect()->back()->with('error', 'invalid identification');
            }
        }

        return view('admin.login');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect('admin/login');
    }

    public function users(Request $request)
    {
        $input = $request->input();
        $users = null;
        if (isset($input['search'])) {
            $keyword = $input['search'];
            $users = User::where('email', 'like', "%{$keyword}%")
                ->orWhere('name', 'like', "%{$keyword}%")
                ->orWhere('id', 'like', "%{$keyword}%")
                ->withTrashed()
                ->get();
        }

        if ($users === null) {
            $users = User::withTrashed()->get();
        }

        return view('admin.users', ['users' => $users]);
    }

    public function userUpdate(Request $request)
    {
        $input = $request->input();
        $name = $input['name'];
        $role = $input['role'];
        $id = $input['id'];
        $count = User::where('name', $name)->count();
        if ($count === 0) {
            $user = User::where('id', $id)->firstOrFail();
            $user->role = $role;
            $user->name = $name;
            $user->save();

            return $this->showMessage("success");
        }

        return $this->error("failed");
    }

    public function userDelete(Request $request)
    {
        $input = $request->input();
        $id = $input['id'];

        $user = User::where('id', $id)->firstOrFail();
        $user->delete();

        return $this->showMessage("success");
    }
}
