<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Team Invitations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Pending Invitations</h3>
                </div>
                <div class="card-body">
                    @if ($invitations && $invitations->count() > 0)
                        <ul class="list-group">
                            @foreach ($invitations as $invitation)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        You've been invited to join <strong>{{ $invitation->team->name }}</strong> by {{ $invitation->inviter->name }}
                                    </span>
                                    <div>
                                        <!-- Accept button -->
                                        <form method="POST" action="{{ route('invitations.accept', $invitation->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Accept</button>
                                        </form>

                                        <!-- Reject button with confirmation -->
                                        <form method="POST" action="{{ route('invitations.reject', $invitation->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to decline this invitation?');">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Decline</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No pending invitations.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
