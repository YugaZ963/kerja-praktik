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

/**
 * Class InventoryExport
 *
 * Handles the export of inventory data to an Excel file.
 */
class InventoryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, ShouldAutoSize
{
    /**
     * The request instance.
     *
     * @var Request|null
     */
    protected $request;

    /**
     * The row number.
     *
     * @var int
     */
    protected $rowNumber = 0;

    /**
     * Create a new export instance.
     *
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * Get the collection of inventory items to be exported.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Inventory::query();
        
        if ($this->request) {
            if ($this->request->has('category') && $this->request->category) {
                $query->where('category', $this->request->category);
            }
            
            if ($this->request->has('status') && $this->request->status) {
                if ($this->request->status == 'low') {
                    $query->whereRaw('stock <= min_stock');
                } elseif ($this->request->status == 'out') {
                    $query->where('stock', 0);
                } elseif ($this->request->status == 'ready') {
                    $query->whereRaw('stock > min_stock');
                }
            }
            
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

            if ($this->request->has('search') && $this->request->search) {
                $search = $this->request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%")
                      ->orWhere('supplier', 'like', "%{$search}%");
                });
            }

            if ($this->request->has('size') && $this->request->size) {
                $size = $this->request->size;
                $query->where('sizes_available', 'like', "%{$size}%");
            }
        }
        
        return $query->orderBy('code')->get();
    }

    /**
     * Get the headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Item Code',
            'Item Name',
            'Category',
            'Current Stock',
            'Minimum Stock',
            'Stock Status',
            'Purchase Price (Rp)',
            'Selling Price (Rp)',
            'Margin (%)',
            'Stock Value (Purchase) (Rp)',
            'Stock Value (Selling) (Rp)',
            'Available Sizes',
            'Supplier',
            'Location',
            'Last Restock',
            'Description'
        ];
    }

    /**
     * Map the data for each row.
     *
     * @param mixed $inventory
     * @return array
     */
    public function map($inventory): array
    {
        $this->rowNumber++;
        
        $sizes = [];
        if (is_string($inventory->sizes_available)) {
            $sizes = json_decode($inventory->sizes_available, true) ?? [];
        } elseif (is_array($inventory->sizes_available)) {
            $sizes = $inventory->sizes_available;
        }
        
        $status = 'Ready';
        if ($inventory->stock == 0) {
            $status = 'Out of Stock';
        } elseif ($inventory->stock <= $inventory->min_stock) {
            $status = 'Low Stock';
        }
        
        $margin = 0;
        if ($inventory->purchase_price > 0) {
            $margin = (($inventory->selling_price - $inventory->purchase_price) / $inventory->purchase_price) * 100;
        }
        
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
     * Apply styles to the worksheet.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet): array
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();
        
        return [
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
            
            "A2:A{$lastRow}" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            "E2:F{$lastRow}" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            "G2:G{$lastRow}" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            "H2:L{$lastRow}" => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]],
        ];
    }

    /**
     * Get the column widths for the export.
     *
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 25,
            'D' => 15,
            'E' => 12,
            'F' => 12,
            'G' => 12,
            'H' => 15,
            'I' => 15,
            'J' => 10,
            'K' => 18,
            'L' => 18,
            'M' => 20,
            'N' => 20,
            'O' => 15,
            'P' => 15,
            'Q' => 30,
        ];
    }

    /**
     * Get the title of the worksheet.
     *
     * @return string
     */
    public function title(): string
    {
        return 'Inventory Report';
    }
}