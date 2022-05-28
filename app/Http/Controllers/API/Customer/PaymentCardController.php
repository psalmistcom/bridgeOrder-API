<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Card\CreateCardRequest;
use App\Http\Requests\Customer\Card\DeleteCardRequest;
use App\Http\Requests\Customer\Card\TopUpWalletWithCardRequest;
use App\Http\Requests\Customer\Card\UpdateCardStatusRequest;
use App\Models\Customer\User;
use App\Models\Finance\PaymentCard;
use App\Services\Finance\PaymentCardService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class PaymentCardController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            return $this->successResponse(PaymentCard::whereUserId($request->user()->id)->get());
        } catch (Exception $e) {
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param CreateCardRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function addCard(CreateCardRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            PaymentCardService::addCard($request->user(), $request->input('reference'));
            DB::commit();
            return $this->success('Payment card added successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param UpdateCardStatusRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function makeCardActive(UpdateCardStatusRequest $request): JsonResponse
    {
        try {
            $card = PaymentCard::whereId($request->input('card_id'))->first();
            DB::beginTransaction();
            PaymentCardService::makeCardActive($request->user(), $card);
            DB::commit();
            return $this->success('Payment card made active successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param TopUpWalletWithCardRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function topUpWalletWithCard(TopUpWalletWithCardRequest $request): JsonResponse
    {
        try {
            $card = PaymentCard::whereId($request->user()->activePaymentCard->id)->first();
            DB::beginTransaction();
            PaymentCardService::topUpWalletWithCard($request->user(), $card, $request->input('amount'), User::class);
            DB::commit();
            return $this->success('Wallet top up successful.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param DeleteCardRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(DeleteCardRequest $request): JsonResponse
    {
        try {
            $card = PaymentCard::whereId($request->input('card_id'))->first();
            DB::beginTransaction();
            PaymentCardService::deleteCard($request->user(), $card);
            DB::commit();
            return $this->success('Payment card deleted successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->fatalErrorResponse($e);
        }
    }
}
