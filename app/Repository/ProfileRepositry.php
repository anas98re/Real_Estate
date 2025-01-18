<?php

namespace App\Repository;

use App\Abstract\BaseRepositoryImplementation;
use App\ApiHelper\ApiResponseCodes;
use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\FileHelper;
use App\http\Constants;
use App\Http\Resources\ActivityResource;
use App\Interfaces\ActivityInterface;
use App\Interfaces\AuthInterface;
use App\Interfaces\ProfileInterface;
use App\Models\Activity;
use App\Models\Activity_user;
use App\Models\Photo;
use App\Models\User;
use App\Models\User_role;
use Illuminate\Support\Facades\DB;

class ProfileRepositry extends BaseRepositoryImplementation implements ProfileInterface
{
    public function model()
    {
        return User::class;
    }

    public function addOrUpdateProfileInfo($request)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();
            $user = User::where('id', auth('sanctum')->user()->id)->first();
            $user->country_id = $data['country_id'] ?? $user->country_id;
            $user->username = $data['username'] ?? $user->username;
            $user->first_name = $data['first_name'] ?? $user->first_name;
            $user->second_name = $data['second_name'] ?? $user->second_name;
            $user->phone = $data['phone'] ?? $user->phone;
            $user->facebook_account = $data['facebook_account'] ?? $user->facebook_account;
            $user->instagram_account = $data['instagram_account'] ?? $user->instagram_account;
            $user->tiktok_account = $data['tiktok_account'] ?? $user->tiktok_account;
            $user->birthday = $data['birthday'] ?? $user->birthday;
            $user->city = $data['city'] ?? $user->city;
            $user->save();

            if (isset($data['cover'])) {
                if ($user->photos->isNotEmpty()) {
                    $lastPhoto = $user->photos->last();
                    $lastPhoto->delete();
                }
                $file = FileHelper::handlingImageName($data['cover']);
                $photo = new Photo(['path' => $file, 'model' => 'User']);
                $user->photos()->save($photo);
            }

            if (isset($data['roles'])) {
                foreach ($data['roles'] as $role) {
                    User_role::where('user_id', $user->id)
                        ->delete(); // Remove existing roles
                }
            }

            if (isset($data['roles'])) {
                foreach ($data['roles'] as $role) {
                    if ($role == Constants::ADMIN_ROLE) continue;
                    $User_role = new User_role();
                    $User_role->user_id = $user->id;
                    $User_role->role_id = $role;
                    $User_role->save();
                }
            }

            DB::commit();
            return ApiResponseHelper::sendMessageResponse('Profile info added .');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => [$e->getMessage()],
            ], ApiResponseCodes::BAD_REQUEST);
        }
    }

    public function showProfileInfo($id)
    {
        try {
            $user = User::with('photos')->findOrFail($id);
            return ApiResponseHelper::sendResponseNew($user, "Done");
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function showMyProfileInfo()
    {
        try {
            $user = User::with('photos')->findOrFail(auth('sanctum')->user()->id);
            return ApiResponseHelper::sendResponseNew($user, "Done");
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }
}
