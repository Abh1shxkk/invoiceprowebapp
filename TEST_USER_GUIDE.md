# 🎯 Test User with Complete Demo Data

## 🚀 Quick Setup

Run this seeder to create a complete test user with demo data for ALL modules!

### Run Locally:
```bash
php artisan db:seed --class=TestUserSeeder
```

### Run on Render (Shell):
```bash
php artisan db:seed --class=TestUserSeeder --force
```

---

## 👤 Test User Credentials

```
Email: demo@invoicepro.com
Password: demo123
Role: User
```

---

## 📊 What Gets Created:

### 1. **User Account** ✅
- **Name**: Demo User
- **Email**: demo@invoicepro.com
- **Password**: demo123
- **Role**: User (with all user permissions)

### 2. **Company Settings** ✅
- **Company Name**: Demo Enterprises Pvt Ltd
- **Address**: Plot No. 123, Sector 18, Noida, UP 201301
- **Phone**: +91-9876543210
- **Email**: contact@demoenterprises.com
- **Website**: www.demoenterprises.com
- **GSTIN**: GSTIN29ABCDE1234F1Z5
- **Tax Rate**: 18%
- **Invoice Prefix**: DEMO
- **Starting Number**: 1001

### 3. **Categories (5)** ✅
1. Electronics - Electronic items and gadgets
2. Software - Software licenses and subscriptions
3. Consulting - Consulting and professional services
4. Hardware - Computer hardware and peripherals
5. Services - General services

### 4. **Clients (5)** ✅

| Name | Company | Email | Phone | Location |
|------|---------|-------|-------|----------|
| Rajesh Kumar | Tech Corp India Pvt Ltd | rajesh@techcorp.in | +91-9876543211 | New Delhi |
| Priya Sharma | Innovate Solutions | priya@innovate.in | +91-9876543212 | Bangalore |
| Amit Patel | DigiTech Services | amit@digitech.in | +91-9876543213 | Mumbai |
| Sneha Reddy | Cloudify India | sneha@cloudify.in | +91-9876543214 | Hyderabad |
| Vikram Singh | WebPro Solutions | vikram@webpro.in | +91-9876543215 | Kolkata |

### 5. **Invoices (5)** ✅

#### Invoice 1 - DEMO-1001 (PAID)
**Client**: Rajesh Kumar (Tech Corp India)
**Items**:
- Laptop Dell Inspiron 15 × 2 @ ₹45,000 = ₹90,000
- Wireless Mouse × 5 @ ₹500 = ₹2,500
**Subtotal**: ₹92,500
**Tax (18%)**: ₹16,650
**Total**: ₹1,09,150

#### Invoice 2 - DEMO-1002 (PENDING)
**Client**: Priya Sharma (Innovate Solutions)
**Items**:
- Microsoft Office 365 License × 10 @ ₹3,500 = ₹35,000
- Antivirus Software × 10 @ ₹1,200 = ₹12,000
**Subtotal**: ₹47,000
**Tax (18%)**: ₹8,460
**Total**: ₹55,460

#### Invoice 3 - DEMO-1003 (PAID)
**Client**: Amit Patel (DigiTech Services)
**Items**:
- Web Development Services × 1 @ ₹75,000 = ₹75,000
- SEO Optimization × 1 @ ₹25,000 = ₹25,000
**Subtotal**: ₹1,00,000
**Tax (18%)**: ₹18,000
**Total**: ₹1,18,000

#### Invoice 4 - DEMO-1004 (OVERDUE)
**Client**: Sneha Reddy (Cloudify India)
**Items**:
- Cloud Hosting - Annual × 1 @ ₹50,000 = ₹50,000
- SSL Certificate × 2 @ ₹2,500 = ₹5,000
**Subtotal**: ₹55,000
**Tax (18%)**: ₹9,900
**Total**: ₹64,900

#### Invoice 5 - DEMO-1005 (DRAFT)
**Client**: Vikram Singh (WebPro Solutions)
**Items**:
- IT Consulting - Monthly × 3 @ ₹30,000 = ₹90,000
- System Maintenance × 1 @ ₹15,000 = ₹15,000
**Subtotal**: ₹1,05,000
**Tax (18%)**: ₹18,900
**Total**: ₹1,23,900

---

## 📈 Summary Statistics:

- **Total Invoices**: 5
- **Paid**: 2 (₹2,27,150)
- **Pending**: 1 (₹55,460)
- **Overdue**: 1 (₹64,900)
- **Draft**: 1 (₹1,23,900)
- **Total Revenue**: ₹4,71,410

---

## 🎯 What You Can Test:

### ✅ Dashboard Module:
- View total revenue
- See invoice statistics
- Check recent invoices
- View payment status

### ✅ Clients Module:
- View all 5 clients
- Edit client details
- Add new clients
- Delete clients

### ✅ Categories Module:
- View all 5 categories
- Create new categories
- Edit categories
- Assign to products

### ✅ Invoices Module:
- View all invoices (Paid, Pending, Overdue, Draft)
- Create new invoices
- Edit existing invoices
- Generate PDF
- Send invoices
- Mark as paid
- Filter by status

### ✅ Reports Module:
- Revenue reports
- Client-wise reports
- Category-wise reports
- Tax reports

### ✅ Settings Module:
- Update company details
- Change tax rate
- Modify invoice settings
- Update payment terms

---

## 🚀 Deploy to Render:

### Option 1: Add to DatabaseSeeder (Automatic)

Update `DatabaseSeeder.php`:
```php
public function run(): void
{
    $this->call([
        RoleSeeder::class,
        UserSeeder::class,
        TestUserSeeder::class,  // Add this line
        CategorySeeder::class,
        ClientSeeder::class,
        InvoiceSeeder::class,
    ]);
}
```

Then push to GitHub - Render will auto-seed!

### Option 2: Manual Seed on Render

In Render Shell:
```bash
php artisan db:seed --class=TestUserSeeder --force
```

---

## 🔄 Reset Test Data:

If you want to reset and recreate test data:

```bash
# Delete test user and all related data
php artisan tinker
User::where('email', 'demo@invoicepro.com')->first()->delete();
exit

# Recreate
php artisan db:seed --class=TestUserSeeder
```

---

## 💡 Pro Tips:

1. **Use this for demos** - Perfect for showing clients
2. **Test all features** - Every module has data
3. **Realistic data** - Indian names, addresses, GSTIN
4. **Multiple statuses** - Test different invoice states
5. **Safe to delete** - Won't affect other users

---

## 🎉 Perfect For:

- ✅ Client demos
- ✅ Feature testing
- ✅ UI/UX testing
- ✅ Screenshots
- ✅ Training
- ✅ Development

---

**Login with demo@invoicepro.com / demo123 and explore! 🚀**
