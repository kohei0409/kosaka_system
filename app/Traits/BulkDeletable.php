<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Models\CustomerDeletionToken;
use App\Mail\DataDeletionCode;

trait BulkDeletable
{
    /**
     * 削除リクエスト（削除コードをメール送信）
     */
    public function requestDelete(Request $request)
    {
        $request->validate([
            'bulk_delete' => 'required|boolean',
        ]);

        // 全体削除用のトークンを生成
        $deletionToken = CustomerDeletionToken::createToken('ALL_' . $this->getDataTypeCode(), null);

        // メール送信
        Mail::to('sawachi@adtrust.jp')->send(
            new DataDeletionCode($deletionToken, $this->getDataTypeName())
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

        // トークンを検証
        $deletionToken = CustomerDeletionToken::where('customer_code', 'ALL_' . $this->getDataTypeCode())
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

            $modelClass = $this->getModelClass();
            $totalCount = $modelClass::count();

            if ($totalCount === 0) {
                return response()->json([
                    'success' => true,
                    'message' => '削除対象のデータがありません。',
                ]);
            }

            // 進捗IDを生成
            $progressId = $this->getDataTypeCode() . '_delete_' . uniqid();

            // 進捗情報を初期化
            Cache::put($progressId, [
                'total' => $totalCount,
                'processed' => 0,
                'status' => 'processing',
            ], now()->addMinutes(10));

            // 全IDを先に取得
            $allIds = $modelClass::pluck('id')->toArray();
            $chunkSize = 1000;
            $processed = 0;

            // IDを分割して削除
            foreach (array_chunk($allIds, $chunkSize) as $idChunk) {
                $modelClass::whereIn('id', $idChunk)->delete();

                $processed += count($idChunk);

                // 進捗を更新
                Cache::put($progressId, [
                    'total' => $totalCount,
                    'processed' => $processed,
                    'percentage' => round(($processed / $totalCount) * 100, 2),
                    'status' => 'processing',
                ], now()->addMinutes(10));
            }

            // 完了状態に更新
            Cache::put($progressId, [
                'total' => $totalCount,
                'processed' => $totalCount,
                'percentage' => 100,
                'status' => 'completed',
            ], now()->addMinutes(10));

            return response()->json([
                'success' => true,
                'message' => "全{$this->getDataTypeName()}（{$totalCount}件）を削除しました。",
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
     * データタイプコードを取得（各コントローラーで実装）
     */
    abstract protected function getDataTypeCode(): string;

    /**
     * データタイプ名を取得（各コントローラーで実装）
     */
    abstract protected function getDataTypeName(): string;

    /**
     * モデルクラスを取得（各コントローラーで実装）
     */
    abstract protected function getModelClass(): string;
}
