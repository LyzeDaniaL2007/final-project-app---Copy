<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $primaryKey = 'pelanggan_id';
    protected $fillable = [
        'pelanggan_nama',
        'pelanggan_alamat',
        'pelanggan_notelp',
        'pelanggan_email'
    ];

    public function pelangganData()
    {
        return $this->hasOne(PelangganData::class, 'pelanggan_data_pelanggan_id', 'pelanggan_id');
    }
    
    // Relasi one-to-many dengan penyewaan
    public function penyewaan()
    {
        return $this->hasMany(Penyewaan::class, 'penyewaan_pelanggan_id', 'pelanggan_id');
    }

    // Method statis untuk operasi CRUD
    public static function createPelanggan($data)
    {
        return self::create($data);
    }

    public static function getAllPelanggan()
    {
        return self::all();
    }

    public static function getPelangganById($id)
    {
        return self::find($id);
    }

    public static function updatePelanggan($id, $data)
    {
        $pelanggan = self::find($id);
        if ($pelanggan) {
            $pelanggan->update($data);
            return $pelanggan;
        }
        return null;
    }

    public static function deletePelanggan($id)
    {
        $pelanggan = self::find($id);
        if ($pelanggan) {
            $pelanggan->delete();
            return $pelanggan;
        }
        return null;
    }
}
