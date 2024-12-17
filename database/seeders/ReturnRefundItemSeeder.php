<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReturnRefundItem;
use App\Models\OrderItem;
use App\Models\RefundReturnImage;

class ReturnRefundItemSeeder extends Seeder
{
    public function run()
    {
        // Get the first user's delivered order items
        $orderItems = OrderItem::whereHas('order', function($query) {
            $query->where('user_id', 1)
                  ->where('status', 'delivered'); // Only get items from delivered orders
        })->get();

        if ($orderItems->isEmpty()) {
            $this->command->info('No delivered orders found for the first user.');
            return;
        }

        // Sample reason tags and descriptions
        $reasons = [
            'wrong_item' => 'Sản phẩm nhận được không đúng với đơn đặt hàng của tôi. Tôi đặt sản phẩm A, nhưng lại giao sản phẩm B',
            'damaged' => 'Sản phẩm bị hư hỏng trong quá trình vận chuyển',
            'not_as_described' => 'Màu sắc và chất liệu không giống như hình ảnh trên website',
            'change_mind' => 'Muốn đổi sang một cây khác phù hợp hơn',
            'quality_issue' => 'Chất lượng sản phẩm không như mong đợi',
			'other' => 'Lý do cá nhân khó có thể giải thích',
        ];

		$reasonTag = array_rand($reasons);
		$reasonDescription = $reasons[$reasonTag];

        // Create return/refund items only for delivered orders
        foreach($orderItems->take(5) as $orderItem) {
            // Only create return/refund if order is within 7 days of delivery
            $orderDeliveryDate = $orderItem->order->updated_at;
            $daysAfterDelivery = rand(1, 7); // Random day within 7 days after delivery
            
            $returnRefundItem = ReturnRefundItem::create([
                'order_items_id' => $orderItem->order_items_id,
                'user_id' => 1,
                'type' => rand(0, 1) ? 'return' : 'refund',
                'quantity' => rand(1, $orderItem->quantity),
                'reason_tag' => $reasonTag,
                'reason_description' => $reasonDescription,
                'status' => ['pending', 'accepted', 'rejected', 'received'][rand(0, 3)],
                'created_at' => $orderDeliveryDate->copy()->addDays($daysAfterDelivery),
                'updated_at' => $orderDeliveryDate->copy()->addDays($daysAfterDelivery + rand(0, 2))
            ]);

            // Create 1-3 images for each return/refund item
            $imageCount = rand(1, 3);
            for($i = 0; $i < $imageCount; $i++) {
                RefundReturnImage::create([
                    'refund_return_image' => file_get_contents(public_path('images/mock/return_' . rand(1, 5) . '.jpg')),
                    'return_refund_id' => $returnRefundItem->return_refund_id,
                    'created_at' => $returnRefundItem->created_at,
                    'updated_at' => $returnRefundItem->updated_at
                ]);
            }
        }
    }
}