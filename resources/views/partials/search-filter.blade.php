{{-- Partial: Filter Form — digunakan oleh sidebar desktop dan drawer mobile --}}
<form action="{{ route('search') }}" method="GET" id="filterForm" class="space-y-6">
    @if(request()->filled('search'))
        <input type="hidden" name="search" value="{{ request('search') }}">
    @endif



    {{-- Kota --}}
    <div>
        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">
            <i class="fa-solid fa-city mr-1.5 text-slate-400"></i>Kota
        </label>
        <select name="city" id="city" onchange="window.dispatchEvent(new CustomEvent('set-loading')); this.form.submit()"
                class="block w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all bg-white text-slate-600">
            <option value="">Semua Kota</option>
            @foreach($cities as $cityName)
                <option value="{{ $cityName }}" {{ request('city') === $cityName ? 'selected' : '' }}>{{ $cityName }}</option>
            @endforeach
        </select>
    </div>

    {{-- Tipe Penghuni (Pill Toggle) --}}
    <div>
        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">
            <i class="fa-solid fa-person-half-dress mr-1.5 text-slate-400"></i>Tipe Penghuni
        </label>
        <div class="grid grid-cols-2 gap-1.5">
            <label class="cursor-pointer">
                <input type="radio" name="gender_type" value=""
                       {{ request('gender_type', '') === '' ? 'checked' : '' }}
                       class="sr-only peer" onchange="window.dispatchEvent(new CustomEvent('set-loading')); this.form.submit()">
                <div class="text-center text-[10px] font-semibold py-2 rounded-xl border transition-all
                    peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 peer-checked:shadow-sm peer-checked:shadow-emerald-200
                    border-slate-200 text-slate-500 hover:border-emerald-300 hover:text-emerald-600">
                    Semua
                </div>
            </label>
            @foreach(\App\Models\Setting::getGenderTypes() as $type)
                <label class="cursor-pointer">
                    <input type="radio" name="gender_type" value="{{ $type }}"
                           {{ request('gender_type', '') === $type ? 'checked' : '' }}
                           class="sr-only peer" onchange="window.dispatchEvent(new CustomEvent('set-loading')); this.form.submit()">
                    <div class="text-center text-[10px] font-semibold py-2 rounded-xl border transition-all capitalize
                        peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 peer-checked:shadow-sm peer-checked:shadow-emerald-200
                        border-slate-200 text-slate-500 hover:border-emerald-300 hover:text-emerald-600">
                        {{ $type }}
                    </div>
                </label>
            @endforeach
        </div>
    </div>

    {{-- Ukuran Kamar --}}
    <div>
        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">
            <i class="fa-solid fa-expand mr-1.5 text-slate-400"></i>Ukuran Kamar
        </label>
        <select name="room_size" id="room_size" onchange="window.dispatchEvent(new CustomEvent('set-loading')); this.form.submit()"
                class="block w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all bg-white text-slate-600">
            <option value="">Semua Ukuran</option>
            <option value="3x3" {{ request('room_size') === '3x3' ? 'selected' : '' }}>3x3 meter</option>
            <option value="3x4" {{ request('room_size') === '3x4' ? 'selected' : '' }}>3x4 meter</option>
            <option value="4x4" {{ request('room_size') === '4x4' ? 'selected' : '' }}>4x4 meter</option>
        </select>
    </div>

    {{-- Rentang Harga --}}
    <div>
        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">
            <i class="fa-solid fa-tags mr-1.5 text-slate-400"></i>Rentang Harga /bulan
        </label>
        <div class="space-y-2">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 font-semibold">Rp</span>
                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min (cth: 500000)"
                       onchange="window.dispatchEvent(new CustomEvent('set-loading')); this.form.submit()"
                       class="block w-full border border-slate-200 rounded-xl pl-8 pr-3 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all placeholder-slate-300">
            </div>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 font-semibold">Rp</span>
                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max (cth: 3000000)"
                       onchange="window.dispatchEvent(new CustomEvent('set-loading')); this.form.submit()"
                       class="block w-full border border-slate-200 rounded-xl pl-8 pr-3 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all placeholder-slate-300">
            </div>
            <div class="flex flex-wrap gap-1 pt-1">
                @foreach(['500000' => '< 500rb', '1000000' => '< 1jt', '2000000' => '< 2jt', '3000000' => '< 3jt'] as $price => $label)
                    <button type="button" onclick="document.querySelectorAll('[name=max_price]').forEach(el => el.value='{{ $price }}'); window.dispatchEvent(new CustomEvent('set-loading')); this.form.submit()"
                            class="text-[9px] font-semibold px-2 py-1 rounded-lg border border-slate-200 text-slate-500 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 transition-all">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Fasilitas --}}
    <div>
        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">
            <i class="fa-solid fa-bath mr-1.5 text-slate-400"></i>Fasilitas
        </label>
        <div class="flex flex-wrap gap-1.5">
            @foreach($facilitiesList as $facility)
                <label class="cursor-pointer">
                    <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                           {{ is_array(request('facilities')) && in_array($facility->id, request('facilities')) ? 'checked' : '' }}
                           class="sr-only peer" onchange="window.dispatchEvent(new CustomEvent('set-loading')); this.form.submit()">
                    <div class="inline-flex items-center text-[10px] font-semibold px-2.5 py-1.5 rounded-xl border transition-all
                        peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 peer-checked:shadow-sm
                        border-slate-200 text-slate-500 hover:border-emerald-300 hover:text-emerald-600 whitespace-nowrap">
                        @if($facility->icon)
                            <i class="fa-solid fa-{{ $facility->icon }} mr-1"></i>
                        @endif
                        {{ $facility->name }}
                    </div>
                </label>
            @endforeach
        </div>
    </div>

    {{-- Urutkan --}}
    <div>
        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2.5">
            <i class="fa-solid fa-arrow-up-wide-short mr-1.5 text-slate-400"></i>Urutkan
        </label>
        <div class="grid grid-cols-1 gap-1.5">
            @foreach([
                'latest'     => ['icon' => 'clock-rotate-left',      'label' => 'Terbaru'],
                'popular'    => ['icon' => 'fire',                    'label' => 'Terpopuler'],
                'price_asc'  => ['icon' => 'arrow-up-short-wide',     'label' => 'Harga Terendah'],
                'price_desc' => ['icon' => 'arrow-down-wide-short',   'label' => 'Harga Tertinggi'],
            ] as $val => $opt)
                <label class="cursor-pointer">
                    <input type="radio" name="sort" value="{{ $val }}"
                           {{ request('sort', 'latest') === $val ? 'checked' : '' }}
                           class="sr-only peer" onchange="window.dispatchEvent(new CustomEvent('set-loading')); this.form.submit()">
                    <div class="flex items-center text-xs font-medium py-2 px-3 rounded-xl border transition-all
                        peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 peer-checked:shadow-sm peer-checked:shadow-emerald-200
                        border-slate-200 text-slate-500 hover:border-emerald-300 hover:text-emerald-600">
                        <i class="fa-solid fa-{{ $opt['icon'] }} mr-2 text-[10px]"></i>{{ $opt['label'] }}
                    </div>
                </label>
            @endforeach
        </div>
    </div>


</form>
