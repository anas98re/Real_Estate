<?php

namespace App\Repository;

use App\Abstract\BaseRepositoryImplementation;
use App\ApiHelper\ApiResponseCodes;
use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\FileHelper;
use App\ApiHelper\Result;
use App\http\Constants;
use App\Interfaces\AuthInterface;
use App\Mail\EmailVerification;
use App\Mail\ForgetPasswordVerification;
use App\Models\Photo;
use App\Models\User;
use App\Models\User_role;
use App\Services\FileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PHPUnit\TextUI\Configuration\Constant;

class AuthRepository extends BaseRepositoryImplementation implements AuthInterface
{

    public function model()
    {
        return User::class;
    }

    public function loginUser($data)
    {
        try {
            DB::beginTransaction();
            $user = null;

            $emailOrName = $data['email-or-name'];

            if (strpos($emailOrName, '@') !== false) {
                $user = User::where('email', $data['email-or-name'])->whereNotNull('email_verified_at')->first();
            } else {
                $user = User::where('name', $data['email-or-name'])->whereNotNull('email_verified_at')->first();
            }

            if (! $user || ! Hash::check($data['password'], $user->password)) {
                return response()->json(
                    [
                        'errors' => ['error ' . (isset($data['email-or-name']) ? 'email' : 'username') . ' or password failed'],
                    ],
                    ApiResponseCodes::BAD_REQUEST
                );
            }

            $token = $user->createToken('anas')->plainTextToken;
            $response = [
                'token' => $token,
                'user' => $user,
            ];

            DB::commit();
            return ApiResponseHelper::sendResponse(new Result($response, 'login successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => [$e->getMessage()],
            ], ApiResponseCodes::BAD_REQUEST);
        }
    }

    public function register($data)
    {
        try {
            DB::beginTransaction();
            $user = User::where('email', $data['email'])->first(['email_verified_at', 'id']);

            if (isset($user) && ! isset($user->email_verified_at)) {
                $user->delete();
            } elseif ($user && isset($user->email_verified_at)) {
                return response()->json([
                    'errors' => ['Email is already registered'],
                ], ApiResponseCodes::BAD_REQUEST);
            }
            $verified = random_int(00000, 99999);
            Mail::to($data['email'])->send(new EmailVerification($verified));

            $data['password'] = Hash::make($data['password']);
            $data['verification_code'] = $verified;
            $data['verification_expired_at'] = now()->addMinutes(10);

            $user = $this->create($data);

            // Adding role as client denamiclly
            User_role::create([
                'user_id' => $user->id,
                'role_id' => Constants::CLIENT_ROLE,
            ]);

            DB::commit();
            return ApiResponseHelper::sendMessageResponse('Verification email sent successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => [$e->getMessage()],
            ], ApiResponseCodes::BAD_REQUEST);
        }
    }

    public function registerVerification($data)
    {
        try {
            DB::beginTransaction();
            $user = $this->where('email', $data['email'])->first();
            if (
                $user &&
                $data['code'] == $user->verification_code &&
                $data['email'] == $user->email
                && now()->lessThanOrEqualTo($user->verification_expired_at)
            ) {
                $user->verified();
                // $token = Auth::login($user);
                $token = $user->createToken('anas')->plainTextToken;

                $response = [
                    'token' => $token,
                    'user' => $user,

                ];

                DB::commit();
                return ApiResponseHelper::sendResponse(
                    new Result($response, 'register successfully', ApiResponseCodes::SUCCESS)
                );
            } elseif (
                $user &&
                $data['code'] == $user->verification_code &&
                $data['email'] == $user->email
                && now()->greaterThan($user->verification_expired_at)
            ) {
                DB::commit();
                return response()->json([
                    'errors' => ['resend code'],
                ], ApiResponseCodes::BAD_REQUEST);
            } else {
                DB::commit();
                return response()->json([
                    'errors' => ['Verification failed'],
                ], ApiResponseCodes::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => [$e->getMessage()],
            ], ApiResponseCodes::BAD_REQUEST);
        }
    }

    public function registerResendVerification($email)
    {
        try {
            DB::beginTransaction();
            $user = User::where('email', $email)->first(['email_verified_at', 'id']);
            $verified = random_int(00000, 99999);

            if (isset($user) && ! isset($user->email_verified_at)) {
                Mail::to($email)->send(new EmailVerification($verified));
                $user->update(['verification_code' => $verified, 'verification_expired_at' => now()->addMinutes(10)]);

                DB::commit();
                return ApiResponseHelper::sendMessageResponse('resend verification successfully');
            } elseif ($user && isset($user->email_verified_at)) {
                DB::commit();
                return response()->json([
                    'errors' => ['API cannot be used'],
                ], ApiResponseCodes::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => [$e->getMessage()],
            ], ApiResponseCodes::BAD_REQUEST);
        }
    }

    public function forgetPasswordStep1($email)
    {
        try {
            DB::beginTransaction();
            $user = User::where('email', $email)->first('id');
            $otp = random_int(10000, 99999);
            Mail::to($email)->send(new ForgetPasswordVerification($otp));
            $user->update(['verification_code' => $otp, 'OTP_verified_at' => null, 'OTP_expired_at' => now()->addMinutes(10)]);

            DB::commit();
            return ApiResponseHelper::sendMessageResponse('send otp successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => [$e->getMessage()],
            ], ApiResponseCodes::BAD_REQUEST);
        }
    }

    public function forgetPasswordStep2($data)
    {
        try {
            DB::beginTransaction();
            $user = $this->where('email', $data['email'])->first();
            if (
                $user &&
                $data['code'] == $user->verification_code &&
                $data['email'] == $user->email &&
                now()->lessThanOrEqualTo($user->OTP_expired_at)
            ) {
                $user->verified_otp();
                $user->update(['OTP_verified_at' => now()]);
                DB::commit();
                return ApiResponseHelper::sendMessageResponse('verification OTP successfully');
            } elseif (
                $user &&
                $data['code'] == $user->OTP_code &&
                $data['email'] == $user->email &&
                now()->greaterThan($user->OTP_expired_at)
            ) {
                DB::commit();
                return response()->json([
                    'errors' => ['resend OTP'],
                ], ApiResponseCodes::BAD_REQUEST);
            }

            DB::commit();
            return response()->json([
                'errors' => ['verification OTP Fail'],
            ], ApiResponseCodes::BAD_REQUEST);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => [$e->getMessage()],
            ], ApiResponseCodes::BAD_REQUEST);
        }
    }

    public function forgetPasswordStep3($data)
    {
        try {
            DB::beginTransaction();
            $user = User::whereNotNull('email_verified_at')->whereNotNull('OTP_verified_at')->where('email', $data['email'])->first();
            if ($user) {
                $user->update(['password' => Hash::make($data['password']), 'OTP_verified_at' => null]);

                DB::commit();
                return ApiResponseHelper::sendMessageResponse('reset password successfully');
            }

            DB::commit();
            return response()->json([
                'errors' => ['not verification OTP to Reset Password'],
            ], ApiResponseCodes::BAD_REQUEST);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => [$e->getMessage()],
            ], ApiResponseCodes::BAD_REQUEST);
        }
    }

    public function OTPResendVerification($email)
    {
        try {
            DB::beginTransaction();
            $user = User::where('email', $email)->first(['OTP_verified_at', 'id']);

            if (isset($user) && ! isset($user->OTP_verified_at)) {
                $otp = random_int(00000, 99999);
                Mail::to($email)->send(new ForgetPasswordVerification($otp));
                $user->update(['OTP_code' => $otp, 'OTP_expired_at' => now()->addMinutes(10)]);

                DB::commit();
                return ApiResponseHelper::sendMessageResponse('resend verification successfully');
            } elseif ($user && isset($user->OTP_verified_at)) {
                DB::commit();
                return response()->json([
                    'errors' => ['API cannot be used'],
                ], ApiResponseCodes::BAD_REQUEST);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => [$e->getMessage()],
            ], ApiResponseCodes::BAD_REQUEST);
        }
    }

    public function showProfile($id)
    {
        try {
            $user = User::with('photos')->findOrFail($id);
            return ApiResponseHelper::sendResponseNew($user, "Done");
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }
}
