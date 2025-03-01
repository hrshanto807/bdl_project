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

        <!-- Show token for debugging (Remove in production) -->
        @if(session('token'))
        <div class="bg-gray-100 text-gray-800 p-3 rounded mb-4 text-sm">
            <strong>Token:</strong> {{ session('token') }}
        </div>
        @endif

        <!-- OTP Verification Form -->
        <form action="{{ route('verifyOTP') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Email input field -->
            <div>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter your email"
                    value="{{ Auth::check() ? Auth::user()->email : old('email') }}"
                    required />
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
                    required />
            </div>

            <!-- Hidden token field (auto-filled if token exists) -->
            <input type="hidden" name="token" value="{{ session('token') }}">

            <!-- Submit button -->
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                Verify OTP
            </button>
        </form>
    </div>
</body>

</html>