<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Http\Requests\Admin\Auth\RegisterRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Models\Admin\Admin;
use App\Models\Role;
use App\Notifications\Admin\Auth\NewAdminNotification;
use App\Notifications\TestNotification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $password = generateRandomString(7);
            $admin = Admin::create([
                'full_name' => $request->input('full_name'),
                'email' => $request->input('email'),
                'password' => Hash::make($password),
                'role_id' => Role::whereSlug(Admin::ROLE_SUPER_ADMIN)->first()->id,
            ]);
            $admin->notify(new NewAdminNotification($password));
            return $this->successResponse($admin, 'Admin user added successfully');
        } catch (Exception $e) {
            return $this->fatalErrorResponse($e);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $admin = Admin::query()->whereEmail($request->input('email'))
                ->with('role')
                ->first();

            if (!$admin || !Hash::check($request->input('password'), $admin->password)) {
                return response()->json(['error' => 'Invalid credentials'], Response::HTTP_BAD_REQUEST);
            }

            $response['token'] =  $admin->createToken('admin', ['admin-access'])->accessToken;
            $response['user'] =  AdminResource::make($admin);
            $admin->logged_in_at = now();
            $admin->save();

            return $this->successResponse($response, 'Login successful');
        } catch (Exception $e) {
            return $this->fatalErrorResponse($e);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function session(Request $request): JsonResponse
    {
        $vendor = Admin::query()->with('role')
            ->find($request->user()->id);

        return response()->json([
            'status' => true,
            'message' => 'Authenticated',
            'user' => AdminResource::make($vendor),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();
        $request->user()->logged_out_at = now();
        $request->user()->save();
        return $this->success('Successfully logged out');
    }
}
