{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>小說網站 - @yield('title', '首頁')</title>

    <!-- Tailwind CDN（不用安裝）-->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Noto Sans TC', sans-serif; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">

    <!-- 頂部導覽列（可留空，之後再加）-->
    <header class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">
                <a href="{{ route('novels.index') }}">小說站</a>
            </h1>
            <div class="text-sm text-gray-600">
                歡迎，訪客
            </div>
        </div>
    </header>

    <!-- 主要內容 -->
    <main>
        @yield('content')
    </main>

    <!-- 頁尾 -->
    <footer class="bg-white border-t mt-12 py-6">
        <div class="container mx-auto px-4 text-center text-sm text-gray-500">
            © 2025 小說網站 | 版權所有
        </div>
    </footer>

</body>
</html>