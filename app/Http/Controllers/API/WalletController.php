<?php

namespace App\Http\Controllers\API;

use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\WalletStoreRequest;
use App\Http\Requests\WalletUpdateRequest;
use App\Http\Resources\WalletResource;
use Illuminate\Support\Facades\Auth;

/**
 * APIs para gerenciar carteiras
 *
 * @group Wallets
 * @authenticated
 *
 * @package App\Http\Controllers\API
 * @version February 1, 2021, 1:00 pm UTC
 * @author hotwyl
 */
class WalletController extends Controller
{
    public function __construct()
    {
        //constante definir quantidade de carteiras para cada usuario
        define('WALLET_LIMIT', 1);

        //constante id usuario logado
        define('USER_ID', Auth::id());
    }

    /**
     * Display a listing of the resource.
     *
     * @return WalletResource
     */
    public function index() : WalletResource
    {
        // Retornar todas as transações da carteira especificada
        $wallets = Auth::user()->wallets;

        // Retornar todas as transações da carteira especificada
        if ($wallets->count() === 0) {
            return WalletResource::make([
                'status' => false,
                'message' => 'Nenhuma carteira encontrada',
                'content' => null
            ], 404);
        }

        // Retornar todas as transações da carteira especificada
        return WalletResource::make([
            'status' => true,
            'message' => 'Carteiras encontradas',
            'content' => $wallets
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param $wallet
     * @return WalletResource
     */
    public function show($wallet) : WalletResource
    {
        // Retornar uma transação específica da carteira especificada
        $wallet = Auth::user()->wallets->where('user_id', USER_ID)->where('id',$wallet)->first();

        // Retornar carteira não encontrada
        if(!$wallet) {
            return WalletResource::make([
                'status' => false,
                'message' => 'Carteira não encontrada',
                'content' => null
            ], 404);
        }

        // Retornar uma transação específica da carteira especificada
        return WalletResource::make([
            'status' => true,
            'message' => 'Carteira encontrada',
            'content' => $wallet
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param WalletStoreRequest $request
     * @return WalletResource
     */
    public function store(WalletStoreRequest $request) : WalletResource
    {
        // verificar quantidade de carteiras
        if(Auth::user()->wallets->count() >= WALLET_LIMIT) {
            return WalletResource::make([
                'status' => false,
                'message' => 'Você já possui o limite de 1 carteiras',
                'content' => null
            ], 400);
        }

        // verificar se a carteira já existe
        if(Auth::user()->wallets->where('user_id', USER_ID)->where('name', $request->name)->first()) {
            return WalletResource::make([
                'status' => false,
                'message' => 'Você já possui uma carteira com esse nome',
                'content' => null
            ], 400);
        }

        try {
            // Lógica para criar uma nova carteira
            $newWallet = $request->validated();
            $newWallet['user_id'] = USER_ID;

            // Criar a carteira
            $wallet = Wallet::create($newWallet);

            // Retornar a carteira criada
            return WalletResource::make([
                'status' => true,
                'message' => 'Carteira criada com sucesso',
                'content' => $wallet
            ], 201);
            
        } catch (\Exception $e) {
            return WalletResource::make([
                'status' => false,
                'message' => 'Erro ao criar carteira',
                'content' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param WalletUpdateRequest $request
     * @param $wallet
     * @return WalletResource
     */
    public function update(WalletUpdateRequest $request, $wallet) : WalletResource
    {
        // Retornar uma transação específica da carteira especificada
        $wallet = Auth::user()->wallets->where('user_id', USER_ID)->where('id',$wallet)->first();

        // Verificar se a carteira pertence ao usuário logado
        if(!$wallet) {
            return WalletResource::make([
                'status' => false,
                'message' => 'Carteira não encontrada',
                'content' => null
            ], 404);
        }

        try {
            // Lógica para atualizar uma transação específica da carteira especificada
            $wallet->update($request->validated());

            // Retornar a carteira atualizada
            return WalletResource::make([
                'status' => true,
                'message' => 'Carteira atualizada com sucesso',
                'content' => $wallet
            ], 200);
        } catch (\Exception $e) {
            return WalletResource::make([
                'status' => false,
                'message' => 'Erro ao atualizar carteira',
                'content' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $wallet
     * @return WalletResource
     */
    public function destroy($wallet) : WalletResource
    {
        // Retornar uma transação específica da carteira especificada
        $wallet = Auth::user()->wallets->where('user_id', USER_ID)->where('id',$wallet)->first();

        // Lógica para deletar uma transação específica da carteira especificada
        if(!$wallet) {
            return WalletResource::make([
                'status' => false,
                'message' => 'Carteira não encontrada',
                'content' => null
            ], 404);
        }

        try {
            // Deletar a carteira
            $wallet->delete();

            // Retornar a carteira deletada
            return WalletResource::make([
                'status' => true,
                'message' => 'Carteira deletada com sucesso',
                'content' => null
            ], 200);
        } catch (\Exception $e) {
            return WalletResource::make([
                'status' => false,
                'message' => 'Erro ao deletar carteira',
                'content' => $e->getMessage()
            ], 500);
        }
    }
}
