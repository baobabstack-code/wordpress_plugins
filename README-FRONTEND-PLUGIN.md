# Simple Frontend Plugin

A lightweight, working WordPress plugin with frontend components and functionality.

## Features

✅ **Contact Form Shortcode** - Fully functional contact form with AJAX submission
✅ **Testimonials Shortcode** - Display customer testimonials  
✅ **Counter Shortcode** - Animated counter display
✅ **Responsive Design** - Works on all devices
✅ **Email Notifications** - Sends form submissions to admin email
✅ **Security** - Includes nonce verification and input sanitization

## Installation

1. Activate the `simple-frontend-plugin.php` in WordPress admin
2. Or upload it to `/wp-content/plugins/` directory

## Shortcodes

### Contact Form
```
[sfp_contact]
```
Displays a contact form that sends messages to your admin email via AJAX.

**Example:**
```
<h2>Get in Touch</h2>
[sfp_contact]
```

### Testimonials
```
[sfp_testimonials]
```
Displays customer testimonials in a beautiful grid layout.

**Example:**
```
[sfp_testimonials]
```

### Counter
```
[sfp_counter number="100"]
```
Displays an animated counter. You can change the number value.

**Parameters:**
- `number` - The number to count up to (default: 100)

**Examples:**
```
[sfp_counter number="1000"]
[sfp_counter number="500"]
```

## Files Included

- `simple-frontend-plugin.php` - Main plugin file with all functionality
- `assets/css/simple-frontend.css` - Styling for all components
- `assets/js/simple-frontend.js` - Frontend JavaScript for form handling and animations

## How It Works

### Contact Form
- Users fill out the form and submit via AJAX
- Messages are validated on both client and server side
- An email is sent to the WordPress admin email
- User receives success/error feedback without page reload

### Testimonials
- Displays in a responsive grid layout
- Includes gradient background and card design
- Fully responsive on mobile devices

### Counter
- Animates when scrolled into view
- Smooth counting animation
- Fully customizable with shortcode attributes

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- jQuery (included with WordPress)

## Security

This plugin includes:
- Nonce verification for AJAX requests
- Input sanitization
- Email validation
- WPML compatibility ready

## Customization

You can customize styles by editing `assets/css/simple-frontend.css`

To modify form fields, edit the HTML in the `sfp_contact_form_shortcode()` function

## License

GPL v2 or later
