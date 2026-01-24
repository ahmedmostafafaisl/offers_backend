<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Payment;
use App\Models\User;

class PaymentsSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ اجلب كل user ids (لو فاضيين هتبقى null)
        $userIds = User::pluck('id')->toArray();

        // ✅ قيم ثابتة
        $types = ['geidea', 'clickpay', 'cash', 'tamara', 'tabby'];
        $statuses = ['pending', 'paid', 'failed'];

        // ✅ أسماء عناصر (items) شكلها حقيقي
        $itemNames = [
            'Offer Subscription',
            'Premium Listing',
            'Featured Offer',
            'Boost Package',
            'Service Discount',
            'Banner Ad',
            'Monthly Plan',
            'Annual Plan',
        ];

        // ✅ ننشئ 10 مدفوعات (أو زوّد الرقم براحتك)
        $count = 10;

        for ($i = 1; $i <= $count; $i++) {

            $itemsCount = rand(2, 4);
            $items = [];
            $sum = 0;

            for ($j = 1; $j <= $itemsCount; $j++) {

                $price = rand(10, 250);     // price
                $qty   = rand(1, 3);        // quantity
                $total = $price * $qty;     // total_amount

                $sum += $total;

                $items[] = [
                    'name'         => $itemNames[array_rand($itemNames)],
                    'item_number'  => 'IT-' . Str::upper(Str::random(6)),
                    'price'        => $price,
                    'quantity'     => $qty,
                    'total_amount' => $total,
                ];
            }

            $status = $statuses[array_rand($statuses)];

            // ✅ ربط payment بيوزر موجود (أو null لو مفيش users)
            $userId = !empty($userIds) ? $userIds[array_rand($userIds)] : null;

            // ✅ رقم سعودي تجريبي
            $phone = '05' . rand(10000000, 99999999);

            // ✅ create payment
            $payment = Payment::create([
                'user_id'      => $userId,
                'payment_type' => $types[array_rand($types)],
                'amount'       => $sum,
                // gateway payment id (لو paid غالبًا)
                'payment_id'   => $status === 'paid' ? (string) Str::uuid() : null,
                'status'       => $status,
                'phone'        => $phone,
            ]);

            // ✅ create items
            foreach ($items as $it) {
                $payment->items()->create($it);
            }

            // ✅ sync amount again from DB to guarantee consistency
            $payment->update([
                'amount' => $payment->items()->sum('total_amount'),
            ]);
        }
    }
}
