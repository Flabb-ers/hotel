<?php


namespace App\Exports;

use App\Models\Tamu;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

// class GuestsExport implements FromCollection
class GuestsExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $search;
    protected $tanggal;

    public function __construct($search, $tanggal)
    {
        // return Tamu::all();
        $this->search = $search;
        $this->tanggal = $tanggal;
    }

    public function query()
    {
        $query = Tamu::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('no_telp', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->tanggal) {
            $query->whereDate('created_at', $this->tanggal);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Lengkap',
            'Email',
            'No. Telepon',
            'Tanggal Daftar',
        ];
    }

    public function map($tamu): array
    {
        return [
            $tamu->id_tamu,
            $tamu->nama_lengkap,
            $tamu->email,
            $tamu->no_telp,
            $tamu->created_at->format('d-m-Y H:i:s'),
        ];
    }
}
