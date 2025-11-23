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

### Error: "Role already exists" OR "User already exists"
**Good news! Yeh matlab hai ki tumhare accounts already ban chuke hain! 🎉**

**Solution: Simply verify and login karo**

Heroku console mein yeh command run karo:
```bash
php artisan tinker
```

Then check karo ki users exist karte hain:
```php
\App\Models\User::where('email', 'admin@invoicepro.com')->exists()
\App\Models\User::where('email', 'user@invoicepro.com')->exists()
exit
```

Agar `true` aaye, toh **directly login karo**:
- Admin: `admin@invoicepro.com` / `password`
- User: `user@invoicepro.com` / `password`

---

### Agar Password Pata Nahi Hai (Reset Password)

Heroku console mein:
```bash
php artisan tinker
```

Then:
```php
$admin = \App\Models\User::where('email', 'admin@invoicepro.com')->first();
$admin->password = bcrypt('newpassword123');
$admin->save();
echo "Password changed to: newpassword123";
exit
```

---

### Fresh Start Chahiye? (⚠️ Deletes ALL data)
```bash
php artisan migrate:fresh --seed --force
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
