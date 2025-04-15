<?php
namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewOrderNotification extends Notification
{
    use Queueable;

    public function __construct(protected Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Yangi buyurtma qabul qilindi')
            ->line("Buyurtma raqami: #{$this->order->id}")
            ->line("Foydalanuvchi: {$this->order->user->name}")
            ->line("Kitob: {$this->order->book->title}")
            ->line("Soni: {$this->order->stock}")
            ->action('Admin panelga o‘tish', url('/admin/orders'))
            ->line('Iltimos tezroq ko‘rib chiqing!');
    }
}
