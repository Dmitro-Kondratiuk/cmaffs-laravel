<?php

namespace App\Services;

use App\Models\User;

class UsersService
{
    const PER_PAGE = 6;

    public function register($data) {
        $newUser           = new User();
        $newUser->name     = htmlspecialchars($data['name']);
        $newUser->email    = htmlspecialchars($data['email']);
        $newUser->password = bcrypt($data['password']);
        if (isset($data['role']) && $data['role'] == 'admin') {
            $newUser->role = $data['role'];
        }
        else {
            $newUser->role = 'user';
        }
        $newUser->save();

        return $newUser;
    }

    public function getUsers($page): array {
        $products = User::skip(($page - 1) * self::PER_PAGE)
            ->take(self::PER_PAGE)
            ->get();

        $totalProducts = User::all()->count();


        $countPages = ceil($totalProducts / self::PER_PAGE);

        return ['users' => $products, 'countPages' => $countPages];
    }

    public function getUserById($id) {

        return User::where('id', $id)->first();
    }

    public function updateUser($data): void {
        User::where(['id' => $data['id']])->update($data);
    }

    public function deleteUser($id): void {
        User::destroy($id);
    }
}
