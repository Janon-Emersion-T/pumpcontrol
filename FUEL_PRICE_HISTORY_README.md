# Fuel Price History System

## Overview
The Fuel Price History system provides comprehensive tracking of all fuel price changes with proper historical data management. This ensures that:
- All price changes are tracked with dates and reasons
- Transactions use the correct price based on when they occurred
- Historical transaction data is never retroactively affected by price changes
- Complete audit trail of all price modifications

## Features Implemented

### 1. Database Schema
- **Table**: `fuel_price_history`
- **Columns**:
  - `fuel_id` - Foreign key to fuels table
  - `price_per_litre` - The price at this point in history
  - `effective_date` - Date from which this price applies
  - `user_id` - User who made the change
  - `notes` - Reason/explanation for the change
  - `is_active` - Flag indicating current active price
  - Timestamps for record keeping

### 2. Navigation
- Added "Price History" menu item in the sidebar under "Fuel Management"
- Icon: currency-dollar
- Route: `/dashboard/fuel-price-history`

### 3. Core Functionality

#### Price Change Workflow
1. Navigate to **Fuel Management > Price History**
2. Click **"Change Fuel Price"**
3. Select the fuel type
4. Enter the new price
5. Set the effective date
6. Add notes explaining the reason for the change
7. Submit

**What Happens:**
- All previous price records for that fuel are marked as `is_active = false`
- New price record is created with `is_active = true`
- The `fuels` table is updated with the current price
- Full audit trail is maintained

#### Price History Display
- **Index Page**: Shows all price changes across all fuels with filters
- **Show Page**: Displays details of a specific price change
- **Fuel Details Page**: Now includes a "Price History" section showing recent price changes

### 4. Important Behavioral Rules

#### How Prices Work
- **Current Transactions**: Use the active price (where `is_active = true`)
- **Historical Transactions**: Already have their price stored in the `meter_readings`, `pump_records`, and `fuel_purchases` tables
- **Future Transactions**: Will use whichever price is active on the transaction date

#### Price on Transaction Date
The system can retrieve the correct price for any date using:
```php
FuelPriceHistory::getPriceOnDate($fuelId, $date);
```

This finds the price that was effective on that specific date.

### 5. Data Integrity

#### Seeded Data
All existing fuels have been seeded with initial price history records:
- Effective date set to 30 days ago
- Marked as active
- Notes: "Initial price record - migrated from existing fuel data"

#### Protection Mechanisms
1. **Cannot delete active prices** - System prevents deletion of the currently active price
2. **Price changes are logged** - Every change includes who made it and when
3. **Historical records preserved** - Old price records remain in the database
4. **Transaction integrity** - Existing transactions keep their original prices

### 6. Views Created

1. **`fuel-price-history/index.blade.php`**
   - Lists all price changes
   - Shows fuel type, price, effective date, user, status
   - Color-coded (active prices highlighted in green)
   - Delete option for historical records

2. **`fuel-price-history/create.blade.php`**
   - Form to change fuel price
   - Information panel explaining how price changes work
   - Validation and error handling
   - Current prices shown in dropdown

3. **`fuel-price-history/show.blade.php`**
   - Detailed view of a specific price change
   - Shows all metadata and notes
   - Link to fuel details
   - Delete option (if not active)

4. **Updated `fuel/edit.blade.php`**
   - Added warning banner about direct price changes
   - Directs users to the proper price history system
   - Explains why using price history is important

5. **Updated `fuel/show.blade.php`**
   - Added "Price History" section
   - Shows latest 10 price changes
   - Quick "Change Price" button
   - Link to view all price history

### 7. Routes
All routes are under the `dashboard/fuel-price-history` prefix:
- `GET /dashboard/fuel-price-history` - List all price history
- `GET /dashboard/fuel-price-history/create` - Show price change form
- `POST /dashboard/fuel-price-history` - Save new price
- `GET /dashboard/fuel-price-history/{id}` - Show specific price record
- `GET /dashboard/fuel-price-history/{id}/edit` - Edit price record metadata
- `PUT /dashboard/fuel-price-history/{id}` - Update price record
- `DELETE /dashboard/fuel-price-history/{id}` - Delete historical price record

### 8. Models & Relationships

#### FuelPriceHistory Model
```php
// Relationships
fuel() - BelongsTo Fuel
user() - BelongsTo User (who made the change)

// Scopes
active() - Get only active prices
forFuel($fuelId) - Get prices for specific fuel
effectiveOn($date) - Get prices effective on a date

// Static Methods
getCurrentPrice($fuelId) - Get current active price
getPriceOnDate($fuelId, $date) - Get price for specific date
```

#### Updated Fuel Model
```php
// New Relationships
priceHistory() - HasMany FuelPriceHistory
currentPriceHistory() - HasOne active FuelPriceHistory
```

## Usage Examples

### Example 1: Changing a Fuel Price
**Scenario**: Petrol price increases from Rs. 350.00 to Rs. 375.00 on October 20, 2025

**Steps**:
1. Go to **Fuel Management > Price History**
2. Click **"Change Fuel Price"**
3. Select: "Petrol (Current: Rs. 350.00)"
4. Enter: Rs. 375.00
5. Effective Date: 2025-10-20
6. Notes: "Government price increase"
7. Submit

**Result**:
- Old price (Rs. 350.00) marked as historical
- New price (Rs. 375.00) becomes active from Oct 20
- All transactions before Oct 20 use Rs. 350.00
- All transactions on/after Oct 20 use Rs. 375.00

### Example 2: Viewing Price History
**Scenario**: Check when Diesel price changed last

**Steps**:
1. Go to **Fuel Management > Fuel Types**
2. Click "View" on Diesel
3. Scroll to "Price History" section
4. See all price changes with dates and who changed them

### Example 3: Audit Trail
**Scenario**: Management wants to know all price changes in the last month

**Steps**:
1. Go to **Fuel Management > Price History**
2. View the list sorted by effective date
3. See all changes with:
   - Which fuel
   - What price
   - When it became effective
   - Who changed it
   - Why (notes)

## Technical Implementation Notes

### Transaction Safety
All price changes are wrapped in database transactions to ensure atomicity:
```php
DB::transaction(function () {
    // 1. Deactivate old prices
    // 2. Create new price record
    // 3. Update fuel table
});
```

### Query Optimization
- Indexed on `[fuel_id, effective_date]` for fast lookups
- Indexed on `[fuel_id, is_active]` for current price queries
- Eager loading used to prevent N+1 queries

### Validation
- Price must be numeric and >= 0
- Effective date is required
- Fuel must exist
- Notes are optional but recommended

## Future Enhancements (Optional)

1. **Bulk Price Changes**: Update multiple fuels at once
2. **Scheduled Price Changes**: Set future effective dates
3. **Price Change Notifications**: Email alerts when prices change
4. **Price Analytics**: Charts showing price trends over time
5. **Import/Export**: Bulk import price changes from CSV
6. **Price Comparison**: Compare prices across different fuel types
7. **Approval Workflow**: Require manager approval for price changes

## Testing

To test the system:

1. **Create a price change**:
   ```
   Go to: /dashboard/fuel-price-history/create
   Change Petrol price from 350 to 360
   Set effective date to today
   Submit
   ```

2. **Verify the change**:
   ```
   Go to: /dashboard/fuel-price-history
   See the new price record as "Active"
   See the old price record as "Historical"
   ```

3. **Check fuel details**:
   ```
   Go to: /dashboard/fuel/1 (Petrol)
   Verify current price shows 360
   See price history section with both records
   ```

## Conclusion

The Fuel Price History system is now fully operational and provides:
- ✅ Complete price change tracking
- ✅ Historical data preservation
- ✅ Proper transaction pricing
- ✅ Full audit trail
- ✅ User-friendly interface
- ✅ Data integrity protection

All existing fuels have been seeded with initial price history. The system is ready for production use.
