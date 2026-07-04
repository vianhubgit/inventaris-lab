/**
 * Dropdown bertingkat (cascading) untuk pemilihan lokasi & barang.
 *
 * Markup yang diharapkan:
 *  - Sebuah <script type="application/json" data-cascade>{...}</script> berisi:
 *      { labs: { "<labId>": { groups: { "<groupId>": { nama, tables: [{id,nama}] } } } },
 *        items: [{ id, nama, lab_id, lab_table_id }] }
 *  - Select dengan atribut: data-cascade-lab, data-cascade-group, data-cascade-table, data-cascade-item
 *  - Nilai awal dapat diset via data-selected pada masing-masing select.
 */
export function initCascade() {
    const dataEl = document.querySelector('script[type="application/json"][data-cascade]');
    if (!dataEl) return;

    let data;
    try {
        data = JSON.parse(dataEl.textContent);
    } catch (e) {
        console.error('Data cascade tidak valid', e);
        return;
    }

    const labSel = document.querySelector('[data-cascade-lab]');
    const groupSel = document.querySelector('[data-cascade-group]');
    const tableSel = document.querySelector('[data-cascade-table]');
    const itemSel = document.querySelector('[data-cascade-item]');
    if (!labSel) return;

    const fill = (select, options, placeholder, selected) => {
        if (!select) return;
        select.innerHTML = '';
        const opt0 = document.createElement('option');
        opt0.value = '';
        opt0.textContent = placeholder;
        select.appendChild(opt0);
        options.forEach((o) => {
            const opt = document.createElement('option');
            opt.value = o.id;
            opt.textContent = o.nama;
            if (String(selected) === String(o.id)) opt.selected = true;
            select.appendChild(opt);
        });
    };

    const refreshGroups = (preserve) => {
        const lab = data.labs[labSel.value];
        const groups = lab ? Object.entries(lab.groups || {}).map(([id, g]) => ({ id, nama: g.nama })) : [];
        fill(groupSel, groups, '— Pilih Kelompok —', preserve ? groupSel?.dataset.selected : '');
    };

    const refreshTables = (preserve) => {
        const lab = data.labs[labSel.value];
        let tables = [];
        if (lab) {
            const gid = groupSel?.value;
            if (gid && lab.groups[gid]) {
                tables = (lab.groups[gid].tables || []).map((t) => ({ id: t.id, nama: t.nama }));
            } else {
                // Tanpa kelompok terpilih: kumpulkan semua meja lab.
                Object.values(lab.groups || {}).forEach((g) =>
                    (g.tables || []).forEach((t) => tables.push({ id: t.id, nama: `${g.nama} • ${t.nama}` }))
                );
            }
        }
        fill(tableSel, tables, '— Pilih Meja —', preserve ? tableSel?.dataset.selected : '');
    };

    const refreshItems = (preserve) => {
        if (!itemSel) return;
        const items = (data.items || []).filter((i) => String(i.lab_id) === String(labSel.value));
        fill(itemSel, items, '— Pilih Barang —', preserve ? itemSel?.dataset.selected : '');
    };

    labSel.addEventListener('change', () => {
        refreshGroups(false);
        refreshTables(false);
        refreshItems(false);
    });
    groupSel?.addEventListener('change', () => refreshTables(false));

    // Inisialisasi (mempertahankan nilai lama saat edit).
    if (labSel.value) {
        refreshGroups(true);
        refreshTables(true);
        refreshItems(true);
    }
}
