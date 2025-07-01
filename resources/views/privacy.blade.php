@php
    use Illuminate\Support\Facades\File;
@endphp

@extends('layouts.home')

@section('content')
    @php
        $parsDown = new Parsedown();
    @endphp

    <main>
        <x-ui.container class="py-10">
            <x-prose>
                {!! $parsDown->text(File::get(resource_path('markdown/privacy.md'))) !!}
            </x-prose>
        </x-ui.container>
    </main>
@endsection
