# 🚀 InvoicePro - Heroku Seeding Guide

## 📋 Overview
Yeh guide tumhe batayega ki Heroku pe deploy karne ke baad database ko kaise seed karna hai taaki admin aur user accounts automatically ban jaye.

---

## 🎯 Quick Start (Sabse Easy Tarika)

### Step 1: Heroku Console Open Karo
1. Apni Heroku app dashboard pe jao
2. **More** (top right corner) > **Run console** pe click karo
3. `bash` type karo aur **Run** pe click karo

### Step 2: Migrations Run Karo
```bash
php artisan migrate --force
```

### Step 3: Data Seed Karo
**Testing ke liye (Demo data ke saath):**
```bash
php artisan db:seed --force
```

Yeh create karega:
- ✅ Admin: `admin@invoicepro.com` / `password`
- ✅ User: `user@invoicepro.com` / `password`  
- ✅ Demo User: `demo@invoicepro.com` / `password` (sample invoices ke saath)

---

## 🔐 Production Setup (Secure)

Agar tum production ke liye deploy kar rahe ho, toh pehle **ProductionSeeder.php** file ko customize karo:

### Step 1: ProductionSeeder Edit Karo (Local pe)
File: `database/seeders/ProductionSeeder.php`

**Change these values:**
```php
// Line 22: Admin email
'email' => 'admin@yourdomain.com', // Apna actual email daalo

// Line 25: Admin password
'password' => Hash::make('YourSecurePassword123!'), // Strong password daalo

// Line 38: Company details
'company_name' => 'Your Company Name',
'address' => 'Your Company Address',

// Line 48: User email
'email' => 'user@yourdomain.com',

// Line 51: User password
'password' => Hash::make('UserPassword123!'),
```

### Step 2: GitHub pe Push Karo
```bash
git add .
git commit -m "Update production seeder with custom credentials"
git push origin main
```

### Step 3: Heroku pe Deploy Karo
Heroku dashboard > **Deploy** tab > **Deploy Branch**

### Step 4: Heroku Console mein Seed Karo
```bash
php artisan migrate --force
php artisan db:seed --class=ProductionSeeder --force
```

---

## 📊 Available Seeders

| Seeder | Kya Create Hota Hai | Kab Use Kare |
|--------|-------------------|--------------|
| `DatabaseSeeder` | Admin, User, Demo User + Sample Data | Testing/Demo |
| `RoleSeeder` | Sirf Roles (admin, user) | Manual setup |
| `UserSeeder` | Admin + User accounts | Production (basic) |
| `ProductionSeeder` | Custom Admin + User | Production (secure) |
| `DemoDataSeeder` | Demo user + Sample invoices/clients | Testing only |

---

## 🛠️ Common Commands

### Sirf Roles Create Karo
```bash
php artisan db:seed --class=RoleSeeder --force
```

### Sirf Users Create Karo (without demo data)
```bash
php artisan db:seed --class=RoleSeeder --force
php artisan db:seed --class=UserSeeder --force
```

### Database Reset + Fresh Seed
⚠️ **WARNING: Yeh saara data delete kar dega!**
```bash
php artisan migrate:fresh --seed --force
```

### Specific Seeder Run Karo
```bash
php artisan db:seed --class=YourSeederName --force
```

---

## 🔑 Default Credentials

### After Running `DatabaseSeeder`:
```
Admin Account:
  Email: admin@invoicepro.com
  Password: password

Regular User:
  Email: user@invoicepro.com
  Password: password

Demo User:
  Email: demo@invoicepro.com
  Password: password
```

### After Running `ProductionSeeder`:
```
Admin Account:
  Email: admin@yourdomain.com (customize in seeder)
  Password: YourSecurePassword123! (customize in seeder)

Regular User:
  Email: user@yourdomain.com (customize in seeder)
  Password: UserPassword123! (customize in seeder)
```

---

## ⚠️ Important Security Notes

1. **Production mein default passwords kabhi use mat karo!**
2. Seeding ke baad immediately passwords change karo
3. `.env` file mein `APP_DEBUG=false` set karo
4. Strong passwords use karo (minimum 12 characters)
5. Admin email apne actual domain ka use karo

---

## 🐛 Troubleshooting

### Error: "Class RoleSeeder not found"
```bash
composer dump-autoload
php artisan db:seed --force
```

### Error: "SQLSTATE[23000]: Integrity constraint violation"
User already exists. Ya toh:
- Different email use karo seeder mein
- Ya database fresh karo: `php artisan migrate:fresh --seed --force`

### Error: "Nothing to migrate"
Database already migrated hai. Directly seeding karo:
```bash
php artisan db:seed --force
```

### Logs Dekhne Ke Liye
Heroku dashboard > **More** > **View logs**

---

## 📝 Next Steps

1. ✅ Migrations run karo
2. ✅ Appropriate seeder run karo
3. ✅ Login karo created credentials se
4. ✅ Password change karo settings se
5. ✅ Company details update karo
6. ✅ Start using your app! 🎉

---

## 💡 Pro Tips

- **Testing**: `DatabaseSeeder` use karo (demo data milega)
- **Production**: `ProductionSeeder` customize karke use karo
- **Quick Setup**: `UserSeeder` use karo (sirf accounts)
- **Fresh Start**: `migrate:fresh --seed` (⚠️ data loss hoga)

---

## 📞 Need Help?

Agar koi issue aaye toh:
1. Heroku logs check karo
2. Database connection verify karo (Config Vars)
3. Migrations properly run hui hai ya nahi check karo
4. Seeder file mein syntax errors check karo

---

**Happy Deploying! 🚀**
