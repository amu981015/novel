{{-- resources/views/novels/index.blade.php --}}
@extends('layouts.app') {{-- 之後再建 layout，現在先用預設 --}}

@section('content')
<div class="container mx-auto px-4 py-8">

    <!-- 分類篩選 -->
    <div class="mb-6 flex gap-2 flex-wrap">
        <a href="{{ route('novels.index') }}"
           class="px-4 py-2 rounded {{ is_null($currentCategory) ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
            全部
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('novels.index', ['category' => $cat->id]) }}"
               class="px-4 py-2 rounded {{ $currentCategory?->id == $cat->id ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <!-- 小說卡片列表 -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($novels as $novel)
            <div class="border rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                <!-- 封面 -->
                @if($novel->cover)
                    <img src="{{ asset('storage/' . $novel->cover) }}" alt="{{ $novel->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="bg-gray-200 border-2 border-dashed rounded-t-lg w-full h-48 flex items-center justify-center">
                        <span class="text-gray-500">無封面</span>
                    </div>
                @endif

                <div class="p-4">
                    <h3 class="font-bold text-lg mb-1">
                        <a href="#" class="hover:text-blue-600">{{ $novel->title }}</a>
                    </h3>
                    <p class="text-sm text-gray-600">作者：{{ $novel->author }}</p>

                    @if($novel->chapters->count() > 0)
                        <p class="text-sm text-gray-500 mt-1">
                            最新：{{ $novel->chapters->last()->title }}
                        </p>
                        <p class="text-xs text-gray-400">
                            更新：{{ $novel->updated_at->format('Y-m-d') }}
                        </p>
                    @else
                        <p class="text-sm text-gray-500">尚未有章節</p>
                    @endif

                    <div class="mt-2 text-xs text-gray-500">
                        {{ $novel->category->name }} ｜ 共 {{ $novel->chapters_count }} 章
                    </div>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-500">目前沒有小說</p>
        @endforelse
    </div>

    <!-- 分頁 -->
    <div class="mt-8">
        {{ $novels->appends(request()->query())->links() }}
    </div>
</div>
@endsection