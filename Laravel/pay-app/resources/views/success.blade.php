@extends('layouts.app')

@section('title', 'İşlem Başarılı')

@section('content')
    <div class="container mt-5">
        <div class="card bg-dark text-white shadow">
            <div class="card-header bg-success">
                <h1 class="h4 mb-0">İşlem Başarılı!</h1>
            </div>
            <div class="card-body">
                {{-- Laravel'de POST verilerini request() ile çekiyoruz --}}
                <div class="result-item mb-2">
                    <span class="font-weight-bold text-info">Sipariş No (order_no):</span> {{ request('order_no') }}
                </div>
                <div class="result-item mb-2">
                    <span class="font-weight-bold text-info">Sipariş ID (order_id):</span> {{ request('order_id') }}
                </div>
                <div class="result-item mb-2">
                    <span class="font-weight-bold text-info">Fatura ID (invoice_id):</span> {{ request('invoice_id') }}
                </div>
                <div class="result-item mb-2">
                    <span class="font-weight-bold text-info">Durum Kodu (status_code):</span> {{ request('status_code') }}
                </div>
                <div class="result-item mb-2">
                    <span class="font-weight-bold text-info">İşlem Tipi (transaction_type):</span> {{ request('transaction_type') }}
                </div>
                <div class="result-item mb-2">
                    <span class="font-weight-bold text-info">Ödeme Durumu (payment_status):</span> {{ request('payment_status') }}
                </div>
                <div class="result-item mb-2">
                    <span class="font-weight-bold text-info">3D Durumu (md_status):</span> {{ request('md_status') }}
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('payment.index') }}" class="btn btn-primary">Yeni Ödeme Yap</a>
            </div>
        </div>
    </div>
@endsection
