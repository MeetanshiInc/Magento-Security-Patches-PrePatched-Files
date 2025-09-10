# Magento / Adobe Commerce Composer Patch [APSB25-88]

This repository provides security patches for **Adobe Commerce on-premises** and **Magento Open Source**.

## Patch: APSB25-88

Released on **September 9, 2025**, APSB25-88 fixes **CVE-2025-54236**, a critical unauthenticated attack vulnerability.  
Apply this patch immediately to secure your store.  

## How to Apply

### 1. Upload the Patch  
Place the `.patch` file in your Magento root directory.  

Example:  

```text
/var/www/html/magento2
├── app
├── bin
├── composer.json
├── APSB25-88.composer.patch
```

### 2. Run the Command  
From the root directory, execute:  

```bash
patch -p1 < APSB25-88.composer.patch
```

If that fails, try:  

```bash
patch -p2 < APSB25-88.composer.patch
```

### 3. Clear Cache  
After applying the patch, clear the cache.  

Via CLI:  

```bash
bin/magento cache:flush
```

Or in the Admin:  

```text
System > Cache Management > Flush Magento Cache
```

---

## Verification  

- Confirm the targeted files were modified.  
- Test your Magento store for normal operation.  

## Notes  

- Always back up your **codebase** and **database** before applying patches.  
- Apply patches on a **staging environment** first.  
- Stay up to date with Adobe security bulletins.  

---

## References  

- [Adobe Security Bulletin APSB25-88](https://helpx.adobe.com/security.html)  
- [Magento DevDocs: Apply patches](https://developer.adobe.com/commerce)
