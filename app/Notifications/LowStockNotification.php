<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Low Stock Alert for ' . $this->product->name)
            ->greeting('Hello!')
            ->line('The stock for **' . $this->product->name . '** is low.')
            ->line('Current quantity: ' . $this->product->quantity)
            ->line('Please take necessary action to reorder this product.')
            ->action('View Product', url('/products/' . $this->product->id))
            ->line('Thank you for using our Smart Inventory Management System!');
    }
}
