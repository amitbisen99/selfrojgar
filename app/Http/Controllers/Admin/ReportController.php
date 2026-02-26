<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use DataTables;
use DB;

class ReportController extends AdminThemeController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Report::with(['user'])->orderBy('id', 'DESC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('user_name', function ($data) {
                    return $data->user ? $data->user->name : '';
                })
                ->addColumn('post_name', function ($data) {
                    return $data->post_name;
                })
                ->editColumn('module_name', function ($data) {
                    return ucwords(str_replace('-', ' ', $data->module_name));
                })
                ->editColumn('created_at', function ($data) {
                    return $data->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('action', function ($data) {
                    $action = '<a href="' . route('report.show', $data->id) . '" class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                    $action .= ' <a href="' . route('report.destroy', $data->id) . '" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Delete Report" onclick="return confirm(\'Are you sure?\')"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    if ($data->post_url) {
                        $action .= ' <a href="' . $data->post_url . '" target="_blank" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Go to Post"><i class="fa fa-share" aria-hidden="true"></i></a>';
                    }

                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.report.index');
    }

    public function show($id)
    {
        $report = Report::with('user')->findOrFail($id);

        $module = strtolower($report->module_name);

        $routeMap = [
            'tourism' => 'tourism-business.show',
            'franchise' => 'franchise-business.show',
            'job' => 'job.show',
            'product' => 'product.show',
            'artist' => 'artist.show',
            'on-demand' => 'on-demand-service.show',
            'whole-sell' => 'whole-sell-product.show',
            'business' => 'businesses.show',
            'advertisement' => 'advertisement.show',
            'property' => 'propertyes.show',
        ];

        $postRoute = null;
        foreach ($routeMap as $key => $route) {
            if (str_contains($module, $key)) {
                $postRoute = $route;
                break;
            }
        }

        $postUrl = null;
        if ($postRoute) {
            try {
                $postUrl = route($postRoute, $report->post_id);
            } catch (\Exception $e) {
                // Ignore if route generation fails
            }
        }

        return view('admin.report.show', compact('report', 'postUrl'));
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        notificationMsg('success', 'Report deleted successfully.');

        return redirect()->back();
    }

    public function destroyPost($id)
    {
        $report = Report::findOrFail($id);
        // Try to resolve the namespace based on module name
        // E.g., if module is 'advertisement', model is \App\Models\Advertisement
        $modelClass = '\\App\\Models\\' . str_replace(' ', '', ucwords(str_replace('-', ' ', $report->module_name)));

        if (class_exists($modelClass)) {
            $post = $modelClass::find($report->post_id);
            if ($post) {
                // Determine if we need to call something specific, or just delete
                $post->delete();
                $report->delete(); // Optionally delete the report too if the post is deleted
                notificationMsg('success', 'Post and Report deleted successfully.');
                return redirect()->route('report.index');
            }
        }

        notificationMsg('error', 'Post not found or could not be deleted.');
        return redirect()->back();
    }
}
