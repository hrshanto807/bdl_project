<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Send OTP</h2>

        <!-- Display success or error message -->
        @if(session('status') == 'success')
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @elseif(session('status') == 'fail')
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <!-- OTP form -->
        <form action="{{ route('sendOTP') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your email" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                Send OTP
            </button>
        </form>
    </div>
</body>
</html>
