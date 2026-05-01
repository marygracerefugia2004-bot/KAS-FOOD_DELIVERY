<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $contacts = $this->allowedContacts($user)->map(function (User $contact) use ($user) {
            $lastMessage = Message::where(function ($query) use ($user, $contact) {
                $query->where('sender_id', $user->id)->where('receiver_id', $contact->id);
            })->orWhere(function ($query) use ($user, $contact) {
                $query->where('sender_id', $contact->id)->where('receiver_id', $user->id);
            })->latest()->first();

            $contact->last_message = $lastMessage?->message;
            $contact->last_message_time = $lastMessage?->created_at;
            $contact->unread_count = Message::where('sender_id', $contact->id)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count();

            return $contact;
        })->sortBy([
            fn (User $contact) => $this->rolePriority($contact->role),
            fn (User $contact) => $contact->last_message_time ? -$contact->last_message_time->timestamp : PHP_INT_MAX,
            fn (User $contact) => strtolower($contact->name),
        ])->values();

        $allowedRecipients = $contacts;

        return view('messages.index', compact('contacts', 'allowedRecipients'));
    }

    public function conversation($userId)
    {
        $currentUser = auth()->user();
        $contact = User::findOrFail($userId);

        if (! $this->canMessage($currentUser, $contact)) {
            return redirect()->back()->with('error', 'You cannot message this user.');
        }

        $messages = Message::where(function ($query) use ($currentUser, $userId) {
            $query->where('sender_id', $currentUser->id)->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($currentUser, $userId) {
            $query->where('sender_id', $userId)->where('receiver_id', $currentUser->id);
        })->orderBy('created_at', 'asc')->get();

        Message::where('sender_id', $userId)->where('receiver_id', $currentUser->id)->update(['is_read' => true]);

        return view('messages.conversation', compact('contact', 'messages'));
    }

    public function getContacts()
    {
        $user = auth()->user();

        return response()->json($this->allowedContacts($user)->values());
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $sender = auth()->user();
        $receiver = User::findOrFail($request->receiver_id);

        if (! $this->canMessage($sender, $receiver)) {
            return response()->json(['error' => 'You cannot message this user.'], 403);
        }

        $message = Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $request->receiver_id,
            'message' => strip_tags($request->message),
            'is_read' => false,
        ]);

        return response()->json(['ok' => true, 'success' => true]);
    }

    private function canMessage($sender, $receiver)
    {
        if ($sender->id === $receiver->id) {
            return false;
        }

        return $this->allowedContacts($sender)->pluck('id')->contains($receiver->id);
    }

    private function allowedContacts(User $user): Collection
    {
        if ($user->role === 'admin') {
            return User::where('id', '!=', $user->id)
                ->where('is_active', true)
                ->whereIn('role', ['user', 'driver'])
                ->orderByRaw("FIELD(role, 'driver', 'user')")
                ->orderBy('name')
                ->get();
        }

        if ($user->role === 'user') {
            $admins = User::where('role', 'admin')
                ->where('is_active', true)
                ->get();

            $driverIds = Order::where('user_id', $user->id)
                ->whereNotNull('driver_id')
                ->whereIn('status', ['assigned', 'out_for_delivery'])
                ->pluck('driver_id')
                ->unique();

            $drivers = User::whereIn('id', $driverIds)
                ->where('role', 'driver')
                ->where('is_active', true)
                ->get();

            return $admins->merge($drivers)->unique('id')->values();
        }

        if ($user->role === 'driver') {
            $admins = User::where('role', 'admin')
                ->where('is_active', true)
                ->get();

            $customerIds = Order::where('driver_id', $user->id)
                ->whereIn('status', ['assigned', 'out_for_delivery'])
                ->pluck('user_id')
                ->unique();

            $customers = User::whereIn('id', $customerIds)
                ->where('role', 'user')
                ->where('is_active', true)
                ->get();

            return $admins->merge($customers)->unique('id')->values();
        }

        return collect();
    }

    private function rolePriority(string $role): int
    {
        return match ($role) {
            'admin' => 0,
            'driver' => 1,
            'user' => 2,
            default => 3,
        };
    }
}
