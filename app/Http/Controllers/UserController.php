<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\DataTables\UsersDataTable;

class UserController extends Controller
{
    public function index()
    {
        $userLogin = Auth::user();
        return view('pages.user.index', ['user' => $userLogin,]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
        ];

        if ($request->email !== $user->email) {
            $rules['email'] = [
                'required',
                'max:255',
                'email',
                'unique:users,email'
            ];

            // Update Email
            $user->email = $request->email;
        } else {
            $rules['email'] = [
                'required',
                'max:255',
                'email',
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        $user->name = $request->name;
        /** @var \App\Models\User $user **/
        $user->save();

        return response()->json([
            'message' => 'Berhasil memperbaruhi profile',
            'name' => $request->name
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Check password matches
        if (!(Hash::check($request->current_password, $user->password))) {
            $errors = [
                'current_password' => ['Password yang anda masukan tidak sesuai dengan password saat ini !']
            ];

            return response()->json(['errors' => $errors], 422);
        }

        // Check new password with current password is same
        if (strcmp($request->current_password, $request->password) == 0) {
            $errors = [
                'password' => ['Password yang baru tidak boleh sama dengan password yang lama !']
            ];

            return response()->json(['errors' => $errors], 422);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        $user->password = Hash::make($request->password);

        /** @var \App\Models\User $user **/
        $user->save();

        return response()->json(['message' => 'Berhasil memperbaruhi password'], 200);
    }

    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|max:1024'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        $photo = "profile_" . uniqid() . "." . $request->photo->extension();
        $request->file('photo')->move(public_path('profile/' . str_replace('/', '-', Auth::user()->id)), $photo);

        User::where('id', Auth::user()->id)->update(['url_photo' => $photo]);

        return response()->json(['message' => 'Berhasil memperbaruhi password', 'photo' => 'profile/' . Auth::user()->id . '/' . $photo], 200);
    }

    public function createUser(UsersDataTable $datatable)
    {
        // $roles = Role::get()->skip(1);
        $roles = Role::get();
        return $datatable->render('pages.user.new', [
            'roles' => $roles
        ]);
    }

    public function saveCreateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        $role = Role::where('id', $request->role)->first();
        $roleName = $role->name;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])->assignRole($roleName);

        return response()->json(['message' => 'Pembuatan akun berhasil'], 200);
    }

}
