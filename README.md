# Pineapple Pager Evil Portal Templates

This repository contains a set of captive portal templates for the WiFi Pineapple Pager and Evil Portal workflow. The active project has been consolidated around the `Default` portal and includes a Google-inspired login page for local testing and demonstration.

## Repository layout

- `Default/` — the active portal used for deployment
- `Default/index.php` — captive portal page
- `Default/MyPortal.php` — authorization handling and demo logging
- `Default/helper.php` — metadata helpers and Pineapple-safe logging logic
- `assets/` — CSS, JavaScript, fonts, and branding assets used by the default page

## Deployment

1. Copy the `Default` folder to the Pineapple portal directory used by your Evil Portal installation.
2. Start the Evil Portal service on the Pineapple Pager.
3. Enable the portal in the Pineapple web interface.
4. Connect a test client to the access point and open the captive portal page.

## Demo configuration

The default portal is configured for a safe local test mode. 

Logging is written to a writable temp directory in root/logs/evillogs.log

## Quick Pineapple fix

If your Pineapple is logging repeated `open() "/www/connecttest.txt" failed` errors, run this on the device:

```bash
mkdir -p /www
cat > /www/connecttest.txt <<'EOF'
OK
EOF

cat > /www/generate_204 <<'EOF'
OK
EOF

chmod 644 /www/connecttest.txt /www/generate_204
nginx -t
/etc/init.d/nginx reload 2>/dev/null || service nginx reload 2>/dev/null || kill -HUP $(pgrep nginx)
```

This satisfies the captive-portal checks that clients make while trying to determine whether they are behind a login page.

## Pineapple-side troubleshooting

A common issue on the WiFi Pineapple is that client devices keep probing captive-portal URLs such as `/connecttest.txt`, `/generate_204`, and `/hotspot-detect.html`. If the Pineapple root is `/www` and those files are missing, nginx will log repeated errors like:

```text
open() "/www/connecttest.txt" failed
```

This is usually not a PHP application error; it is the client checking whether it is behind a captive portal.

### Quick diagnosis on the Pineapple

```bash
echo "===== PINEAPPLE CAPTIVE PORTAL DIAGNOSIS ====="
echo
uname -a
echo
id
echo
which nginx || command -v nginx || echo "nginx not found"
nginx -v 2>&1 || true
echo
ls -ld /www 2>/dev/null || echo "No /www"
ls -l /www 2>/dev/null | head -50 || true
echo
for d in /www /var/www /srv/www /tmp/www /root/www /opt/www; do
  for f in "$d/connecttest.txt" "$d/generate_204" "$d/generate_204.html" "$d/hotspot-detect.html"; do
    if [ -e "$f" ]; then
      echo "FOUND: $f"
      ls -l "$f"
    else
      echo "MISSING: $f"
    fi
  done
done
echo
sed -n '1,220p' /etc/nginx/nginx.conf 2>/dev/null || echo "No nginx.conf"
echo
ps w 2>/dev/null | grep -E 'nginx|apache|lighttpd|php-fpm' | grep -v grep || echo "No web server process found"
echo
===== END =====
```

### Fix on the Pineapple

Create the missing captive-portal probe files in the web root and reload nginx:

```bash
mkdir -p /www
cat > /www/connecttest.txt <<'EOF'
OK
EOF

cat > /www/generate_204 <<'EOF'
OK
EOF

chmod 644 /www/connecttest.txt /www/generate_204

nginx -t
/etc/init.d/nginx reload 2>/dev/null || service nginx reload 2>/dev/null || kill -HUP $(pgrep nginx)
```

### Other Pineapple-side tweaks that matter

- Confirm nginx is serving `/www` as the document root. The standard config usually contains:

```nginx
root /www;
```

- Make sure the portal page is correctly linked from the Pineapple portal directory. The Pineapple usually exposes the portal via `/www/index.php` and symlinks like:

```bash
ls -l /www
```

- Keep the portal file names consistent with the code that the device checks for, especially `connecttest.txt`, `generate_204.html`, and `hotspot-detect.html`.

- If the Pineapple is using its built-in Evil Portal service, make sure the portal is enabled in the UI and the active portal directory is the one being served by the web root.

- If the symptom persists, check the actual `/etc/nginx/nginx.conf` and confirm the root has not been changed away from `/www`.

## Notes

- This project is intended for authorized lab testing and controlled local development.
- Use only on networks you own or are explicitly authorized to test.
- The default portal is designed to be easy to customize for different captive portal layouts.

## License

This project is provided as-is for educational and authorized testing use.

## Disclaimer

Use this project only in environments where you have explicit permission to test and deploy captive portal functionality.
