<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Profession;
use App\Skill;
use App\User;
use App\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->with('team', 'skills', 'profile.profession')
            ->when(request('team'), function ($query, $team) {
                if ($team === 'with_team') {
                    $query->has('team');
                } elseif ($team === 'without_team') {
                    $query->doesntHave('team');
                }
            })
            ->search(request('search'))
            ->orderBy('created_at', 'DESC')
            ->paginate();

        $users->appends(request(['search', 'team']));

        $title = 'Usuarios';

        return view('users.index')->with(compact('users', 'title'));
    }

    public function trashed()
    {
        $users = User::onlyTrashed()->paginate();

        $title = 'Listado de usuarios en la papelera';

        return view('users.index', compact('users', 'title'));
    }

    public function create()
    {
        return $this->form('users.create', new User);
    }

    public function store(CreateUserRequest $request)
    {
        $request->createUser();

        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        if ($user == null) {
            return response()->view('errors.404', [], 404);
        }

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return $this->form('users.edit', $user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $request->updateUser($user);

        return redirect()->route('users.show', $user);
    }

    public function trash(User $user)
    {
        $user->profile()->delete();
        $user->delete();

        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        $user = User::onlyTrashed()->where('id', $id)->firstOrFail();

        $user->forceDelete();

        return redirect()->route('users.trashed');
    }

    protected function form($view, User $user)
    {
        return view($view, [
            'user' => $user,
            'professions' => Profession::orderBy('title', 'ASC')->get(),
            'skills' => Skill::orderBy('name', 'ASC')->get(),
            'roles' => trans('users.roles')
        ]);
    }
}
