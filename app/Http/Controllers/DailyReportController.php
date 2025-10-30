<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyReport;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class DailyReportController extends Controller
{
    // ✅ 日報一覧
    public function index(Request $request)
{
    $user = Auth::user();
    $query = DailyReport::where('user_id', $user->id)->with(['customer', 'user']);

    // 🔍 日付で検索
    if ($request->filled('report_date')) {
        $query->whereDate('report_date', $request->report_date);
    }

    // 🔍 担当者で検索（プルダウン用）
    if ($request->filled('manager_id')) {
        $query->where('ManagerCode', $request->manager_id);
    }

    // 🔹 プルダウン用に担当者リストを取得
    $managers = \App\Models\User::whereIn('Code', DailyReport::pluck('ManagerCode'))->pluck('name', 'Code');

    // 100件ずつ表示（ページネーション）
    $reports = $query->orderBy('report_date', 'desc')->paginate(100);

    return view('daily_reports.index', compact('reports', 'user', 'managers'));
}



    // ✅ 日報の新規作成ページを表示
    public function create()
    {
        $customers = Customer::all(); // 全顧客を取得
        return view('daily_reports.create', compact('customers'));
    }

    // ✅ 日報を保存
    public function store(Request $request)
    {

    }

    // ✅ 日報の詳細表示
    public function show($id)
    {
        try {
            $report = DailyReport::with(['customer', 'user'])->findOrFail($id);

            // 既読処理
            if (!$report->is_read) {
                $report->update(['is_read' => true, 'read_by' => auth()->id()]);
            }

            return view('daily_reports.show', compact('report'));
        } catch (\Throwable $e) {
            \Log::error("DailyReport ID: {$id} の取得エラー - " . $e->getMessage());
            return response()->json(['error' => '日報の取得に失敗しました'], 500);
        }
    }


    // ✅ 日報の編集ページを表示
    public function edit($id)
    {

    }

    // ✅ 日報を更新
    public function update(Request $request, $id)
    {

    }

    // ✅ 日報を削除
    public function destroy($id)
    {

    }


    public function addComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $report = DailyReport::findOrFail($id);
        $report->addComment($request->comment);

        return response()->json([
            'message' => 'コメントを追加しました！',
            'comments' => $report->comments, // 最新のコメント一覧を返す
        ]);
    }

    public function updateComment(Request $request, $id, $commentIndex)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $report = DailyReport::findOrFail($id);
        $report->updateComment($commentIndex, $request->comment);

        return response()->json(['message' => 'コメントを更新しました', 'comments' => $report->comments]);
    }

    public function deleteComment($id, $commentIndex)
    {
        $report = DailyReport::findOrFail($id);
        $report->deleteComment($commentIndex);

        return response()->json(['message' => 'コメントを削除しました', 'comments' => $report->comments]);
    }


}
