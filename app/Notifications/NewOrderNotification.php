<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

    /**
     * Qaysi kanallar orqali yuboriladi (email va database).
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Email orqali yuboriladigan xabar.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Yangi buyurtma qabul qilindi')
            ->line("Buyurtma raqami: #{$this->order->id}")
            ->line("Foydalanuvchi: {$this->order->user->name}")
            ->line("Kitob: {$this->order->book->title}")
            ->line("Soni: {$this->order->stock}")
            ->action('Admin panelga o‘tish', url('/admin/orders/' . $this->order->id))
            ->line('Iltimos tezroq ko‘rib chiqing!');
    }

    /**
     * Ma'lumotlar bazasiga saqlanadigan xabar.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'Yangi buyurtma: #' . $this->order->id,
            'order_id' => $this->order->id,
            'user' => $this->order->user->name,
            'book' => $this->order->book->title,
            'stock' => $this->order->stock,
            'url' => '/admin/orders/' . $this->order->id,
        ];
    }
}
