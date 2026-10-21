<?php

namespace App\Jobs;

use App\Models\ImportError;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProcessProductImport implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public array $backoff = [10, 60];

    public int $timeout = 60;

    public function __construct(private string $csvPath, private string $batch) {}

    public function handle(): void
    {
        // Kalau job diulang (retry), buang error dari percobaan sebelumnya
        ImportError::where('batch', $this->batch)->delete();

        $lines = file(Storage::path($this->csvPath), FILE_IGNORE_NEW_LINES);
        $header = str_getcsv(array_shift($lines) ?? '');
        $rowNumber = 1; // baris 1 adalah header

        foreach ($lines as $line) {
            $rowNumber++;

            if (trim($line) === '') {
                continue;
            }

            $row = str_getcsv($line);

            if (count($row) !== count($header)) {
                $this->recordError(
                    $rowNumber,
                    $line,
                    'Jumlah kolom '.count($row).', seharusnya '.count($header).'.'
                );
                continue;
            }

            $validator = Validator::make(array_combine($header, $row), [
                'name' => ['required', 'string'],
                'category_id' => ['required', 'exists:categories,id'],
                'price' => ['required', 'integer', 'min:0'],
            ]);

            if ($validator->fails()) {
                $this->recordError($rowNumber, $line, implode(' ', $validator->errors()->all()));
                continue;
            }

            $data = $validator->validated();

            Product::updateOrCreate(
                ['name' => $data['name'], 'category_id' => $data['category_id']],
                ['price' => $data['price']],
            );
        }

        Storage::delete($this->csvPath);
    }

    private function recordError(int $rowNumber, string $rowData, string $message): void
    {
        ImportError::create([
            'batch' => $this->batch,
            'row_number' => $rowNumber,
            'row_data' => $rowData,
            'message' => $message,
        ]);
    }
}
