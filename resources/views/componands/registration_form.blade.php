<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Registration Form</h2>
        <form action="{{ route('UserRegister') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="firstName" class="block text-gray-700 font-medium mb-2">First Name</label>
                <input type="text" id="firstName" name="firstName" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your first name" required>
            </div>
            <div>
                <label for="lastName" class="block text-gray-700 font-medium mb-2">Last Name</label>
                <input type="text" id="lastName" name="lastName" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your last name" required>
            </div>
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your email" required>
            </div>
            <div>
                <label for="mobile" class="block text-gray-700 font-medium mb-2">Mobile</label>
                <input type="text" id="mobile" name="mobile" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your mobile number" required>
            </div>
            <div>
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                Complete
            </button>
        </form>
        <!-- back to login page -->
        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Back to Login</a>
        </div>
    </div>
</body>

</html>