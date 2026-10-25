@extends('layouts.app')

@section('title', $article['Название'])

@section('content')
    <h1>{{ $article['Название'] }}</h1>

    <p>{{ $article['Дата'] }}</p>

    <img
        src="{{ asset($article['full_image']) }}"
        alt="{{ $article['Название'] }}"
        style="max-width: 100%; height: auto;"
    >

    <p>{{ $article['Описание'] }}</p>

    <a href="{{ route('home') }}">Вернуться к статьям</a>
@endsection
