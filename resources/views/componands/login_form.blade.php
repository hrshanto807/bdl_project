<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

        <!-- Display success message -->
        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <!-- Display error messages -->
        @if(session('status') == 'failed')
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('message') }}
        </div>
        @endif

        <!-- Display form validation errors -->
        @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <!-- Login form -->
        <form action="{{ route('UserLogin') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full p-3 border rounded-lg" placeholder="Enter your email" required>
            </div>

            <div>
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full p-3 border rounded-lg" placeholder="Enter your password" required>
            </div>

            <div class="flex justify-between items-center text-sm">
                <a href="{{ route('forgot') }}" class="text-blue-600 hover:underline">Forgot Password?</a>
                <a href="{{ route('register') }}" class="text-green-600 hover:underline">Sign Up</a>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700">
                Login
            </button>
        </form>
    </div>
</body>

</html>