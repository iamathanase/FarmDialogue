# FarmDialogue Deployment Guide

## Deployment Fixed ✅

The deployment error has been resolved by adding proper Vercel configuration.

### What Was Fixed

**Problem:** 
```
sh: line 1: vite: command not found
Error: Command "vite build" exited with 127
```

**Root Cause:** 
- Vercel was trying to build the project as a Node.js/Vite application
- There was no `package.json` file
- FarmDialogue is a static HTML/CSS/JS frontend site with PHP backend

**Solution Implemented:**
1. ✅ Created `package.json` with proper build scripts
2. ✅ Created `vercel.json` configuration that serves the static frontend
3. ✅ Created `.vercelignore` to exclude unnecessary files

---

## Deployment Architecture

### Frontend (Deployed to Vercel)
- **Type:** Static HTML/CSS/JavaScript
- **Directory:** `front/`
- **Hosting:** Vercel (https://farmdialogue.vercel.app)
- **Build:** No build step required

### Backend (Separate Deployment)
- **Type:** PHP API
- **Directory:** `back/`
- **Hosting:** Traditional PHP hosting or serverless functions
- **Database:** MySQL

---

## How to Redeploy

### Option 1: Automatic Deployment (Recommended)
1. Push changes to GitHub `main` branch
2. Vercel automatically builds and deploys
3. Your site updates at `https://farmdialogue.vercel.app`

```bash
git add .
git commit -m "Your message"
git push origin main
```

### Option 2: Manual Deployment
```bash
# Using Vercel CLI
vercel --prod
```

### Option 3: Redeploy Existing Commit
Go to Vercel Dashboard → FarmDialogue → Deployments → Click "Redeploy" on existing commit

---

## Configuration Files Explained

### `vercel.json`
```json
{
  "version": 2,
  "builds": [],
  "public": "front",
  "cleanUrls": true
}
```
- `builds`: Empty (no build steps needed for static files)
- `public`: Specifies `front/` folder as the root for deployment
- `cleanUrls`: Removes `.html` from URLs (optional)

### `package.json`
```json
{
  "scripts": {
    "build": "echo 'Static site - no build required'"
  }
}
```
- Provides a `build` script so Vercel doesn't fail
- Build does nothing since we're just serving static files

### `.vercelignore`
Excludes unnecessary files from being deployed:
- `.git/`, node_modules, logs, etc.

---

## Monitoring & Troubleshooting

### Check Deployment Status
1. Go to: https://vercel.com/iamathanase/FarmDialogue
2. Click "Deployments" tab
3. View build logs for any issues

### Common Issues & Fixes

**Issue: Build still fails**
- Clear Vercel cache: Dashboard → Settings → Advanced → Purge Cache → Redeploy
- Ensure `front/` folder has `index.html`

**Issue: Pages not loading**
- Check that all HTML files are in `front/` directory
- Verify CSS and JS paths are correct (use relative paths)

**Issue: API calls fail**
- Backend is hosted separately (update API endpoints in `front/assets/js/script.js`)
- Use CORS to allow requests from Vercel domain

---

## Environment Variables (if needed)

Add to Vercel Dashboard → Settings → Environment Variables:
```
API_BACKEND_URL=https://your-php-hosting.com
```

Then use in `front/assets/js/script.js`:
```javascript
const API_URL = process.env.API_BACKEND_URL;
```

---

## Next Steps

### Backend Deployment
1. Deploy PHP backend to:
   - **Option A:** Traditional PHP hosting (Hostgator, Bluehost, etc.)
   - **Option B:** Heroku with PHP runtime
   - **Option C:** Railway.app with PHP support
   - **Option D:** AWS Lambda with PHP

2. Update API endpoints in frontend:
  - Edit `front/assets/js/script.js`
   - Change API URLs to point to your backend domain

### Database
1. Set up MySQL database on hosting provider
2. Run `database/schema.sql` to create tables
3. Update `back/config.php` with database credentials

### DNS & Custom Domain
1. Point domain to Vercel: https://vercel.com/docs/concepts/projects/domains
2. Frontend: `farmdialogue.example.com` → Vercel
3. Backend: `api.farmdialogue.example.com` → PHP Hosting

---

## Quick Links

- 🌍 **Live Site:** https://farmdialogue.vercel.app
- 📦 **GitHub:** https://github.com/iamathanase/FarmDialogue
- 🚀 **Vercel Dashboard:** https://vercel.com/iamathanase/FarmDialogue
- 📚 **Vercel Docs:** https://vercel.com/docs

---

## Support

For deployment issues:
1. Check Vercel build logs
2. Review this guide's troubleshooting section
3. See `docs/SETUP_LOCAL.md` for local testing
4. Check GitHub issues/discussions
