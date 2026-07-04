@php($procurement = $procurement ?? null)
@php($isNew = old('is_new_item', $procurement?->is_new_item ? '1' : '0'))

<div class="space-y-4">
    <div>
        <label class="form-label">Jenis Pengajuan <span class="text-red-500">*</span></label>
        <div class="flex gap-4">
            <label class="flex items-center gap-2">
                <input type="radio" name="is_new_item" value="0" @checked($isNew === '0' || $isNew === 0)
                       class="border-gray-300 text-brand-600 focus:ring-brand-500">
                <span class="text-sm">Barang sudah ada</span>
            </label>
            <label class="flex items-center gap-2">
                <input type="radio" name="is_new_item" value="1" @checked($isNew === '1' || $isNew === 1)
                       class="border-gray-300 text-brand-600 focus:ring-brand-500">
                <span class="text-sm">Barang baru</span>
            </label>
        </div>
    </div>

    {{-- Barang sudah ada: dipilih lewat lokasi agar daftar barang tidak terlalu panjang --}}
    <div data-existing-item class="space-y-4">
        @php
            $selItemId = old('item_id', $procurement?->item_id);
            $selItem = $items->firstWhere('id', $selItemId);
            $selLab = $selItem?->lab_id;
            $selTable = $selItem?->lab_table_id;
            $selGroup = null;
            foreach ($labs as $__lab) {
                foreach ($__lab->groups as $__g) {
                    if ($__g->tables->contains('id', $selTable)) { $selGroup = $__g->id; break 2; }
                }
            }
            $cascade = ['labs' => [], 'items' => []];
            foreach ($labs as $__lab) {
                $__groups = [];
                foreach ($__lab->groups as $__g) {
                    $__groups[$__g->id] = [
                        'nama' => $__g->display_name,
                        'tables' => $__g->tables->map(fn ($t) => ['id' => $t->id, 'nama' => $t->display_name])->values(),
                    ];
                }
                $cascade['labs'][$__lab->id] = ['groups' => $__groups];
            }
            foreach ($items as $__it) {
                $cascade['items'][] = ['id' => $__it->id, 'nama' => $__it->nama, 'lab_id' => $__it->lab_id, 'lab_table_id' => $__it->lab_table_id];
            }
        @endphp

        <script type="application/json" data-cascade>@json($cascade)</script>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="form-label">Laboratorium</label>
                <select data-cascade-lab class="form-select">
                    <option value="">— Pilih Lab —</option>
                    @foreach($labs as $l)
                        <option value="{{ $l->id }}" @selected($selLab == $l->id)>{{ $l->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Kelompok</label>
                <select data-cascade-group data-selected="{{ $selGroup }}" class="form-select">
                    <option value="">— Pilih Kelompok —</option>
                </select>
            </div>
            <div>
                <label class="form-label">Meja</label>
                <select data-cascade-table data-selected="{{ $selTable }}" class="form-select">
                    <option value="">— Pilih Meja —</option>
                </select>
            </div>
            <div>
                <label class="form-label">Barang <span class="text-red-500">*</span></label>
                <select name="item_id" data-cascade-item data-selected="{{ $selItemId }}" class="form-select">
                    <option value="">— Pilih Lab dulu —</option>
                </select>
                @error('item_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    {{-- Barang baru --}}
    <div data-new-item class="hidden space-y-4">
        <div>
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select">
                <option value="">— Pilih Kategori —</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" @selected(old('category_id', $procurement?->category_id) == $c->id)>{{ $c->nama }}</option>
                @endforeach
            </select>
            @error('category_id')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label">Nama Barang Baru</label>
            <input type="text" name="nama_barang_baru" value="{{ old('nama_barang_baru', $procurement?->nama_barang_baru) }}" class="form-input" placeholder="mis. Switch 24 Port">
            @error('nama_barang_baru')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label class="form-label">Jumlah <span class="text-red-500">*</span></label>
        <input type="number" name="jumlah" min="1" value="{{ old('jumlah', $procurement?->jumlah ?? 1) }}" class="form-input" required>
        @error('jumlah')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="form-label">Alasan Pengajuan <span class="text-red-500">*</span></label>
        <textarea name="alasan" rows="3" class="form-textarea" required>{{ old('alasan', $procurement?->alasan) }}</textarea>
        @error('alasan')<p class="form-error">{{ $message }}</p>@enderror
    </div>
</div>

<div class="mt-6 flex gap-3">
    <button class="btn-primary">{{ $procurement ? 'Simpan Perubahan' : 'Kirim Pengajuan' }}</button>
    <a href="{{ route('sekretaris.procurements.index') }}" class="btn-secondary">Batal</a>
</div>
