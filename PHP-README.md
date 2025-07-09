# PHP Contact Form Setup for Prismatic Minds

This is a simple PHP script that handles contact form submissions and sends emails directly from your server.

## Files Included

- `contact-handler.php` - The main PHP script that processes the form
- `contact.html` - Updated contact form (already configured)

## Setup Instructions

### 1. Server Requirements

Your server must support:
- PHP 7.0 or higher
- `mail()` function enabled
- Web server (Apache, Nginx, etc.)

### 2. Upload Files

Upload both files to your web server:
```
your-domain.com/
├── contact.html
├── contact-handler.php
├── styles.css
├── logo.png
└── other site files...
```

### 3. Configure Email Settings

Open `contact-handler.php` and update the email address on line 60:

```php
$to = 'your-email@yourdomain.com';  // Change this to your email
```

### 4. Test the Form

1. Visit your contact form: `https://your-domain.com/contact.html`
2. Fill out and submit the form
3. Check your email for the submission

## Features

- ✅ Input validation and sanitization
- ✅ Handles all form fields including checkboxes
- ✅ Formatted email with clean layout
- ✅ Custom success and error pages
- ✅ Mobile-friendly design
- ✅ Reply-to header set to visitor's email
- ✅ Prevents direct access to PHP script

## Email Format

The emails you receive will include:
- Sender's name and contact information
- Their message
- How they found you (checkbox responses)
- Timestamp of submission

## Security Notes

- All inputs are sanitized to prevent XSS attacks
- Email addresses are validated
- Form data is escaped for safe HTML output
- Only POST requests are accepted

## Troubleshooting

### Form doesn't send emails
1. Check if your server's `mail()` function is enabled
2. Verify your email address is correct in the PHP script
3. Check server error logs for PHP errors
4. Some servers require additional mail configuration

### Enable debug mode
Uncomment these lines at the top of `contact-handler.php` to see error messages:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

**Remember to disable debug mode in production!**

### Common server mail issues
- **Shared hosting**: Contact your hosting provider about mail configuration
- **VPS/Dedicated**: You may need to configure postfix or sendmail
- **Alternative**: Consider using SMTP with PHPMailer for better reliability

## Alternative: Using SMTP (More Reliable)

For better email delivery, you can modify the script to use SMTP with PHPMailer:

1. Download PHPMailer
2. Replace the `mail()` function with SMTP configuration
3. Use your email provider's SMTP settings

This ensures emails are more likely to be delivered and not marked as spam.

## Support

If you need help with server configuration or the script isn't working:
1. Check your server's PHP error logs
2. Verify mail() function is enabled: `php -m | grep mail`
3. Test with a simple PHP mail script first

The script is designed to be simple and work on most standard PHP hosting environments. 