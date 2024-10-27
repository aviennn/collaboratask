<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Team') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Create a New Team</h6>
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
                            <form method="POST" action="{{ route('user.teams.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Team Name</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="members">Members</label>
                                    <select name="members[]" id="members" class="form-control" multiple="multiple">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="has_rewards">Enable Points and Rewards</label>
                                    <select name="has_rewards" id="has_rewards" class="form-control">
                                        <option value="1">With Points and Rewards</option>
                                        <option value="0">Normal</option>
                                    </select>
                                </div>
                                 <!-- Image upload field -->
                                <div class="form-group">
                                    <label for="team_image">Upload Team Image</label>
                                    <input type="file" name="team_image" id="team_image" class="form-control">
                                </div>
                                <div>
                                    <p>
                                       <b><i> Note that the grading has a fixed poins depending on the priority and grade given by the Team Leader. </i></b>
                                    </p>
                                    <br>
                                    <p>
                                    <b>Base on priority:</b>
                                    <br>
                                    High = <b>55pts</b>
                                    <br>
                                    Medium = 35pts
                                    <br>
                                    Easy = 15pts
                                    </p>

                                    <br>

                                    <p>
                                    <b>Team Leader's grading system:</b>
                                    <br>
                                    Excellent = 50pts
                                    <br>
                                    Very Good = 30pts
                                    <br>
                                    Good = 10pts
                                    </p>
                                    <br>
                                    
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Create Team</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    $(document).ready(function() {
        $('#members').select2({
            placeholder: 'Select members',
            allowClear: true
        });
    });
</script>
