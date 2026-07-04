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

    {{-- Barang sudah ada --}}
    <div data-existing-item>
        <label class="form-label">Pilih Barang</label>
        <select name="item_id" class="form-select">
            <option value="">— Pilih Barang —</option>
            @foreach($items as $i)
                <option value="{{ $i->id }}" @selected(old('item_id', $procurement?->item_id) == $i->id)>{{ $i->nama }}</option>
            @endforeach
        </select>
        @error('item_id')<p class="form-error">{{ $message }}</p>@enderror
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
