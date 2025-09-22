<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Laundry Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Register</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium">Name</label>
                <input id="name" type="text" name="name" required autofocus
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium">Email</label>
                <input id="email" type="email" name="email" required
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium">Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                Register
            </button>
        </form>

        <p class="mt-4 text-center text-sm">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login here</a>
        </p>
    </div>
</body>
</html>
