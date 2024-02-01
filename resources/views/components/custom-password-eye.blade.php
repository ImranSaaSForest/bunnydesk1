<div class="flex absolute inset-y-0 right-0 items-center pr-3 cursor-pointer" onclick="togglePasswordVisibility()">
    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <!-- SVG path for eye icon -->
    </svg>
</div>

<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById('password');
        var eyeIcon = document.getElementById('eyeIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            // Change the eye icon to represent an open eye
        } else {
            passwordInput.type = 'password';
            // Change the eye icon to represent a closed eye
        }
    }
</script>
