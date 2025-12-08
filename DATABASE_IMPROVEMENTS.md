# Database Structure Improvements - Farmer Module

## Overview
This document outlines the comprehensive database enhancements made to the Farmer Module for improved data management, scalability, and business intelligence.

---

## 📊 Enhanced Tables

### 1. **Farmers Table** (`2025_12_09_000001_enhance_farmers_table.php`)

#### **Business Information**
- `business_registration_number` - Official business registration
- `business_type` - Individual, Partnership, or Corporation
- `tax_id_number` - Tax identification for compliance

#### **Contact & Communication**
- `secondary_phone` - Backup contact number
- `whatsapp_number` - WhatsApp for quick communication

#### **Payment Information**
- `bank_name`, `bank_account_number`, `bank_account_name` - Banking details for payouts
- `mobile_wallet_provider`, `mobile_wallet_number` - GCash, PayMaya support

#### **Certifications & Compliance**
- `organic_certification` - Organic farming certification
- `certification_expiry_date` - Track certification validity
- `food_safety_certification` - Food safety standards
- `gmp_certified` - Good Manufacturing Practice
- `halal_certified` - Halal certification status

#### **Farm Details**
- `farm_size_unit` - Hectares, acres, or sq meters
- `latitude`, `longitude` - GPS coordinates for location mapping
- `total_chickens` - Current livestock count
- `farming_method` - Free-range, Cage-free, Organic, etc.

#### **Performance Metrics**
- `average_rating` - Aggregate buyer ratings
- `total_reviews` - Number of reviews received
- `completed_orders` - Track successful deliveries
- `success_rate` - Performance percentage

#### **Status & Verification**
- `verification_status` - Unverified, Pending, Verified, Rejected
- `verified_at`, `verified_by` - Audit trail for verification
- `verification_notes` - Admin notes on verification

#### **Operational**
- `is_active` - Account active status
- `accepting_orders` - Currently accepting new orders
- `operation_start_time`, `operation_end_time` - Business hours
- `operation_days` - Days of operation (JSON array)

---

### 2. **Products Table** (`2025_12_09_000002_enhance_products_table.php`)

#### **Quality & Grading**
- `quality_grade` - AA, A, B, C grading
- `color` - White, Brown, Blue eggs
- `average_weight_grams` - Egg weight specification
- `is_organic`, `is_free_range` - Production method flags
- `hen_breed` - Breed of laying hens

#### **Storage & Freshness**
- `laying_date` - When eggs were laid
- `shelf_life_days` - Expected freshness period
- `expiry_date` - Product expiration
- `storage_condition` - Refrigerated or Room Temperature
- `storage_temperature` - Optimal temperature in Celsius

#### **Inventory Management**
- `initial_quantity` - Starting stock
- `sold_quantity` - Total units sold
- `reserved_quantity` - Reserved for pending orders
- `available_quantity` - Current available stock
- `minimum_order_quantity`, `maximum_order_quantity` - Order limits
- `reorder_level` - When to restock
- `low_stock_alert` - Alert flag

#### **Pricing**
- `original_price` - Regular price before discount
- `discount_percentage` - Active discount
- `is_negotiable` - Price negotiation allowed
- `minimum_acceptable_price` - Lowest acceptable offer

#### **Location & Delivery**
- `province`, `city`, `barangay`, `postal_code` - Detailed address
- `delivery_radius_km` - Maximum delivery distance
- `offers_delivery`, `offers_pickup` - Delivery options
- `delivery_fee` - Delivery charge

#### **Certifications & Compliance**
- `fda_approved` - FDA approval status
- `batch_number` - Production batch tracking
- `certification_documents` - JSON array of document paths

#### **Performance Metrics**
- `view_count` - Product page views
- `inquiry_count` - Number of inquiries
- `order_count` - Total orders
- `conversion_rate` - View to order conversion

#### **Product Visibility**
- `is_featured` - Featured product flag
- `is_promoted` - Promoted/sponsored product
- `featured_until` - Feature expiration
- `priority_order` - Display priority

#### **Timestamps**
- `last_restocked_at` - Last inventory update
- `last_sold_at` - Most recent sale
- `published_at` - When product went live

---

### 3. **Sizes Table** (`2025_12_09_000003_enhance_sizes_table.php`)

#### **Inventory Management**
- `initial_tray_count`, `sold_tray_count`, `reserved_tray_count`, `available_tray_count` - Stock tracking per size

#### **Size Details**
- `eggs_per_tray` - Standard is 30
- `weight_per_egg_grams` - Individual egg weight
- `total_weight_kg` - Total weight calculation

#### **Pricing & Discounts**
- `original_price_per_tray` - Base price
- `discount_percentage` - Size-specific discount
- `has_special_offer`, `special_offer_until` - Promotional pricing

#### **Availability**
- `availability_status` - Available, Low Stock, Out of Stock, Discontinued
- `low_stock_threshold` - Alert trigger level
- `allow_backorder` - Accept orders when out of stock

#### **Performance**
- `order_count` - Sales per size
- `popularity_score` - Popularity metric
- `last_sold_at` - Last sale timestamp

---

## 🆕 New Tables

### 4. **Farmer Reviews** (`farmer_reviews`)

**Purpose:** Track buyer feedback and farmer ratings

**Key Fields:**
- Rating breakdown (Overall, Quality, Delivery, Communication, Packaging)
- Review comments and farmer replies
- Verification status and moderation
- Helpful votes tracking
- Image attachments (JSON array)

**Business Value:**
- Build trust through transparent reviews
- Identify top-performing farmers
- Improve service quality
- Resolve disputes with evidence

---

### 5. **Inventory Logs** (`inventory_logs`)

**Purpose:** Complete audit trail of stock movements

**Log Types:**
- Initial Stock, Restock, Sale, Reservation
- Cancellation, Return, Damage, Expiry
- Adjustment, Transfer

**Key Fields:**
- Quantity before/after changes
- Reason and notes
- User who performed action
- Pricing at time of change

**Business Value:**
- Identify shrinkage and losses
- Track inventory accuracy
- Compliance and accountability
- Data for predictive restocking

---

### 6. **Product Analytics** (`product_analytics`)

**Purpose:** Daily performance metrics per product

**Metrics Tracked:**
- Views (total, unique, detail)
- Engagement (inquiries, messages, favorites, shares)
- Sales (orders, revenue, units sold, cancellations)
- Performance (conversion rate, avg order value, response time)
- Matches (generated, accepted, conversion rate)

**Business Value:**
- Identify best-selling products
- Optimize pricing strategies
- Improve marketing efforts
- Forecast demand

---

### 7. **Farmer Activity Logs** (`farmer_activity_logs`)

**Purpose:** Comprehensive audit trail of farmer actions

**Logged Activities:**
- CRUD operations on all entities
- Login/logout events
- Profile changes
- Order status updates
- Message interactions

**Key Fields:**
- Action type and entity
- Before/after values (JSON)
- IP address and user agent
- Severity level (Info, Warning, Error, Critical)

**Business Value:**
- Security monitoring
- Dispute resolution
- Compliance requirements
- User behavior analysis

---

### 8. **Farmer Earnings** (`farmer_earnings`)

**Purpose:** Financial tracking and payout management

**Financial Breakdown:**
- Gross amount (order total)
- Platform fee (commission)
- Payment gateway fee
- Delivery fee
- Tax amount
- Net amount (farmer receives)

**Payout Management:**
- Payout status tracking
- Payment method details
- Reference numbers
- Processing timestamps

**Business Value:**
- Transparent financial tracking
- Automated commission calculation
- Streamlined payout processing
- Financial reporting and analytics

---

## 🔍 Indexing Strategy

### Performance Optimizations

**Farmers Table:**
- `verification_status`, `is_active`, `average_rating`
- Composite: `[latitude, longitude]` for location searches

**Products Table:**
- `status`, `egg_type`, `quality_grade`, `is_organic`, `is_featured`
- Composite: `[farmer_id, status]`, `[province, city]`

**Sizes Table:**
- `availability_status`
- Composite: `[product_id, size_name]`, `[farmer_id, availability_status]`

**All New Tables:**
- Appropriate indexes on foreign keys and frequently queried fields
- Unique constraints where necessary (e.g., one review per transaction)

---

## 🗑️ Soft Deletes

Implemented on:
- Farmers
- Products
- Sizes
- Farmer Reviews

**Benefits:**
- Data recovery capability
- Historical data preservation
- Compliance with data retention policies
- Reversible delete operations

---

## 📝 Usage Instructions

### Running Migrations

```bash
# Run all new migrations
php artisan migrate

# Rollback if needed
php artisan migrate:rollback --step=8

# Fresh migration (WARNING: Deletes all data)
php artisan migrate:fresh
```

### After Migration

1. **Update Models** - Add new fields to fillable arrays
2. **Update Seeders** - Populate new fields with sample data
3. **Update Controllers** - Handle new fields in CRUD operations
4. **Update Views** - Display new information in UI
5. **Update Validation** - Add validation rules for new fields

---

## 🎯 Business Impact

### For Farmers:
- ✅ Better business management tools
- ✅ Transparent financial tracking
- ✅ Performance insights and analytics
- ✅ Multiple payment options
- ✅ Professional verification system

### For Buyers:
- ✅ Detailed product information
- ✅ Quality assurance through ratings
- ✅ Better delivery tracking
- ✅ Certified and verified farmers

### For Admin:
- ✅ Complete audit trails
- ✅ Financial oversight
- ✅ Performance monitoring
- ✅ Compliance management
- ✅ Data-driven decision making

---

## 🔐 Data Privacy & Security

- Sensitive financial data encrypted
- Personal information protected
- Audit logs for compliance
- GDPR-ready with soft deletes
- Secure payment information storage

---

## 📈 Scalability Considerations

- Proper indexing for fast queries
- Normalized structure for data integrity
- JSON fields for flexible metadata
- Partitioning-ready timestamp columns
- Efficient foreign key relationships

---

## 🚀 Next Steps

1. **Phase 1:** Run migrations in staging environment
2. **Phase 2:** Test data integrity
3. **Phase 3:** Update application code
4. **Phase 4:** Deploy to production
5. **Phase 5:** Monitor performance metrics

---

## 📞 Support

For questions or issues related to these database changes:
- Review migration files for field details
- Check model relationships in `app/Models`
- Consult API documentation for usage examples

---

**Last Updated:** December 9, 2025
**Version:** 2.0
**Status:** Ready for Production
