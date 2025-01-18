<?php

use App\Actions\ActivityActions\AddActivityAction;
use App\Actions\ActivityActions\GetAllActivitiesAction;
use App\Actions\ActivityActions\GetUserActivitiesAction;
use App\Actions\ActivityActions\RemoveActivityAction;
use App\Actions\ActivityActions\UpdateActivityAction;
use App\Actions\AppointmentActions\AcceptAppointmentAction;
use App\Actions\AppointmentActions\AddAppointmentAction;
use App\Actions\AppointmentActions\RealtyAppointmentsAction;
use App\Actions\AppointmentActions\RemoveAppointmentAction;
use App\Actions\AppointmentActions\UpdateAppointmentAction;
use App\Actions\AppointmentActions\UserAppointmentsAction;
use App\Actions\AuthActions\ForgetPasswordStep1Action;
use App\Actions\AuthActions\ForgetPasswordStep2Action;
use App\Actions\AuthActions\ForgetPasswordStep3Action;
use App\Actions\AuthActions\ForgetPaswordResendVerificationAction;
use App\Actions\AuthActions\LoginAction;
use App\Actions\AuthActions\RegisterAction;
use App\Actions\AuthActions\RegisterResendVerificationAction;
use App\Actions\AuthActions\RegisterVerificationAction;
use App\Actions\ChatActions\AddMessageToChatAction;
use App\Actions\ChatActions\AddMessageToChatRoomAction;
use App\Actions\ChatActions\AddUserToChatRoomAction;
use App\Actions\ChatActions\ConectUserAction;
use App\Actions\ChatActions\ConnectUserAction;
use App\Actions\ChatActions\CreateChatRoomAction;
use App\Actions\ChatActions\DisConnectUserAction;
use App\Actions\ChatActions\FileNameChatingAction;
use App\Actions\ChatActions\GetChatRoomMessagesAction;
use App\Actions\ChatActions\GetPrivateChatMessagesAction;
use App\Actions\ChatActions\SendFileToChatAction;
use App\Actions\PermissionActions\AddPermissionAction;
use App\Actions\PermissionActions\AllPermissionsAction;
use App\Actions\PermissionActions\GetPermissionsByRoleAction;
use App\Actions\PermissionActions\RemovePermissionAction;
use App\Actions\PermissionActions\UpdatePermissionAction;
use App\Actions\ProfileActions\AddOrUpdateProfileInfoActoion;
use App\Actions\ProfileActions\AddProfileInfoActoion;
use App\Actions\ProfileActions\ShowMyProfileAction;
use App\Actions\ProfileActions\ShowProfileAction;
use App\Actions\ProfileActions\UpdateProfileInfoAction;
use App\Actions\RatingActions\AddRatingAction;
use App\Actions\RatingActions\PublicRealtyRatingAction;
use App\Actions\RatingActions\RealtyRatingsAction;
use App\Actions\RatingActions\RemoveRatingAction;
use App\Actions\RatingActions\UpdateRatingAction;
use App\Actions\RealtyActions\AddRealtyAction;
use App\Actions\RealtyActions\FilterRealtiesAction;
use App\Actions\RealtyActions\FilterRealtiesByAgentIdAction;
use App\Actions\RealtyActions\GetAllRealtiesAction;
use App\Actions\RealtyActions\GetRealtiesbyUserAction;
use App\Actions\RealtyActions\GetUserRealtiesAction;
use App\Actions\RealtyActions\RealtyInfoAction;
use App\Actions\RealtyActions\RemoveRealtyAction;
use App\Actions\RealtyActions\UpdateRealtyAction;
use App\Actions\ServiceActions\AddServiceAction;
use App\Actions\ServiceActions\GetAllServicesAction;
use App\Actions\ServiceActions\GetUserServiceAction;
use App\Actions\ServiceActions\RemoveServiceAction;
use App\Actions\ServiceActions\UpdateServiceAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocale;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Registeration
Route::group(['prefix' => 'user'], function () {
    Route::post('/register', RegisterAction::class);
    Route::post('/register/verification', RegisterVerificationAction::class);
    Route::post('/register/resendVerification', RegisterResendVerificationAction::class);
    Route::post('/login', LoginAction::class);
    Route::post('/forgetPassword/step1', ForgetPasswordStep1Action::class);
    Route::post('/forgetPassword/step2', ForgetPasswordStep2Action::class);
    Route::post('/forgetPassword/step3', ForgetPasswordStep3Action::class);
    Route::post('/forgetPassword/resendVerification', ForgetPaswordResendVerificationAction::class);
});

// Route::middleware(['setLocal'])->group(function () {
Route::middleware(['setlocale'])->group(function () {
    Route::group(['prefix' => '{locale}', 'where' => ['locale' => 'en|fr']], function () {

        Route::group(['prefix' => 'chat'], function () {
            Route::get('/files/{filename}', FileNameChatingAction::class);
            Route::post('send-file/{id}', SendFileToChatAction::class);
            Route::get('get_private-message-chat/{id}', GetPrivateChatMessagesAction::class);
            Route::post('add-message-to-chat/{id}', AddMessageToChatAction::class);
        });
    });
});
Route::middleware(['auth:sanctum', 'setlocale'])->group(function () {

    Route::group(['prefix' => '{locale}', 'where' => ['locale' => 'en|fr']], function () {
        // Profile
        Route::group(['prefix' => 'profile'], function () {
            Route::get('showMyProfile', ShowMyProfileAction::class);
            Route::get('showProfile/{id}', ShowProfileAction::class);
            Route::post('addOrupdateProfileInfo', AddOrUpdateProfileInfoActoion::class);
        });

        // Permissions
        Route::group(['prefix' => 'permission'], function () {
            Route::get('getAll/{perPage}', AllPermissionsAction::class);
            Route::post('add', AddPermissionAction::class);
            Route::post('update/{id}', UpdatePermissionAction::class);
            Route::post('remove/{id}', RemovePermissionAction::class);
            Route::get('getPermissionsByRole/{role}', GetPermissionsByRoleAction::class);
        });

        // Live Chat
        Route::group(['prefix' => 'chat'], function () {
            //private chat
            Route::post('connect-user', ConnectUserAction::class);
            Route::post('dis-connect-user', DisConnectUserAction::class);
            // Route::post('add-message-to-chat/{id}', AddMessageToChatAction::class);
            // Route::get('get_private-message-chat/{id}', GetPrivateChatMessagesAction::class);

            //public chat
            Route::post('createChatRoom', CreateChatRoomAction::class);
            Route::post('addUserToChatRoom/{roomId}', AddUserToChatRoomAction::class);
            Route::get('getChatRoomMessages/{roomId}', GetChatRoomMessagesAction::class);
            Route::post('addMessageToChatRoom/{roomId}', AddMessageToChatRoomAction::class);
        });

        // Realty
        Route::group(['prefix' => 'realty'], function () {
            Route::post('getAll', GetAllRealtiesAction::class);
            Route::post('add', AddRealtyAction::class);
            Route::get('realtyInfoById/{id}', RealtyInfoAction::class);
            Route::post('update/{id}', UpdateRealtyAction::class);
            Route::post('remove/{id}', RemoveRealtyAction::class);
            Route::post('useraUthenticatedRealties', GetUserRealtiesAction::class);
            Route::post('userRealtiesByUserId/{userId}', GetRealtiesbyUserAction::class);

            // Filer
            Route::post('filter', FilterRealtiesAction::class);
            Route::post('filterRealtiesByAgentId/{userId}', FilterRealtiesByAgentIdAction::class);
        });

        // Activity
        Route::group(['prefix' => 'activity'], function () {
            Route::get('getAll/{perPage}', GetAllActivitiesAction::class);
            Route::post('add', AddActivityAction::class);
            Route::post('update/{id}', UpdateActivityAction::class);
            Route::post('remove/{id}', RemoveActivityAction::class);
            Route::get('userActivities/{perPage}', GetUserActivitiesAction::class);
        });

        // Service
        Route::group(['prefix' => 'service'], function () {
            Route::get('getAll/{perPage}', GetAllServicesAction::class);
            Route::post('add', AddServiceAction::class);
            Route::post('update/{id}', UpdateServiceAction::class);
            Route::post('remove/{id}', RemoveServiceAction::class);
            Route::get('userServices/{perPage}', GetUserServiceAction::class);
        });

        // Rating
        Route::group(['prefix' => 'rating'], function () {
            Route::post('add/{id}', AddRatingAction::class);
            Route::post('update/{id}', UpdateRatingAction::class);
            Route::post('remove/{id}', RemoveRatingAction::class);
            Route::get('realtyRatings/{id}', RealtyRatingsAction::class);
            Route::get('publicRealtyRating/{id}', PublicRealtyRatingAction::class);
        });

        // Appointment
        Route::group(['prefix' => 'appointment'], function () {
            Route::post('add/{id}', AddAppointmentAction::class);
            Route::post('update/{id}', UpdateAppointmentAction::class);
            Route::post('remove/{id}', RemoveAppointmentAction::class);
            Route::get('realtyAppointments/{id}', RealtyAppointmentsAction::class);
            Route::get('userAppointments', UserAppointmentsAction::class);
            Route::post('acceptAppointment/{id}', AcceptAppointmentAction::class);
        });
    });
});
