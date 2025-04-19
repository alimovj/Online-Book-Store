<?php
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // GET: /api/users
    public function index(Request $request)
    {
        $users = User::withCount(['books', 'orders'])->paginate(10);
        return UserResource::collection($users);
    }

    // GET: /api/users/{id}
    public function show($id)
    {
        $user = User::with(['books', 'orders'])->findOrFail($id);
        return new UserResource($user);
    }

    // POST: /api/users
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        return (new UserResource($user))->additional(['message' => 'Foydalanuvchi yaratildi']);
    }

    // PUT: /api/users/{id}
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());
        return (new UserResource($user))->additional(['message' => 'Foydalanuvchi yangilandi']);
    }

    // DELETE: /api/users/{id}
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'Foydalanuvchi o‘chirildi']);
    }
}
