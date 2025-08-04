<?php

namespace App\Exports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Http\Request;

class InventoryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, ShouldAutoSize
{
    protected $request;
    protected $rowNumber = 0;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Inventory::query();
        
        // Apply filters if request is provided
        if ($this->request) {
            // Filter berdasarkan kategori
            if ($this->request->has('category') && $this->request->category) {
                $query->where('category', $this->request->category);
            }
            
            // Filter berdasarkan status stok
            if ($this->request->has('status') && $this->request->status) {
                if ($this->request->status == 'low') {
                    $query->whereRaw('stock <= min_stock');
                } elseif ($this->request->status == 'out') {
                    $query->where('stock', 0);
                } elseif ($this->request->status == 'ready') {
                    $query->whereRaw('stock > min_stock');
                }
            }
            
            // Filter berdasarkan periode
            if ($this->request->has('period') && $this->request->period) {
                switch ($this->request->period) {
                    case 'today':
                        $query->whereDate('updated_at', today());
                        break;
                    case 'week':
                        $query->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;
                    case 'month':
                        $query->whereMonth('updated_at', now()->month)
                              ->whereYear('updated_at', now()->year);
                        break;
                    case 'year':
                        $query->whereYear('updated_at', now()->year);
                        break;
                }
            }

            // Filter berdasarkan search
            if ($this->request->has('search') && $this->request->search) {
                $search = $this->request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%")
                      ->orWhere('supplier', 'like', "%{$search}%");
                });
            }

            // Filter berdasarkan ukuran
            if ($this->request->has('size') && $this->request->size) {
                $size = $this->request->size;
                $query->where('sizes_available', 'like', "%{$size}%");
            }
        }
        
        return $query->orderBy('code')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Kode Item',
            'Nama Item',
            'Kategori',
            'Stok Saat Ini',
            'Minimum Stok',
            'Status Stok',
            'Harga Beli (Rp)',
            'Harga Jual (Rp)',
            'Margin (%)',
            'Nilai Stok Beli (Rp)',
            'Nilai Stok Jual (Rp)',
            'Ukuran Tersedia',
            'Supplier',
            'Lokasi',
            'Terakhir Restock',
            'Deskripsi'
        ];
    }

    /**
     * @param mixed $inventory
     * @return array
     */
    public function map($inventory): array
    {
        $this->rowNumber++;
        
        // Decode sizes_available
        $sizes = [];
        if (is_string($inventory->sizes_available)) {
            $sizes = json_decode($inventory->sizes_available, true) ?? [];
        } elseif (is_array($inventory->sizes_available)) {
            $sizes = $inventory->sizes_available;
        }
        
        // Calculate status
        $status = 'Siap';
        if ($inventory->stock == 0) {
            $status = 'Habis';
        } elseif ($inventory->stock <= $inventory->min_stock) {
            $status = 'Stok Rendah';
        }
        
        // Calculate margin
        $margin = 0;
        if ($inventory->purchase_price > 0) {
            $margin = (($inventory->selling_price - $inventory->purchase_price) / $inventory->purchase_price) * 100;
        }
        
        // Calculate stock values
        $stockValuePurchase = $inventory->stock * $inventory->purchase_price;
        $stockValueSelling = $inventory->stock * $inventory->selling_price;
        
        return [
            $this->rowNumber,
            $inventory->code,
            $inventory->name,
            $inventory->category,
            $inventory->stock,
            $inventory->min_stock,
            $status,
            $inventory->purchase_price,
            $inventory->selling_price,
            round($margin, 2),
            $stockValuePurchase,
            $stockValueSelling,
            is_array($sizes) && !empty($sizes) ? implode(', ', $sizes) : '-',
            $inventory->supplier,
            $inventory->location,
            $inventory->last_restock,
            $inventory->description ?? '-'
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();
        
        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
            
            // All data styling
            "A1:{$lastColumn}{$lastRow}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ],
            
            // Number columns alignment
            "A2:A{$lastRow}" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // No
            "E2:F{$lastRow}" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Stock columns
            "G2:G{$lastRow}" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Status
            "H2:L{$lastRow}" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]], // Price columns
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 15,  // Kode
            'C' => 25,  // Nama
            'D' => 15,  // Kategori
            'E' => 12,  // Stok
            'F' => 12,  // Min Stok
            'G' => 12,  // Status
            'H' => 15,  // Harga Beli
            'I' => 15,  // Harga Jual
            'J' => 10,  // Margin
            'K' => 18,  // Nilai Beli
            'L' => 18,  // Nilai Jual
            'M' => 20,  // Ukuran
            'N' => 20,  // Supplier
            'O' => 15,  // Lokasi
            'P' => 15,  // Last Restock
            'Q' => 30,  // Deskripsi
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Laporan Inventaris';
    }
}