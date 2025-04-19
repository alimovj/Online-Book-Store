@foreach ($orders as $order)
<tr>
    <td>{{ $order->id }}</td>
    <td>{{ $order->book->title }}</td>
    <td>{{ $order->user->name }}</td>
    <td>
        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            <select name="status" onchange="this.form.submit()">
                <option value="pending"   {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="on_way"    {{ $order->status == 'on_way' ? 'selected' : '' }}>On Way</option>
                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="canceled"  {{ $order->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
        </form>
    </td>
</tr>
@endforeach
