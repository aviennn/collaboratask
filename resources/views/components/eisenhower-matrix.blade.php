<div class="container-fluid">
    <h2 class="text-center mb-4">Eisenhower Matrix</h2>

    <!-- Personal Tasks Section -->
    <h3 class="text-center mb-4">Personal Tasks</h3>

    <div class="row">
        <!-- Urgent & Important (Do First) -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title">Urgent & Important (Do First)</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($personalUrgentImportant as $task)
                            <li class="mb-2">
                                <strong>{{ $task->name }}</strong> 
                                <span class="text-muted small">(Due: {{ $task->due_date->format('M d, Y') }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Not Urgent & Important (Schedule) -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title">Not Urgent & Important (Schedule)</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($personalNotUrgentImportant as $task)
                            <li class="mb-2">
                                <strong>{{ $task->name }}</strong> 
                                <span class="text-muted small">(Due: {{ $task->due_date->format('M d, Y') }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Urgent & Not Important (Delegate) -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title">Urgent & Not Important (Delegate)</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($personalUrgentNotImportant as $task)
                            <li class="mb-2">
                                <strong>{{ $task->name }}</strong> 
                                <span class="text-muted small">(Due: {{ $task->due_date->format('M d, Y') }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Not Urgent & Not Important (Eliminate) -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title">Not Urgent & Not Important (Eliminate)</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($personalNotUrgentNotImportant as $task)
                            <li class="mb-2">
                                <strong>{{ $task->name }}</strong> 
                                <span class="text-muted small">(Due: {{ $task->due_date->format('M d, Y') }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Tasks Section -->
    <h3 class="text-center mb-4">Team Tasks</h3>

    <div class="row">
        <!-- Urgent & Important (Do First) -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title">Urgent & Important (Do First)</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($teamUrgentImportant as $task)
                            <li class="mb-2">
                                <strong>{{ $task->name }}</strong> 
                                <span class="text-muted small">(Due: {{ $task->due_date->format('M d, Y') }})</span>
                                <br>
                                <span class="text-muted small">Team: {{ $task->team->name }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Not Urgent & Important (Schedule) -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title">Not Urgent & Important (Schedule)</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($teamNotUrgentImportant as $task)
                            <li class="mb-2">
                                <strong>{{ $task->name }}</strong> 
                                <span class="text-muted small">(Due: {{ $task->due_date->format('M d, Y') }})</span>
                                <br>
                                <span class="text-muted small">Team: {{ $task->team->name }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Urgent & Not Important (Delegate) -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title">Urgent & Not Important (Delegate)</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($teamUrgentNotImportant as $task)
                            <li class="mb-2">
                                <strong>{{ $task->name }}</strong> 
                                <span class="text-muted small">(Due: {{ $task->due_date->format('M d, Y') }})</span>
                                <br>
                                <span class="text-muted small">Team: {{ $task->team->name }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Not Urgent & Not Important (Eliminate) -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title">Not Urgent & Not Important (Eliminate)</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($teamNotUrgentNotImportant as $task)
                            <li class="mb-2">
                                <strong>{{ $task->name }}</strong> 
                                <span class="text-muted small">(Due: {{ $task->due_date->format('M d, Y') }})</span>
                                <br>
                                <span class="text-muted small">Team: {{ $task->team->name }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
