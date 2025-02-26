@extends('layout.sidebar')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-10">
    <div class="bg-white w-full max-w-4xl p-10 rounded-2xl shadow-2xl grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Profile Image & Info -->
        <div class="flex flex-col items-center justify-center bg-blue-50 p-6 rounded-2xl">
            <img src="{{ asset('images/user.webp') }}" alt="Profile Picture" class="w-40 h-40 rounded-full shadow-lg border-4 border-blue-500" />
            <h2 class="text-3xl font-bold mt-4 text-gray-800">{{ Auth::user()->firstName }} {{ Auth::user()->lastName }}</h2>
            <p class="text-gray-600 mt-2"><span class="font-bold">Email: </span>{{ Auth::user()->email }}</p>
            <p class="text-gray-600"><span class="font-bold">Phone: </span>+{{ Auth::user()->mobile }}</p>

            <a href="{{ route('userLogout') }}" class="w-full mt-6">
                <button class="w-full bg-red-500 text-white py-3 rounded-lg font-semibold hover:bg-red-600 transition">
                    Log Out
                </button>
            </a>
        </div>

        <!-- Profile Update Form -->
        <div>
            <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Update Profile</h2>

            <form action="{{ route('updateProfile') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

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

                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                    Update Profile
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
