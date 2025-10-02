<div>
    <label class="form-label">Fuel Type</label>
    <select name="fuel_id" required class="input-field">
        <option value="">-- Select Fuel --</option>
        @foreach ($fuels as $fuel)
            <option value="{{ $fuel->id }}" @selected(old('fuel_id', $purchase->fuel_id ?? '') == $fuel->id)>
                {{ $fuel->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="form-label">Quantity (Litres)</label>
        <input type="number" step="0.01" name="quantity" class="input-field"
               value="{{ old('quantity', $purchase->quantity ?? '') }}" required>
    </div>

    <div>
        <label class="form-label">Unit Price</label>
        <input type="number" step="0.01" name="unit_price" class="input-field"
               value="{{ old('unit_price', $purchase->unit_price ?? '') }}" required>
    </div>

    <div>
        <label class="form-label">Purchase Date</label>
        <input type="date" name="purchase_date" class="input-field"
               value="{{ old('purchase_date', $purchase->purchase_date ?? now()->toDateString()) }}" required>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="form-label">Supplier Name</label>
        <input type="text" name="supplier_name" class="input-field"
               value="{{ old('supplier_name', $purchase->supplier_name ?? '') }}">
    </div>

    <div>
        <label class="form-label">Reference</label>
        <input type="text" name="reference" class="input-field"
               value="{{ old('reference', $purchase->reference ?? '') }}">
    </div>
</div>

<div>
    <label class="form-label">Notes</label>
    <textarea name="notes" rows="3" class="input-field">{{ old('notes', $purchase->notes ?? '') }}</textarea>
</div>
