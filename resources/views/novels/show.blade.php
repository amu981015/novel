{{-- resources/views/novels/show.blade.php --}}
@extends('layouts.app')

@section('title', $novel->title)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">

    <!-- 書名與基本資訊 -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- 封面 -->
            <div class="flex-shrink-0">
                @if($novel->cover)
                <img src="{{ asset('storage/'.$novel->cover) }}" alt="{{ $novel->title }}" class="w-48 h-64 object-cover rounded">
                @else
                <div class="bg-gray-200 border-2 border-dashed rounded w-48 h-64 flex items-center justify-center">
                    <span class="text-gray-500">無封面</span>
                </div>
                @endif
            </div>

            <!-- 資訊 -->
            <div class="flex-1">
                <h1 class="text-3xl font-bold mb-3">{{ $novel->title }}</h1>
                <div class="text-gray-600 space-y-1">
                    <p>作者：{{ $novel->author }}</p>
                    <p>分類：{{ $novel->category->name }}</p>
                    <p>狀態：
                        @switch($novel->status)
                        @case(0) 連載中 @break
                        @case(1) 完結 @break
                        @case(2) 停更 @break
                        @endswitch
                    </p>
                    <p>總章節：{{ $novel->chapters_count }} 章</p>
                </div>

                <div class="mt-4 flex gap-3">
                    <button class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        加入書架
                    </button>
                    @if($latestChapter)
                    <a href="{{ route('novels.chapter', [$novel->id, $latestChapter->chapter_num]) }}"
                        class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        最新章節
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- 簡介 -->
        @if($novel->intro)
        <div class="mt-8 border-t pt-6">
            <h3 class="font-bold text-lg mb-3">小說簡介</h3>
            <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $novel->intro }}</p>
        </div>
        @endif
    </div>

    <!-- 章節列表 -->
    <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
        <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
    <h3 class="font-bold text-gray-800">
        章節目錄 
        <span class="text-sm font-normal text-gray-500">({{ $novel->chapters_count }} 章)</span>
    </h3>
    
    
    @if($sort === 'asc')
        <a href="{{ route('novels.show', ['id' => $novel->id, 'sort' => 'desc']) }}" 
           class="text-sm text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
            <span>↓</span> 點擊倒序
        </a>
    @else
        <a href="{{ route('novels.show', ['id' => $novel->id, 'sort' => 'asc']) }}" 
           class="text-sm text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
            <span>↑</span> 點擊正序
        </a>
    @endif
</div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-2 p-4">
            @forelse($chapters as $chapter)
            <a href="{{ route('novels.chapter', [$novel->id, $chapter->chapter_num]) }}"
                class="block px-2 py-2 text-sm text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded truncate border-b border-dashed border-gray-100 md:border-none"
                title="{{ $chapter->title }}">
                {{ str_pad($chapter->chapter_num, 4, '0', STR_PAD_LEFT) }}章 {{ $chapter->title }}
            </a>
            @empty
            <div class="col-span-full text-center py-8 text-gray-500">尚未發布章節</div>
            @endforelse
        </div>

        <div class="px-6 py-4 border-t">
            {{ $chapters->appends(request()->query())->links() }}
        </div>
    </div>

</div>
@endsection