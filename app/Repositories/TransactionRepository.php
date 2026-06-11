<?php

namespace App\Repositories;

use App\interfaces\TransactionRepositoryInterface;
use App\Models\Product;
use App\Models\Store;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $exceute)
    {
        $query = Transaction::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        });

        if ($limit) {
            $query->take($limit);
        }

        if ($exceute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(?string $search, ?int $rowPerPage)
    {
        $query = $this->getAll(
            $search,
            $rowPerPage,
            false
        );

        return $query->paginate($rowPerPage);
    }

    public function getById(
        string $id
    ) {
        $query = Transaction::where('id', $id);

        return $query->first();
    }

    public function getByCode(
        string $code
    ) {
        $query = Transaction::where('code', $code);

        return $query->first();
    }

    public function create(
        array $data
    ) {
        DB::beginTransaction();

        try {
            $transaction = new Transaction;
            $transaction->code = 'BLUE' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $transaction->buyer_id = $data['buyer_id'];
            $transaction->store_id = $data['store_id'];
            $transaction->address_id = $data['address_id'];
            $transaction->address = $data['address'];
            $transaction->city = $data['city'];
            $transaction->postal_code = $data['postal_code'];
            $transaction->shipping = $data['shipping'];
            $transaction->shipping_type = $data['shipping_type'];
            $transaction->shipping_cost = 0;
            $transaction->tax = 0;
            $transaction->grand_total = 0;
            $transaction->save();


            $transactionDetailRepository = new TransactionDetailRepository;

            $transactionDetails = [];

            foreach ($data['products'] as $transactionDetail) {
                $transactionDetail = $transactionDetailRepository->create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $transactionDetail['product_id'],
                    'qty'            => $transactionDetail['qty']
                ]);

                $transactionDetail[] = $transactionDetail;
            }

            $subtotal = array_reduce($transactionDetails, function ($carry, $item) {
                return $carry + $item->subtotal;
            }, 0);

            $weight = $this->getTotalWeight($transactionDetails);

            $calculation = $this->calculateShippingAndTax($data, $subtotal, $weight);

            $transaction->shipping_cart = $calculation['shipping_cart'];
            $transaction->tax = $calculation['tax'];
            $transaction->grand_total = $calculation['grand_total'];
            $transaction->save();

            DB::commit();
            // Set your Merchant Server Key
            \Midtrans\Config::$serverKey = config('midtrans.serverKey');
            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            \Midtrans\Config::$isProduction = config('midtrans.isProduction');
            // Set sanitization on (default)
            \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
            // Set 3DS transaction for credit card to true
            \Midtrans\Config::$is3ds = config('midtrans.is3ds');

            $params = array(
                'transaction_details' => array(
                    'order_id' => $transaction->code,
                    'gross_amount' => $transaction->grand_total
                ),
                'customer_details' => array(
                    'first_name' => $transaction->buyer->name,
                    'email' => $transaction->buyer->email,
                ),
            );

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->snap_token = $snapToken;

            return $transaction;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    private function getTotalWeight(array $transactionDetails): int
    {
        $productIds = collect($transactionDetails)->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $totalWeight = 0;

        foreach ($transactionDetails as $item) {
            $product = $products[$item['product_id']] ?? null;

            if ($product) {
                $totalWeight += $product->weight * $item['qty'];
            }
        }

        return $totalWeight;
    }

    private function calculateShippingAndTax(array $data, float $subtotal, int $weight): array
    {
        $origin = Store::find($data['store_id'])->address_id;
        $destination = $data['address_id'];

        $response = Http::withHeaders([
            'key' => 'xxx'
        ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $data['shipping']
        ]);

        $result = $response->json();

        $shippingCost = 0;

        foreach ($result['data'] as $courier) {
            if ($courier['code'] == $data['shipping'] && $courier['service'] == $data['shipping_type']) {
                $shippingCost = $courier['cost'];
            }
        }

        return [
            'shipping_cost' => $shippingCost,
            'tax' => $subtotal * 0.11,
            'grand_total' => $subtotal * 1.11 + $shippingCost
        ];
    }
}
