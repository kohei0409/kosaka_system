<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreSalesCourseRequest;
use App\Http\Requests\UpdateSalesCourseRequest;
use App\Models\SalesCourse;


class SalesCourseController extends Controller
{
    use \App\Traits\BulkDeletable;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salesCourses = SalesCourse::all();
        return view('salescourse.index', compact('salesCourses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = \App\Models\User::all(['Code', 'name']);
        $managers = \App\Models\User::where('role_id', '<', 4)->get(['Code', 'name', 'role_id']);
        return view('salescourse.create', compact('users', 'managers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalesCourseRequest $request)
    {
        // 入力値を取得
        $data = $request->validated();

        // ManagerCodeを使ってユーザー名を取得
        $manager = \App\Models\User::where('Code', $data['ManagerCode'])->first();
        $data['Manager'] = $manager ? $manager->name : null;

        // ExecutiveOfficerCodeを使ってユーザー名を取得
        $executiveOfficer = \App\Models\User::where('Code', $data['ExecutiveOfficerCode'])->first();
        $data['ExecutiveOfficer'] = $executiveOfficer ? $executiveOfficer->name : null;

        // データベースに保存
        SalesCourse::create($data);

        return redirect()->route('salescourses.index')->with('success', 'Sales course created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesCourse $salesCourse)
    {
        return view('salescourse.show', compact('salesCourse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $salesCourse = SalesCourse::find($id);

        if (!$salesCourse) {
            abort(404, 'Sales Course not found.');
        }

        // role_idが4より小さいユーザーのみ取得
        $users = \App\Models\User::where('role_id', '<', 4)->get(['Code', 'name', 'role_id']);

        return view('salescourse.edit', compact('salesCourse', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalesCourseRequest $request, $id)
    {
        // 入力値を取得
        $data = $request->validated();

        // 該当のSalesCourseを取得
        $salesCourse = SalesCourse::find($id);

        if (!$salesCourse) {
            abort(404, 'Sales Course not found.');
        }

        // ManagerCodeを使ってユーザー名を取得
        $manager = \App\Models\User::where('Code', $data['ManagerCode'])->first();
        $data['Manager'] = $manager ? $manager->name : null;

        // ExecutiveOfficerCodeを使ってユーザー名を取得
        $executiveOfficer = \App\Models\User::where('Code', $data['ExecutiveOfficerCode'])->first();
        $data['ExecutiveOfficer'] = $executiveOfficer ? $executiveOfficer->name : null;

        // データを更新
        $salesCourse->update($data);

        return redirect()->route('salescourses.index')->with('success', 'Sales course updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $salesCourse = SalesCourse::find($id);

        if (!$salesCourse) {
            abort(404, 'Sales Course not found.');
        }

        $salesCourse->delete();

        return redirect()->route('salescourses.index')->with('success', 'Sales course deleted successfully.');
    }

    public function showUploadForm()
    {
        return view('salescourse.upload');
    }

public function uploadCSV(Request $request)
{
    // バリデーション
    $validator = Validator::make($request->all(), [
        'file' => 'required|file|mimes:csv,txt|max:51200', // 最大50MB
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    try {
        // ファイルの読み込み
        $file = $request->file('file');
        $filePath = $file->getRealPath();

        // ファイル内容を行ごとにUTF-8に変換
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$lines || count($lines) < 2) {
            return back()->with('error', 'CSVファイルが空、またはフォーマットが不正です。');
        }

        $lines = array_map(fn($line) => mb_convert_encoding($line, 'UTF-8', 'SJIS-win'), $lines);

        // ヘッダー行を取得
        $headerMapping = [
            '営業コース' => 'Course',
            '担当者コード' => 'ManagerCode',
            '担当者名' => 'Manager',
            '責任者コード' => 'ExecutiveOfficerCode',
            '責任者名' => 'ExecutiveOfficer',
            '部門コード' => 'Department',
        ];
        $header = array_map('trim', str_getcsv($lines[0]));
        $header = array_map(fn($column) => $headerMapping[$column] ?? $column, $header);
        unset($lines[0]);

        DB::beginTransaction();
   foreach ($lines as $line) {
    if (empty(trim($line))) {
        continue; // 空行をスキップ
    }

    $row = array_filter(str_getcsv($line), fn($value) => trim($value) !== '');

    // ヘッダーとデータ行の列数を再チェック
    if (count($header) !== count($row)) {
        \Log::warning('Invalid Row Length', ['row' => $row, 'expected_length' => count($header)]);
        continue;
    }

    $rowData = array_combine($header, $row);

    // 重複チェック: 同じ営業コース、担当者コード、責任者コードの組み合わせが既に存在する場合はスキップ
    $exists = DB::table('SalesCorse')
        ->where('Course', $rowData['Course'])
        ->where('ManagerCode', $rowData['ManagerCode'])
        ->where('ExecutiveOfficerCode', $rowData['ExecutiveOfficerCode'])
        ->exists();

    if ($exists) {
        \Log::info('Duplicate Row Skipped:', $rowData);
        continue; // 重複が見つかった場合、この行をスキップ
    }

    DB::table('SalesCorse')->insert([
        'Course' => $rowData['Course'] ?? null,
        'ManagerCode' => $rowData['ManagerCode'] ?? null,
        'Manager' => $rowData['Manager'] ?? null,
        'ExecutiveOfficerCode' => $rowData['ExecutiveOfficerCode'] ?? null,
        'ExecutiveOfficer' => $rowData['ExecutiveOfficer'] ?? null,
        'Department' => $rowData['Department'] ?? null,
    ]);
    \Log::info('Row Inserted:', $rowData);
}


        DB::commit();
    } catch (\Exception $e) {
        DB::rollback();
        \Log::error('CSV Upload Error: ' . $e->getMessage());
        return back()->with('error', 'Upload failed: ' . $e->getMessage());
    }

    return redirect()->route('salescourses.index')->with('success', 'CSV uploaded successfully!');
}

    protected function getDataTypeCode(): string
    {
        return 'SALESCOURSE';
    }

    protected function getDataTypeName(): string
    {
        return '営業コースデータ';
    }

    protected function getModelClass(): string
    {
        return \App\Models\SalesCourse::class;
    }
}
