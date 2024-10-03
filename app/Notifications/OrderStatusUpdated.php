<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $action;

    public function __construct(Order $order, $action)
    {
        $this->order = $order;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $subject = '';
        $message = '';
        $orderUrl = route('orders.show', ['id' => $this->order->id]);

        switch ($this->action) {
            case 'created':
                $subject = 'New Order Created';
                $message = 'A new order has been generated for the product: ' . $this->order->product->name . '. Please review it.';
                break;

            case 'approved':
                $subject = 'Order Approved';
                $message = 'The order for the product: ' . $this->order->product->name . ' has been approved. Please! ' . $this->order->supplier->name . ' check this order.';
                break;

            case 'disapproved':
                $subject = 'Order Disapproved';
                $message = 'Sorry! The order for the product: ' . $this->order->product->name . ' has been disapproved.';
                break;

            case 'shipped':
                $subject = 'Order Shipped';
                $message = 'The order for the product: ' . $this->order->product->name . ' has been accepted and shipped it.';
                break;

            case 'rejected':
                $subject = 'Order Rejected';
                $message = 'Sorry! The order for the product: ' . $this->order->product->name . ' has been rejected.';
                break;

            case 'delivered':
                $subject = 'Order Delivered';
                $message = 'Thank you! The order for the product: ' . $this->order->product->name . ' has been delivered.';
                break;

            case 'cancelled':
                $subject = 'Order Cancelled';
                $message = 'Sorry! The order for the product: ' . $this->order->product->name . ' has been cancelled.';
                break;

            default:
                $subject = 'Order Update';
                $message = 'The order for the product: ' . $this->order->product->name . ' has been updated.';
                break;
        }

        $mailMessage = (new MailMessage)
            ->subject($subject)
            ->line($message)
            ->line('Order Quantity: ' . $this->order->quantity)
//            ->action('View Order', $orderUrl)
            ->line('Thank you for using our application!');

        if ($this->action === 'created') {
            $mailMessage->line('To take action, please review the order:')
                ->action('Review Order', $orderUrl);
        }
        return $mailMessage;
    }

}
