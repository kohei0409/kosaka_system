<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OrderData;

class OrderDataController extends Controller
{
    use \App\Traits\BulkDeletable;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $search = $request->input('search', '');

        $query = OrderData::query();

// 検索フィルタリング
        if ($search) {
            // スペースでキーワードを分割（全角スペース・半角スペース対応）
            $keywords = preg_split('/\s+/', trim($search));

            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->where(function ($subQ) use ($keyword) {
                        $subQ->where('CustomerCode', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('ProductCode', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('JANCode', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('ManufacturerName', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('ProductName', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('Specification', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('CustomerName', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('SalesDate', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('InvoiceNumber', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('SalesCourse', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('SalesRepresentativeName', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('SalesCategory', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('UpdateDate', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('CheckUnique', 'LIKE', '%' . $keyword . '%');
                    });
                }
            });
        }


        $orders = $query->paginate(50)->appends(['search' => $search]);
        return view('orderdata.index', compact('orders', 'search'));
    }

    /**
     * Show the form for uploading a CSV file.
     */
    public function showUploadForm()
    {
        return view('orderdata.upload'); // orderdata/upload.blade.php というビューを表示
    }

    /**
     * CSVアップロード処理
     */
    public function upload(Request $request)
    {
        // バリデーション
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:512000', // 最大500MB
        ]);

        // ファイル取得
        $file = $request->file('file');
        $filePath = $file->getRealPath();

        // PHPの最大実行時間を延長
        ini_set('max_execution_time', 0); // 無制限
        ini_set('memory_limit', '2000M'); // 必要に応じて増加

        try {
            // ファイルのエンコーディング変換
            $fileContent = file_get_contents($filePath);
            $fileContent = mb_convert_encoding($fileContent, 'UTF-8', 'SJIS-win');
            $tempFilePath = tempnam(sys_get_temp_dir(), 'csv');
            file_put_contents($tempFilePath, $fileContent);

            $fileStream = fopen($tempFilePath, 'r');
            if (!$fileStream) {
                return back()->with('error', 'ファイルを開けませんでした。');
            }

            $header = fgetcsv($fileStream); // ヘッダー行をスキップ

            $batchSize = 1000; // バッチサイズ
            $batch = [];

            DB::beginTransaction();
            while (($row = fgetcsv($fileStream)) !== false) {
                $row = array_pad($row, 25, null); // 列数を補正

                // CheckUnique列の生成
                $checkUnique = $row[16] . '_' . $row[17]; // InvoiceNumber (6番目の列) と LineNumber (9番目の列) を結合

                // 重複データの確認
                $exists = DB::table('OrderData')->where('CheckUnique', $checkUnique)->exists();

                if (!$exists) {
                    // 重複がない場合はバッチに追加
                    $batch[] = [
                        'CustomerCode' => $row[0] ?? null,
                        'BranchCode' => $row[1] ?? null,
                        'ProductCode' => $row[2] ?? null,
                        'JANCode' => $row[3] ?? null,
                        'ManufacturerName' => $row[4] ?? null,
                        'ProductName' => $row[5] ?? null,
                        'Specification' => $row[6] ?? null,
                        'Quantity' => $row[7] ?? null,
                        'Unit' => $row[8] ?? null,
                        'TotalUnits' => $row[9] ?? null,
                        'SalesUnitPrice' => $row[10] ?? null,
                        'SalesAmount' => $row[11] ?? null,
                        'LotNumber' => $row[12] ?? null,
                        'SerialNumber' => $row[13] ?? null,
                        'CustomerName' => $row[14] ?? null,
                        'SalesDate' => $row[15] ?? null,
                        'InvoiceNumber' => $row[16] ?? null,
                        'LineNumber' => $row[17] ?? null,
                        'SalesCourse' => $row[18] ?? null,
                        'SalesRepresentativeName' => $row[19] ?? null,
                        'SalesCategory' => $row[20] ?? null,
                        'UpdateDate' => $row[21] ?? null,
                        'UpdateCount' => $row[22] ?? null,
                        'InvalidCategory' => $row[23] ?? null,
                        'CheckUnique' => $checkUnique, // 結合した値を保存
                    ];
                } else {
                    // 重複している場合は更新する（必要に応じて）
                    DB::table('OrderData')->where('CheckUnique', $checkUnique)->update([
                        'CustomerCode' => $row[0] ?? null,
                        'BranchCode' => $row[1] ?? null,
                        'ProductCode' => $row[2] ?? null,
                        'JANCode' => $row[3] ?? null,
                        'ManufacturerName' => $row[4] ?? null,
                        'ProductName' => $row[5] ?? null,
                        'Specification' => $row[6] ?? null,
                        'Quantity' => $row[7] ?? null,
                        'Unit' => $row[8] ?? null,
                        'TotalUnits' => $row[9] ?? null,
                        'SalesUnitPrice' => $row[10] ?? null,
                        'SalesAmount' => $row[11] ?? null,
                        'LotNumber' => $row[12] ?? null,
                        'SerialNumber' => $row[13] ?? null,
                        'CustomerName' => $row[14] ?? null,
                        'SalesDate' => $row[15] ?? null,
                        'SalesCourse' => $row[18] ?? null,
                        'SalesRepresentativeName' => $row[19] ?? null,
                        'SalesCategory' => $row[20] ?? null,
                        'UpdateDate' => $row[21] ?? null,
                        'UpdateCount' => $row[22] ?? null,
                        'InvalidCategory' => $row[23] ?? null,
                    ]);
                }

                // バッチサイズに達したらデータベースに挿入
                if (count($batch) >= $batchSize) {
                    DB::table('OrderData')->insert($batch);
                    $batch = []; // バッチをクリア
                }
            }

// 残りのデータを挿入
            if (!empty($batch)) {
                DB::table('OrderData')->insert($batch);
            }


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('CSVアップロードエラー: ' . $e->getMessage());
            return back()->with('error', 'CSVアップロード中にエラーが発生しました。');
        } finally {
            if (isset($fileStream)) {
                fclose($fileStream);
            }
        }

        return redirect()->route('orderdata.index')->with('success', 'CSVが正常にアップロードされました！');
    }


    /**
     * その他のリソース操作 (CRUD機能)
     */
    public function create()
    {
        return view('orderdata.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'CustomerCode' => 'required|string',
            'ProductCode' => 'required|string',
            // その他必要なバリデーション
        ]);

        OrderData::create($data);

        return redirect()->route('orderdata.index')->with('success', 'データが作成されました。');
    }

    public function show(OrderData $orderData)
    {
        return view('orderdata.show', compact('orderData'));
    }

    public function edit(OrderData $orderData)
    {
        return view('orderdata.edit', compact('orderData'));
    }

    public function update(Request $request, OrderData $orderData)
    {
        $data = $request->validate([
            'CustomerCode' => 'required|string',
            'ProductCode' => 'required|string',
            // その他必要なバリデーション
        ]);

        $orderData->update($data);

        return redirect()->route('orderdata.index')->with('success', 'データが更新されました。');
    }

    public function destroy(OrderData $orderData)
    {
        $orderData->delete();

        return redirect()->route('orderdata.index')->with('success', 'データが削除されました。');
    }

    protected function getDataTypeCode(): string
    {
        return 'ORDERDATA';
    }

    protected function getDataTypeName(): string
    {
        return '受注データ';
    }

    protected function getModelClass(): string
    {
        return \App\Models\OrderData::class;
    }
}
