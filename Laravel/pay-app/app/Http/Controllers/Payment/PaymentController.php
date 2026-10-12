<?php

namespace App\Http\Controllers\Payment;

use App\Helpers\RequestHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckStatusRequest;
use App\Http\Requests\ConfirmPaymentRequest;
use App\Http\Requests\GetPosRequest;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\PurchaseLinkRequest;
use App\Http\Requests\RefundRequest;
use App\Mapper\Common\GetTokenMapper;
use App\Mapper\Payment\CheckStatusMapper;
use App\Mapper\Payment\ConfirmPaymentMapper;
use App\Mapper\Payment\GetPosMapper;
use App\Mapper\Payment\Payment2dMapper;
use App\Mapper\Payment\PurchaseLinkMapper;
use App\Mapper\Payment\RefundMapper;
use App\Requests\Common\GetTokenRequest;
use App\Requests\Payment\ConfirmPaymentRequestModel;
use App\Requests\Payment\GetCheckStatusRequestModel;
use App\Requests\Payment\Payment2DRequestModel;
use App\Requests\Payment\Payment3DRequestModel;
use App\Requests\Payment\PurchaseLinkRequestModel;
use App\Requests\Payment\RefundRequestModel;
use App\Requests\Payment\GetPosRequestModel;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    private GetTokenRequest $getTokenRequest;
    private Payment2DRequestModel $payment2dRequest;
    private Payment3DRequestModel $payment3dRequest;
    private GetPosRequestModel $posRequest;
    private RefundRequestModel $refundProcessRequest;
    private GetCheckStatusRequestModel $getCheckStatusRequest;
    private PurchaseLinkRequestModel $purchaseLinkRequestModel;
    private ConfirmPaymentRequestModel $confirmPaymentRequestModel;
    public function __construct(
        GetTokenRequest            $getTokenRequest,
        Payment2DRequestModel      $payment2dRequest,
        Payment3DRequestModel      $payment3dRequest,
        GetPosRequestModel          $posRequest,
        RefundRequestModel       $refundProcessRequest,
        GetCheckStatusRequestModel $getCheckStatusRequest,
        PurchaseLinkRequestModel $purchaseLinkRequestModel,
        ConfirmPaymentRequestModel $confirmPaymentRequestModel,
    ){
        $this->getTokenRequest = $getTokenRequest;
        $this->payment2dRequest = $payment2dRequest;
        $this->payment3dRequest = $payment3dRequest;
        $this->posRequest = $posRequest;
        $this->refundProcessRequest = $refundProcessRequest;
        $this->getCheckStatusRequest = $getCheckStatusRequest;
        $this->purchaseLinkRequestModel = $purchaseLinkRequestModel;
        $this->confirmPaymentRequestModel = $confirmPaymentRequestModel;
    }

    public function getToken()
    {
        $apiUrl = getenv('BASE_URL') . 'api/token';

        $this->getTokenRequest->setAppId(config('services.payment.app_id'));
        $this->getTokenRequest->setAppSecret(config('services.payment.app_secret'));

        $body = $this->getTokenRequest->getTokenData();
        $client = new Client([
            'verify' => false
        ]);

        try {
            $response = $client->post($apiUrl, [
                'body' => $body,
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);
            $data = GetTokenMapper::map($response);
            $token = $data->getData()->getToken();
            $expiredAt = $data->getData()->getExpiredAt();
            $dataArray = $data->toArray();
            $statusCode = $data->getStatusCode();
            if ($statusCode === 100) {
                Log::channel('info')->info('Token alındı.');
                Session::put('token', $token);
                Session::put('expiredAt', $expiredAt);
                Session::save();
                return $dataArray;
            } else {
                Log::channel('error')->error('Token alırken bir hata oluştu. Hata kodu: ', [$statusCode]);
                return response()->json(['message' => 'Token alırken bir hata oluştu. Hata kodu: ' . $statusCode]);
            }
        } catch (\Exception $e) {
            Log::channel('error')->error('Token alırken bir hata oluştu. Hata kodu: ', [$e->getMessage()]);
            return response()->json(['message' => 'Token alırken bir hata oluştu: ' . $e->getMessage()]);
        }

    }

    /**
     * @param GetPosRequest $request
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPos(GetPosRequest $request)
    {
        $this->getToken();
        $tokenValue = Session::get('token');
        $apiUrl = config('services.payment.base_url').'api/getpos';
        $client = new Client([
            'verify' => false
        ]);
        $validatedData = $request->validated();
        RequestHelper::getPosRequest($this->posRequest,$validatedData);
        $body = $this->posRequest->getData();

        try {
            $response = $client->post($apiUrl,[
                'body' => $body,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $tokenValue,
                ]
            ]);

            $data = GetPosMapper::map($response);
            $status_code = $data->getStatusCode();
            if($status_code === 100) {
                $responseData = json_decode($response->getBody(),true);
                Log::channel('info')->info('Get Pos Başarılı:',[$data->toJson()]);
                return $responseData;
            }else {
                Log::channel('error')->error('İşlem başarısız.');
                return response()->json(['message' => 'İşlem başarısız.']);
            }
        }catch (\Exception $e){
            Log::channel('error')->error('İşlem sırasında bir hata oluştu: ',[$e->getMessage()]);
            return response()->json(['message' => 'İşlem sırasında bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    public function processPayment3d(PaymentRequest $request)
{
    $apiUrl = config('services.payment.base_url') . 'api/paySmart3D';

    $validatedData = $request->validated();
    $total = (float) $validatedData['total'];

    RequestHelper::payment3dRequest($this->payment3dRequest, $validatedData);
    $this->payment3dRequest->setItems($this->getItemRequestData($total));
    $body = $this->payment3dRequest->toArray();

    $client = new Client([
        'verify'          => false, // production'da true yap
        'timeout'         => 30,
        'allow_redirects' => false,
    ]);

    try {
        $response = $client->post($apiUrl, [
            'json'    => $body,
            'headers' => ['Authorization' => 'Bearer ' . Session::get('token')],
        ]);

        $status = $response->getStatusCode();

        // Sağlayıcı sonucu return_url'e yönlendiriyorsa: kendi sayfana geç
        if ($status >= 300 && $status < 400) {
            $location = $response->getHeaderLine('Location');
            parse_str((string) parse_url($location, PHP_URL_QUERY), $params);

            $route = (($params['payment_status'] ?? '0') === '1') ? 'success' : 'error';
            return redirect()->route($route, $params);
        }

        if ($status === 200) {
            Log::channel('info')->info('3D Başarılı.');
            return response((string) $response->getBody()); // 3D doğrulama HTML'i
        }

        Log::channel('error')->error('3D Ödeme işlemi başarısız.', ['status' => $status]);
        return redirect()->route('error', ['status_description' => 'Ödeme işlemi başarısız.']);
    } catch (\Throwable $e) {
        Log::channel('error')->error('3D ödeme hatası: ' . $e->getMessage()); // getTrace() loglama
        return redirect()->route('error', [
            'status_description' => 'Ödeme işlemi sırasında bir hata oluştu. Lütfen tekrar deneyin.',
        ]);
    }
}

    /**
     * @param PaymentRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processPayment2d(PaymentRequest $request)
    {
        $apiUrl = config('services.payment.base_url').'api/paySmart2D';

        $validatedData = $request->validated();
        $total = (float)$validatedData['total'];

        RequestHelper::payment2dRequest($this->payment2dRequest,$validatedData);

        $itemRequestData = $this->getItemRequestData($total);

        $this->payment2dRequest->setItems($itemRequestData);

        $body = $this->payment2dRequest->toJson();

        $client = new Client([
            'verify' => false,
        ]);
        try {
            $response = $client->post($apiUrl, [
                'body' => $body,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . Session::get('token'),
                ]
            ]);
            $data = Payment2dMapper::map($response);
            $dataArray = $data->toArray();
            $status_code = $data->getStatusCode();
            if($status_code === 100) {
                Log::channel('info')->info('2D Başarılı.',[$dataArray]);
                return redirect()->route('success', $dataArray['data']);
            } else {
                Log::channel('error')->error('2D Başarısız.',[$status_code]);
                return redirect()->route('error',$dataArray['data']);
            }
        } catch (\Exception $e) {
            Log::channel('error')->error('Ödeme işlemi sırasında bir hata oluştu: ',[$e->getMessage()]);
            return response()->json(['message' => 'Ödeme işlemi sırasında bir hata oluştu: ' . $e->getMessage()]);
        }
    }

    public function refund(RefundRequest $request)
    {
        $apiUrl = config('services.payment.base_url').'api/refund';
        $client = new Client([
            'verify' => false
        ]);
        $validatedData = $request->validated();
        RequestHelper::getRefundRequest($this->refundProcessRequest,$validatedData);
        $body = $this->refundProcessRequest->getData();
        Log::channel('info')->info('Refund İsteği:',[$body]);

        try {
            $response = $client->post($apiUrl,[
                'body' => $body,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . Session::get('token'),
                ]
            ]);
            $responseData = json_decode($response->getBody(),true);
            if (isset($responseData['status_code']) && $responseData['status_code'] != 100) {
                Log::channel('error')->error('Refund API Hatası:', $responseData);

                return redirect()->route('response-refund.index', ['data' => $responseData]);
            }
            $data = RefundMapper::map($response);
            $status_code = $data->getStatusCode();
            Log::channel('info')->info('Refund response:', $data->toArray());
            if($status_code === 100) {
                $responseData = json_decode($response->getBody(),true);
                Log::channel('info')->info('Refund Başarılı:',[$data->toJson()]);
                return redirect()->route('response-refund.index', ['data' => $responseData]);
            }else {
                Log::channel('error')->error('İşlem başarısız.');
                return response()->json(['message' => 'İşlem başarısız.']);
            }
        }catch (\Exception $e){
            Log::channel('error')->error('İşlem sırasında bir hata oluştu: ',[$e->getMessage()]);
            return response()->json(['message' => 'İşlem sırasında bir hata oluştu: ' . $e->getMessage()]);
        }

    }

    public function checkStatus(CheckStatusRequest $request)
    {
        $apiUrl = config('services.payment.base_url').'api/checkstatus';
        $client = new Client([
            'verify' => false
        ]);
        $validatedData = $request->validated();
        RequestHelper::getCheckStatusRequest($this->getCheckStatusRequest,$validatedData);
        $body = $this->getCheckStatusRequest->getData();
        Log::channel('info')->info('CheckStatus İsteği:',[$body]);

        try {
            $response = $client->post($apiUrl,[
                'body' => $body,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . Session::get('token'),
                ]
            ]);
            $responseData = json_decode($response->getBody(),true);
            if (isset($responseData['status_code']) && $responseData['status_code'] != 100) {
                Log::channel('error')->error('CheckStatus API Hatası:', $responseData);

                return redirect()->route('check-status-response', ['data' => $responseData]);
            }
            $data = CheckStatusMapper::map($response);
            $status_code = $data->getStatusCode();
            Log::channel('info')->info('CheckStatus response:', $data->toArray());
            if($status_code === 100) {
                $responseData = json_decode($response->getBody(),true);
                Log::channel('info')->info('CheckStatus Başarılı:',[$data->toJson()]);
                return redirect()->route('check-status-response', ['data' => $responseData]);
            }else {
                Log::channel('error')->error('İşlem başarısız.');
                return response()->json(['message' => 'İşlem başarısız.']);
            }
        }catch (\Exception $e){
            Log::channel('error')->error('İşlem sırasında bir hata oluştu: ',[$e->getMessage()]);
            return response()->json(['message' => 'İşlem sırasında bir hata oluştu: ' . $e->getMessage()]);
        }

    }


    public function purchaseLink(PurchaseLinkRequest $request)
    {
        $apiUrl = config('services.payment.base_url').'purchase/link';
        $client = new Client([
            'verify' => false
        ]);
        $validatedData = $request->validated();
        RequestHelper::getPurchaseLinkRequest($this->purchaseLinkRequestModel,$validatedData);
        $body = PurchaseLinkRequestModel::finalizePurchaseLinkData($this->purchaseLinkRequestModel);
        Log::channel('info')->info('Purchase Link İsteği:',[$body]);

        try {
            $response = $client->post($apiUrl,[
                'json' => $body,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . Session::get('token'),
                ]
            ]);

            $data = PurchaseLinkMapper::map($response);
            if ($data->getStatusCode() == 100) {
                return redirect()->away($data->getLink());
            }

            return redirect()->route('purchase-link.index', ['data' => $data->toArray()]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['msg' => $e->getMessage()]);
        }

    }

    public function confirmPayment(ConfirmPaymentRequest $request)
    {
        $apiUrl = config('services.payment.base_url').'api/confirmPayment';

        $client = new Client([
            'verify' => false
        ]);
        $validatedData = $request->validated();
        RequestHelper::getConfirmPaymentRequest($this->confirmPaymentRequestModel,$validatedData);
        $body = $this->confirmPaymentRequestModel->getData();

        Log::channel('info')->info('Confirm payment İsteği:',[$body]);

        try {
            $response = $client->post($apiUrl,[
                'body' => $body,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . Session::get('token'),
                ]
            ]);

            $data = ConfirmPaymentMapper::map($response);
            Log::channel('info')->info('ConfirmPayment response:', $data->toArray());
            if ($data->getStatusCode() == 100) {
                $responseData = json_decode($response->getBody(),true);
                Log::channel('info')->info('ConfirmPayment Başarılı:',[$data->toJson()]);
                return redirect()->route('confirm-payment-response', ['data' => $responseData]);
            }else {
                Log::channel('error')->error('İşlem başarısız.');
                $responseData = json_decode($response->getBody(),true);

                return redirect()->route('confirm-payment-response', ['data' => $responseData]);
            }

        }catch (\Exception $e){
            Log::channel('error')->error('İşlem sırasında bir hata oluştu: ',[$e->getMessage()]);
            return response()->json(['message' => 'İşlem sırasında bir hata oluştu: ' . $e->getMessage()]);

        }

    }

    public function payment3dView()
    {
        return view('payment-process-3D');
    }

    public function payment2dView()
    {
        return view('payment-process-2D');
    }

    public function refundView()
    {
        return view('refund');
    }

    public function responseRefund()
    {
        return view('response-refund');
    }

    public function checkStatusView()
    {
        return view('checkstatus');
    }

    public function checkStatusResponse()
    {
        return view('response-checkstatus');
    }

    public function purchaseLinkView()
    {
        return view('purchase-link');
    }

    public function confirmPaymentView()
    {
        return view('confirm-payment');
    }

    public function confirmPaymentResponse()
    {
        return view('response-confirm-payment');
    }


    public function error(Request $request)
    {
        $params = $request->only([
            'invoice_id', 'order_no', 'status_code', 'status_description', 'pos_bank',
        ]);
        Log::channel('info')->info('Bankadan dönüş (hata):', $params);

        return view('error', ['p' => $params]);
    }

    public function success(Request $request)
    {
        $params = $request->only([
            'invoice_id', 'order_no', 'order_id', 'credit_card_no', 'auth_code',
            'status_description', 'pos_bank', 'transaction_type',
        ]);
        Log::channel('info')->info('Bankadan dönüş (başarılı):', $params);

        return view('success', ['p' => $params]);
    }

    public function index()
    {
        return view('index');
    }

    public function parameters()
    {
        return view('parameters');
    }

    /**
     * @param float $total
     * @return array<int, array{name: string, price: float, quantity: int, description: string}>
     */
    public function getItemRequestData(float $total): array
    {
        return [
            [
                'name'        => 'item 1',
                'price'       => round($total, 2),
                'quantity'    => 1,
                'description' => 'items description',
            ],
        ];
    }

}
