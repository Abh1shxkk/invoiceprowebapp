# 🚀 Render Deployment Guide - InvoicePro

**Complete step-by-step guide to deploy your Laravel app on Render.com (FREE, NO CREDIT CARD)**

---

## ✅ Prerequisites

- GitHub account
- Render account (free signup at https://render.com)
- Your code pushed to GitHub

---

## 📋 Step 1: Prepare Your Project

### 1.1 Files Already Created ✅

Your project now has:
- `build.sh` - Build script for Render
- `.env.render` - Environment template

### 1.2 Generate APP_KEY

Run this command locally:
```bash
php artisan key:generate --show
```

**Copy the output** (looks like: `base64:xxxxxxxxxxxxx`)
You'll need this later!

### 1.3 Push to GitHub

```bash
git add .
git commit -m "Ready for Render deployment"
git push origin main
```

---

## 🗄️ Step 2: Create PostgreSQL Database on Render

### 2.1 Go to Render Dashboard
1. Open https://dashboard.render.com
2. Click **"New +"** button (top right)
3. Select **"PostgreSQL"**

### 2.2 Configure Database
Fill in these details:

| Field | Value |
|-------|-------|
| **Name** | `invoicepro-db` |
| **Database** | `invoicepro` |
| **User** | `invoicepro` |
| **Region** | **Singapore** (closest to India) |
| **Plan** | **Free** |

### 2.3 Create Database
1. Click **"Create Database"**
2. Wait 1-2 minutes for database to be ready
3. **IMPORTANT**: Keep this page open, you'll need connection details!

### 2.4 Copy Database Connection Details

Once database is created, you'll see:

**Internal Database URL** (looks like):
```
postgres://invoicepro:xxxxx@dpg-xxxxx-singapore-postgres.render.com/invoicepro
```

**Copy these details separately:**
- **Host**: `dpg-xxxxx-singapore-postgres.render.com`
- **Port**: `5432`
- **Database**: `invoicepro`
- **Username**: `invoicepro`
- **Password**: `xxxxxxxxxxxxxxxx`

---

## 🌐 Step 3: Create Web Service on Render

### 3.1 Create New Web Service
1. Go back to Render Dashboard
2. Click **"New +"** → **"Web Service"**
3. Click **"Connect a repository"**
4. If first time, click **"Connect GitHub"** and authorize Render
5. Select your **`INVOICEPRO`** repository

### 3.2 Configure Web Service

Fill in these details:

| Field | Value |
|-------|-------|
| **Name** | `invoicepro` |
| **Region** | **Singapore** |
| **Branch** | `main` |
| **Runtime** | **PHP** |
| **Build Command** | `./build.sh` |
| **Start Command** | `php artisan serve --host=0.0.0.0 --port=$PORT` |
| **Plan** | **Free** |

### 3.3 Add Environment Variables

Scroll down to **"Environment Variables"** section.

Click **"Add Environment Variable"** and add these **ONE BY ONE**:

#### Basic App Settings:
```
APP_NAME = InvoicePro
APP_ENV = production
APP_DEBUG = false
APP_KEY = base64:xxxxx (paste the key you generated in Step 1.2)
APP_URL = https://invoicepro.onrender.com
```

#### Database Settings (use values from Step 2.4):
```
DB_CONNECTION = pgsql
DB_HOST = dpg-xxxxx-singapore-postgres.render.com
DB_PORT = 5432
DB_DATABASE = invoicepro
DB_USERNAME = invoicepro
DB_PASSWORD = xxxxxxxxxxxxxxxx
```

#### Other Settings:
```
SESSION_DRIVER = database
CACHE_DRIVER = file
QUEUE_CONNECTION = database
FILESYSTEM_DISK = public
LOG_CHANNEL = stack
LOG_LEVEL = error
```

### 3.4 Deploy!

1. Click **"Create Web Service"** button at the bottom
2. Render will start building your app
3. **Wait 5-10 minutes** for first deployment
4. You'll see build logs in real-time

---

## ✅ Step 4: Verify Deployment

### 4.1 Check Build Logs

Watch the logs for:
- ✅ `Composer install` - Installing dependencies
- ✅ `npm run build` - Building assets
- ✅ `php artisan migrate` - Running migrations
- ✅ `php artisan db:seed` - Seeding database
- ✅ `Deploy successful` - All done!

### 4.2 Get Your App URL

Once deployed, your app will be live at:
```
https://invoicepro.onrender.com
```

(Or whatever name you chose in Step 3.2)

### 4.3 Test Your App

1. Open the URL in browser
2. You should see your InvoicePro app!
3. Try logging in with seeded credentials

---

## 🔧 Step 5: Post-Deployment (Optional)

### 5.1 Run Additional Commands

If you need to run any artisan commands:

1. Go to your Web Service in Render Dashboard
2. Click **"Shell"** tab (top menu)
3. Run commands like:

```bash
# Clear caches
php artisan cache:clear
php artisan config:clear

# Create storage link
php artisan storage:link

# Run specific seeder
php artisan db:seed --class=UserSeeder
```

### 5.2 View Logs

To debug issues:
1. Go to **"Logs"** tab
2. See real-time application logs
3. Check for errors

---

## 🎯 Important Notes

### Free Tier Limitations:

⚠️ **App sleeps after 15 minutes of inactivity**
- First request after sleep takes ~30 seconds to wake up
- Subsequent requests are fast

⚠️ **Database retention: 90 days**
- After 90 days, free database is deleted
- Backup your data regularly!

⚠️ **750 hours/month**
- Enough for 1 app running 24/7

### Keep Your App Awake (FREE Solution):

Use **UptimeRobot** to ping your app every 5 minutes:

1. Go to https://uptimerobot.com
2. Sign up (free, no credit card)
3. Click **"Add New Monitor"**
4. Select **"HTTP(s)"**
5. **Friendly Name**: `InvoicePro`
6. **URL**: `https://invoicepro.onrender.com`
7. **Monitoring Interval**: `5 minutes`
8. Click **"Create Monitor"**

Now your app won't sleep! 🎉

---

## 🔄 Updating Your App

### Auto-Deploy (Enabled by Default):

Every time you push to GitHub `main` branch:
```bash
git add .
git commit -m "Your changes"
git push origin main
```

Render will **automatically rebuild and redeploy**! 🚀

### Manual Deploy:

1. Go to your Web Service in Render
2. Click **"Manual Deploy"** → **"Deploy latest commit"**

---

## 🐛 Troubleshooting

### Issue 1: Build Fails

**Check:**
- Build logs for specific error
- Make sure `build.sh` has correct permissions
- Verify all dependencies in `composer.json`

**Solution:**
```bash
# In Render Shell
chmod +x build.sh
```

### Issue 2: 500 Internal Server Error

**Check:**
- Logs tab for error details
- APP_KEY is set correctly
- Database connection is correct

**Solution:**
```bash
# In Render Shell
php artisan config:clear
php artisan cache:clear
php artisan key:generate
```

### Issue 3: Database Connection Error

**Check:**
- Environment variables are correct
- Database host/password match
- Database is running (check database dashboard)

**Solution:**
- Re-copy database credentials from database dashboard
- Update environment variables in web service

### Issue 4: Assets Not Loading

**Check:**
- `npm run build` ran successfully in build logs
- Public directory is accessible

**Solution:**
```bash
# In Render Shell
php artisan storage:link
npm run build
```

### Issue 5: Migration Errors

**Solution:**
```bash
# In Render Shell
php artisan migrate:fresh --force
php artisan db:seed --force
```

---

## 📊 Monitoring Your App

### Check Usage:
1. Dashboard → Your Web Service
2. See **"Metrics"** tab for:
   - CPU usage
   - Memory usage
   - Request count
   - Response times

### Set Up Alerts:
1. Go to **"Settings"** → **"Notifications"**
2. Add email for deploy notifications
3. Get notified on build failures

---

## 🎨 Custom Domain (Optional)

### Add Your Own Domain:

1. Go to **"Settings"** → **"Custom Domains"**
2. Click **"Add Custom Domain"**
3. Enter your domain (e.g., `invoicepro.yourdomain.com`)
4. Add CNAME record in your DNS:
   - **Name**: `invoicepro`
   - **Value**: `invoicepro.onrender.com`
5. Wait for DNS propagation (5-30 minutes)
6. Render will auto-generate SSL certificate!

### Free Domain Options:
- **Freenom**: Free .tk, .ml, .ga domains
- **GitHub Student Pack**: Free .me domain

---

## 💾 Database Backup

### Manual Backup:

1. Go to Database in Render Dashboard
2. Click **"Connect"** → Copy **External Database URL**
3. Run locally:

```bash
# Install PostgreSQL client
# Windows: Download from postgresql.org

# Backup
pg_dump "postgres://invoicepro:xxx@dpg-xxx.render.com/invoicepro" > backup.sql

# Restore (if needed)
psql "postgres://invoicepro:xxx@dpg-xxx.render.com/invoicepro" < backup.sql
```

### Automated Backup (Recommended):

Set up a cron job or use a service like:
- **SimpleBackups** (free tier available)
- **BackupNinja**

---

## 📈 Upgrading (If Needed)

### When to Upgrade:

If you need:
- ✅ No sleep (always active)
- ✅ More resources
- ✅ Longer database retention
- ✅ Priority support

### Pricing:

**Starter Plan**: $7/month per service
- No sleep
- Always active
- Better performance

**Database Plan**: $7/month
- Unlimited retention
- More storage
- Better performance

---

## 🎯 Quick Reference

### Your App URLs:
- **App**: https://invoicepro.onrender.com
- **Dashboard**: https://dashboard.render.com

### Important Commands:
```bash
# View logs
# (In Render Dashboard → Logs tab)

# Run migrations
php artisan migrate --force

# Seed database
php artisan db:seed --force

# Clear cache
php artisan cache:clear
php artisan config:clear

# Create storage link
php artisan storage:link
```

### Default Credentials (After Seeding):
```
Admin:
Email: admin@invoicepro.com
Password: password

User:
Email: user@invoicepro.com
Password: password
```

---

## 📞 Support

### Render Support:
- **Docs**: https://render.com/docs
- **Community**: https://community.render.com
- **Status**: https://status.render.com

### Common Links:
- **Dashboard**: https://dashboard.render.com
- **Billing**: https://dashboard.render.com/billing
- **Account Settings**: https://dashboard.render.com/settings

---

## ✅ Deployment Checklist

Before going live, verify:

- [ ] App is accessible at Render URL
- [ ] Database is connected and seeded
- [ ] Login works with test credentials
- [ ] All pages load correctly
- [ ] Assets (CSS/JS) are loading
- [ ] File uploads work (if applicable)
- [ ] UptimeRobot is set up (to prevent sleep)
- [ ] Environment variables are correct
- [ ] APP_DEBUG is set to `false`
- [ ] APP_ENV is set to `production`
- [ ] Logs show no errors
- [ ] Database backup plan is in place

---

## 🎉 Congratulations!

Your **InvoicePro** app is now live on Render! 🚀

**Next Steps:**
1. Share your app URL with others
2. Set up UptimeRobot to keep it awake
3. Monitor logs for any issues
4. Plan regular database backups
5. Consider custom domain for professional look

---

**Happy Deploying! 🎊**

*Made with ❤️ for InvoicePro*
