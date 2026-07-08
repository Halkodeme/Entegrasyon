@extends('layouts.app')

@php
    // status_code 100 ise başarılı kabul ediyoruz
    $statusCode = request('data.status_code');
    $isSuccess = ($statusCode == 100);

    // Dinamik sınıflar
    $headerClass = $isSuccess ? 'bg-success' : 'bg-danger';
    $textClass = $isSuccess ? 'text-success' : 'text-danger';
    $alertClass = $isSuccess ? 'alert-outline-success border-success text-success' : 'alert-outline-danger border-danger text-danger';
    $title = $isSuccess ? 'İşlem Başarılı' : 'İşlem Başarısız';
@endphp

@section('title', $title)

@section('content')
    <div class="container mt-5">
        <div class="card bg-dark text-white shadow">
            <div class="card-header {{ $headerClass }}">
                <h1 class="h4 mb-0">{{ $title }}!</h1>
            </div>
            <div class="card-body">
                <p class="{{ $isSuccess ? 'text-light' : 'text-warning' }}">
                    {{ $isSuccess ? 'İade işleminiz başarıyla onaylanmıştır.' : 'İşlem gerçekleştirilirken bir hata oluştu. Lütfen detayları kontrol ediniz.' }}
                </p>
                <hr class="bg-secondary">

                @if(request('data.status_description'))
                    <div class="alert {{ $alertClass }} mb-4">
                        <strong>Banka Mesajı:</strong> {{ request('data.status_description') }}
                    </div>
                @endif

                <div class="row">
                    @if(request('data.order_no'))
                        <div class="col-md-6 mb-2">
                            <span class="font-weight-bold text-info">Sipariş No:</span> {{ request('data.order_no') }}
                        </div>
                    @endif

                    @if(request('data.invoice_id'))
                        <div class="col-md-6 mb-2">
                            <span class="font-weight-bold text-info">Fatura ID:</span> {{ request('data.invoice_id') }}
                        </div>
                    @endif

                    @if(request('data.status_code'))
                        <div class="col-md-6 mb-2">
                            <span class="font-weight-bold text-info">Durum Kodu:</span>
                            <span class="badge {{ $isSuccess ? 'badge-success' : 'badge-danger' }}">
                                {{ request('data.status_code') }}
                            </span>
                        </div>
                    @endif

                    @if(request('data.transaction_id'))
                        <div class="col-md-6 mb-2">
                            <span class="font-weight-bold text-info">İşlem ID:</span> {{ request('data.transaction_id') }}
                        </div>
                    @endif

                    @if(request('data.transaction_type'))
                        <div class="col-md-6 mb-2">
                            <span class="font-weight-bold text-info">İşlem Tipi:</span> {{ request('data.transaction_type') }}
                        </div>
                    @endif

                    {{-- Refund'a özel alanlar --}}
                    @if(request('data.total_refunded_amount'))
                        <div class="col-md-6 mb-2">
                            <span class="font-weight-bold text-info">İade Edilen Tutar:</span>
                            <span class="text-success">{{ number_format(request('data.total_refunded_amount'), 2) }} TRY</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                @if(!$isSuccess)
                    <a href="{{ route('refund-view.index') }}" class="btn btn-warning">Tekrar Dene</a>
                @else
                    <button onclick="window.print()" class="btn btn-info">Yazdır</button>
                @endif
                <a href="/" class="btn btn-outline-light">Ana Sayfaya Dön</a>
            </div>
        </div>
    </div>
@endsection
