<?php

namespace LaraMod\Admin\Pages\Controllers;

use App\Http\Controllers\Controller;
use \LaraMod\Admin\Pages\Models\Pages;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class PagesController extends Controller
{

    private $data = [];

    public function __construct()
    {
        config()->set('admincore.menu.pages.active', true);
    }

    public function index()
    {
        $this->data['items'] = Pages::paginate(20);

        return view('adminpages::pages.list', $this->data);
    }

    public function getForm(Request $request)
    {
        $this->data['item'] = ($request->has('id') ? Pages::find($request->get('id')) : new Pages());

        return view('adminpages::pages.form', $this->data);
    }

    public function postForm(Request $request)
    {
        if(!$request->has('slug')){
            $request->merge(['slug' => str_slug(
                $request->get('title_'.config('app.fallback_locale', 'en'))
            )]);
        }

        $item = Pages::firstOrNew(['id' => $request->get('id')]);
        try {
            $item->autoFill($request);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['errors' => $e->getMessage()]);
        }

        return redirect()->route('admin.pages')->with('message', [
            'type' => 'success',
            'text' => 'Item saved.',
        ]);
    }

    public function delete(Request $request)
    {
        if (!$request->has('id')) {
            return redirect()->route('admin.pages')->with('message', [
                'type' => 'danger',
                'text' => 'No ID provided!',
            ]);
        }
        try {
            Pages::find($request->get('id'))->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.pages')->with('message', [
                'type' => 'danger',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.pages')->with('message', [
            'type' => 'success',
            'text' => 'Item moved to trash.',
        ]);
    }

    public function dataTable()
    {
        $items = Pages::select(['id','title_'.config('app.fallback_locale', 'en'),'created_at', 'updated_at', 'viewable']);

        return DataTables::of($items)
            ->addColumn('action', function ($item) {
                return '<a href="' . route('admin.pages.form',
                        ['id' => $item->id]) . '" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>'
                    . '<a href="' . route('admin.pages.delete',
                        ['id' => $item->id]) . '" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>';
            })
            ->editColumn('updated_at', function ($item) {
                return $item->updated_at->format('d.m.Y H:i');
            })
            ->addColumn('status', function ($item) {
                return !$item->viewable ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>';
            })
            ->orderColumn('updated_at $1', 'id $1')
            ->make('true');
    }


}