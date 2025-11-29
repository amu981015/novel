{{-- resources/views/novels/chapter.blade.php --}}
@extends('layouts.app')

@section('title', $novel->title . ' - 第' . str_pad($chapter->chapter_num, 4, '0', STR_PAD_LEFT) . '章 ' . $chapter->title)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">

    <!-- 標題 -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold mb-2">{{ $novel->title }}</h1>
        <h2 class="text-xl text-gray-600">
            第 {{ str_pad($chapter->chapter_num, 4, '0', STR_PAD_LEFT) }} 章　{{ $chapter->title }}
        </h2>
    </div>

    <!-- 正文內容 -->
    <article class="bg-white rounded-lg shadow-lg p-8 md:p-12 leading-8 text-lg text-gray-800">
        {{-- 關鍵！保留原文的換行與段落 --}}
        <div class="whitespace-pre-wrap">{{ $chapter->content }}</div>
    </article>

    <!-- 翻頁按鈕 -->
    <div class="mt-10 flex justify-center gap-6 text-lg">
        @if($prevChapter)
            <a href="{{ route('novels.chapter', [$novel->id, $prevChapter->chapter_num]) }}"
               class="px-8 py-3 bg-blue-600 text-white rounded hover:bg-blue-700">
                ← 上一章
            </a>
        @else
            <span class="px-8 py-3 text-gray-400">沒有上一章</span>
        @endif

        <a href="{{ route('novels.show', $novel->id) }}"
           class="px-8 py-3 bg-gray-600 text-white rounded hover:bg-gray-700">
            回目錄
        </a>

        @if($nextChapter)
            <a href="{{ route('novels.chapter', [$novel->id, $nextChapter->chapter_num]) }}"
               class="px-8 py-3 bg-green-600 text-white rounded hover:bg-green-700">
                下一章 →
            </a>
        @else
            <span class="px-8 py-3 text-gray-400">沒有下一章</span>
        @endif
    </div>

</div>
@endsection