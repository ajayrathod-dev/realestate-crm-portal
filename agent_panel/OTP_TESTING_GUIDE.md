# Agent Panel OTP System - Testing Guide

## Database Setup

### Step 1: Create Tables

Run the SQL file to create the required tables:

1. Open phpMyAdmin
2. Select the `property` database
3. Go to "Import" tab
4. Upload `agent_tables.sql` from `c:\pk\htdocs\property\agent_panel\`
5. Click "Go" to execute

**Expected Result:**
- Two new tables created: `users_ap` and `otp_ap`

### Step 2: Verify Tables

Run these SQL queries to verify:

```sql
DESCRIBE users_ap;
DESCRIBE otp_ap;
```

**Expected Output:**
- `users_ap`: id, user_id, name, created_at, address, occupation
- `otp_ap`: id, uuid, otp, status, created_at, user_id

---

## Testing the OTP System

### Test 1: New User Registration

**Steps:**
1. Navigate to: `http://localhost/property/agent_panel/login.php`
2. Enter mobile number: `1234567890` (test number)
3. Click "Send OTP"

**Expected Result:**
- Redirected to `otp.php`
- New record in `users_ap` table
- New record in `otp_ap` table with OTP = `1234` (hardcoded for this number)
- UUID cookie set in browser

**Verify in Database:**
```sql
SELECT * FROM users_ap WHERE user_id='1234567890';
SELECT * FROM otp_ap WHERE user_id='1234567890' ORDER BY id DESC LIMIT 1;
```

---

### Test 2: OTP Verification

**Steps:**
1. On `otp.php` page
2. Enter OTP: `1234`
3. Click "Verify OTP"

**Expected Result:**
- Redirected to `dashboard.php`
- Dashboard displays successfully
- User name shows in header (will show mobile number initially as name is '-')

---

### Test 3: Real Mobile Number (Optional - Requires SMS API)

**Steps:**
1. Logout from agent panel
2. Navigate to `login.php`
3. Enter a real mobile number (e.g., `9876543210`)
4. Click "Send OTP"

**Expected Result:**
- SMS sent to the mobile number with 4-digit OTP
- Redirected to `otp.php`
- New user created in `users_ap`
- OTP stored in `otp_ap`

**Note:** SMS will only be sent if AuthKey API is active and configured.

---

### Test 4: Session Persistence

**Steps:**
1. After successful login, navigate to different pages:
   - `dashboard.php`
   - `property_add.php`
   - Any other page in agent_panel
2. Check if session remains active

**Expected Result:**
- All pages accessible without re-login
- Header shows user information
- No redirect to login page

---

### Test 5: Logout Functionality

**Steps:**
1. Click "Logout" in the sidebar
2. Verify redirect to `login.php`
3. Try accessing `dashboard.php` directly

**Expected Result:**
- Redirected to `login.php`
- UUID status changed to 'unverified' in `otp_ap` table
- Direct access to protected pages redirects to login

**Verify in Database:**
```sql
SELECT status FROM otp_ap WHERE user_id='1234567890' ORDER BY id DESC LIMIT 1;
-- Should show 'unverified'
```

---

### Test 6: Invalid OTP

**Steps:**
1. Login with mobile number
2. On `otp.php`, enter wrong OTP (e.g., `0000`)
3. Click "Verify OTP"

**Expected Result:**
- Error notification displayed
- Remains on `otp.php` page
- No access to dashboard

---

### Test 7: Existing User Login

**Steps:**
1. Logout
2. Login again with same mobile number used in Test 1
3. Check database for new OTP

**Expected Result:**
- No new user created in `users_ap`
- New OTP generated in `otp_ap`
- Can login with new OTP

**Verify in Database:**
```sql
SELECT COUNT(*) FROM users_ap WHERE user_id='1234567890';
-- Should return 1 (same user)

SELECT COUNT(*) FROM otp_ap WHERE user_id='1234567890';
-- Should return 2 or more (multiple OTP entries)
```

---

## Quick Test Credentials

For quick testing without SMS:

**Mobile Number:** `1234567890`  
**OTP:** `1234` (hardcoded)

This combination will work without requiring actual SMS delivery.

---

## Troubleshooting

### Issue: "Invalid Mobile Number" error

**Solution:**
- Make sure you entered a mobile number (not empty)
- Check if `users_ap` table exists in database

### Issue: OTP page redirects to logout

**Solution:**
- Check if UUID cookie is set in browser
- Verify `otp_ap` table has the record with matching UUID
- Clear browser cookies and try again

### Issue: SMS not received

**Solution:**
- Check if AuthKey API credentials are correct
- Verify internet connection
- Use test number `1234567890` with OTP `1234` for testing without SMS

### Issue: Dashboard shows mobile number instead of name

**Solution:**
- This is expected for new users (name is set to '-')
- Update user name in `users_ap` table:
  ```sql
  UPDATE users_ap SET name='Agent Name' WHERE user_id='1234567890';
  ```

---

## Database Queries for Monitoring

### View all agent users:
```sql
SELECT * FROM users_ap ORDER BY created_at DESC;
```

### View recent OTPs:
```sql
SELECT * FROM otp_ap ORDER BY created_at DESC LIMIT 10;
```

### Check active sessions:
```sql
SELECT * FROM otp_ap WHERE status='verified' ORDER BY created_at DESC;
```

### Clear test data:
```sql
DELETE FROM users_ap WHERE user_id='1234567890';
DELETE FROM otp_ap WHERE user_id='1234567890';
```

---

## Summary

✅ Database tables created with "_ap" suffix  
✅ Login page accepts mobile numbers  
✅ OTP generation and SMS integration  
✅ OTP verification page functional  
✅ Session management with UUID cookies  
✅ Logout functionality working  
✅ All authentication uses new tables  

The agent panel now has a complete OTP-based authentication system identical to the customer folder implementation!
