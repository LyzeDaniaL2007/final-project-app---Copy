<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyewaanDetail extends Model
{
    protected $table = 'penyewaan_detail';
    protected $primaryKey = 'penyewaan_detail_id';
    protected $fillable = [
        'penyewaan_detail_penyewaan_id',
        'penyewaan_detail_alat_id',
        'penyewaan_detail_jumlah',
        'penyewaan_detail_subharga',
    ];

    public function penyewaan()
    {
        return $this->belongsTo(Penyewaan::class, 'penyewaan_id', 'penyewaan_id');
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id', 'alat_id');
    }

    public static function createPenyewaanDetail($data)
    {
        return self::create($data);
    }

    public static function getAllPenyewaanDetail()
    {
        return self::all();
    }

    public static function getPenyewaanDetailById($id)
    {
        return self::find($id);
    }

    public static function updatePenyewaanDetail($id, $data)
    {
        $penyewaanDetail = self::find($id);
        if ($penyewaanDetail) {
            $penyewaanDetail->update($data);
            return $penyewaanDetail;
        }
        return null;
    }

    public static function deletePenyewaanDetail($id)
    {
        $penyewaanDetail = self::find($id);
        if ($penyewaanDetail) {
            $penyewaanDetail->delete();
            return $penyewaanDetail;
        }
        return null;
    }
}
