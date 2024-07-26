<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:entrada,saida',
            'description' => 'nullable|in:salário,investimento,extra,financiamento,emprestimo,aluguel,luz,agua,internet,alimentação,transporte,educação,lazer,vestuario,poupança,outros',
            'amount' => 'required|numeric|between:0,999999.99',
            'status' => 'nullable|in:pendente,pago,cancelado',
            'recurrence' => 'nullable|boolean',
            'period' => 'nullable|in:diario,semanal,quinzenal,mensal,bimestral,trimestral,semestral,anual',
            'installments' => 'nullable|integer',
            'due_date' => 'nullable|date',
            'payment_date' => 'nullable|date',
            'annotation' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type.required' => 'O campo tipo é obrigatório',
            'type.in' => 'O campo tipo deve ser entrada ou saida',
            'description.in' => 'O campo descrição deve ser salário, investimento, extra, financiamento, emprestimo, aluguel, luz, agua, internet, alimentação, transporte, educação, lazer, vestuario, poupança ou outros',
            'amount.required' => 'O campo valor é obrigatório',
            'amount.numeric' => 'O campo valor deve ser um número',
            'amount.between' => 'O campo valor deve ser entre 0 e 999999.99',
            'status.in' => 'O campo status deve ser pendente, pago ou cancelado',
            'recurrence.boolean' => 'O campo recorrência deve ser um booleano',
            'period.in' => 'O campo período deve ser diario, semanal, quinzenal, mensal, bimestral, trimestral, semestral ou anual',
            'installments.integer' => 'O campo parcelas deve ser um número inteiro',
            'due_date.date' => 'O campo data de vencimento deve ser uma data',
            'payment_date.date' => 'O campo data de pagamento deve ser uma data',
            'annotation.string' => 'O campo anotação deve ser uma string',
            'annotation.max' => 'O campo anotação deve ter no máximo 255 caracteres',
        ];
    }
}
