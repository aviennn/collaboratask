@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h3>User Settings <i class="fas fa-cog ml-2"></i></h3>
        </div>
        <div class="card-body">
            <!-- Font Family Form -->
            <form action="{{ route('user.updateSettings') }}" method="POST" id="settingsForm">
                @csrf
                <div class="row">
                    <!-- Font Family Selection -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="font_family" class="font-weight-bold">Font Family</label>
                            <select name="font_family" id="font_family" class="form-control">
                                <option value="Arial" {{ Auth::user()->font_family == 'Arial' ? 'selected' : '' }}>Arial</option>
                                <option value="Helvetica" {{ Auth::user()->font_family == 'Helvetica' ? 'selected' : '' }}>Helvetica</option>
                                <option value="Times New Roman" {{ Auth::user()->font_family == 'Times New Roman' ? 'selected' : '' }}>Times New Roman</option>
                                <option value="Courier New" {{ Auth::user()->font_family == 'Courier New' ? 'selected' : '' }}>Courier New</option>
                                <option value="Verdana" {{ Auth::user()->font_family == 'Verdana' ? 'selected' : '' }}>Verdana</option>
                                <option value="Roboto" {{ Auth::user()->font_family == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Font Size Selection -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="font_size" class="font-weight-bold">Font Size</label>
                            <select name="font_size" id="font_size" class="form-control">
                                <option value="14px" {{ Auth::user()->font_size == '14px' ? 'selected' : '' }}>14px</option>
                                <option value="16px" {{ Auth::user()->font_size == '16px' ? 'selected' : '' }}>16px</option>
                                <option value="18px" {{ Auth::user()->font_size == '18px' ? 'selected' : '' }}>18px</option>
                                <option value="20px" {{ Auth::user()->font_size == '20px' ? 'selected' : '' }}>20px</option>
                            </select>
                        </div>
                    </div>

                    <!-- Font Color Selection -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="font_color" class="font-weight-bold">Font Color</label>
                            <input type="color" name="font_color" id="font_color" value="{{ Auth::user()->font_color ?? '#000000' }}" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Background Color Selection -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="background_color" class="font-weight-bold">Background Color</label>
                            <input type="color" name="background_color" id="background_color" value="{{ Auth::user()->background_color ?? '#ffffff' }}" class="form-control">
                        </div>
                    </div>

                    <!-- Navbar Color Selection -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="navbar_color" class="font-weight-bold">Navbar Color</label>
                            <input type="color" name="navbar_color" id="navbar_color" value="{{ Auth::user()->navbar_color ?? '#333333' }}" class="form-control">
                        </div>
                    </div>

                    <!-- Sidebar Color Selection -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sidebar_color" class="font-weight-bold">Sidebar Color</label>
                            <input type="color" name="sidebar_color" id="sidebar_color" value="{{ Auth::user()->sidebar_color ?? '#222222' }}" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Preset Themes -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="theme_presets" class="font-weight-bold">Select a Theme</label>
                            <select id="theme_presets" name="theme_presets" class="form-control">
                                <option value="">Select a theme</option>
                                @foreach(config('themes') as $theme_key => $theme)
                                    <option value="{{ $theme_key }}">{{ ucfirst(str_replace('_', ' ', $theme_key)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Submit and Reset Buttons -->
                <div class="form-group d-flex justify-content-between">
                    <button type="submit" class="btn btn-success d-flex align-items-center"><i class="fas fa-save mr-2"></i> Save Changes</button>
                    <button type="submit" class="btn btn-danger d-flex align-items-center" form="resetForm"><i class="fas fa-undo-alt mr-2"></i> Reset Theme</button>
                </div>
            </form>

            <!-- Reset Theme Button -->
            <form action="{{ route('user.resetTheme') }}" method="POST" id="resetForm" style="display: none;">
                @csrf
            </form>
        </div>

        <!-- Live Preview Section -->
        <div class="card-footer bg-light">
            <h4 class="font-weight-bold">Live Preview</h4>
            <div id="livePreview" class="p-3 border bg-light">
                This is a live preview of how your settings will look.
            </div>
        </div>
    </div>
</div>

<!-- Live Preview Script -->
<script>
    const fontFamily = document.getElementById('font_family');
    const fontSize = document.getElementById('font_size');
    const fontColor = document.getElementById('font_color');
    const backgroundColor = document.getElementById('background_color');
    const navbarColor = document.getElementById('navbar_color');
    const sidebarColor = document.getElementById('sidebar_color');
    const themePresetsDropdown = document.getElementById('theme_presets');

    const livePreview = document.getElementById('livePreview');
    const navbar = document.querySelector('.main-header');
    const sidebar = document.querySelector('.main-sidebar');
    const body = document.querySelector('.content-wrapper');

    // Function to update the live preview styles
    function updateLivePreview() {
        livePreview.style.fontFamily = fontFamily.value;
        livePreview.style.fontSize = fontSize.value;
        livePreview.style.color = fontColor.value; // Change font color in live preview
        livePreview.style.backgroundColor = backgroundColor.value;

        // Apply styles to other elements for full preview experience
        body.style.backgroundColor = backgroundColor.value;
        body.style.color = fontColor.value; // Apply font color to body as well
        navbar.style.backgroundColor = navbarColor.value;
        sidebar.style.backgroundColor = sidebarColor.value;

        // Apply font color to nav links in live preview (if relevant)
        const navLinks = document.querySelectorAll('.main-header .nav-link, .main-sidebar a');
        navLinks.forEach(link => {
            link.style.color = fontColor.value; // Change nav links to match font color
        });
    }

    // Apply theme changes from the preset themes
    themePresetsDropdown.addEventListener('change', function() {
        if (themePresetsDropdown.value !== '') {
            const theme = @json(config('themes'));

            if (theme[themePresetsDropdown.value]) {
                const selectedTheme = theme[themePresetsDropdown.value];
                fontFamily.value = selectedTheme.font_family;
                fontSize.value = selectedTheme.font_size;
                fontColor.value = selectedTheme.font_color;
                backgroundColor.value = selectedTheme.background_color;
                navbarColor.value = selectedTheme.navbar_color;
                sidebarColor.value = selectedTheme.sidebar_color;

                updateLivePreview(); // Update live preview after applying the theme
            }
        }
    });

    // Event listeners for live preview updates
    fontFamily.addEventListener('change', updateLivePreview);
    fontSize.addEventListener('change', updateLivePreview);
    fontColor.addEventListener('input', updateLivePreview); // For color inputs, use 'input'
    backgroundColor.addEventListener('input', updateLivePreview);
    navbarColor.addEventListener('input', updateLivePreview);
    sidebarColor.addEventListener('input', updateLivePreview);

    // Initial live preview update
    updateLivePreview();
</script>
@endsection
