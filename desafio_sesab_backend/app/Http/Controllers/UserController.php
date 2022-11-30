<?php

namespace App\Http\Controllers;

use App\Models\Addresses;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Users;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Users::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = Arr::except($validated, ['addresses']);
        $addresses = Arr::only($validated, ['addresses']);
        try {
            return DB::transaction(function () use ($user, $addresses) {
                $newUser = Users::create($user);
                foreach ($addresses['addresses'] as $address) {
                    $searchAddress = Addresses::where(['cep' => $address['cep'], 'logradouro' => $address['logradouro']])->first();
                    if ($searchAddress !== null) {
                        $newUser->Addresses()->attach($searchAddress->id);
                    } else {
                        $newAddress = Addresses::create($address);
                        $newUser->Addresses()->save($newAddress);
                    }
                }
                return response()->json($newUser, 201);
            });
        } catch (\Throwable $th) {
            if ($th->getCode() == 23505) {
                return response('Dados incorretos!', 409);
            } else {
                return $th;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Users  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Users $user)
    {
        return response($user, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\Users  $user
     */
    public function update(UpdateUserRequest $request, Users $user)
    {
        $validated = $request->validated();

        $newUser = Arr::except($validated, ['addresses']);
        $addresses = Arr::only($validated, ['addresses']);
        try {
            return DB::transaction(function () use ($newUser, $user, $addresses) {
                $user->update($newUser);
                foreach ($addresses['addresses'] as $address) {
                    Log::info($address);
                    $searchAddress = Addresses::find($address['id']);
                    if ($searchAddress !== null) {
                        $searchAddress->cep = $address['cep'];
                        $searchAddress->logradouro = $address['logradouro'];
                        $searchAddress->save();
                    } else {
                        $newAddress = Addresses::create($address);
                        $user->Addresses()->save($newAddress);
                    }
                }
                return response()->json($user, 201);
            });
        } catch (\Throwable $th) {
            if ($th->getCode() == 23505) {
                return response('Dados incorretos!', 409);
            } else {
                return $th;
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Users  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Users $user)
    {
        $user->delete();
        return response("Deletado com sucesso", 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Addresses  $user
     * @return \Illuminate\Http\Response
     */
    public function destroyAddress(Addresses $address)
    {
        $address->delete();
        return response("Deletado com sucesso", 201);
    }
}