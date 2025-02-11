<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Admin extends Model
{

    use HasFactory;
    
    protected $table = 'admin';
    protected $primaryKey = 'admin_id';  // Primary key adalah admin_id
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['admin_username', 'admin_password'];

    // Get all admin data
    public static function getAllAdmin()
    {
        return self::all();
    }

    // Get admin by ID
    public static function getAdminById($id)
    {
        return self::where('admin_id', $id)->first();  // Menggunakan admin_id sebagai kolom pencarian
    }

    // Create a new admin
    public static function createAdmin($data)
    {
        $data['admin_password'] = Hash::make($data['admin_password']);
        return self::create($data);
    }

    // Update an admin
    public static function updateAdmin($id, $data)
    {
        $admin = self::where('admin_id', $id)->first();
        if ($admin) {
            $admin->update($data);
        }
        return $admin;
    }

    // Delete an admin
    public static function deleteAdmin($id)
    {
        $admin = self::where('admin_id', $id)->first();
        if ($admin) {
            $admin->delete();
        }
        return $admin;
    }
}