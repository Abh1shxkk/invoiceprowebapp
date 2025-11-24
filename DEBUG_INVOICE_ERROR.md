# 🔍 Debug Invoice Edit 500 Error

## Error:
```
GET https://invoicepros.app/dashboard/invoices/14/edit 500 (Internal Server Error)
```

---

## 🛠️ Steps to Debug:

### Step 1: Check Heroku Logs
In Heroku Dashboard:
1. Go to your app
2. Click **More** → **View logs**
3. Look for the error when you try to edit invoice

OR in Heroku console:
```bash
heroku logs --tail
```

Then try to edit an invoice and see the error.

---

### Step 2: Common Causes

#### Cause 1: Missing Migration
The new migration for profile_photo_path might need to run:

```bash
php artisan migrate --force
```

#### Cause 2: Missing Relationship/Data
Invoice might be trying to load related data that doesn't exist.

#### Cause 3: Settings Table Issue
If invoice edit page loads user settings and tax_rate column is missing.

---

## ✅ Quick Fix (Try This First):

### In Heroku Console:

```bash
# Run pending migrations
php artisan migrate --force

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check if invoice exists
php artisan tinker
```

Then in tinker:
```php
\App\Models\Invoice::find(14);
exit
```

---

## 🔍 Get Exact Error:

Run this in Heroku console to see the actual error:

```bash
php artisan tinker
```

Then:
```php
try {
    $invoice = \App\Models\Invoice::with('client', 'items')->find(14);
    dd($invoice);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
    echo "\nFile: " . $e->getFile();
    echo "\nLine: " . $e->getLine();
}
exit
```

---

## 📋 Next Steps:

1. Run migrations: `php artisan migrate --force`
2. Clear caches (commands above)
3. Check Heroku logs for exact error
4. Share the error message with me

---

**Bhai, Heroku console mein yeh commands run karo aur batao kya error aaya!**
