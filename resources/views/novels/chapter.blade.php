@extends('layouts.app')

@section('title', $novel->title . ' - ' . $chapter->title)

@section('content')
<div id="reader-app" class="bg-[#f6f7f9] min-h-screen pb-12 transition-colors duration-300">
    
    <div class="sticky top-0 z-50 bg-white/90 backdrop-blur shadow-sm border-b px-4 py-2 flex justify-between items-center">
        <div class="text-sm truncate max-w-[200px]">
            <a href="{{ route('novels.show', $novel->id) }}" class="hover:text-blue-600">
                &larr; {{ $novel->title }}
            </a>
        </div>
        <div class="flex gap-2 text-sm">
            <button onclick="changeTheme('light')" class="px-2 py-1 border rounded bg-white">白</button>
            <button onclick="changeTheme('sepia')" class="px-2 py-1 border rounded bg-[#f4ecd8]">暖</button>
            <button onclick="changeTheme('dark')" class="px-2 py-1 border rounded bg-gray-800 text-white">黑</button>
            <button onclick="changeSize(1)" class="px-2 py-1 border rounded">A+</button>
            <button onclick="changeSize(-1)" class="px-2 py-1 border rounded">A-</button>
        </div>
    </div>

    <div class="container mx-auto px-4 max-w-4xl mt-8">
        <h2 class="text-2xl md:text-3xl font-bold mb-6 text-center text-gray-800" id="chapter-title">
            {{ $chapter->title }}
        </h2>

        <article id="content-body" class="rounded-lg shadow-sm p-6 md:p-12 text-lg leading-loose text-justify bg-white text-gray-800 transition-colors duration-300">
             @foreach(explode("\n", $chapter->content) as $paragraph)
                 @if(trim($paragraph))
                    <p class="mb-4 indent-8">{{ trim($paragraph) }}</p>
                 @endif
             @endforeach
        </article>

        <div class="mt-12 flex justify-between gap-4">
            @if($prevChapter)
                <a href="{{ route('novels.chapter', [$novel->id, $prevChapter->chapter_num]) }}" id="prev-btn"
                   class="flex-1 py-4 text-center bg-blue-600 text-white rounded hover:bg-blue-700 transition">上一章</a>
            @else
                <button disabled class="flex-1 py-4 bg-gray-300 text-gray-500 rounded cursor-not-allowed">無上一章</button>
            @endif

            <a href="{{ route('novels.show', $novel->id) }}" class="px-4 py-4 border border-gray-300 rounded hover:bg-gray-100">目錄</a>

            @if($nextChapter)
                <a href="{{ route('novels.chapter', [$novel->id, $nextChapter->chapter_num]) }}" id="next-btn"
                   class="flex-1 py-4 text-center bg-blue-600 text-white rounded hover:bg-blue-700 transition">下一章</a>
            @else
                <button disabled class="flex-1 py-4 bg-gray-300 text-gray-500 rounded cursor-not-allowed">無下一章</button>
            @endif
        </div>
    </div>
</div>

<script>
    // 簡單的 JS 實作主題切換與鍵盤翻頁
    const contentBody = document.getElementById('content-body');
    const appBg = document.getElementById('reader-app');
    const title = document.getElementById('chapter-title');
    let currentSize = 18; // 預設 18px

    // 初始化讀取 LocalStorage 設定 (記憶使用者偏好)
    if(localStorage.getItem('reader_theme')) changeTheme(localStorage.getItem('reader_theme'));
    if(localStorage.getItem('reader_size')) {
        currentSize = parseInt(localStorage.getItem('reader_size'));
        contentBody.style.fontSize = currentSize + 'px';
    }

    function changeTheme(theme) {
        localStorage.setItem('reader_theme', theme);
        if (theme === 'dark') {
            appBg.className = 'bg-gray-900 min-h-screen pb-12';
            contentBody.className = 'rounded-lg p-6 md:p-12 leading-loose text-justify bg-gray-800 text-gray-300';
            title.className = 'text-2xl md:text-3xl font-bold mb-6 text-center text-gray-300';
        } else if (theme === 'sepia') {
            appBg.className = 'bg-[#f4ecd8] min-h-screen pb-12';
            contentBody.className = 'rounded-lg p-6 md:p-12 leading-loose text-justify bg-[#fffdf7] text-[#5d4037]';
            title.className = 'text-2xl md:text-3xl font-bold mb-6 text-center text-[#5d4037]';
        } else {
            appBg.className = 'bg-[#f6f7f9] min-h-screen pb-12';
            contentBody.className = 'rounded-lg p-6 md:p-12 leading-loose text-justify bg-white text-gray-800';
            title.className = 'text-2xl md:text-3xl font-bold mb-6 text-center text-gray-800';
        }
    }

    function changeSize(step) {
        currentSize += step;
        if(currentSize < 14) currentSize = 14;
        if(currentSize > 32) currentSize = 32;
        contentBody.style.fontSize = currentSize + 'px';
        localStorage.setItem('reader_size', currentSize);
    }

    // 鍵盤監聽
    document.addEventListener('keydown', function(e) {
        if(e.key === "ArrowLeft") {
            const btn = document.getElementById('prev-btn');
            if(btn) window.location.href = btn.href;
        }
        if(e.key === "ArrowRight") {
            const btn = document.getElementById('next-btn');
            if(btn) window.location.href = btn.href;
        }
    });
</script>
@endsection