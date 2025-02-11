<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'kategori_id';
    public $timestamps = true;

    protected $fillable = [
        'kategori_nama'
    ];

    public function alat()
    {
        return $this->hasMany(Alat::class, 'alat_kategori_id', 'kategori_id');
    }

    // Custom Methods
    public static function getAllKategori()
    {
        return self::all();
    }

    public static function getKategoriById($id)
    {
        return self::find($id);
    }

    public static function createKategori($data)
    {
        return self::create($data);
    }

    public function updateKategori($id, $data)
    {
        $kategori = self::find($id);
        if ($kategori) {
            $kategori->update($data);
            return $kategori;
        }
        return null;
    }

    public function deleteKategori($id)
    {
        $kategori = self::find($id);
        if ($kategori) {
            $kategori->delete();
            return $kategori;
        }
        return null;
    }
}
