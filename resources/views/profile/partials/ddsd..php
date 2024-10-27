<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>
    <head>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    </head>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Main Profile Information Container -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row items-center">
                        <!-- Profile Picture with Border -->
                        <div class="w-full md:w-1/3 mb-4 md:mb-0">
                        @php
                                // If the user has selected a sample border, use that image
                                if ($user->selected_border === 'sample-1') {
                                    $borderImage = asset('images/sample-borders/border-sample-1.png');
                                } elseif ($user->selected_border === 'sample-2') {
                                    $borderImage = asset('images/sample-borders/border-sample-2.png');
                                } else {
                                    // Get the active border, if available, otherwise use a placeholder
                                    $activeBorder = $user->borders->where('pivot.is_active', 1)->first();
                                    $borderImage = $activeBorder ? asset($activeBorder->image) : asset('images/placeholder/border-placeholder.png');
                                }
                            @endphp
                            <div class="relative mx-auto md:mx-0 profile-picture-container">
                                <!-- Profile Border -->
                                <img src="{{ $borderImage }}" 
                                     class="profile-border absolute inset-0 w-full h-full object-cover rounded-full" 
                                     alt="Profile Border">

                                <!-- Profile Picture -->
                                <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('dist/img/avatar5.png') }}" 
                                     class="profile-picture rounded-full object-cover" 
                                     alt="Profile Picture">
                            </div>
                        </div>

                        <!-- User Information -->
                        <div class="w-full md:w-2/3">
                            <h3 class="text-2xl font-semibold text-gray-900">{{ Auth::user()->full_name ?? Auth::user()->name }}</h3>
                            <p class="text-gray-700"><strong>Full Name:</strong> {{ Auth::user()->full_name ?? '' }}</p>
                            <p class="text-gray-700"><strong>Account:</strong> {{ Auth::user()->usertype ?? '' }}</p>
                            <p class="text-gray-700"><strong>Bio:</strong> {{ Auth::user()->bio ?? '' }}</p>
                            <p class="text-gray-700"><strong>Email:</strong> {{ Auth::user()->email }}</p>
                            <p class="text-gray-700"><strong>Address:</strong> {{ Auth::user()->address ?? '' }}</p>
                            <!-- Edit Button -->
                            <div class="mt-4">
                                <a href="{{ route('profile.edit') }}" 
                                   class="px-4 py-2 text-black rounded-md shadow-sm hover:bg-white-800" 
                                   style="background-color: #00e2f2;">
                                    Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Border Selection Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Select Your Profile Border</h3>
                    <form action="{{ route('profile.update-border') }}" method="POST">
                        @csrf

                        <div class="border-list flex gap-6 mt-4">
                            <!-- User's Unlocked Borders -->
                            @foreach($user->borders as $border)
                                <label class="cursor-pointer border-selection-item">
                                    <input type="radio" name="border_id" value="{{ $border->id }}" {{ $border->pivot->is_active ? 'checked' : '' }} class="hidden">
                                    <div class="border-item flex flex-col items-center justify-center">
                                        <img src="{{ asset($border->image) }}" class="w-16 h-16 rounded-full {{ $border->pivot->is_active ? 'ring-4 ring-blue-500' : '' }}" alt="{{ $border->name }}">
                                        <p class="mt-2">{{ $border->name }}</p>
                                    </div>
                                </label>
                            @endforeach

                            <!-- Sample Borders -->
                            <label class="cursor-pointer border-selection-item">
                                <input type="radio" name="border_id" value="sample-1" class="hidden">
                                <div class="border-item flex flex-col items-center justify-center">
                                    <img src="{{ asset('images/sample-borders/border-sample-1.png') }}" class="w-16 h-16 rounded-full" alt="Sample Border 1">
                                    <p class="mt-2">Sample Border 1</p>
                                </div>
                            </label>

                            <label class="cursor-pointer border-selection-item">
                                <input type="radio" name="border_id" value="sample-2" class="hidden">
                                <div class="border-item flex flex-col items-center justify-center">
                                    <img src="{{ asset('images/sample-borders/border-sample-2.png') }}" class="w-16 h-16 rounded-full" alt="Sample Border 2">
                                    <p class="mt-2">Sample Border 2</p>
                                </div>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-4">
                            <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-700">Update Border</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- About Me Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">About Me</h3>
                    <p class="text-gray-700 mt-2">{{ Auth::user()->about_me ?? 'No information provided.' }}</p>
                </div>
            </div>

            <!-- Skills Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Skills</h3>
                    @if(Auth::user()->skills)
                        @foreach(json_decode(Auth::user()->skills) as $skill)
                            <div class="mb-2">
                                <p class="text-gray-700">{{ $skill }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-700">No skills added.</p>
                    @endif
                </div>
            </div>

            <!-- Education Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Education</h3>
                    <p class="text-gray-700 mt-2">{{ Auth::user()->education ?? 'No information provided.' }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>