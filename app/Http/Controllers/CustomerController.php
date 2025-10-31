<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
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

        $query = Customer::query();

        // グループでフィルタリング
        if ($group !== 'all' && isset($kanaRanges[$group])) {
            $query->where(function ($q) use ($kanaRanges, $group) {
                foreach ($kanaRanges[$group] as $kana) {
                    $q->orWhere('CustomerKana', 'LIKE', $kana . '%');
                }
            });
        }

        // 検索キーワードでフィルタリング
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->Where('CustomerOfficialName1', 'LIKE', '%' . $search . '%')
                    ->orWhere('CustomerOfficialName2', 'LIKE', '%' . $search . '%')
                    ->orWhere('SalesCourse', 'LIKE', '%' . $search . '%')
                    ->orWhere('ManagerCode', 'LIKE', '%' . $search . '%')
                    ->orWhere('PhoneNumber', 'LIKE', '%' . $search . '%')
                    ->orWhere('PhoneNumber2', 'LIKE', '%' . $search . '%')
                    ->orWhere('PhoneNumber3', 'LIKE', '%' . $search . '%')
                    ->orWhere('ManagerName', 'LIKE', '%' . $search . '%')
                    ->orWhere('Fax', 'LIKE', '%' . $search . '%')
                    ->orwhere('CustomerName', 'LIKE', '%' . $search . '%')
                    ->orWhere('CustomerKana', 'LIKE', '%' . $search . '%')
                    ->orWhere('Address1', 'LIKE', '%' . $search . '%')
                    ->orWhere('Address2', 'LIKE', '%' . $search . '%')
                    ->orWhere('PhoneNumber', 'LIKE', '%' . $search . '%');
            });
        }

        $customers = $query->paginate(300);

        return view('customers.index', compact('customers', 'group', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }

    public function showUploadForm()
    {
        return view('customers.upload');
    }

    public function uploadCSV(Request $request)
    {
        $expectedLength = 22; // 期待される列数

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:51200', // 最大50MB
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();

        try {
            $fileContent = mb_convert_encoding(file_get_contents($filePath), 'UTF-8', 'SJIS-win');
            $data = array_map('str_getcsv', explode("\n", $fileContent));

            if (!empty($data)) {
                $header = $data[0];
                unset($data[0]);

                DB::beginTransaction();
                try {
                    foreach ($data as $row) {
                        if (count(array_filter($row)) === 0) {
                            continue; // 空白行をスキップ
                        }

                        if (count($row) > $expectedLength) {
                            $row = array_slice($row, 0, $expectedLength);
                        } elseif (count($row) < $expectedLength) {
                            $row = array_pad($row, $expectedLength, null);
                        }

                        // 日付のバリデーション
                        $updateDate = $row[19];
                        if (!$this->isValidDate($updateDate)) {
                            $updateDate = null; // 無効な日付はNULLにする
                        }

                        // 重複チェック
                        $existingCustomer = DB::table('CustomerData')
                            ->where('CustomerCode', $row[0])
                            ->where('BranchCode', $row[1])
                            ->first();

                        if (!$existingCustomer) {
                            DB::table('CustomerData')->insert([
                                'CustomerCode' => $row[0],
                                'BranchCode' => $row[1],
                                'CustomerOfficialName1' => $row[2],
                                'CustomerOfficialName2' => $row[3],
                                'CustomerName' => $row[4],
                                'CustomerKana' => $row[5],
                                'RepresentativeName' => $row[6],
                                'ManagerName' => $row[7],
                                'PostalCode' => $row[8],
                                'Address1' => $row[9],
                                'Address2' => $row[10],
                                'PhoneNumber' => $row[11],
                                'PhoneNumber2' => $row[12],
                                'PhoneNumber3' => $row[13],
                                'Fax' => $row[14],
                                'SalesCourse' => $row[15],
                                'ManagerCode' => $row[16],
                                'TransactionStopType' => $row[17],
                                'TransactionEndType' => $row[18],
                                'UpdateDate' => $updateDate, // バリデート済みの日付
                                'UpdateCount' => $row[20],
                                'InvalidType' => $row[21],
                            ]);
                        }
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    \Log::error('データベースエラー:', ['message' => $e->getMessage()]);
                    return back()->with('error', 'CSVアップロード中にエラーが発生しました: ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            \Log::error('ファイル処理エラー:', ['message' => $e->getMessage()]);
            return back()->with('error', 'ファイル処理中にエラーが発生しました: ' . $e->getMessage());
        }

        return redirect()->route('customers.index')->with('success', 'CSVが正常にアップロードされました！');
    }

    /**
     * 有効な日付かどうかを判定するヘルパーメソッド
     */
    private function isValidDate($date)
    {
        if ($date === '0' || empty($date)) {
            return false;
        }

        $d = \DateTime::createFromFormat('Ymd', $date);
        return $d && $d->format('Ymd') === $date;
    }

    /**
     * 削除リクエスト（削除コードをメール送信）
     */
    public function requestDelete(Request $request)
    {
        $request->validate([
            'bulk_delete' => 'required|boolean',
        ]);

        // 全体削除用のトークンを生成（customer_code, branch_codeはnull）
        $deletionToken = \App\Models\CustomerDeletionToken::createToken('ALL', null);

        // 全体削除用のダミー顧客オブジェクトを作成
        $dummyCustomer = new Customer();
        $dummyCustomer->CustomerCode = 'ALL';
        $dummyCustomer->BranchCode = null;
        $dummyCustomer->CustomerOfficialName1 = '全得意先データ';

        // メール送信
        \Illuminate\Support\Facades\Mail::to('sawachi@adtrust.jp')->send(
            new \App\Mail\CustomerDeletionCode($deletionToken, $dummyCustomer)
        );

        return response()->json([
            'success' => true,
            'message' => '削除確認コードを sawachi@adtrust.jp に送信しました。メールに記載されたコードを入力してください。',
            'expires_at' => $deletionToken->expires_at->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * 削除実行（コード確認後）
     */
    public function confirmDelete(Request $request)
    {
        $request->validate([
            'bulk_delete' => 'required|boolean',
            'token' => 'required|string|size:6',
        ]);

        // トークンを検証（全体削除用）
        $deletionToken = \App\Models\CustomerDeletionToken::where('customer_code', 'ALL')
            ->where('token', $request->token)
            ->where('used', false)
            ->first();

        if (!$deletionToken) {
            return response()->json([
                'success' => false,
                'message' => '無効な削除コードです。',
            ], 400);
        }

        if (!$deletionToken->isValid()) {
            return response()->json([
                'success' => false,
                'message' => '削除コードの有効期限が切れています。',
            ], 400);
        }

        try {
            // トークンを使用済みにする
            $deletionToken->markAsUsed();

            // 削除処理開始
            $totalCount = Customer::count();

            if ($totalCount === 0) {
                return response()->json([
                    'success' => true,
                    'message' => '削除対象のデータがありません。',
                ]);
            }

            // 進捗IDを生成
            $progressId = 'customer_delete_' . uniqid();

            // 進捗情報を初期化
            \Illuminate\Support\Facades\Cache::put($progressId, [
                'total' => $totalCount,
                'processed' => 0,
                'status' => 'processing',
            ], now()->addMinutes(10));

            // チャンク処理で削除（1000件ずつ）
            $chunkSize = 1000;
            $processed = 0;

            Customer::chunk($chunkSize, function ($customers) use ($progressId, &$processed, $totalCount) {
                $ids = $customers->pluck('id')->toArray();
                Customer::whereIn('id', $ids)->delete();

                $processed += count($ids);

                // 進捗を更新
                \Illuminate\Support\Facades\Cache::put($progressId, [
                    'total' => $totalCount,
                    'processed' => $processed,
                    'percentage' => round(($processed / $totalCount) * 100, 2),
                    'status' => 'processing',
                ], now()->addMinutes(10));
            });

            // 完了状態に更新
            \Illuminate\Support\Facades\Cache::put($progressId, [
                'total' => $totalCount,
                'processed' => $totalCount,
                'percentage' => 100,
                'status' => 'completed',
            ], now()->addMinutes(10));

            return response()->json([
                'success' => true,
                'message' => "全得意先データ（{$totalCount}件）を削除しました。",
                'progress_id' => $progressId,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '削除処理中にエラーが発生しました: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 削除進捗状況を取得
     */
    public function deleteProgress(Request $request)
    {
        $progressId = $request->input('progress_id');

        if (!$progressId) {
            return response()->json([
                'success' => false,
                'message' => '進捗IDが指定されていません。',
            ], 400);
        }

        $progress = \Illuminate\Support\Facades\Cache::get($progressId);

        if (!$progress) {
            return response()->json([
                'success' => false,
                'message' => '進捗情報が見つかりません。',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'progress' => $progress,
        ]);
    }

}
