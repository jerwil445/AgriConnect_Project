# Admin Dashboard Enhancements: Advanced Analytics

This plan outlines the addition of several high-impact charts and analytics to the AgriConnect Admin Dashboard to provide deeper insights into the ecosystem.

## User Review Required

> [!IMPORTANT]
> The data for "Supply vs Demand" requires fuzzy matching or categorized product names. I will implement a basic version grouped by the `product_name` field.

> [!TIP]
> I will use a premium color palette (Indigo, Emerald, Rose, Amber) to match the existing glassmorphic design and ensure visual excellence.

## Proposed Changes

### 1. Data Layer: AdminController
I will update the `dashboard` method in `AdminController` to fetch additional data points:
- **User Growth**: Signups per day for the last 30 days.
- **KYC Status**: Breakdown of `kyc_status` for all users.
- **Top Farmers**: Total `total_amount` sum for each farmer from `transactions`.
- **Supply vs Demand**: Count of `products` vs `demands` grouped by name.

#### [MODIFY] [AdminController.php](file:///c:/Users/Jerwil/AgriConnect_Project/app/Http/Controllers/AdminController.php)

### 2. UI Layer: Dashboard View
I will add new chart containers and update the JavaScript initialization to include:
- **User Acquisition Chart**: Line chart with dual datasets (Farmers, Buyers).
- **KYC Status Funnel**: Doughnut chart.
- **Top Performing Farmers**: Horizontal Bar chart.
- **Supply-Demand Gap**: Grouped Bar chart.

#### [MODIFY] [dashboard.blade.php](file:///c:/Users/Jerwil/AgriConnect_Project/resources/views/admin/dashboard.blade.php)

## Open Questions

1. **Timeframe**: Should the user growth trends be for the last 7 days, 30 days, or 12 months? (I'll default to 30 days for better trend visualization).
2. **Additional Metrics**: Are there any specific KPIs (Key Performance Indicators) you'd like to track (e.g., average transaction value)?

## Verification Plan

### Automated Tests
- I will check the console for any Chart.js initialization errors.
- I will verify that the Blade variables are correctly passed and rendered.

### Manual Verification
- Verify the responsiveness of the new chart layout on different screen sizes.
- Ensure the tooltips and labels are clear and professional.
