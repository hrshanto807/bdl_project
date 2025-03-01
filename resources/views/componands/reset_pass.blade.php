<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Reset Password</h2>

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

        <!-- Password Reset Form -->
        <form action="{{ route('resetPassword') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="password" class="block text-gray-700 font-medium mb-2">New Password</label>
                <input type="password" id="password" name="password" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter new password" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                Reset Password
            </button>
        </form>
    </div>
</body>

</html>