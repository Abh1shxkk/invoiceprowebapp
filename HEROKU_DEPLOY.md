# Deploying InvoicePro to Heroku

This guide will help you deploy your Laravel application to Heroku using your GitHub account.

## Prerequisites
1.  **Heroku Account:** You mentioned you signed up.
2.  **GitHub Repository:** Your code must be pushed to a GitHub repository.
3.  **Heroku CLI (Optional):** Useful for running commands, but you can do most things via the website.

---

## Step 1: Prepare Your Project
1.  **Push to GitHub:** Ensure your latest code (including the `Procfile` I just created) is pushed to your GitHub repository.
    ```bash
    git add .
    git commit -m "Prepare for Heroku deployment"
    git push origin main
    ```

## Step 2: Create Heroku App
1.  Log in to your [Heroku Dashboard](https://dashboard.heroku.com/).
2.  Click **New** > **Create new app**.
3.  Give your app a unique name (e.g., `invoicepro-yourname`) and choose your region.
4.  Click **Create app**.

## Step 3: Connect to GitHub
1.  In your app's dashboard, go to the **Deploy** tab.
2.  Under "Deployment method", select **GitHub**.
3.  Click **Connect to GitHub** and authorize Heroku to access your repositories.
4.  Search for your `INVOICEPRO` repository and click **Connect**.

## Step 4: Configure Environment Variables
1.  Go to the **Settings** tab.
2.  Click **Reveal Config Vars**.
3.  Add the following variables (copy values from your local `.env` where applicable, but **use the production settings below**):

    | Key | Value |
    | --- | --- |
    | `APP_NAME` | InvoicePro |
    | `APP_ENV` | production |
    | `APP_KEY` | (Copy this from your local .env file) |
    | `APP_DEBUG` | false |
    | `APP_URL` | https://your-app-name.herokuapp.com |
    | `LOG_CHANNEL` | errorlog |

    *Note: Do NOT add database credentials yet. Heroku will handle that in the next step.*

## Step 5: Setup Database (PostgreSQL)
Heroku uses PostgreSQL instead of MySQL by default.
1.  Go to the **Resources** tab.
2.  In the **Add-ons** search box, type `Heroku Postgres`.
3.  Select **Heroku Postgres** and choose the **Eco** or **Mini** plan (or whatever your Student Pack covers).
4.  Click **Submit Order Form**.
5.  Heroku automatically adds a `DATABASE_URL` config var to your settings. Laravel detects this automatically!

## Step 6: Deploy
1.  Go back to the **Deploy** tab.
2.  Scroll down to **Manual deploy**.
3.  Select the branch to deploy (usually `main`) and click **Deploy Branch**.
4.  Wait for the build to finish. You should see "Build succeeded".

## Step 7: Run Migrations
You need to set up the database tables on the live server.
1.  Click **More** (top right) > **Run console**.
2.  Type `bash` and click **Run**.
3.  In the terminal that opens, run:
    ```bash
    php artisan migrate --force
    ```
4.  (Optional) To seed data:
    ```bash
    php artisan db:seed --class=DemoDataSeeder --force
    ```

## Step 8: Open Your App
Click the **Open app** button in the top right corner. Your InvoicePro application should now be live!

## Troubleshooting
- **Assets not loading (CSS/JS):** Ensure `npm run build` ran during deployment (Heroku usually handles this if it detects `package.json`). If styles are missing, check the logs.
- **500 Error:** Check your logs by clicking **More** > **View logs** to see the specific error message.
