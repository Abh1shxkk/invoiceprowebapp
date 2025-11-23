# 🎯 Heroku Seeding - Quick Commands

## 🚀 FASTEST WAY (Copy-Paste in Heroku Console)

### For Testing/Demo:
```bash
php artisan migrate --force && php artisan db:seed --force
```
**Credentials:**
- Admin: `admin@invoicepro.com` / `password`
- User: `user@invoicepro.com` / `password`
- Demo: `demo@invoicepro.com` / `password`

---

### For Production (Clean):
```bash
php artisan migrate --force && php artisan db:seed --class=RoleSeeder --force && php artisan db:seed --class=UserSeeder --force
```
**Credentials:**
- Admin: `admin@invoicepro.com` / `password`
- User: `user@invoicepro.com` / `password`

---

### For Production (Custom - After editing ProductionSeeder):
```bash
php artisan migrate --force && php artisan db:seed --class=ProductionSeeder --force
```
**Credentials:** (As per your customization in ProductionSeeder.php)

---

## 🔄 Reset Everything (⚠️ Deletes all data):
```bash
php artisan migrate:fresh --seed --force
```

---

## 📍 How to Access Heroku Console:
1. Go to your Heroku app dashboard
2. Click **More** (top right) → **Run console**
3. Type `bash` and click **Run**
4. Paste any command above

---

## 🐛 Troubleshooting

### Error: "Role already exists"
Agar yeh error aaye toh seeders already run ho chuke hain. Do this:

**Option 1: Skip RoleSeeder (Recommended)**
```bash
php artisan db:seed --class=UserSeeder --force
```

**Option 2: Fresh Start (⚠️ Deletes all data)**
```bash
php artisan migrate:fresh --seed --force
```

**Option 3: Just create missing users**
```bash
php artisan tinker
```
Then paste:
```php
$admin = \App\Models\User::firstOrCreate(['email' => 'admin@invoicepro.com'], ['name' => 'Admin', 'password' => bcrypt('password'), 'role' => 'admin', 'email_verified_at' => now()]);
$admin->assignRole('admin');
exit
```

---

## ✅ Verify Seeding Worked:
```bash
php artisan tinker
```
Then type:
```php
\App\Models\User::count();
\App\Models\User::where('role', 'admin')->first()->email;
exit
```

---

**That's it! Ab login karo aur enjoy! 🎉**
