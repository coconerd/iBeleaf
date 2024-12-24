<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReturnRefundItem;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\RefundReturnImage;
use App\Models\Order;
use Carbon\Carbon;

class ReturnRefundItemSeeder extends Seeder
{
    public function run()
    {
        // First create some delivered orders
        $user = User::orderBy('user_id', 'asc')->first();
        
        // Create 10 delivered orders
        $orders = Order::factory()
            ->count(50)
            ->delivered() // Use the delivered state we defined in OrderFactory
            ->create([
                'user_id' => $user->user_id,
                'created_at' => now()->subDays(rand(0, 365)), // Orders from 8-365 days ago
                'deliver_time' => function (array $attrs) {
                    // Convert created_at from Closure to actual date before parsing
                    $createdDate = is_string($attrs['created_at']) 
                        ? Carbon::parse($attrs['created_at']) 
                        : $attrs['created_at'];
                        
                    return $createdDate->copy()->addDays(rand(1, 3));
                }
            ]);

        // For each order, create 1-3 order items
        foreach ($orders as $order) {
            $orderItems = OrderItem::factory()
                ->count(rand(1, 3))
                ->forOrder($order)
                ->create([
					'product_id' => \App\Models\Product::orderBy('product_id', 'asc')
					->inRandomOrder()
					->first()->product_id
				]);
        }

        // Now create return/refund items for these orders
        $reasons = [
            'wrong_item' => 'Sản phẩm nhận được không đúng với đơn đặt hàng của tôi. Tôi đặt sản phẩm A, nhưng lại giao sản phẩm B. 😠',
            'damaged' => 'Sản phẩm bị hỏng trong quá trình vận chuyển. Lúc nhận hàng thật sự shock. 😭 Mong shop hỗ trợ đổi mới.',
            'not_as_described' => 'Màu sắc và chất liệu không giống như hình ��nh trên website. 😕 Review tốt thế mà khác xa thực tế.',
            'change_mind' => 'Do nhu cầu cá nhân thay đổi, mong muốn đổi sang một cây khác phù hợp hơn. Mong shop hỗ trợ. 😊',
            'quality_issue' => 'Chất lượng sản phẩm không như mong đợi. Rất bực mình! 😡 Tưởng cây xanh mướt hóa ra héo queo.',
            'other' => 'Lý do cá nhân khó giải thích. Mong shop thông cảm và hỗ trợ. 🙏'
        ];

        $statuses = ['pending', 'accepted', 'rejected', 'received', 'refunded', 'renewed'];

        // Get all order items from our newly created delivered orders
        $orderItems = OrderItem::whereIn('order_id', $orders->pluck('order_id'))->get();

        foreach ($orderItems as $orderItem) {
            // 70% chance to create a return/refund request for each order item
            if (rand(1, 100) <= 70) {
                $orderCreatedAt = Carbon::parse($orderItem->order->created_at);
                $orderDeliverTime = Carbon::parse($orderItem->order->deliver_time);
                
                // Create return/refund request 1-5 days after delivery
                $requestCreatedAt = $orderDeliverTime->copy()->addDays(rand(1, 5));
                
                $reasonTag = array_rand($reasons);
                $returnRefundItem = ReturnRefundItem::create([
                    'order_items_id' => $orderItem->order_items_id,
                    'user_id' => $user->user_id,
                    'type' => rand(0, 1) ? 'return' : 'refund',
                    'quantity' => rand(1, $orderItem->quantity),
                    'reason_tag' => $reasonTag,
                    'reason_description' => $reasons[$reasonTag],
                    'status' => $statuses[array_rand($statuses)],
                    'created_at' => $requestCreatedAt,
                    'updated_at' => $requestCreatedAt->copy()->addDays(rand(1, 2)) // Request processed within 1-2 days
                ]);

                // Add 1-3 images for each return/refund request
                for ($i = 0; $i < rand(1, 3); $i++) {
                    RefundReturnImage::create([
                        'refund_return_image' => file_get_contents(public_path('images/mock/return_' . rand(1, 5) . '.jpg')),
                        'return_refund_id' => $returnRefundItem->return_refund_id,
                        'created_at' => $returnRefundItem->created_at,
                        'updated_at' => $returnRefundItem->updated_at
                    ]);
                }
            }
        }
        
        $this->command->info('Created ' . $orders->count() . ' delivered orders with return/refund requests.');
    }
}