<?php

namespace App\Http\Controllers\API;

use App\Exceptions\NotEnoughMoney;
use App\Http\Requests\Transfer\StoreRequest;
use App\Http\Controllers\Controller;
use App\Transfer;
use App\Http\Resources\Transfer as TransferResource;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return TransferResource::collection(Transfer::paginate())
            ->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Transfer\StoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $transfer = new Transfer();
        $transfer->fill($request->all());
        $transfer->refreshRate();
        try {
            if (!$transfer->save()) {
                return response('', 500);
            }
        } catch (NotEnoughMoney $ex) {
            return response()->json(['error' => $ex->getMessage()], 400);
        }

        return (new TransferResource($transfer))
            ->response();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return (new TransferResource(Transfer::findOrFail((int)$id)))
            ->response();
    }
}
