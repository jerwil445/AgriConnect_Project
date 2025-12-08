# 🚀 Quick Test Commands

## Step-by-Step Testing Commands

### 1️⃣ Create Test Data
```bash
cd c:\xampp\htdocs\AgriConnect_Project
php artisan db:seed --class=FarmerModuleTestSeeder
```

### 2️⃣ Start Development Server
```bash
php artisan serve
```

### 3️⃣ Access URLs (Open in Browser)

**Login First:**
```
http://localhost:8000/login
```
Use your farmer account credentials

**Then Test These Pages:**

✅ **Earnings Dashboard**
```
http://localhost:8000/farmer/earnings
```

✅ **Reviews & Ratings**
```
http://localhost:8000/farmer/reviews
```

✅ **Enhanced Profile Form**
```
http://localhost:8000/farmer/profile/edit
```

✅ **Activity Logs**
```
http://localhost:8000/farmer/activities
```

✅ **Inventory Logs**
```
http://localhost:8000/farmer/inventory/logs
```

✅ **Analytics (Already Working)**
```
http://localhost:8000/farmer/analytics
```

---

## 🔧 Troubleshooting Commands

**Clear Cache:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

**Check Logs:**
```bash
tail -f storage/logs/laravel.log
```

**Database Check:**
```bash
php artisan tinker
```
Then:
```php
// Check farmer exists
\App\Models\Farmer::count()

// Check earnings
\App\Models\FarmerEarning::count()

// Check reviews
\App\Models\FarmerReview::count()

// Check activity logs
\App\Models\FarmerActivityLog::count()
```

---

## 📊 Quick Data Verification

**In Tinker (php artisan tinker):**

```php
// Get first farmer's stats
$farmer = \App\Models\Farmer::first();
echo "Average Rating: " . $farmer->average_rating . "\n";
echo "Total Reviews: " . $farmer->total_reviews . "\n";
echo "Completed Orders: " . $farmer->completed_orders . "\n";

// Check earnings
$earnings = \App\Models\FarmerEarning::where('farmer_id', $farmer->id)->get();
echo "Total Earnings Records: " . $earnings->count() . "\n";
echo "Total Amount: ₱" . $earnings->sum('net_amount') . "\n";

// Check reviews
$reviews = \App\Models\FarmerReview::where('farmer_id', $farmer->id)->get();
echo "Total Reviews: " . $reviews->count() . "\n";
echo "Average Rating: " . $reviews->avg('overall_rating') . "\n";
```

---

## ✅ Expected Results

After seeding, you should have:
- ✅ 10 Transactions
- ✅ ~7 Earnings Records (from delivered orders)
- ✅ 8 Customer Reviews
- ✅ 7 Activity Logs
- ✅ 5 Inventory Logs
- ✅ Updated Farmer Stats

---

## 🎯 What to Look For

### Earnings Page
- 4 colored summary cards
- Line chart with monthly data
- Table with earnings history
- Correct totals and calculations

### Reviews Page
- Overall rating number
- Star visualization
- Rating distribution bars
- Review cards with comments
- Reply functionality

### Profile Edit
- 5 tabs that switch smoothly
- All form fields editable
- Validation on required fields
- Save button works

### Activity Logs
- List of recent actions
- IP addresses captured
- Device types shown

### Inventory Logs
- Stock movements listed
- Quantity changes tracked
- Reasons displayed

---

## 🐛 If Something Doesn't Work

1. **Run migrations:**
   ```bash
   php artisan migrate
   ```

2. **Re-seed data:**
   ```bash
   php artisan db:seed --class=FarmerModuleTestSeeder
   ```

3. **Check for errors:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

4. **Clear everything:**
   ```bash
   php artisan optimize:clear
   ```

---

## 📸 Screenshot Checklist

Take screenshots of:
- [ ] Earnings dashboard (full page)
- [ ] Monthly earnings chart
- [ ] Reviews page with ratings
- [ ] Profile edit form (all tabs)
- [ ] Activity logs table
- [ ] Inventory logs table
- [ ] Any errors encountered

---

## ✨ Test Completion Checklist

- [ ] Test data created successfully
- [ ] All pages load without errors
- [ ] Charts render correctly
- [ ] Forms submit and save data
- [ ] Validation works properly
- [ ] Activity logging captures actions
- [ ] No console errors
- [ ] Mobile responsive (test on phone)
- [ ] All calculations accurate
- [ ] Navigation works smoothly

---

**Ready to Test!** 🎉

Start with command #1, then proceed through the testing guide!
