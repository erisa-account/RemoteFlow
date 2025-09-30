<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestService
{
    public function getAll(array $filters)
    {
        $query = User::query();

        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }
        if (!empty($filters['sort_by']) && !empty($filters['sort_order'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_order']);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = $filters['per_page'] ?? 15;

        return $query->paginate($perPage);
    }

    public function store(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}