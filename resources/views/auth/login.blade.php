<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hillside Laundry Shop - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Hillside Laundry Shop</h2>
        <p class="text-2xl font-bold mb-6 text-center text-blue-600">Login</p>    

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-600 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium">Email</label>
                <input id="email" type="email" name="email" required autofocus
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium">Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <div class="mb-4 flex items-center">
                <input id="remember" type="checkbox" name="remember" class="mr-2">
                <label for="remember" class="text-sm">Remember me</label>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                Login
            </button>
        </form>

        <p class="mt-4 text-center text-sm">
            Don’t have an account? 
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register here</a>
        </p>
    </div>
</body>
</html>
