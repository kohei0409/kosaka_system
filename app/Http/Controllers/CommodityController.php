<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Commodity;

class CommodityController extends Controller
{
    /**
     * 商品データの一覧を表示
     */
    public function index(Request $request)
    {
        $group = $request->input('group', 'all');
        $search = $request->input('search', '');

        $kanaRanges = [
            'a' => ['ｱ', 'ｲ', 'ｳ', 'ｴ', 'ｵ'],
            'k' => ['ｶ', 'ｷ', 'ｸ', 'ｹ', 'ｺ'],
            's' => ['ｻ', 'ｼ', 'ｽ', 'ｾ', 'ｿ'],
            't' => ['ﾀ', 'ﾁ', 'ﾂ', 'ﾃ', 'ﾄ'],
            'n' => ['ﾅ', 'ﾆ', 'ﾇ', 'ﾈ', 'ﾉ'],
            'h' => ['ﾊ', 'ﾋ', 'ﾌ', 'ﾍ', 'ﾎ'],
            'm' => ['ﾏ', 'ﾐ', 'ﾑ', 'ﾒ', 'ﾓ'],
            'y' => ['ﾔ', 'ﾕ', 'ﾖ'],
            'r' => ['ﾗ', 'ﾘ', 'ﾙ', 'ﾚ', 'ﾛ'],
            'w' => ['ﾜ', 'ｦ', 'ﾝ'],
        ];

        $query = Commodity::query();

        // グループフィルタリング
        if ($group !== 'all' && isset($kanaRanges[$group])) {
            $query->where(function ($q) use ($kanaRanges, $group) {
                foreach ($kanaRanges[$group] as $kana) {
                    $q->orWhere('ProductNameKana', 'LIKE', $kana . '%');
                }
            });
        }


       // 検索フィルタリング
if ($search) {
    // 検索キーワードを正規化（半角カナを全角カナに変換）
    $normalizedSearch = mb_convert_kana($search, 'k'); // 'K' は半角カナを全角カナに変換

    // スペースでキーワードを分割
    $keywords = preg_split('/\s+/', trim($normalizedSearch));

    $query->where(function ($q) use ($keywords) {
        foreach ($keywords as $keyword) {
            $q->where(function ($subQ) use ($keyword) {
                // 正規化されたデータベースの列と検索キーワードで比較
                $subQ->whereRaw("CONVERT(ProductCode USING utf8mb4) LIKE ?", ['%' . $keyword . '%'])
                    ->orWhereRaw("CONVERT(JANCode USING utf8mb4) LIKE ?", ['%' . $keyword . '%'])
                    ->orWhereRaw("CONVERT(ManufacturerName USING utf8mb4) LIKE ?", ['%' . $keyword . '%'])
                    ->orWhereRaw("CONVERT(ProductName USING utf8mb4) LIKE ?", ['%' . $keyword . '%'])
                    ->orWhereRaw("CONVERT(Specification USING utf8mb4) LIKE ?", ['%' . $keyword . '%'])
                    ->orWhereRaw("CONVERT(Abbreviation USING utf8mb4) LIKE ?", ['%' . $keyword . '%'])
                    ->orWhereRaw("CONVERT(DentalFormulaName USING utf8mb4) LIKE ?", ['%' . $keyword . '%'])
                    ->orWhereRaw("CONVERT(ProductNameKana USING utf8mb4) LIKE ?", ['%' . $keyword . '%'])
                    ->orWhereRaw("CONVERT(Publisher USING utf8mb4) LIKE ?", ['%' . $keyword . '%'])
                    ->orWhereRaw("CONVERT(PublisherName USING utf8mb4) LIKE ?", ['%' . $keyword . '%'])

                    // オリジナルの検索キーワードでも比較
                    ->orWhere('ProductCode', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('JANCode', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('ManufacturerName', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('ProductName', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('Specification', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('Abbreviation', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('DentalFormulaName', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('ProductNameKana', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('Publisher', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('PublisherName', 'LIKE', '%' . $keyword . '%');
            });
        }
    });
}



        $commodities = $query->paginate(300);

        return view('commodities.index', compact('commodities', 'group', 'search'));
    }

    public function showUploadForm()
    {
        return view('commodities.upload'); // commodities/upload.blade.php というビューを表示
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
    ini_set('memory_limit', '512M'); // 必要に応じて増加

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
            $row = array_pad($row, 41, null); // 列数を補正

            // データが既に存在しているか確認
            $exists = DB::table('CommodityData')->where('ProductCode', $row[0])->exists();

            if (!$exists) {
                // バッチにデータ追加
                $batch[] = [
                    'ProductCode' => $row[0],
                    'JANCode' => $row[1],
                    'ManufacturerName' => $row[2],
                    'ProductName' => $row[3],
                    'Specification' => $row[4],
                    'Abbreviation' => $row[5],
                    'DentalFormulaName' => $row[6],
                    'ProductNameKana' => $row[7],
                    'Publisher' => $row[8],
                    'PublisherName' => $row[9],
                    'InternalName' => $row[10],
                    'NormalQuantity1' => $row[11],
                    'NormalQuantity2' => $row[12],
                    'NormalQuantity3' => $row[13],
                    'StandardUnitPrice1' => $row[14],
                    'StandardUnitPrice2' => $row[15],
                    'StandardUnitPrice3' => $row[16],
                    'ListPrice' => $row[17],
                    'PatientPrice' => $row[18],
                    'ProductGroupCode' => $row[19],
                    'ProductUnifiedCode' => $row[20],
                    'UpdateDate' => $row[21],
                    'UpdateCount' => $row[22],
                    'StopClassification' => $row[23],
                    'InvalidClassification' => $row[24],
                    'PauseClassification' => $row[25],
                    'StockClassification' => $row[26],
                    'StockCount' => $row[27],
                    'OutstandingOrderCount' => $row[28],
                    'ShortageCount' => $row[29],
                    'PendingOrderCount' => $row[30],
                    'StockUpdateDate' => $row[31],
                    'SaleQuantity1' => $row[32],
                    'SaleQuantity2' => $row[33],
                    'SaleQuantity3' => $row[34],
                    'SaleUnitPrice1' => $row[35],
                    'SaleUnitPrice2' => $row[36],
                    'SaleUnitPrice3' => $row[37],
                    'SaleStartDateTime' => $row[38],
                    'SaleEndDateTime' => $row[39],
                    'SaleInformationUpdateDate' => $row[40],
                ];
            }

            // バッチサイズに達したらデータベースに挿入
            if (count($batch) >= $batchSize) {
                DB::table('CommodityData')->insert($batch);
                $batch = []; // バッチをクリア
            }
        }

        // 残りのデータを挿入
        if (!empty($batch)) {
            DB::table('CommodityData')->insert($batch);
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

    return redirect()->route('commodities.index')->with('success', 'CSVが正常にアップロードされました！');
}

}
