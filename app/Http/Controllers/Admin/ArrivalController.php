<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Arrival\BulkDestroyArrival;
use App\Http\Requests\Admin\Arrival\DestroyArrival;
use App\Http\Requests\Admin\Arrival\IndexArrival;
use App\Http\Requests\Admin\Arrival\StoreArrival;
use App\Http\Requests\Admin\Arrival\UpdateArrival;
use App\Models\Arrival;
use App\Models\Take;
use App\Models\Tour;
use App\Models\Image;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

class ArrivalController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexArrival $request
     * @return array|Factory|View
     */
    public function index(IndexArrival $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Arrival::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name', 'begin', 'end', 'tour_name',],

            // set columns to searchIn
            ['id', 'name', 'begin', 'end', 'tour_name',]
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.arrival.index', ['data' => $data]);
            
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {   
        $this->authorize('admin.arrival.create');
        // $take = new Take();
        return view('admin.arrival.create');
                
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreArrival $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreArrival $request)
    {   
        // dd($request->all());
        $sanitized = $request->getSanitized();
        $arrival = Arrival::create($sanitized);
        if ($request->has('hideimage')) {
            foreach ($request->hideimage as $image) {
                $file_data = $image;
                $file_name = 'image_'.uniqid() .'.png';
                @list($type, $file_data) = explode(';', $file_data);
                @list(, $file_data) = explode(',', $file_data);

                if($file_data!=""){
                  $file = Storage::disk('public')->put($arrival->id.'/'.$file_name,base64_decode($file_data));
                }    
                $photo = Image::create([
                    'arrival_id' => $arrival->id,
                    'filename' => $file_name,
                    'filedata' => $file_data
                ]);
                // dd($photo);
                $arrival->images()->attach($photo->id);
            }
        };
        if ($request->ajax()) {
            return ['redirect' => url('admin/arrivals'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }
        return redirect('admin/arrivals');
    }

    /**
     * Display the specified resource.
     *
     * @param Arrival $arrival
     * @throws AuthorizationException
     * @return void
     */
    public function show(Arrival $arrival)
    {
        
        $this->authorize('admin.arrival.show', $arrival);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Arrival $arrival
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Arrival $arrival)
    {   
       $this->authorize('admin.arrival.edit', $arrival);
       
        return view('admin.arrival.edit', [
            'arrival' => $arrival,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateArrival $request
     * @param Arrival $arrival
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateArrival $request, Arrival $arrival)
    {   
        // dd($request->all());
        $sanitized = $request->getSanitized();
        $arrival->update($sanitized);
        $arrival->images()->delete();
        $oldfile = new Filesystem;
        $oldfile->cleanDirectory('storage/'.$arrival->id);
        $photo_id_array=[];

        if ($request->has('hideimage')) {
            foreach ($request->hideimage as $image) {
                $file_data = $image;
                $file_name = 'image_'.uniqid() .'.png';
                @list($type, $file_data) = explode(';', $file_data);
                @list(, $file_data) = explode(',', $file_data);

                if($file_data!=""){
                    $file = Storage::disk('public')->put($arrival->id.'/'.$file_name,base64_decode($file_data));
                }    
                $photo = Image::create([
                    'arrival_id' => $arrival->id,
                    'filename' => $file_name,
                    'filedata' => $file_data
                ]);
                $photo_id_array[] =$photo->id;
        
            }
            $arrival->images()->sync($photo_id_array);
        };
        if ($request->ajax()) {
            return [
                'redirect' => url('admin/arrivals'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }
        return redirect('admin/arrivals');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyArrival $request
     * @param Arrival $arrival
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyArrival $request, Arrival $arrival)
    {
        $arrival->images()->delete();
        $arrival->images()->detach();    
        $arrival->delete();
        $oldfile = new Filesystem;
        $oldfile->cleanDirectory('storage/'.$arrival->id);


        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyArrival $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyArrival $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Arrival::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
