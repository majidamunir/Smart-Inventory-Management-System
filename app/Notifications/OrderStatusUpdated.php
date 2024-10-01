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
                $message = 'The order for the product: ' . $this->order->product->name . ' has been approved.';
                break;

            case 'disapproved':
                $subject = 'Order Disapproved';
                $message = 'The order for the product: ' . $this->order->product->name . ' has been disapproved.';
                break;

            case 'processing':
                $subject = 'Order is Processing';
                $message = 'The order for the product: ' . $this->order->product->name . ' is now being processed.';
                break;

            case 'shipped':
                $subject = 'Order Shipped';
                $message = 'The order for the product: ' . $this->order->product->name . ' has been shipped.';
                break;

            case 'delivered':
                $subject = 'Order Delivered';
                $message = 'The order for the product: ' . $this->order->product->name . ' has been delivered.';
                break;

            case 'cancelled':
                $subject = 'Order Cancelled';
                $message = 'The order for the product: ' . $this->order->product->name . ' has been cancelled.';
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
            ->action('View Order', $orderUrl)
            ->line('Thank you for using our application!');

        if ($this->action === 'created') {
            $mailMessage->line('To take action, please review the order:')
                ->action('Review Order', $orderUrl);
        }

        return $mailMessage;
    }

}
