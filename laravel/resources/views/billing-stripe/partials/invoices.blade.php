<x-heading title="Invoices" description="Here you can see all the invoices and download them." />

<x-ui.card class="w-full gap-4! py-4!">
    @php($invoices = user()->invoices())
    @foreach ($invoices as $key => $invoice)
        <div class="flex items-center justify-between px-4">
            <div class="flex items-center gap-10">
                <span>{{ $invoice->date()->toFormattedDateString() }}</span>
                <span>{{ $invoice->total() }}</span>
            </div>
            <a href="{{ route('billing.invoices.download', ['id' => $invoice->id]) }}" class="underline">
                View receipt
            </a>
        </div>
        @if (count($invoices) > 1 && $key < count($invoices) - 1)
            <x-ui.separator />
        @endif
    @endforeach
</x-ui.card>
