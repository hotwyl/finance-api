<?php

namespace App\Http\Controllers\API;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionStoreRequest;
use App\Http\Requests\TransactionUpdateRequest;
use App\Http\Resources\TransactionResource;
use Illuminate\Support\Facades\Auth;

/**
 * APIs para gerenciar transações
 *
 * @group Transactions
 * @authenticated
 *
 * @package App\Http\Controllers\API
 * @version February 1, 2021, 1:00 pm UTC
 * @author hotwyl
 */
class TransactionController extends Controller
{
    public function __construct()
    {
        //constante id usuario logado
        define('USER_ID', Auth::id());

        //constante carteiras
        define('WALLETS', Auth::user()->wallets);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return TransactionResource
     */
    public function index(Request $request) : TransactionResource
    {
        $query = Transaction::query();
        $orderby = $request->input('orderby') ?  $request->input('orderby') : 'due_date';
        $paginate = $request->input('paginate') ?  $request->input('paginate') : 10;
        $ano = $request->input('year') ? $request->input('year') : date('Y', strtotime('now'));
        $mes = $request->input('month') ? $request->input('month') : date('m', strtotime('now'));

        // Lógica para retornar todas as transações agrupando pór carteiras e por meses
        $transactions = $query->whereIn('wallet_id', WALLETS->pluck('id'))
            ->whereYear('due_date', $ano)
            ->whereMonth('due_date', $mes)
            ->orderBy($orderby, 'asc')
            ->paginate($paginate);

        // Retornar todas as transações da carteira especificada
        if ($transactions->count() === 0) {
            return TransactionResource::make([
                'status' => false,
                'message' => 'Nenhuma transação encontrada',
                'content' => null
            ], 404);
        }

        // somar total de entradas e saidas
        $sums['totalEntrada']= $transactions->where('type', 'entrada')->sum('amount');
        $sums['totalSaida']= $transactions->where('type', 'saida')->sum('amount');
        $sums['balancoSaldo']= $sums['totalEntrada'] - $sums['totalSaida'];

        // Retornar todas as transações da carteira especificada
        return TransactionResource::make([
            'status' => false,
            'message' => 'Transações encontradas',
            'content' => ['sums' => $sums, 'transactions' => $transactions]
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param $transaction
     * @return TransactionResource
     */
    public function show($transaction) : TransactionResource
    {
        // Lógica para retornar uma transação específica da carteira especificada
        $transaction = Transaction::where('id', $transaction)->whereIn('wallet_id', WALLETS->pluck('id')->toArray())->first();

        // Retornar transação não encontrada
        if(!$transaction) {
            return TransactionResource::make([
                'status' => false,
                'message' => 'Transação não encontrada',
                'content' => null
            ], 404);
        }

        // Retornar uma transação específica da carteira especificada
        return TransactionResource::make([
            'status' => true,
            'message' => 'Transação encontrada',
            'content' => $transaction
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TransactionStoreRequest $request
     * @return TransactionResource
     */
    public function store(TransactionStoreRequest $request) : TransactionResource
    {
        $carteira = (in_array($request->input('wallet_id'), WALLETS->pluck('id')->toArray())) ?  $request->input('wallet_id') : WALLETS->pluck('id')->first();

        if (!$carteira) {
            return TransactionResource::make([
                'status' => false,
                'message' => 'Nenhuma carteira encontrada',
                'content' => null
            ], 404);
        }

        try {
            // Lógica para criar uma nova carteira
            $newTransaction = $request->validated();
            $newTransaction['wallet_id'] =  $carteira;

            // Lógica para criar uma nova transação
            $transaction = Transaction::create($newTransaction);

            return TransactionResource::make([
                'status' => true,
                'message' => 'Transação criada com sucesso',
                'content' => $transaction
            ], 201);

        } catch (\Exception $e) {
            return TransactionResource::make([
                'status' => false,
                'message' => 'Erro ao criar transação',
                'content' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TransactionUpdateRequest $request
     * @param $transaction
     * @return TransactionResource
     */
    public function update(TransactionUpdateRequest $request, $transaction) : TransactionResource
    {
        // Lógica para retornar uma transação específica da carteira especificada
        $transaction = Transaction::where('id', $transaction)->whereIn('wallet_id', WALLETS->pluck('id'))->first();

        // Retornar transação não encontrada
        if(!$transaction) {
            return TransactionResource::make([
                'status' => false,
                'message' => 'Transação não encontrada',
                'content' => null
            ], 404);
        }

        try {
            // Lógica para atualizar a carteira
            $transaction->update($request->validated());

            // Retornar a carteira atualizada
            return TransactionResource::make([
                'status' => true,
                'message' => 'Transação atualizada com sucesso',
                'content' => $transaction
            ], 200);
        } catch (\Exception $e) {
            return TransactionResource::make([
                'status' => false,
                'message' => 'Erro ao atualizar transação',
                'content' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($transaction)
    {
        // Lógica para retornar uma transação específica da carteira especificada
        $transaction = Transaction::where('id', $transaction)->whereIn('wallet_id', WALLETS->pluck('id'))->first();

        // Retornar transação não encontrada
        if(!$transaction) {
            return TransactionResource::make([
                'status' => false,
                'message' => 'Transação não encontrada',
                'content' => null
            ], 404);
        }

        try {
            // Lógica para deletar a carteira
            $transaction->delete();

            // Retornar a carteira deletada
            return TransactionResource::make([
                'status' => true,
                'message' => 'Transação deletada com sucesso',
                'content' =>  null
            ], 200);
        } catch (\Exception $e) {
            return TransactionResource::make([
                'status' => false,
                'message' => 'Erro ao deletar transação',
                'content' => $e->getMessage()
            ], 500);
        }
    }
}
