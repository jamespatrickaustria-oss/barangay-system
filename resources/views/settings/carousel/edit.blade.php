@extends('layouts.app')

@section('title', 'Edit Carousel Slide')

@section('content')
@php
    $routePrefix = $routePrefix ?? (auth()->check() ? auth()->user()->role : 'admin');
@endphp

<style>
    .edit-shell {
        padding: 18px 0 44px;
    }

    .breadcrumb {
        display: flex;
        gap: 8px;
        align-items: center;
        margin-bottom: 24px;
        font-size: 14px;
    }

    .breadcrumb a {
        color: #1d4ed8;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .breadcrumb span {
        color: #64748b;
    }

    .page-header {
        margin-bottom: 32px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 32px;
        font-weight: 700;
        color: #0f172a;
    }

    .page-header p {
        margin: 8px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .card {
        background: white;
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 18px;
        padding: 28px;
        box-shadow: 0 22px 60px rgba(15, 23, 42, 0.08);
    }
</style>

<div class="edit-shell">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route($routePrefix . '.carousel-settings.index') }}">Carousel Management</a>
            <span>/</span>
            <span>Edit Slide #{{ $slide->slot }}</span>
        </div>

        <div class="page-header">
            <h1>Edit Carousel Slide</h1>
            <p>Update the details for slot {{ $slide->slot }}. Changes are saved immediately.</p>
        </div>

        <div class="card">
            @include('settings.carousel.form', ['slide' => $slide, 'routePrefix' => $routePrefix])
        </div>
    </div>
</div>
@endsection
