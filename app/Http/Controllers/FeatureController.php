<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Permission;

class FeatureController extends Controller
{
    // public function index(Datatables $datatables)
    // {
    //     $columns = [
    //         'id' => ['title' => 'No.', 'orderable' => false, 'searchable' => false, 'render' => function () {
    //             return 'function(data,type,fullData,meta){return meta.settings._iDisplayStart+meta.row+1;}';
    //         }],
    //         'name' => ['name' => 'name', 'title' => 'Nama'],
    //         'description' => ['name' => 'description', 'title' => 'Deskripsi'],
    //         'aksi' => [
    //             'orderable' => false, 'width' => '110px', 'searchable' => false, 'printable' => false, 'class' => 'text-center', 'width' => '130px', 'exportable' => false
    //         ],
    //     ];

    //     if ($datatables->getRequest()->ajax()) {
    //         $features = Feature::query()
    //             ->select('features.id', 'features.name', 'features.description');

    //         return $datatables->eloquent($features)
    //             ->filterColumn('name', function (Builder $query, $keyword) {
    //                 $sql = "features.name  like ?";
    //                 $query->whereRaw($sql, ["%{$keyword}%"]);
    //             })
    //             ->filterColumn('description', function (Builder $query, $keyword) {
    //                 $sql = "features.description  like ?";
    //                 $query->whereRaw($sql, ["%{$keyword}%"]);
    //             })
    //             ->addColumn('aksi', function (Feature $data) {
    //                 $button = '';

    //                 if (auth()->feature()->can('detail fitur')) {
    //                     $button .= '<a href="/setting/permission/' . $data->id . '" class="btn btn-sm btn-info me-2"><i class="bi bi-card-checklist"></i></a>';
    //                 }

    //                 if (auth()->feature()->can('ubah fitur')) {
    //                     $button .= '<a href="javascript:void(0)" onclick="onEdit(' . htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') . ')" class="btn btn-sm btn-warning me-2"><i class="bi bi-pen"></i></a>';
    //                 }

    //                 if (auth()->feature()->can('hapus fitur')) {
    //                     $button .= '<a href="javascript:void(0)" onclick="onDelete(' . htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') . ')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>';
    //                 }

    //                 return $button;
    //             })
    //             ->rawColumns(['aksi'])
    //             ->toJson();
    //     }

    //     $columnsArrExPr = [0, 1, 2, 3];
    //     $html = $datatables->getHtmlBuilder()
    //         ->columns($columns)
    //         ->parameters([
    //             'order' => [[1, 'desc']],
    //             'responsive' => true,
    //             'autoWidth' => false,
    //             'dom' => 'lfrtip',
    //             'lengthMenu' => [
    //                 [10, 25, 50, -1],
    //                 ['10 Data', '25 Data', '50 Data', 'Semua Data']
    //             ],
    //             // 'buttons' => $this->buttonDatatables($columnsArrExPr),
    //         ]);

    //     // $roles = Role::paginate(10);

    //     $compact = compact('html');

    //     return view("pages.setting.feature", $compact);
    // }

    public function index()
    {
        $features = Feature::orderBy("created_at", "desc")->get();

        return view("pages.setting.feature", compact("features"));
    }

    public function store(Request $request)
    {
        // return request()->all();

        try {
            DB::beginTransaction();

            if (request("id")) {
                $feature = Feature::find(request("id"));
                $feature->updated_by = Auth::user()->id;

                $message = "diperbaharui";
            } else {
                $feature = new Feature;
                $feature->created_by = Auth::user()->id;

                $message = "ditambahkan";
            }

            $feature->name = request("name");
            $feature->description = request("description");
            $feature->save();

            $tasks = ["lihat", "tambah", "edit", "hapus"];

            foreach ($tasks as $task) {
                $name = strtolower($feature->name);
                $featureDescription = str_replace('-', ' ', strtolower($feature->name));

                Permission::updateOrCreate(
                    [
                        "feature_id" => $feature->id,
                        "name" => "{$task} {$name}",
                    ],
                    [
                        "description" => "{$task} {$featureDescription}",
                        "guard_name" => "web",
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [],
                'message' => "Berhasil {$message}",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);


            return response()->json([
                'success' => false,
                'message' => "Gagal {$message}",
            ], 500);
        }
    }

    public function destroy()
    {
        try {
            DB::beginTransaction();

            $feature = Feature::find(request("id"));
            $feature->update([
                'deleted_by' => Auth::user()->id,
            ]);
            $feature->delete();

            Permission::where("feature_id", request("id"))
                ->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);


            return response()->json([
                'success' => false,
                'message' => 'Gagal dihapus',
            ], 500);
        }
    }
}
