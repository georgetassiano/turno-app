<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'incomes' => $this->collection->sum(function (TransactionResource $transaction) {
                    return $transaction['transactable_type'] == 'checks' ? $transaction['transactable']['amount'] : 0;
                }),
                'expenses' => $this->collection->sum(function (TransactionResource $transaction) {
                    return $transaction['transactable_type'] == 'expenses' ? $transaction['transactable']['amount'] : 0;
                }),
            ],
        ];
    }
}
