<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\BackLog;

class BackLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $query = BackLog::query();

// 検索フィルタリング
        if ($search) {
            // スペースで検索ワードを分割
            $keywords = preg_split('/\s+/', trim($search)); // 空白で分割

            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->where(function ($subQ) use ($keyword) {
                        $subQ->where('CustomerCode', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('ProductCode', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('JanCode', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('ManufacturerName', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('ProductName', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('CustomerName', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('OrderDate', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('InvoiceNumber', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('SalesCourse', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('SalesRepresentative', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('SalesCategory', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('LastUpdateDate', 'LIKE', '%' . $keyword . '%');
                    });
                }
            });
        }

        $backlogs = $query->paginate(50)->appends(['search' => $search]);
        return view('backlogs.index', compact('backlogs', 'search'));
    }

    /**
     * Show the form for uploading a CSV file.
     */
    public function showUploadForm()
    {
        return view('backlogs.upload');
    }

    /**
     * CSVアップロード処理
     */
    public function upload(Request $request)
    {
        Log::info('CSVアップロード処理開始');

        // バリデーション
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:512000',
        ]);
        Log::info('ファイルバリデーション成功');

        // ファイル取得
        $file = $request->file('file');
        if (!$file) {
            Log::error('ファイルがアップロードされていません。');
            return back()->with('error', 'ファイルがアップロードされていません。');
        }

        $filePath = $file->getRealPath();
        Log::info('ファイル取得成功: ' . $filePath);

        try {
            // ファイルの読み込み
            $fileContent = file_get_contents($filePath);
            if ($fileContent === false) {
                Log::error('ファイルの読み込みに失敗しました。');
                return back()->with('error', 'ファイルの読み込みに失敗しました。');
            }

            Log::info('ファイルの読み込み成功');

            // エンコーディング変換
            $fileContent = mb_convert_encoding($fileContent, 'UTF-8', 'SJIS-win');
            $tempFilePath = tempnam(sys_get_temp_dir(), 'csv');
            file_put_contents($tempFilePath, $fileContent);
            Log::info('エンコーディング変換成功');

            // CSVファイルのオープン
            $fileStream = fopen($tempFilePath, 'r');
            if (!$fileStream) {
                Log::error('CSVファイルを開けませんでした。');
                return back()->with('error', 'CSVファイルを開けませんでした。');
            }

            // ヘッダー取得
            $header = fgetcsv($fileStream);
            if (!$header || count($header) < 10) {
                Log::error('CSVファイルのヘッダーが読み取れませんでした。ヘッダー内容: ' . json_encode($header));
                return back()->with('error', 'CSVファイルのヘッダーが読み取れませんでした。');
            }
            Log::info('CSVヘッダー: ' . implode(',', $header));

            $batchSize = 1000;
            $batch = [];
            $lineNumber = 0;

            DB::beginTransaction();
            while (($row = fgetcsv($fileStream)) !== false) {
                $lineNumber++;
                Log::info("処理中の行 {$lineNumber}: " . json_encode($row));

                $row = array_pad($row, 24, null);

                if (!isset($row[16]) || !isset($row[17])) {
                    Log::error("CSVデータに InvoiceNumber または LineNumber がありません（行 {$lineNumber}）: " . json_encode($row));
                    continue;
                }

                $checkUnique = $row[16] . '_' . $row[17];
                $exists = DB::table('BackLog')->where('InvoiceNumber', $row[16])->where('LineNumber', $row[17])->exists();

                if (!$exists) {
                    $batch[] = [
                        'CustomerCode' => $row[0] ?? null,
                        'BranchCode' => $row[1] ?? null,
                        'ProductCode' => $row[2] ?? null,
                        'JanCode' => $row[3] ?? null,
                        'ManufacturerName' => $row[4] ?? null,
                        'ProductName' => $row[5] ?? null,
                        'Specification' => $row[6] ?? null,
                        'Quantity' => $row[7] ?? null,
                        'Unit' => $row[8] ?? null,
                        'OrderQuantity' => $row[9] ?? null,
                        'RemainingOrderQty' => $row[10] ?? null,
                        'OutOfStockQty' => $row[11] ?? null,
                        'SalesUnitPrice' => $row[12] ?? null,
                        'SalesAmount' => $row[13] ?? null,
                        'CustomerName' => $row[14] ?? null,
                        'OrderDate' => $row[15] ?? null,
                        'InvoiceNumber' => $row[16] ?? null,
                        'LineNumber' => $row[17] ?? null,
                        'SalesCourse' => $row[18] ?? null,
                        'SalesRepresentative' => $row[19] ?? null,
                        'SalesCategory' => $row[20] ?? null,
                        'LastUpdateDate' => $row[21] ?? null,
                        'UpdateCount' => $row[22] ?? null,
                    ];
                }

                if (count($batch) >= $batchSize) {
                    Log::info("バッチデータ登録開始: " . count($batch) . "件");
                    DB::table('BackLog')->insert($batch);
                    Log::info("データ登録成功: " . count($batch) . "件");
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                DB::table('BackLog')->insert($batch);
            }

            Log::info('データベーストランザクションをコミットします');
            DB::commit();
            Log::info('コミット成功');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CSVアップロードエラー: ' . $e->getMessage());
            return back()->with('error', 'CSVアップロード中にエラーが発生しました: ' . $e->getMessage());
        }

        return redirect()->route('backlogs.index')->with('success', 'CSVが正常にアップロードされました！');
    }

}
