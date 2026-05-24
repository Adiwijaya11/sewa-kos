@extends('layouts.dashboard')

@section('title', 'Tambah Kos - KosinAja')
@section('header_title', 'Pasang Properti Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="kosForm()">
    <!-- Header Card -->
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
        <h2 class="text-base font-bold text-slate-800">Formulir Pendaftaran Kos</h2>
        <p class="text-xs text-slate-400 mt-1">Lengkapi informasi properti kos Anda untuk mulai menarik calon penyewa.</p>
    </div>

    <!-- Error Validation Alert -->
    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs">
            <p class="font-bold mb-1.5"><i class="fa-solid fa-circle-exclamation mr-1.5"></i>Mohon perbaiki kesalahan berikut:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('owner.listings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- SECTION 1: MAIN INFO -->
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm space-y-5">
            <h3 class="font-bold text-slate-800 text-sm border-b border-slate-50 pb-2.5 flex items-center">
                <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs mr-2 font-bold">1</span>
                Informasi Utama
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                <!-- Title -->
                <div class="space-y-1.5 sm:col-span-2 md:col-span-3">
                    <label class="text-xs font-bold text-slate-700 block">Nama Properti Kos <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Kos Putri Eksklusif Menteng Indah tipe A" 
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs" required>
                </div>

                <!-- Price -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 block">Harga Sewa (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price') }}" placeholder="Contoh: 1500000" min="10000"
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs" required>
                </div>

                <!-- Room Size -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 block">Ukuran Kamar <span class="text-red-500">*</span></label>
                    <input type="text" name="room_size" value="{{ old('room_size') }}" placeholder="Contoh: 3x4 m atau 4x4" 
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs" required>
                </div>

                <!-- Max People -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 block">Maksimal Penghuni <span class="text-red-500">*</span></label>
                    <input type="number" name="max_people" value="{{ old('max_people', 1) }}" placeholder="Contoh: 1" min="1" max="50"
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs" required>
                </div>

                <!-- Total Rooms -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 block">Total Kamar <span class="text-red-500">*</span></label>
                    <input type="number" name="total_rooms" value="{{ old('total_rooms', 10) }}" placeholder="Contoh: 10" min="1" max="1000"
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs" required>
                </div>

                <!-- Available Rooms -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 block">Kamar Tersedia <span class="text-red-500">*</span></label>
                    <input type="number" name="available_rooms" value="{{ old('available_rooms', 5) }}" placeholder="Contoh: 5" min="0" max="1000"
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs" required>
                </div>

                <!-- Gender -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 block">Tipe Penghuni Kos <span class="text-red-500">*</span></label>
                    <select name="gender_type" class="w-full h-10 px-3 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs capitalize" required>
                        <option value="">-- Pilih Tipe --</option>
                        @foreach(\App\Models\Setting::getGenderTypes() as $type)
                            <option value="{{ $type }}" {{ old('gender_type') === $type ? 'selected' : '' }}>
                                @if($type === 'putra') Khusus Putra
                                @elseif($type === 'putri') Khusus Putri
                                @elseif($type === 'campur') Campur / Bebas
                                @else Kos {{ $type }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Description -->
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-700 block">Deskripsi Kos <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" placeholder="Tuliskan spesifikasi kamar, akses kunci 24 jam, kebijakan listrik, dan informasi pelengkap lainnya..." 
                          class="w-full p-3.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs" required>{{ old('description') }}</textarea>
                <span class="text-[10px] text-slate-400 block">Minimal 20 karakter penjelasan yang jelas.</span>
            </div>
        </div>

        <!-- SECTION 2: MAP AND LOCATION -->
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm space-y-5">
            <h3 class="font-bold text-slate-800 text-sm border-b border-slate-50 pb-2.5 flex items-center">
                <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs mr-2 font-bold">2</span>
                Lokasi & Peta Koordinat
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Province -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 block">Provinsi <span class="text-red-500">*</span></label>
                    <input type="text" name="province" id="provinceInput" value="{{ old('province') }}" placeholder="Contoh: DKI Jakarta" 
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs" required>
                </div>

                <!-- City -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 block">Kota / Kabupaten <span class="text-red-500">*</span></label>
                    <input type="text" name="city" id="cityInput" value="{{ old('city') }}" placeholder="Contoh: Jakarta Selatan" 
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs" required>
                </div>

                <!-- Address -->
                <div class="space-y-1.5 md:col-span-3">
                    <label class="text-xs font-bold text-slate-700 block">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="address" rows="2" placeholder="Contoh: Jl. Menteng Raya No. 45, RT 02/RW 03, Menteng" 
                              class="w-full p-3 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs" required>{{ old('address') }}</textarea>
                </div>
            </div>

            <!-- Google Maps Link Auto-Parser -->
            <div class="space-y-1.5 p-4 bg-emerald-50/40 rounded-2xl border border-emerald-100/50">
                <label class="text-xs font-bold text-slate-700 block flex items-center">
                    <i class="fa-solid fa-map-location-dot text-emerald-600 mr-2 text-sm"></i>
                    Tempel Link Google Maps atau Koordinat (Auto-Detect Pin)
                </label>
                <p class="text-[10px] text-slate-400 leading-normal mb-2">
                    Tempelkan link Google Maps (share link) atau koordinat mentah (contoh: <code>-6.2088, 106.8456</code>). Sistem akan otomatis mendeteksi dan memindahkan pin peta di bawah!
                </p>
                <div class="relative">
                    <input type="text" id="gmapsLinkInput" placeholder="Tempel link Google Maps atau koordinat di sini..." 
                           class="w-full h-10 pl-3.5 pr-10 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs bg-white">
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <i class="fa-solid fa-clipboard text-xs"></i>
                    </div>
                </div>
                <span id="gmapsParserFeedback" class="text-[10px] hidden font-semibold block"></span>
            </div>
            
            <!-- Map Picker Widget -->
            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-700 block">Pin Peta Koordinat (Opsional)</label>
                <p class="text-[10px] text-slate-400 leading-relaxed mb-2.5">
                    Klik pada peta di bawah ini untuk menandai posisi akurat kos Anda. Ini membantu pencari kos menemukan hunian Anda di pencarian peta terdekat.
                </p>
                
                <!-- Coordinates output -->
                <div class="grid grid-cols-2 gap-3 mb-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-bold block">Latitude</span>
                        <input type="text" name="latitude" id="latitudeInput" value="{{ old('latitude') }}" readonly 
                               class="text-xs font-bold text-slate-700 bg-transparent border-none p-0 focus:ring-0 select-all cursor-default">
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-bold block">Longitude</span>
                        <input type="text" name="longitude" id="longitudeInput" value="{{ old('longitude') }}" readonly 
                               class="text-xs font-bold text-slate-700 bg-transparent border-none p-0 focus:ring-0 select-all cursor-default">
                    </div>
                </div>

                <!-- Map Div -->
                <div id="mapPicker" class="h-64 rounded-xl border border-slate-200 shadow-inner z-10"></div>
            </div>

            <!-- Nearby Places (Aksesibilitas & SEO) -->
            <div class="border-t border-slate-100 pt-5 space-y-4">
                <div>
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center">
                        <i class="fa-solid fa-route text-emerald-500 mr-2"></i>Aksesibilitas Lokasi Sekitar (Bagus untuk SEO)
                    </h4>
                    <p class="text-[10px] text-slate-400 mt-0.5">Tuliskan nama kampus, pusat perbelanjaan, rumah sakit, atau stasiun terdekat beserta estimasi waktu tempuh untuk mempermudah pencarian.</p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Near Campus -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-600 block flex items-center"><i class="fa-solid fa-graduation-cap text-emerald-500 mr-1.5 w-4 text-center"></i>Dekat Kampus / Sekolah</label>
                        <input type="text" name="near_campus" value="{{ old('near_campus') }}" placeholder="Contoh: Kampus UGM (5 menit jalan kaki)" 
                               class="w-full h-10 px-3.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs">
                    </div>

                    <!-- Near Mall -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-600 block flex items-center"><i class="fa-solid fa-bag-shopping text-emerald-500 mr-1.5 w-4 text-center"></i>Dekat Pusat Belanja / Mall</label>
                        <input type="text" name="near_mall" value="{{ old('near_mall') }}" placeholder="Contoh: Ambarrukmo Plaza (10 menit berkendara)" 
                               class="w-full h-10 px-3.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs">
                    </div>

                    <!-- Near Hospital -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-600 block flex items-center"><i class="fa-solid fa-house-medical text-emerald-500 mr-1.5 w-4 text-center"></i>Dekat Rumah Sakit / Klinik</label>
                        <input type="text" name="near_hospital" value="{{ old('near_hospital') }}" placeholder="Contoh: RS Sardjito (6 menit)" 
                               class="w-full h-10 px-3.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs">
                    </div>

                    <!-- Near Station -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-600 block flex items-center"><i class="fa-solid fa-train text-emerald-500 mr-1.5 w-4 text-center"></i>Dekat Stasiun / Terminal / Halte</label>
                        <input type="text" name="near_station" value="{{ old('near_station') }}" placeholder="Contoh: Stasiun Tugu (10 menit)" 
                               class="w-full h-10 px-3.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 text-xs">
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: FACILITIES -->
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm space-y-5">
            <h3 class="font-bold text-slate-800 text-sm border-b border-slate-50 pb-2.5 flex items-center">
                <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs mr-2 font-bold">3</span>
                Fasilitas Properti <span class="text-red-500 ml-1">*</span>
            </h3>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($facilities as $facility)
                    <label class="flex items-center space-x-3.5 p-3 rounded-xl border border-slate-100 hover:border-emerald-100 bg-white hover:bg-slate-50/50 cursor-pointer shadow-sm transition-all select-none">
                        <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" 
                               {{ is_array(old('facilities')) && in_array($facility->id, old('facilities')) ? 'checked' : '' }}
                               class="rounded border-slate-200 text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                        <div class="min-w-0">
                            <!-- Facility Icon -->
                            <span class="text-xs text-slate-700 flex items-center font-medium truncate">
                                @if($facility->icon)
                                    <i class="fa-solid fa-{{ $facility->icon }} text-emerald-500 mr-2 flex-shrink-0"></i>
                                @endif
                                {{ $facility->name }}
                            </span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- SECTION 4: MULTI IMAGES UPLOAD -->
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm space-y-5">
            <h3 class="font-bold text-slate-800 text-sm border-b border-slate-50 pb-2.5 flex items-center">
                <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs mr-2 font-bold">4</span>
                Galeri Foto Kos <span class="text-red-500 ml-1">*</span>
            </h3>
            
            <div class="space-y-4">
                <!-- Drop & Select Zone -->
                <label class="border-2 border-dashed border-slate-200 hover:border-emerald-500 rounded-2xl p-6 bg-slate-50/50 hover:bg-emerald-50/10 flex flex-col items-center justify-center cursor-pointer transition-all relative">
                    <i class="fa-regular fa-images text-3xl text-slate-400 mb-2"></i>
                    <span class="text-xs font-bold text-slate-700">Unggah Gambar Kamar & Properti</span>
                    <span class="text-[10px] text-slate-400 mt-1 text-center max-w-md">
                        Klik untuk memilih foto. Anda dapat menambahkan beberapa foto satu per satu (otomatis terakumulasi di bawah).
                    </span>
                    <span class="text-[9px] text-emerald-600 font-semibold mt-1">Format: JPEG, PNG, WEBP (maks 3MB per foto)</span>
                    <input type="file" id="galleryInput" name="images[]" multiple class="hidden" accept="image/*" @change="addFiles">
                </label>
                
                <!-- Preview Grid with Action Overlay -->
                <div x-show="previews.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3.5 p-3.5 bg-slate-50 rounded-2xl border border-slate-100" x-cloak>
                    <template x-for="(img, idx) in previews" :key="img.id">
                        <div class="relative group aspect-square rounded-xl overflow-hidden border border-slate-200 bg-white shadow-sm transition-all hover:scale-[1.02]">
                            <img :src="img.src" class="w-full h-full object-cover">
                            
                            <!-- Index Badge -->
                            <span class="absolute top-2 left-2 bg-slate-900/70 backdrop-blur-sm text-white text-[9px] font-bold px-1.5 py-0.5 rounded-lg" x-text="'Foto ' + (idx + 1)"></span>
                            
                            <!-- Remove Overlay (Always visible on hover) -->
                            <button type="button" @click="removeFile(idx)" 
                                    class="absolute inset-0 bg-red-950/70 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center text-white transition-opacity duration-200">
                                <div class="w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 flex items-center justify-center shadow-lg transition-transform transform active:scale-95 mb-1">
                                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                                </div>
                                <span class="text-[9px] font-bold tracking-wider uppercase">Hapus Foto</span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- FORM SUBMIT BUTTONS -->
        <div class="flex items-center space-x-3 justify-end pt-2">
            <a href="{{ route('owner.listings.index') }}" class="px-5 h-11 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold flex items-center justify-center transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 h-11 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all flex items-center justify-center shadow-lg shadow-emerald-600/20">
                <i class="fa-solid fa-cloud-arrow-up mr-2"></i>Tayangkan Properti Kos
            </button>
        </div>
    </form>
</div>

<script>
    function kosForm() {
        return {
            previews: [],
            filesList: [],
            
            addFiles(event) {
                const files = event.target.files;
                if (!files) return;

                Array.from(files).forEach(file => {
                    const isDuplicate = this.filesList.some(f => f.name === file.name && f.size === file.size);
                    if (isDuplicate) return;

                    if (file.size > 3 * 1024 * 1024) {
                        Swal.fire({
                            title: 'Ukuran File Terlalu Besar',
                            text: `File "${file.name}" melebihi batas maksimal 3MB.`,
                            icon: 'warning',
                            confirmButtonColor: '#10b981'
                        });
                        return;
                    }

                    if (!file.type.match('image.*')) {
                        Swal.fire({
                            title: 'Tipe File Tidak Valid',
                            text: `File "${file.name}" bukan format gambar yang didukung.`,
                            icon: 'warning',
                            confirmButtonColor: '#10b981'
                        });
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const id = Date.now() + Math.random().toString(36).substr(2, 9);
                        this.filesList.push(file);
                        this.previews.push({
                            id: id,
                            name: file.name,
                            src: e.target.result
                        });
                        this.syncFileInput();
                    };
                    reader.readAsDataURL(file);
                });

                event.target.value = '';
            },

            removeFile(index) {
                this.previews.splice(index, 1);
                this.filesList.splice(index, 1);
                this.syncFileInput();
            },

            syncFileInput() {
                const fileInput = document.getElementById('galleryInput');
                if (!fileInput) return;

                const dataTransfer = new DataTransfer();
                this.filesList.forEach(file => {
                    dataTransfer.items.add(file);
                });
                fileInput.files = dataTransfer.files;
            }
        };
    }

    // Leaflet map initialization for picker
    window.addEventListener('load', () => {
        // Initial coordinates (Jakarta default)
        const defaultLat = -6.2088;
        const defaultLng = 106.8456;

        document.getElementById('latitudeInput').value = defaultLat;
        document.getElementById('longitudeInput').value = defaultLng;

        const map = L.map('mapPicker').setView([defaultLat, defaultLng], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Custom emerald pin marker
        let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        const updateCoordinates = (lat, lng) => {
            document.getElementById('latitudeInput').value = parseFloat(lat).toFixed(6);
            document.getElementById('longitudeInput').value = parseFloat(lng).toFixed(6);
        };

        // When user drag marker
        marker.on('dragend', function (event) {
            const position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
        });

        // When user clicks anywhere on map
        map.on('click', function (e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            marker.setLatLng([lat, lng]);
            updateCoordinates(lat, lng);
        });

        // Google Maps Link Parser Engine
        const gmapsInput = document.getElementById('gmapsLinkInput');
        const feedback = document.getElementById('gmapsParserFeedback');

        gmapsInput.addEventListener('input', function (e) {
            const val = e.target.value.trim();
            if (!val) {
                feedback.classList.add('hidden');
                return;
            }

            let lat = null;
            let lng = null;

            // 1. Try to parse direct coordinates: "lat, lng" (e.g. -6.2088, 106.8456)
            const coordRegex = /^(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)$/;
            const matchCoord = val.match(coordRegex);
            if (matchCoord) {
                lat = parseFloat(matchCoord[1]);
                lng = parseFloat(matchCoord[2]);
            } else {
                // 2. Try to parse from @lat,lng in URL (e.g. /@something,-6.2088,106.8456,15z)
                const atRegex = /@(-?\d+\.\d+),(-?\d+\.\d+)/;
                const matchAt = val.match(atRegex);
                if (matchAt) {
                    lat = parseFloat(matchAt[1]);
                    lng = parseFloat(matchAt[2]);
                } else {
                    // 3. Try to parse query parameters: ?q=lat,lng or ?query=lat,lng
                    const queryRegex = /[?&](q|query)=(-?\d+\.\d+),(-?\d+\.\d+)/;
                    const matchQuery = val.match(queryRegex);
                    if (matchQuery) {
                        lat = parseFloat(matchQuery[2]);
                        lng = parseFloat(matchQuery[3]);
                    }
                }
            }

            if (lat && lng && !isNaN(lat) && !isNaN(lng)) {
                // Bounds safety check (lat -90 to 90, lng -180 to 180)
                if (lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    // Update outputs
                    updateCoordinates(lat, lng);
                    
                    // Update Map & Marker
                    marker.setLatLng([lat, lng]);
                    map.setView([lat, lng], 16);

                    // Show success feedback
                    feedback.textContent = `✓ Koordinat berhasil terdeteksi: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                    feedback.className = "text-[10px] font-semibold text-emerald-600 mt-1 block";
                    feedback.classList.remove('hidden');
                    return;
                }
            }

            // 4. Fallback: If it's a URL (like https://maps.app.goo.gl/...) resolve via backend proxy!
            if (val.startsWith('http')) {
                feedback.textContent = "⏳ Mendeteksi dan melacak lokasi Google Maps...";
                feedback.className = "text-[10px] font-semibold text-blue-600 mt-1 block";
                feedback.classList.remove('hidden');

                fetch(`/resolve-maps-url?url=${encodeURIComponent(val)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const parsedLat = data.lat;
                            const parsedLng = data.lng;
                            updateCoordinates(parsedLat, parsedLng);
                            marker.setLatLng([parsedLat, parsedLng]);
                            map.setView([parsedLat, parsedLng], 16);

                            feedback.textContent = `✓ Lokasi berhasil dideteksi dari link: ${parsedLat.toFixed(6)}, ${parsedLng.toFixed(6)}`;
                            feedback.className = "text-[10px] font-semibold text-emerald-600 mt-1 block";
                        } else {
                            feedback.textContent = `⚠ Gagal mendeteksi lokasi: ${data.message || 'Koordinat tidak ditemukan'}`;
                            feedback.className = "text-[10px] font-semibold text-red-600 mt-1 block";
                        }
                    })
                    .catch(err => {
                        feedback.textContent = `⚠ Terjadi kesalahan koneksi saat melacak lokasi.`;
                        feedback.className = "text-[10px] font-semibold text-red-600 mt-1 block";
                    });
            } else {
                // Show error feedback for non-URLs that look like coords but failed
                if (val.includes(',')) {
                    feedback.textContent = `⚠ Koordinat tidak terbaca. Pastikan formatnya latitude, longitude (contoh: -6.2088, 106.8456)`;
                    feedback.className = "text-[10px] font-semibold text-amber-600 mt-1 block";
                    feedback.classList.remove('hidden');
                } else {
                    feedback.classList.add('hidden');
                }
            }
        });
    });
</script>
@endsection
