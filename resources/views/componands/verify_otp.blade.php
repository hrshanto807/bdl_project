<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Verify OTP</h2>

        <!-- Display success or failure messages -->
        @if(session('status') == 'success')
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @elseif(session('status') == 'fail')
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <!-- OTP Verification Form -->
        <form action="{{ route('verifyOTP') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Visible email input field -->
            <div>
                <!-- <label for="email" class="block text-gray-700 font-medium mb-2">Email</label> -->
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    placeholder="Enter your email" 
                    value="{{ Auth::check() ? Auth::user()->email : old('email') }}" 
                    required style="display: none;"
                />
            </div>

            <!-- OTP input field -->
            <div>
                <label for="otp" class="block text-gray-700 font-medium mb-2">OTP</label>
                <input 
                    type="text" 
                    id="otp" 
                    name="otp" 
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    placeholder="Enter your OTP" 
                    required
                />
            </div>

            <!-- Submit button -->
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                Verify OTP
            </button>
        </form>
    </div>

    <script>
        // Auto-fill the email from localStorage if available
        const emailField = document.getElementById('email');
        const storedEmail = localStorage.getItem('userEmail');
        if (storedEmail) {
            emailField.value = storedEmail; // Populate from localStorage
        }

        // Optionally: Save the email to localStorage when form is submitted
        document.querySelector('form').addEventListener('submit', function () {
            const email = emailField.value;
            localStorage.setItem('userEmail', email); // Store email in localStorage
        });
    </script>
</body>
</html>
