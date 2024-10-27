<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Team') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Edit Team</h6>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('user.teams.update', $team->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT') <!-- Add this to indicate a PUT request -->
                            <div class="form-group">
                                <label for="name">Team Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $team->name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control">{{ $team->description }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="members">Members</label>
                                <select name="members[]" id="members" class="form-control" multiple="multiple">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @if($team->members->contains($user->id)) selected @endif>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
    <label for="has_rewards">Enable Points and Rewards</label>
    <select name="has_rewards" id="has_rewards" class="form-control">
        <option value="1" @if($team->has_rewards) selected @endif>With Points and Rewards</option>
        <option value="0" @if(!$team->has_rewards) selected @endif>Normal</option>
    </select>
</div>
 <!-- Image upload field -->
 <div class="form-group">
        <label for="team_image">Upload Team Image</label>
        <input type="file" name="team_image" id="team_image" class="form-control">
    </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update Team</button>
                            </div>
                        </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: black !important;
    }
</style>

<script>
    $(document).ready(function() {
        $('#members').select2({
            placeholder: 'Select members',
            allowClear: true
        });
    });
</script>