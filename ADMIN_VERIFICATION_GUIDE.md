# Admin Verification System Guide

## Overview
The AgriConnect platform now requires **admin verification** for all Farmer and Buyer accounts before they can log in. This ensures quality control and prevents unauthorized access.

## How It Works

### 1. User Registration
When a Farmer or Buyer registers:
- Account is created with `kyc_status = 'pending'`
- User receives a success message: *"Your account is pending admin verification. You will be able to login once approved."*
- User is redirected to the login page

### 2. Login Attempt (Pending Status)
If a Farmer or Buyer tries to login with a pending account:
- Login credentials are validated
- System checks `kyc_status`
- User is logged out immediately
- Error message displayed: *"Your account is pending verification by an administrator. Please wait for approval."*

### 3. Admin Verification Process
Admins can verify users through the Admin Dashboard:

**Steps:**
1. Login as Admin
2. Navigate to **Users Management** section
3. View all users with their KYC status
4. Filter by:
   - Role (Farmer, Buyer, Admin)
   - KYC Status (Pending, Verified, Rejected)
5. Update user's KYC status:
   - **Verified**: User can now login
   - **Rejected**: User cannot login (with rejection message)
   - **Pending**: User must wait for approval

### 4. Login After Verification
Once verified:
- Farmer/Buyer can login successfully
- Redirected to their respective dashboard
- Full access to platform features

## KYC Status Types

| Status | Description | Can Login? |
|--------|-------------|------------|
| `pending` | Awaiting admin review | ❌ No |
| `verified` | Approved by admin | ✅ Yes |
| `rejected` | Rejected by admin | ❌ No |

## User Roles & Verification

| Role | Requires Verification? | Notes |
|------|----------------------|-------|
| **Farmer** | ✅ Yes | Must be verified before login |
| **Buyer** | ✅ Yes | Must be verified before login |
| **Admin** | ❌ No | Can login immediately |

## Error Messages

### Pending Account
```
Your account is pending verification by an administrator. Please wait for approval.
```

### Rejected Account
```
Your account has been rejected. Please contact the administrator for more information.
```

## Admin Dashboard Features

### User Management
- View all registered users
- Filter by role and KYC status
- Quick KYC status update dropdown
- Bulk actions for user management

### Verification Workflow
1. Admin receives new user registration
2. Admin reviews user information:
   - Personal details
   - Farm/Business information
   - Contact information
3. Admin decides: Verify or Reject
4. User receives status update
5. User can login (if verified)

## Database Structure

### Users Table
```sql
kyc_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending'
```

### Default Values
- New Farmer/Buyer: `kyc_status = 'pending'`
- New Admin: `kyc_status = 'pending'` (but can login anyway)

## Testing the System

### Test Scenario 1: Pending User
1. Register as Farmer/Buyer
2. Try to login immediately
3. Expected: Login blocked with pending message

### Test Scenario 2: Verified User
1. Admin verifies the user
2. User tries to login
3. Expected: Login successful, redirected to dashboard

### Test Scenario 3: Rejected User
1. Admin rejects the user
2. User tries to login
3. Expected: Login blocked with rejection message

### Test Scenario 4: Admin User
1. Register/Create admin account
2. Login immediately
3. Expected: Login successful regardless of KYC status

## Security Features

✅ **Automatic Logout**: Unverified users are logged out immediately after credential check  
✅ **Session Regeneration**: Security tokens refreshed on each login attempt  
✅ **Input Preservation**: Email is preserved on failed login for better UX  
✅ **Role-Based Access**: Different verification rules for different roles  
✅ **Admin Bypass**: Admins can always access the system  

## Future Enhancements

- Email notifications when account is verified/rejected
- Automated verification for trusted domains
- Verification reason/notes from admin
- User appeal process for rejected accounts
- Verification expiry and renewal system

## Support

For issues or questions about the verification system:
1. Check this guide first
2. Review user's KYC status in admin panel
3. Verify database `kyc_status` column
4. Check Laravel logs for authentication errors

---

**Last Updated**: December 10, 2025  
**Version**: 1.0
