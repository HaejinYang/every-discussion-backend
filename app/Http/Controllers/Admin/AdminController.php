<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Opinion;
use App\Models\Topic;
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

    public function topics(Request $request)
    {
        $input = $request->input();
        $topics = null;
        if (isset($input['search'])) {
            $keyword = $input['search'];
            $users = Topic::where('title', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%")
                ->withTrashed()
                ->get();
        }

        if ($topics === null) {
            $topics = Topic::withTrashed()->get();
        }

        return view('admin.topics', ['topics' => $topics]);
    }

    public function topicUpdate(Request $request)
    {
        $input = $request->input();
        $title = $input['title'];
        $description = $input['description'];
        $id = $input['id'];
        $count = Topic::where('title', $title)->count();
        if ($count === 0) {
            $topic = Topic::where('id', $id)->firstOrFail();
            $topic->title = $title;
            $topic->description = $description;
            $topic->save();

            return $this->showMessage("success");
        }

        return $this->error("failed");
    }

    public function topicDelete(Request $request)
    {
        $input = $request->input();
        $id = $input['id'];

        $topic = Topic::where('id', $id)->firstOrFail();
        $topic->delete();

        return $this->showMessage("success");
    }

    public function opinions(Request $request)
    {
        $input = $request->input();
        $opinions = null;
        if (isset($input['search'])) {
            $keyword = $input['search'];
            $opinions = Opinion::where('title', 'like', "%{$keyword}%")
                ->orWhere('content', 'like', "%{$keyword}%")
                ->withTrashed()
                ->get();
        }

        if ($opinions === null) {
            $opinions = Opinion::withTrashed()->get();
        }

        return view('admin.opinions', ['opinions' => $opinions]);
    }

    public function opinionUpdate(Request $request)
    {
        $input = $request->input();
        $title = $input['title'];
        $content = $input['content'];
        $id = $input['id'];

        $opinion = Opinion::where('id', $id)->firstOrFail();
        $opinion->title = $title;
        $opinion->content = $content;
        $opinion->save();

        return $this->showMessage("success");
    }

    public function opinionDelete(Request $request)
    {
        $input = $request->input();
        $id = $input['id'];

        $opinion = Opinion::where('id', $id)->firstOrFail();
        $opinion->delete();

        return $this->showMessage("success");
    }
}
