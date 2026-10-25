
@extends('layouts.app')

@section('title', 'Контакты')

@section('content')
    <h1>Контакты</h1>

    <p>Контактная информация:</p>

    <ul>
        @foreach ($contacts as $contact)
            <li>
                <strong>{{ $contact['name'] }}</strong>:
                {{ $contact['value'] }}
            </li>
        @endforeach
    </ul>
@endsection
