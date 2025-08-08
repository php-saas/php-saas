<x-heading title="Invoices" description="Here you can see all the invoices and download them." />

@if (user()->transactions()->count() > 0)
    <x-ui.card class="w-full gap-4! py-4!">
        @php($transactions = user()->transactions()->simplePaginate(10))
        @foreach ($transactions as $key => $transaction)
            <div class="flex items-center justify-between px-4">
                <div class="flex items-center gap-10">
                    <span>{{ $transaction->created_at->format('M d, Y') }}</span>
                    <span>{{ $transaction->total / 100 }} {{ $transaction->currency }}</span>
                </div>
                <a href="{{ route('billing.invoices.download', ['transaction' => $transaction]) }}" class="underline">
                    View receipt
                </a>
            </div>
            @if (count($transactions) > 1 && $key < count($transactions) - 1)
                <x-ui.separator />
            @endif
        @endforeach
    </x-ui.card>
    {!! $transactions->links() !!}
@else
    <x-ui.alert>You don't have any invoices yet!</x-ui.alert>
@endif
