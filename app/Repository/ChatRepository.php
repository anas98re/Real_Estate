<?php

namespace App\Repository;

use App\Abstract\BaseRepositoryImplementation;
use App\ApiHelper\ApiResponseCodes;
use App\ApiHelper\ApiResponseHelper;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\RatingResource;
use App\Interfaces\ChatInterface;
use App\Interfaces\RatingInterface;
use App\Models\ChatRoom;
use App\Models\ChatRoomUser;
use App\Models\messagees_chat;
use App\Models\rating;
use App\Models\Realty;
use App\Models\User;
use App\Models\user_socket_connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Psy\Command\WhereamiCommand;

class ChatRepository extends BaseRepositoryImplementation implements ChatInterface
{
    public function model()
    {
        return User::class;
    }

    public function connectUser($request)
    {
        $user = auth('sanctum')->user();

        user_socket_connection::updateOrCreate(
            ['user_id' => $user->id, 'socket_id' => $request->socket_id],
            ['status' => 'connected']
        );

        return response()->json(['authorized' => true, 'user' => $user]);
    }

    public function disConnectUser($request)
    {
        $userConnection = user_socket_connection::where('socket_id', $request->socket_id)->first();

        if ($userConnection) {
            $userConnection->update(['status' => 'disconnected']);
            return response()->json(['message' => 'User disconnected']);
        }

        return response()->json(['message' => 'Connection not found'], 404);
    }

    public function addMessageToChat($request, $id)
    {
        // $user = auth('sanctum')->user();

        messagees_chat::create([
            'sender_id' => 2,
            'receiver_id' => $id,
            'author' => '$user->name',
            'message' => $request['message'],
            'sent_at' => now(),
        ]);

        return response()->json(['status' => 'Message saved successfully']);
    }

    public function getFile($request, $filename)
    {
        Log::debug("Requested filename: " . $filename);

        // $user = auth('sanctum')->user();

        // Locate the file in the database
        $file = messagees_chat::where('file_path', 'uploads/'.$filename)->first();

        // Check if file exists in the database
        if (!$file) {
            Log::error("File not found in database: " . $filename);
            return response()->json(['error' => 'File not found in database'], 404);
        }

        Log::debug("Database file path: " . $file->file_path);
        // Determine the file path in storage
        $filePath = storage_path('app/public/uploads/' . $filename);

        // Check if the file physically exists in the storage
        if (!file_exists($filePath)) {
            Log::error("File not found in storage: " . $filePath);
            return response()->json(['error' => 'File not found'], 404);
        }

        // Serve the file as a download
        return response()->download($filePath);
    }

    public function sendFile($request, $id)
    {
        // $user = auth('sanctum')->user(); // Ensure the user is authenticated
        Log::debug("ddddddddddddddddddddddddddddddddddddddddddddddd");

        if (!$request->hasFile('file')) {
            return response()->json(['status' => 'No file provided'], 400);
        }

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        // Ensure you have a 'uploads' disk configured in config/filesystems.php
        $path = $file->storeAs('uploads', $filename, 'public');

        // Optionally associate the file with a message or save it under a specific chat identified by $id
        $message = messagees_chat::create([
            'sender_id' => 2,
            'receiver_id' => 2,
            'author' => 'anas',
            'message' => $filename, // Storing filename, adjust according to your requirements
            'file_path' => $path,
            'type' => 'file',
            'sent_at' => now(),
        ]);

        return response()->json(['status' => 'File uploaded successfully', 'file_path' => $path]);
    }


    public function getPrivateChatMessages($id)
    {
        // $user = auth('sanctum')->user();
        $messages = messagees_chat::where(
            function ($query) use ($id) {
                $query->where('sender_id', 2)
                    ->where('receiver_id', $id);
            }
        )->orWhere(
            function ($query) use ($id) {
                $query->where('receiver_id', 2)
                    ->where('sender_id', $id);
            }
        )->orderBy('sent_at')->get();

        $data = MessageResource::collection($messages);

        return $messages->count() != 0
            ? response()->json($data)
            : response()->json('No messages');
    }


    // Public chat operations


    public function createChatRoom($request)
    {
        try {
            DB::beginTransaction();
            $user = auth('sanctum')->user();

            $chatRoom = ChatRoom::create([
                'name' => $request->name,
                'created_by' => $user->id,
            ]);

            // Add the creator to the room
            ChatRoomUser::create([
                'user_id' => $user->id,
                'chat_room_id' => $chatRoom->id,
                'is_have_permission' => true,
            ]);

            DB::commit();
            $data = new ChatResource($chatRoom);
            return ApiResponseHelper::sendResponseNew($data, "Chat room created successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => [$e->getMessage()],
            ], ApiResponseCodes::BAD_REQUEST);
        }
    }

    public function addUserToChatRoom($request, $id)
    {
        try {
            DB::beginTransaction();
            $user = auth('sanctum')->user();
            $chatRoom = ChatRoom::findOrFail($id);

            // Check if the current user is an admin of the chat room
            $isHavePermission = ChatRoomUser::where('user_id', $user->id)
                ->where('chat_room_id', $chatRoom->id)
                ->where('is_have_permission', true)
                ->exists();

            if (!$isHavePermission) {
                return response()->json(['message' => 'You do not have permission to add users to this room'], 403);
            }

            // Add the new user to the room
            ChatRoomUser::updateOrCreate(
                ['user_id' => $request->user_id, 'chat_room_id' => $chatRoom->id],
                ['is_have_permission' => $request->is_have_permission ?? false]
            );

            DB::commit();
            return response()->json(['status' => 'User added to chat room successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => [$e->getMessage()],
            ], ApiResponseCodes::BAD_REQUEST);
        }
    }

    public function getChatRoomMessages($roomId)
    {
        $user = auth('sanctum')->user();

        // Check if the user is a member of the chat room
        $isMember = ChatRoomUser::where('user_id', $user->id)
            ->where('chat_room_id', $roomId)
            ->exists();

        if (!$isMember) {
            return response()->json(['message' => 'You are not a member of this chat room'], 403);
        }

        $messages = messagees_chat::where('chat_room_id', $roomId)
            ->orderBy('sent_at')
            ->get();

        $data = MessageResource::collection($messages);

        return $messages->count() != 0
            ? response()->json($data)
            : response()->json('No messages');
    }

    public function addMessageToChatRoom($request, $roomId)
    {
        $user = auth('sanctum')->user();

        // Check if the user is a member of the chat room
        $isMember = ChatRoomUser::where('user_id', $user->id)
            ->where('chat_room_id', $roomId)
            ->exists();

        if (!$isMember) {
            return response()->json(['message' => 'You are not a member of this chat room'], 403);
        }

        $messages = messagees_chat::create([
            'sender_id' => $user->id,
            'chat_room_id' => $roomId,
            'author' => $user->name,
            'message' => $request['message'],
            'sent_at' => now(),
        ]);
        $data = new MessageResource($messages);
        return ApiResponseHelper::sendResponseNew($data, "Message saved successfully");
    }
}
