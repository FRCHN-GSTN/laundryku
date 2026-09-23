<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name', 'email', 'password', 'phone', 'address', 'role', 'is_active'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public const ROLES = [
        'admin'    => 'Admin',
        'staff'    => 'Staff',
        'customer' => 'Customer',
    ];

    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function getCustomers()
    {
        return $this->where('role', 'customer')->findAll();
    }

    public function getAllUsers($search = '', $role = '')
    {
        $builder = $this->builder();

        if ($search !== '') {
            $builder->groupStart();
            $builder->like('name', $search);
            $builder->orLike('email', $search);
            $builder->orLike('phone', $search);
            $builder->groupEnd();
        }

        if ($role !== '') {
            $builder->where('role', $role);
        }

        $builder->orderBy('created_at', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function isAdmin($userId)
    {
        $user = $this->find($userId);
        return $user && $user['role'] === 'admin';
    }
}
