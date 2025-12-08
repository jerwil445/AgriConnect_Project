# 🧪 Farmer Module - Testing Guide

## Overview
This guide will walk you through testing all the new features implemented in the Farmer Module.

---

## 🚀 STEP 1: Prepare Test Data

### Run the Test Data Seeder

```bash
# Navigate to your project directory
cd c:\xampp\htdocs\AgriConnect_Project

# Run the seeder
php artisan db:seed --class=FarmerModuleTestSeeder
```

**What this creates:**
- ✅ 10 sample transactions
- ✅ Earnings records with different statuses (paid, unpaid, pending)
- ✅ 8 customer reviews with ratings
- ✅ Activity logs
- ✅ Inventory movement logs
- ✅ Updates farmer statistics

**Expected Output:**
```
🌱 Seeding Farmer Module Test Data...
✓ Using Farmer ID: 1
✓ User ID: 1

📦 Creating Sample Transactions...
   ✓ Transaction #1 created
   ... (10 total)

💰 Creating Earnings Records...
   ✓ Earning record created for Transaction #1 - ₱950.00
   ... (varies based on delivered orders)

⭐ Creating Customer Reviews...
   ✓ Review created with 4.5 stars
   ... (8 reviews)

📝 Creating Activity Logs...
   ✓ Created new product listing
   ... (7 activities)

📊 Creating Inventory Logs...
   ✓ sale: -10 trays - Product sold to buyer
   ... (5 logs)

✅ SEEDING COMPLETE!
```

---

## 🧪 STEP 2: Test Earnings Dashboard

### Access the Page
1. Start your development server:
   ```bash
   php artisan serve
   ```

2. Login as a farmer
3. Navigate to: `http://localhost:8000/farmer/earnings`
   - OR click "Earnings" in the sidebar

### What to Test

#### ✅ **Summary Cards (Top Section)**
Test all 4 cards display correctly:

**Card 1 - Total Earnings (Blue)**
- [ ] Shows total of all earnings
- [ ] Displays "All time earnings" text
- [ ] Has money icon
- [ ] Blue gradient background

**Card 2 - Paid Out (Green)**
- [ ] Shows sum of paid earnings
- [ ] Displays "Received payments" text
- [ ] Has checkmark icon
- [ ] Green gradient background

**Card 3 - Pending Payout (Amber)**
- [ ] Shows sum of unpaid earnings
- [ ] Displays "Awaiting payment" text
- [ ] Has clock icon
- [ ] Amber/orange gradient background

**Card 4 - Processing (Purple)**
- [ ] Shows pending status earnings
- [ ] Displays "Being processed" text
- [ ] Has refresh icon
- [ ] Purple gradient background

#### ✅ **Monthly Earnings Chart**
- [ ] Chart displays if there's data
- [ ] Shows last 12 months
- [ ] Two lines: Net Earnings (green) and Platform Fees (red)
- [ ] X-axis shows month names
- [ ] Y-axis shows amounts in PHP
- [ ] Hover shows exact values
- [ ] Chart is responsive

#### ✅ **Earnings History Table**
- [ ] All columns display:
  - Date
  - Transaction ID
  - Gross Amount
  - Platform Fee (with percentage)
  - Net Amount (green text)
  - Status badge
  - Payout Date
- [ ] Status badges color-coded:
  - Paid = Green
  - Unpaid = Amber
  - Processing = Purple
  - On Hold = Red
- [ ] Pagination works
- [ ] Shows 20 records per page

#### ✅ **Empty State**
To test empty state:
1. Delete all earnings temporarily
2. Refresh page
3. Should show:
   - Money icon
   - "No Earnings Yet" message
   - Helpful text

**Screenshot Checklist:**
- [ ] Take screenshot of full earnings page
- [ ] Note any display issues
- [ ] Check mobile responsiveness

---

## ⭐ STEP 3: Test Reviews & Ratings

### Access the Page
Navigate to: `http://localhost:8000/farmer/reviews`
- OR click "Reviews" in sidebar

### What to Test

#### ✅ **Overall Rating Section**
- [ ] Large rating number displays (e.g., 4.5)
- [ ] Star visualization shows correctly (yellow stars)
- [ ] Shows correct number of total reviews
- [ ] Centered layout

#### ✅ **Rating Distribution**
- [ ] 5 bars for stars 5, 4, 3, 2, 1
- [ ] Bars show percentage width correctly
- [ ] Count shows on right side
- [ ] Bars are yellow colored

#### ✅ **Category Ratings Cards**
Test all 4 cards:
- [ ] Product Quality rating
- [ ] Delivery rating
- [ ] Communication rating
- [ ] Packaging rating
- [ ] All show decimal number (e.g., 4.5)

#### ✅ **Review Cards**
For each review, verify:
- [ ] Buyer avatar with first initial
- [ ] Buyer name displays
- [ ] Star rating shows
- [ ] "Verified Purchase" badge (if applicable)
- [ ] Comment text displays
- [ ] Timestamp shows (e.g., "2 days ago")
- [ ] Rating details show (Quality, Delivery, Communication)

#### ✅ **Farmer Reply Section**
For reviews without reply:
- [ ] Reply input box displays
- [ ] "Reply" button present
- [ ] Can type in input

For reviews with reply:
- [ ] Reply box with green icon
- [ ] "Your Reply" label
- [ ] Reply text displays
- [ ] Reply timestamp shows

#### ✅ **Test Adding a Reply**
1. Find a review without reply
2. Type: "Thank you for your feedback!"
3. Click "Reply" button
4. Verify:
   - [ ] Page refreshes
   - [ ] Success message shows
   - [ ] Reply now appears
   - [ ] Reply form hidden

#### ✅ **Pagination**
- [ ] Shows if more than 15 reviews
- [ ] Previous/Next buttons work
- [ ] Page numbers clickable

---

## 📝 STEP 4: Test Enhanced Profile Form

### Access the Page
Navigate to: `http://localhost:8000/farmer/profile/edit`

### What to Test

#### ✅ **Tab Navigation**
Test all 5 tabs:
- [ ] Basic Information tab (active by default)
- [ ] Farm Details tab
- [ ] Business & Certification tab
- [ ] Payment Information tab
- [ ] Operational tab
- [ ] Clicking tabs switches content
- [ ] Active tab highlighted in green
- [ ] Inactive tabs gray

#### ✅ **Tab 1: Basic Information**
Fields to test:
- [ ] First Name (required)
- [ ] Last Name (required)
- [ ] Email (required, unique)
- [ ] Primary Phone
- [ ] Secondary Phone
- [ ] WhatsApp Number
- [ ] Address (textarea)
- [ ] Profile Picture upload

**Test Validation:**
1. Clear First Name
2. Click Save
3. Verify:
   - [ ] Error message shows
   - [ ] Form doesn't submit

#### ✅ **Tab 2: Farm Details**
Fields to test:
- [ ] Farm Name (required)
- [ ] Farm Size (number input)
- [ ] Unit dropdown (hectares, acres, sq_meters)
- [ ] Total Chickens
- [ ] Farming Method
- [ ] Years of Experience (0-100)
- [ ] Latitude (decimal)
- [ ] Longitude (decimal)
- [ ] Farm Address (textarea)

**Test Functionality:**
1. Enter farm size: 5.5
2. Select unit: hectares
3. Enter coordinates: 7.1907, 125.4553 (Davao)
4. Click Save
5. Verify:
   - [ ] Success message
   - [ ] Values saved
   - [ ] Page redirects

#### ✅ **Tab 3: Business & Certification**
Fields to test:
- [ ] Business Type dropdown
- [ ] Registration Number
- [ ] Tax ID (TIN)
- [ ] Certification
- [ ] Organic Certification
- [ ] Certification Expiry (date picker)
- [ ] Food Safety Certification
- [ ] GMP Certified checkbox
- [ ] Halal Certified checkbox

**Test Checkboxes:**
1. Check GMP Certified
2. Check Halal Certified
3. Save
4. Reload page
5. Verify:
   - [ ] Checkboxes still checked

#### ✅ **Tab 4: Payment Information**
Fields to test:
- [ ] Bank Name
- [ ] Account Name
- [ ] Account Number
- [ ] Mobile Wallet Provider
- [ ] Mobile Wallet Number

**Security Test:**
1. Enter bank details
2. Save
3. Reload page
4. Verify:
   - [ ] Details saved correctly
   - [ ] No errors in console

#### ✅ **Tab 5: Operational**
Fields to test:
- [ ] Accepting Orders checkbox
- [ ] Operation Start Time (time picker)
- [ ] Operation End Time (time picker)
- [ ] Operation Days (7 checkboxes for each day)

**Test Operation Days:**
1. Check: Monday, Tuesday, Wednesday, Thursday, Friday
2. Uncheck: Saturday, Sunday
3. Save
4. Reload
5. Verify:
   - [ ] Weekdays checked
   - [ ] Weekend unchecked

#### ✅ **Form Submission**
Complete form test:
1. Fill all tabs with data
2. Click "Save Changes"
3. Verify:
   - [ ] Success message: "Profile updated successfully"
   - [ ] Redirects to profile view page
   - [ ] All data saved correctly

---

## 🔍 STEP 5: Verify Activity Logging

### Access Activity Logs
Navigate to: `http://localhost:8000/farmer/activities`

### What to Test

#### ✅ **Log Entries Display**
- [ ] Table shows activity logs
- [ ] Columns visible:
  - Action (created, updated, deleted, etc.)
  - Entity Type
  - Description
  - Date/Time
  - IP Address
  - Device Type
- [ ] Most recent at top
- [ ] 50 entries per page

#### ✅ **Test Activity Creation**
1. Go to profile edit
2. Change farm name
3. Click Save
4. Go back to activity logs
5. Verify:
   - [ ] New "Updated farmer profile" log appears
   - [ ] Shows your IP address
   - [ ] Shows device type
   - [ ] Timestamp is current

#### ✅ **Test Review Reply Logging**
1. Go to reviews page
2. Reply to a review
3. Go to activity logs
4. Verify:
   - [ ] "Replied to customer review" log exists
   - [ ] Entity Type: Review
   - [ ] Severity: info

---

## 📦 STEP 6: Test Inventory Logs

### Access Inventory Logs
Navigate to: `http://localhost:8000/farmer/inventory/logs`

### What to Test

#### ✅ **Log Display**
- [ ] Shows inventory movements
- [ ] Displays:
  - Type (sale, restock, damage, etc.)
  - Product name
  - Quantity before
  - Quantity change (+/-)
  - Quantity after
  - Reason
  - Date
  - Performed by (username)
- [ ] Pagination works

#### ✅ **Data Accuracy**
- [ ] Negative changes for sales
- [ ] Positive changes for restocks
- [ ] Quantity calculations correct
- [ ] Product links work

---

## 🎨 STEP 7: Visual & UI Testing

### General UI Checks

#### ✅ **Sidebar Navigation**
- [ ] "Earnings" menu item visible
- [ ] "Reviews" menu item visible
- [ ] Icons display correctly
- [ ] Active state highlights current page
- [ ] Hover effects work

#### ✅ **Responsive Design**
Test on different screen sizes:

**Desktop (1920x1080):**
- [ ] All cards in row
- [ ] Tables readable
- [ ] Forms properly laid out

**Tablet (768px):**
- [ ] Cards stack nicely
- [ ] Tables scrollable
- [ ] Forms still usable

**Mobile (375px):**
- [ ] One column layout
- [ ] Buttons accessible
- [ ] Text readable

#### ✅ **Color Scheme**
- [ ] Green theme consistent
- [ ] Status badges appropriate colors
- [ ] Gradient cards attractive
- [ ] Text contrasts readable

---

## 🐛 STEP 8: Error Testing

### Test Error Scenarios

#### ✅ **Form Validation**
1. Submit empty required field
   - [ ] Error message shows
   - [ ] Form doesn't submit

2. Enter invalid email
   - [ ] Validation error

3. Upload oversized image (>2MB)
   - [ ] Size error

#### ✅ **Database Errors**
1. Try to reply to non-existent review
   - [ ] 404 error handled

2. Access logs for wrong farmer
   - [ ] Authorization check works

---

## 📊 STEP 9: Performance Testing

### Load Time Tests

#### ✅ **Page Load Speed**
Measure load times:
- [ ] Earnings page: < 2 seconds
- [ ] Reviews page: < 2 seconds
- [ ] Profile form: < 1.5 seconds

#### ✅ **Chart Rendering**
- [ ] Chart.js loads smoothly
- [ ] No lag when switching tabs
- [ ] Responsive when resizing

---

## ✅ TESTING CHECKLIST

Use this comprehensive checklist:

### Earnings Dashboard
- [ ] All 4 summary cards display correctly
- [ ] Chart renders with proper data
- [ ] Table shows earnings history
- [ ] Pagination works
- [ ] Empty state displays when no data
- [ ] Mobile responsive

### Reviews & Ratings
- [ ] Overall rating displays
- [ ] Rating distribution shows
- [ ] Category ratings present
- [ ] Review cards formatted correctly
- [ ] Can reply to reviews
- [ ] Reply form submits successfully
- [ ] Pagination works

### Enhanced Profile Form
- [ ] All 5 tabs functional
- [ ] Tab switching works
- [ ] All fields editable
- [ ] Validation works
- [ ] Form submits successfully
- [ ] Success message shows
- [ ] Data persists after save

### Activity Logging
- [ ] Logs display correctly
- [ ] New activities logged
- [ ] Profile updates logged
- [ ] Review replies logged
- [ ] IP and device captured

### Inventory Logs
- [ ] Logs display
- [ ] Shows quantity changes
- [ ] Product links work

### General
- [ ] Sidebar updated
- [ ] Navigation works
- [ ] No console errors
- [ ] Mobile responsive
- [ ] Fast loading times

---

## 📝 STEP 10: Document Issues

### Issue Template

For any issues found:

```markdown
**Page:** Earnings Dashboard
**Issue:** Chart not rendering
**Steps to Reproduce:**
1. Navigate to /farmer/earnings
2. Wait for page load
3. Observe chart area

**Expected:** Chart with earnings data
**Actual:** Blank canvas element

**Browser:** Chrome 120
**Screenshot:** [attach]
```

### Common Issues & Solutions

**Issue: "Earnings page blank"**
- Solution: Run seeder to create test data

**Issue: "Chart not showing"**
- Solution: Check Chart.js loaded in browser console

**Issue: "Form not saving"**
- Solution: Check validation errors, check database connection

**Issue: "Reviews not displaying"**
- Solution: Ensure reviews exist in database

---

## 🎯 Success Criteria

The testing is successful if:

✅ All summary cards display correct calculations
✅ Charts render properly with real data
✅ Tables show data with proper formatting
✅ Forms submit and save correctly
✅ Validation prevents invalid data
✅ Activity logging captures all actions
✅ No JavaScript console errors
✅ Mobile responsive on all pages
✅ Page load times under 2 seconds
✅ Navigation works seamlessly

---

## 🚀 Next Steps After Testing

1. **Document all findings**
2. **Fix any bugs discovered**
3. **Optimize slow queries**
4. **Enhance UI based on feedback**
5. **Proceed to Product Features implementation**

---

## 📞 Testing Support

If you encounter issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check browser console for JavaScript errors
3. Verify database connection
4. Ensure all migrations ran successfully

---

**Happy Testing!** 🎉

All features should work smoothly. Report any issues you find!
