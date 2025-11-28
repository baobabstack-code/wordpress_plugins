# Deployment Guide

This WordPress plugin is configured to automatically deploy to the sandbox site at https://baobabstack.sblik.com/ using GitHub Actions.

## Deployment Methods

### 1. Conditional Deployment (Recommended)
Add `[deploy]` to your commit message to trigger deployment:
```bash
git commit -m "Add new feature [deploy]"
git push
```

### 2. Manual Deployment
Trigger deployment manually from GitHub:
1. Go to the **Actions** tab in your GitHub repository
2. Select the "Deploy to Sandbox" workflow
3. Click "Run workflow"
4. Choose the branch and click "Run workflow"

### 3. Automatic Deployment
The workflow will also run on every push to `master`, but will only deploy if the commit message contains `[deploy]`.

## Required Secrets

Configure these secrets in your GitHub repository settings (**Settings > Secrets and variables > Actions**):

| Secret Name | Description | Example |
|------------|-------------|---------|
| `FTP_SERVER` | FTP server hostname | `ftp.baobabstack.sblik.com` |
| `FTP_USERNAME` | FTP username | `your-ftp-username` |
| `FTP_PASSWORD` | FTP password | `your-ftp-password` |
| `FTP_SERVER_DIR` | Server directory path | `/public_html/wp-content/plugins/my-custom-plugin/` |

## Setting Up Secrets

1. Go to your repository on GitHub
2. Click **Settings > Secrets and variables > Actions**
3. Click **New repository secret**
4. Add each secret listed above

## Workflow Details

- **Trigger**: Push to master with `[deploy]` in commit message, or manual trigger
- **PHP Version**: 7.4
- **Composer**: Installs production dependencies only
- **Excluded Files**: Git files, node_modules, tests, IDE files, documentation

## Deployment Process

1. Code is checked out from the repository
2. PHP 7.4 is set up
3. Composer dependencies are installed (production only)
4. Files are uploaded to the sandbox server via FTP
5. Excluded files (tests, git files, etc.) are not deployed

## Troubleshooting

- **Deployment not running?** Check that your commit message includes `[deploy]`
- **FTP connection failed?** Verify FTP credentials in repository secrets
- **Permission errors?** Ensure FTP user has write permissions to the target directory
- **View logs**: Go to Actions tab > Select the workflow run > View logs

## Alternative: SSH Deployment

If you prefer SSH over FTP, you can modify `.github/workflows/deploy.yml` to use SSH deployment action instead. Replace the FTP Deploy Action with an SSH-based action like `appleboy/ssh-action`.

## Using the Event Ticket PDF Template

1. Activate the standalone `Gravity PDF - Event Ticket Template` plugin located in `event-ticket-pdf/`.
2. Ensure the [Gravity PDF](https://gravitypdf.com/) plugin is installed and active alongside Gravity Forms.
3. In the form's **Settings > PDF**, create a new PDF feed and pick **Event Ticket (SimplyBiz)** from the template list.
4. For automatic field detection, label your form fields: **Event Name**, **Registrant Name**, **Number of Attendees**, **Add-ons** (checkbox/multi-select recommended), and optionally **Company Logo** (file upload). The template will gracefully fall back if a value is missing.
5. The QR code uses the Gravity Forms `{entry:url}` merge tag; ensure the form confirmations/permissions allow viewing the entry if you want the QR to be scannable.
