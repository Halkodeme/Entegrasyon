@extends('layouts.app')

@section('title', '3D Ödeme İşlemi')

@section('content')
    <div class="container mt-4 mb-5">
        <div class="card shadow-lg border-0" style="background: rgba(255, 255, 255, 0.9); border-radius: 15px;">
            <div class="card-body p-4">

                <h2 class="h4 text-dark mb-4 border-bottom pb-2">3D Güvenli Ödeme İşlemleri</h2>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('process-3d') }}" method="POST" id="payment-form">
                    @csrf
                    <input type="hidden" name="hash_key" id="hash_key" value="">

                    @php
                        $invoice_id = date('Ymd') . '-' . rand(1000, 9999);
                    @endphp

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="invoice_id" class="font-weight-bold text-dark small text-uppercase">Fatura Numarası</label>
                            <input type="text" class="form-control bg-light" id="invoice_id" name="invoice_id" value="{{ $invoice_id }}" readonly>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="invoice_description" class="font-weight-bold text-dark small text-uppercase">Fatura Açıklaması</label>
                            <input type="text" class="form-control" id="invoice_description" name="invoice_description" placeholder="Hizmet Bedeli" required>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label for="cc_holder_name" class="font-weight-bold text-dark small text-uppercase">Kart Üzerindeki İsim / Soyisim</label>
                        <input type="text" class="form-control" id="cc_holder_name" name="cc_holder_name" placeholder="AD SOYAD" required>
                    </div>

                    <div class="form-group">
                        <label for="cc_no" class="font-weight-bold text-dark small text-uppercase">Kart Numarası</label>
                        <input type="text" class="form-control" id="cc_no" name="cc_no" maxlength="16" placeholder="0000 0000 0000 0000"
                               onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" required oninput="updateInstallments()">
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="expiry_month" class="font-weight-bold text-dark small text-uppercase">Ay (AA)</label>
                            <input type="text" class="form-control" id="expiry_month" name="expiry_month"
                                   maxlength="2" placeholder="01" required
                                   oninput="validateExpiry(this, 'month')">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="expiry_year" class="font-weight-bold text-dark small text-uppercase">Yıl (YY)</label>
                            <input type="text" class="form-control" id="expiry_year" name="expiry_year"
                                   maxlength="2" placeholder="26" required
                                   oninput="validateExpiry(this, 'year')">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="cvv" class="font-weight-bold text-dark small text-uppercase">CVV</label>
                            <input type="text" class="form-control" id="cvv" name="cvv" maxlength="3" placeholder="***" required
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, '');">
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-md-5 form-group">
                            <label for="total" class="font-weight-bold text-dark small text-uppercase">Tutar</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="total" name="total" placeholder="0.00" required
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); updateInstallments()">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-white">TL</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="installments_number" class="font-weight-bold text-dark small text-uppercase">Taksit</label>
                            <select class="form-control" id="installments_number" name="installments_number" required>
                                <option value="1">Tek Çekim</option>
                            </select>
                        </div>

                        <div class="col-md-3 form-group">
                            <label for="transaction_type" class="font-weight-bold text-dark small text-uppercase">Tip</label>
                            <select class="form-control" id="transaction_type" name="transaction_type" required>
                                <option value="Auth">Auth</option>
                                <option value="PreAuth">PreAuth</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success btn-lg btn-block shadow" style="height: 55px; font-weight: bold; font-size: 1.1rem;">
                                <i class="fas fa-shield-alt mr-2"></i> GÜVENLİ ÖDEMEYİ BAŞLAT
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let lastBin = '';
        let lastTotal = '';

        function updateInstallments() {
            const ccNo = document.getElementById('cc_no').value.replace(/\s+/g, '');
            const totalInput = document.getElementById('total');
            const total = totalInput ? totalInput.value : 0;
            const currentBin = ccNo.substring(0, 6);

            if (currentBin.length === 6 && (currentBin !== lastBin || total !== lastTotal) && total > 0) {
                lastBin = currentBin;
                lastTotal = total;

                fetch("{{ route('payment.get-pos') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept' : 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        credit_card: currentBin,
                        amount: total
                    })
                })
                    .then(response => response.json())
                    .then(res => {
                        const installmentsSelect = document.getElementById('installments_number');
                        installmentsSelect.innerHTML = '';
                        if (res.status_code === 100 && res.data) {
                            res.data.forEach((item, index) => {
                                const option = document.createElement('option');
                                option.value = item.installments_number;
                                option.setAttribute('data-hash', item.hash_key);

                                let title = item.installments_number == 1 ? "Tek Çekim" : `${item.installments_number} Taksit`;
                                option.text = `${title} - ${item.amount_to_be_paid} ${item.currency_code}`;
                                installmentsSelect.appendChild(option);

                                // İlk taksitin hash'ini saklı alana yaz
                                if(index === 0) document.getElementById('hash_key').value = item.hash_key;
                            });
                        } else {
                            installmentsSelect.innerHTML = '<option value="1">Taksit seçenekleri alınamadı</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Hata:', error);
                        lastBin = '';
                        lastTotal = '';
                    });
            }
        }

        function validateExpiry(input, type) {
            input.value = input.value.replace(/[^0-9]/g, '');

            let value = parseInt(input.value);

            if (type === 'month') {
                if (value > 12) {
                    input.value = '12';
                }
                if (input.value.length === 1 && value > 1 && value <= 9) {
                }
            }

            if (type === 'year') {
                if (input.value.length === 2 && value < 26) {
                    input.value = '26';
                }
            }
        }

        document.getElementById('installments_number').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('hash_key').value = selectedOption.getAttribute('data-hash');
        });
    </script>
@endsection
