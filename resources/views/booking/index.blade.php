@extends('layouts.app')

@section('title', __('site.booking.title'))
@section('description', __('site.booking.subtitle'))

@section('content')

{{-- Hero --}}
<section class="relative text-white overflow-hidden" style="min-height: 340px;">
    <div class="absolute inset-0"
         style="background-image: url('https://images.unsplash.com/photo-1585435557343-3b092031a831?w=1920&q=80&auto=format&fit=crop'); background-size: cover; background-position: center;">
    </div>
    <div class="absolute inset-0 bg-gradient-to-br from-green-950/92 to-emerald-800/85"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <h1 class="text-4xl font-extrabold mb-3">{{ __('site.booking.title') }}</h1>
        <p class="text-green-100">{{ __('site.booking.subtitle') }}</p>
    </div>
</section>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    @if(session('success'))
        <div class="mb-8 p-5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
        <form method="POST" action="{{ route(app()->getLocale() === 'ar' ? 'ar.booking.store' : 'booking.store') }}"
              class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        {{ __('site.booking.name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-400 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        {{ __('site.booking.phone') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent @error('phone') border-red-400 @enderror">
                    @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('site.booking.email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email') border-red-400 @enderror">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Test/Package --}}
            @if($packages->isNotEmpty())
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('site.booking.test') }}</label>
                    <select name="package_id"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                        <option value="">— {{ __('site.tests.all') }} —</option>
                        @foreach($packages as $pkg)
                            <option value="{{ $pkg->id }}" {{ old('package_id') == $pkg->id ? 'selected' : '' }}>
                                {{ $pkg->name }}{{ $pkg->price_egp ? ' — ' . number_format($pkg->price_egp, 0) . ' ' . __('site.common.egp') : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('site.booking.test') }}</label>
                    <input type="text" name="test_name" value="{{ old('test_name') }}"
                           placeholder="e.g. CBC, Lipid Profile..."
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            @endif

            {{-- Branch --}}
            @if($branches->isNotEmpty())
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('site.booking.branch') }}</label>
                    <select name="branch_id"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                        <option value="">— {{ __('site.branches.title') }} —</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}{{ $branch->city ? ' — ' . $branch->city : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('site.booking.date') }}</label>
                <input type="date" name="preferred_date" value="{{ old('preferred_date') }}"
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent @error('preferred_date') border-red-400 @enderror">
                @error('preferred_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('site.booking.notes') }}</label>
                <textarea name="notes" rows="3"
                          class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('notes') }}</textarea>
            </div>

            <button type="submit"
                    class="w-full py-3.5 bg-green-700 hover:bg-green-800 text-white font-bold rounded-xl transition-colors text-base">
                {{ __('site.booking.submit') }}
            </button>

            <p class="text-center text-xs text-slate-400">
                {{ __('site.booking.hint') }}:
                <a href="tel:19000" class="text-green-600 font-semibold hover:text-green-700">19XXX</a>
            </p>
        </form>
    </div>
</div>

@endsection
