# APSB25-50 Patch Installation

This guide explains how to apply Magento 2 core file changes (`Category.php` and `Filter.php`) as a patch using Composer, without directly modifying files in the `vendor/` directory.

## What You Received

You have two modified core Magento files:

* `vendor/magento/module-catalog/Helper/Category.php`
* `vendor/magento/module-catalog/Model/Layer/Filter/Filter.php`

These are part of a pre-release patch or security update. **Do not copy them directly into the `vendor/` directory**, as it is managed by Composer and any manual changes will be lost on update.

## Proper Way to Apply Magento Core File Fixes

### Step 1: Generate a Patch File

Use Git or `diff` to generate a `.patch` file that includes changes from both files.

#### Option A: Using Git (recommended if you're using version control)

1. Replace the original files in your Magento installation with the patched versions.
2. From the Magento root directory, run:

   ```bash
   git diff > patches/catalog-security-fix.patch
   ```

#### Option B: Using `diff` (if you have original and patched versions separately)

```bash
diff -Naur original/Category.php patched/Category.php > catalog-security-fix.patch
diff -Naur original/Filter.php patched/Filter.php >> catalog-security-fix.patch
```

Place the final `catalog-security-fix.patch` file in a new folder inside your Magento root directory, e.g., `patches/`.

### Step 2: Configure `composer.json`

Install the Composer patch plugin if not already installed:

```bash
composer require cweagans/composer-patches
```

Then, in your `composer.json`, add the patch reference under the `extra` section:

```json
"extra": {
  "patches": {
    "magento/module-catalog": {
      "Security fix for Category & Filter": "patches/catalog-security-fix.patch"
    }
  }
}
```

### Step 3: Apply the Patch

Run the following command to apply the patch:

```bash
composer install
```

You should see output confirming that the patch was applied to `magento/module-catalog`.

## Notes

* Do not manually edit files in the `vendor/` directory.
* Using Composer patches ensures that your changes are preserved through future Magento upgrades.
* You can commit the `.patch` file and the `composer.json` changes to version control for team collaboration and deployment.

---








Here's a detailed patching guide to [Magento Open Source Security Update APSB25-50](https://meetanshi.com/blog/apsb25-50-security-patches-for-magento/).

We can also help you install the Patches you want, visit our [Magento Security Patches Installation Service](https://meetanshi.com/magento-security-patches-installation-service.html).
