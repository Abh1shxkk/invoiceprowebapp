# 🔧 Render Build Error Fix

## Problem:
```
composer: command not found
```

Render detected **Node.js** instead of **PHP** runtime.

---

## ✅ Solution 1: Fix in Render Dashboard (EASIEST)

### Step 1: Delete Current Service
1. Go to Render Dashboard
2. Select your `invoicepro` web service
3. Settings → Scroll down → "Delete Web Service"

### Step 2: Create New Service (Correctly)
1. Click "New +" → "Web Service"
2. Connect your GitHub repo
3. **IMPORTANT**: Fill these EXACTLY:

| Field | Value |
|-------|-------|
| **Name** | `invoicepro` |
| **Region** | Singapore |
| **Branch** | `main` |
| **Root Directory** | (leave empty) |
| **Environment** | **PHP** ⚠️ (NOT Node.js!) |
| **Build Command** | `composer install --optimize-autoloader --no-dev && npm ci && npm run build` |
| **Start Command** | `php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT` |

### Step 3: Add Environment Variables
Same as before (see RENDER_DEPLOYMENT.md)

### Step 4: Deploy
Click "Create Web Service"

---

## ✅ Solution 2: Use Docker (ADVANCED)

Files created:
- `Dockerfile` ✅
- `docker-entrypoint.sh` ✅
- `render.yaml` ✅

### In Render Dashboard:
1. Delete old service
2. Create new service
3. Select **"Docker"** as environment
4. Render will auto-detect Dockerfile
5. Add environment variables
6. Deploy!

---

## ✅ Solution 3: Use Nixpacks Config (RECOMMENDED)

Create this file to force PHP detection:

**File: `nixpacks.toml`**
```toml
[phases.setup]
nixPkgs = ['php82', 'php82Packages.composer', 'nodejs-18_x', 'postgresql']

[phases.install]
cmds = [
  'composer install --no-dev --optimize-autoloader --no-interaction',
  'npm ci',
]

[phases.build]
cmds = [
  'npm run build',
  'php artisan config:cache',
  'php artisan route:cache',
  'php artisan view:cache',
]

[start]
cmd = 'php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=$PORT'
```

Then:
1. Push this file to GitHub
2. Render will auto-detect PHP!

---

## 🎯 Which Solution to Use?

### **Easiest**: Solution 1 (Dashboard Fix)
- Just recreate service with correct settings
- 5 minutes
- No code changes

### **Best**: Solution 3 (Nixpacks)
- Add `nixpacks.toml` file
- Push to GitHub
- Most reliable

### **Advanced**: Solution 2 (Docker)
- Full control
- Slower builds
- Use if others fail

---

## 📝 Quick Fix Steps:

1. **Delete current service** in Render
2. **Create new service**
3. **Select "PHP" as environment** ⚠️
4. **Use these commands**:
   - Build: `composer install --optimize-autoloader --no-dev && npm ci && npm run build`
   - Start: `php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT`
5. **Add environment variables**
6. **Deploy!**

---

**Problem solved! 🎉**
