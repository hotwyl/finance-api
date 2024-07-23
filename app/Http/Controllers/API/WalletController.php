<?php

namespace App\Http\Controllers\API;

use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use App\Http\Resources\WalletResource;

class WalletController extends Controller
{
    public function index(Wallet $wallet)
    {
        $wallets = Wallet::all();
        return WalletResource::collection($wallets);
    }

    public function show(Wallet $wallet)
    {
        // Retornar uma transação específica da carteira especificada
        return new WalletResource($wallet);
    }

    public function store(StoreWalletRequest $request)
    {
        // Lógica para criar uma nova transação para a carteira especificada
        $wallet = Wallet::create($request->validated());

        return new WalletResource($wallet);;
    }

    public function update(UpdateWalletRequest  $request, Wallet $wallet)
    {
        // Lógica para atualizar uma transação específica da carteira especificada
        $wallet->update($request->validated());

        return new WalletResource($wallet);
    }

    public function destroy(Wallet $wallet)
    {
        // Lógica para deletar uma transação específica da carteira especificada
        $wallet->delete();

        return response()->noContent();
    }
}
