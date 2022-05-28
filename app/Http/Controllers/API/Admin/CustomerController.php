<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customer\DeleteCustomerRequest;
use App\Http\Requests\Admin\Customer\UpdateCustomerStatusRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Models\Customer\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CustomerController extends Controller
{
    /**
     * @return JsonResponse|BinaryFileResponse
     * @throws Exception
     */
    public function index(): BinaryFileResponse|JsonResponse
    {
        return $this->datatableResponse(
            User::latest(),
            CustomerResource::class
        );
    }

    /**
     * @param User $user
     * @param UpdateCustomerStatusRequest $request
     * @return JsonResponse
     */
    public function updateStatus(User $user, UpdateCustomerStatusRequest $request): JsonResponse
    {
        try {
            User::whereIn(
                'id',
                $request->get('customer')
            )->update([
                'status' => $request->input('status')
            ]);

            return $this->success('Customer(s) status updated successfully');
        } catch (Exception $e) {
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();
            return $this->success('Customer deleted successfully');
        } catch (Exception $e) {
            return $this->fatalErrorResponse($e);
        }
    }
}
