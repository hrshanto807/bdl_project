<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">User Profile</h2>
        
        <form action="{{ route('updateProfile') }}" method="POST">
    @csrf
    @method('PUT') <!-- Spoofs PUT request through POST -->

    <div class="space-y-4">
        <div>
            <label class="block text-gray-700 font-medium mb-2">First Name</label>
            <input type="text" name="firstName" value="{{ Auth::user()->firstName }}" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required />
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-2">Last Name</label>
            <input type="text" name="lastName" value="{{ Auth::user()->lastName }}" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required />
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-2">Email</label>
            <input type="email" name="email" value="{{ Auth::user()->email }}" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required />
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-2">Mobile</label>
            <input type="text" name="mobile" value="{{ Auth::user()->mobile }}" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required />
        </div>
    </div>

    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition mt-4">
        Update Profile
    </button>
</form>


        <a href="{{ route('userLogout') }}" class="mt-4 block text-center">
            <button class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                LogOut
            </button>
        </a>
    </div>
</body>
</html>
