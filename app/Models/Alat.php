<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $table = 'alat';
    protected $primaryKey = 'alat_id';
    protected $fillable = [
        'alat_kategori_id',
        'alat_nama',
        'alat_deskripsi',
        'alat_hargaperhari',
        'alat_stok',
    ];

    // Relasi ke model alat (Many to One)
    public function Kategori()
    {
        return $this->belongsTo(Kategori::class, 'alat_kategori_id', 'kategori_id');
    }

    // Relasi ke model PenyewaanDetail (One to Many)
    public function penyewaanDetail()
    {
        return $this->hasMany(PenyewaanDetail::class, 'penyewaan_detail_alat_id', 'alat_id');
    }

    public static function getAllalat()
    {
        return self::all();
    }

    public static function getalatById($id)
    {
        return self::find($id);
    }

    public static function createalat($data)
    {
        return self::create($data);
    }

    public function updatealat($id, $data)
    {
        $alat = self::find($id);
        if ($alat) {
            $alat->update($data);
            return $alat;
        }
        return null;
    }

    public function deletealat($id)
    {
        $alat = self::find($id);
        if ($alat) {
            $alat->delete();
            return $alat;
        }
        return null;
    }
}
