# 🔄 Restore Test Data on Render

## Problem:
Local test data is not on Render (only default seeded data).

---

## ✅ Solution 1: Added Test User to Seeder (DONE!)

**Test account ab seeders mein add ho gaya hai!** ✅

### New Test Account:
```
Email: test@invoicepro.com
Password: test123
Company: Test Company
```

### To Apply on Render:

**Option A: Redeploy (Automatic)**
```bash
# Push updated seeder to GitHub
git add database/seeders/UserSeeder.php
git commit -m "Add test user to seeder"
git push origin main

# Render will auto-deploy and run seeders!
```

**Option B: Manual Seed (Quick)**
```bash
# In Render Shell:
php artisan db:seed --class=UserSeeder --force
```

---

## ✅ Solution 2: Import Full Local Database

### Step 1: Export Local Database

**If using MySQL:**
```bash
mysqldump -u root -p invoicepro > local_backup.sql
```

**If using SQLite:**
```bash
sqlite3 database/database.sqlite .dump > local_backup.sql
```

### Step 2: Convert to PostgreSQL

**Option A: Use pgloader (if MySQL)**
```bash
# Install pgloader
# Windows: Download from https://pgloader.io

# Convert and import directly
pgloader mysql://root@localhost/invoicepro postgresql://your-render-db-url
```

**Option B: Manual conversion**
1. Go to: https://www.rebasedata.com/convert-mysql-to-postgresql-online
2. Upload `local_backup.sql`
3. Download PostgreSQL version

### Step 3: Import to Render Database

**Get Render DB credentials:**
1. Render Dashboard → PostgreSQL Database
2. Click "Connect" → Copy "External Database URL"

**Import:**
```bash
# Install PostgreSQL client (if not installed)
# Windows: Download from postgresql.org

# Import
psql "postgresql://invoicepro:password@dpg-xxx.render.com/invoicepro" < local_backup.sql
```

---

## ✅ Solution 3: Manually Create Test Data on Render

### In Render Shell:

```bash
# Open tinker
php artisan tinker

# Create test user
$user = new App\Models\User();
$user->name = 'Test User';
$user->email = 'test@invoicepro.com';
$user->password = bcrypt('test123');
$user->email_verified_at = now();
$user->role = 'user';
$user->save();
$user->assignRole('user');

# Create test settings
$setting = new App\Models\Setting();
$setting->user_id = $user->id;
$setting->company_name = 'Test Company';
$setting->address = 'Test Address';
$setting->tax_rate = 18.00;
$setting->invoice_prefix = 'TEST';
$setting->save();

# Create test client
$client = new App\Models\Client();
$client->user_id = $user->id;
$client->name = 'Test Client';
$client->email = 'client@test.com';
$client->phone = '1234567890';
$client->address = 'Client Address';
$client->save();

# Exit
exit
```

---

## 🎯 Recommended Approach:

### **For Now (Quick Fix):**
```bash
# Push updated seeder
git add .
git commit -m "Add test user to seeder"
git push origin main

# Wait for auto-deploy (2-3 min)
# Test account will be available!
```

### **For Future (Permanent):**
Keep adding important test data to seeders, so it's always available on every deployment.

---

## 📝 Current Seeded Accounts:

After deployment, you'll have:

| Email | Password | Role | Company |
|-------|----------|------|---------|
| admin@invoicepro.com | password | Admin | InvoicePro Admin |
| user@invoicepro.com | password | User | John Doe Enterprises |
| test@invoicepro.com | test123 | User | Test Company |

---

## 💡 Pro Tip:

**Create a TestDataSeeder** for all your test data:

```php
// database/seeders/TestDataSeeder.php
class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Create test clients
        // Create test invoices
        // Create test categories
        // etc...
    }
}
```

Then run only when needed:
```bash
php artisan db:seed --class=TestDataSeeder
```

---

**Test account ab available hai! Push karo aur deploy hone do! 🚀**
