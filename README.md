# Analytics Dashboard WordPress Plugin

A powerful, privacy-focused analytics plugin that tracks and displays website statistics directly in your WordPress dashboard - no external services required!

## Description

This plugin automatically tracks all visitor activity on your WordPress site and displays beautiful, interactive analytics in your admin dashboard. Unlike Google Analytics, all data stays on your server, giving you complete privacy and control.

## Features

- **Automatic Visitor Tracking** - No setup required, starts tracking immediately
- **Real-time Visitor Count** - See who's online right now (updates every 30 seconds)
- **Beautiful Dashboard** - Interactive charts and statistics
- **Date Range Filters** - View data for 7, 30, or 90 days
- **Privacy-Focused** - All data stored on your server
- **No External Dependencies** - Works completely offline
- **Lightweight** - Minimal impact on site performance
- **Mobile Responsive** - Dashboard works on all devices

### What It Tracks:

- Total page views
- Unique visitors (session-based)
- Popular pages and posts
- Traffic sources (referrers)
- Browser statistics (Chrome, Firefox, Safari, etc.)
- Device types (Desktop, Mobile, Tablet)
- Real-time visitor count
- Pages per visitor average

## Installation

### Method 1: Upload via WordPress Admin (Recommended)

1. **Download/Create Plugin ZIP:**
   - Download the entire `wordpress_plugin` folder
   - Zip the folder (make sure the files are at the root of the zip, not in a subfolder)

2. **Install in WordPress:**
   - Go to WordPress Admin → Plugins → Add New
   - Click "Upload Plugin" button
   - Choose your zip file
   - Click "Install Now"
   - Click "Activate"

3. **Access Dashboard:**
   - Look for "Analytics" in your WordPress admin menu (left sidebar)
   - Click it to see your analytics dashboard

### Method 2: Manual Installation

1. **Upload Files:**
   - Upload the `wordpress_plugin` folder to `/wp-content/plugins/`
   - Rename it to `analytics-dashboard` if desired

2. **Activate:**
   - Go to WordPress Admin → Plugins
   - Find "My Custom Plugin"
   - Click "Activate"

3. **Done!**
   - The database table is created automatically
   - Tracking starts immediately
   - View your dashboard under "Analytics" menu

## How to Use

### Viewing Analytics

1. **Access Dashboard:**
   - WordPress Admin → Analytics (in left sidebar)

2. **Dashboard Sections:**
   - **Stats Cards**: Quick overview of total views, visitors, online users, and averages
   - **Visits Over Time Chart**: Line graph showing page views and unique visitors
   - **Browser Chart**: Donut chart of browser usage
   - **Device Chart**: Pie chart of device types
   - **Popular Pages**: Table of most visited pages
   - **Top Referrers**: Table of traffic sources

3. **Date Filters:**
   - Click "Last 7 Days", "Last 30 Days", or "Last 90 Days" to filter data

### Configuration

The plugin works out of the box with default settings:
- ✅ Tracking enabled automatically
- ❌ Admin visits not tracked (configurable)
- 🔴 Real-time updates every 30 seconds

## Technical Details

### Database

Creates one table: `{prefix}_my_plugin_analytics`

Stores:
- Page URL and title
- Visitor IP and session ID
- Browser and device info
- Referrer URL
- Timestamp

### Performance

- **Lightweight tracking**: Single INSERT query per page view
- **Efficient queries**: Indexed database fields
- **Async updates**: AJAX for real-time data
- **No external calls**: Everything runs locally

### Privacy & GDPR

- All data stored on your server
- No cookies except session tracking
- IP addresses stored (can be anonymized if needed)
- No data shared with third parties

## Requirements

- **PHP**: 7.4 or higher
- **WordPress**: 5.8 or higher
- **MySQL**: 5.6 or higher

## File Structure

```
wordpress_plugin/
├── src/
│   ├── Admin/              # Admin dashboard functionality
│   │   ├── Admin.php       # Dashboard controller
│   │   └── views/
│   │       └── admin-page.php  # Dashboard HTML
│   ├── Analytics/          # Analytics core
│   │   ├── Tracker.php     # Visitor tracking logic
│   │   └── Data.php        # Data retrieval methods
│   ├── Public/             # Frontend tracking
│   │   └── PublicFacing.php
│   ├── Plugin.php          # Main plugin class
│   ├── Loader.php          # Hook registration
│   ├── Activator.php       # Activation logic
│   └── Deactivator.php     # Deactivation logic
├── assets/
│   ├── css/
│   │   └── admin.css       # Dashboard styles
│   └── js/
│       └── admin.js        # Charts & AJAX
├── vendor/
│   └── autoload.php        # PSR-4 autoloader
└── my-custom-plugin.php    # Main plugin file
```

## Frequently Asked Questions

**Q: Does it slow down my site?**
A: No, tracking uses a single lightweight database query per page view.

**Q: Can I track admin pages?**
A: Admin pages are excluded by default to avoid inflating stats.

**Q: How long is data stored?**
A: Forever, unless you manually delete it from the database.

**Q: Can I export data?**
A: Currently no export feature, but all data is in your database table.

**Q: Does it work with caching plugins?**
A: Yes, tracking happens server-side before page is cached.

## Troubleshooting

**No data showing:**
- Make sure the plugin is activated
- Visit some pages on your site (not admin pages)
- Check if database table `{prefix}_my_plugin_analytics` exists

**Charts not loading:**
- Clear browser cache
- Check browser console for JavaScript errors
- Ensure Chart.js CDN is accessible

**Tracking not working:**
- Check if tracking is enabled in options
- Verify PHP version is 7.4+
- Check for plugin conflicts

## Support & Development

- **GitHub**: https://github.com/baobabstack-code/wordpress_plugin
- **Issues**: Report bugs on GitHub Issues
- **Version**: 1.0.0

## License

GPL v2 or later

## Changelog

### 1.0.0
- Initial release
- Automatic visitor tracking
- Analytics dashboard with charts
- Real-time visitor count
- Browser and device statistics
- Popular pages and referrers
