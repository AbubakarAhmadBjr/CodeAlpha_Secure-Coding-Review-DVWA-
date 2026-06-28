# Secure Coding Review: Fully Hardened DVWA

## Project Overview
This repository contains a **fully hardened version of DVWA (Damn Vulnerable Web Application)** after performing a comprehensive secure code review. All vulnerabilities reported by Semgrep (28 findings) have been identified and fixed across all security levels (Low, Medium, High, and Impossible). The application is now resistant to:

- SQL Injection (CWE-89)
- Command Injection (CWE-78)
- Cross-Site Scripting (XSS) (CWE-79)
- Cross-Site Request Forgery (CSRF) (CWE-352)
- File Inclusion / Path Traversal (CWE-98)
- Insecure File Upload (CWE-434)
- Weak Cryptography (MD5) (CWE-327)
- Insecure Session Cookies (CWE-384)

## Vulnerabilities Fixed (Full List)
| Module | Vulnerability | Fix Applied |
|--------|---------------|-------------|
| `sqli/` | SQL Injection | Prepared statements + integer validation |
| `exec/` | Command Injection | IP validation + escapeshellarg |
| `upload/` | Insecure File Upload | Extension/MIME/size checks + random rename |
| `xss_r/` | Reflected XSS | htmlspecialchars + CSP headers |
| `csrf/` | CSRF + MD5 | CSRF tokens + password_hash (Argon2id) |
| `fi/` | File Inclusion (LFI) | Whitelist pages |
| `api/src/HealthController.php` | Command Injection | IP validation + escapeshellarg |
| `bac/` | SQL Injection | Prepared statements |
| `brute/` | SQL Injection | Prepared statements |
| `csp/source/jsonp.php` | XSS (JSONP) | htmlspecialchars on callback |
| `sqli_blind/` | Blind SQL Injection | Prepared statements + integer validation |
| `view_help.php` | File Inclusion | Regex validation for $id |
| `view_source.php` | File Inclusion | Regex validation for $id, $security |
| `view_source_all.php` | File Inclusion | Regex validation for $id |
| `dvwa/includes/dvwaPage.inc.php` | Insecure Cookies | HttpOnly, SameSite=Strict |

## Tools Used
- **Semgrep** – SAST scanning (OWASP Top 10 ruleset)
- **PHP_CodeSniffer + Security standard** – PHP security audit
- **Manual testing** – Exploit validation

## Verification Results
- **Semgrep:** 0 blocking findings after fixes.
- **PHPCS:** Clean scan (no security errors).
- **Manual Exploits:** All original payloads are blocked.

## How to Run
1. Clone this repository into XAMPP's `htdocs/` folder.
2. Configure `config/config.inc.php` with database credentials.
3. Access `http://localhost/dmva/setup.php` to create the database.
4. Login with `admin` / `password`.

## Secure Coding Recommendations
- Always use **prepared statements** for database queries.
- Validate inputs with **whitelists** (not blacklists).
- Encode all dynamic output with `htmlspecialchars()`.
- Use **CSRF tokens** for state-changing requests.
- Store passwords with `password_hash()` (Argon2id/bcrypt).
- Set secure cookie flags: `HttpOnly`, `SameSite=Strict`, `Secure`.

## References
- OWASP Top 10 (2021)
- CWE/SANS Top 25
- OWASP ASVS v4.0

---

**Author:** Abubakar Ahmad  
**Date:** June 28, 2026  
**Project:** CodeAlpha Task 3 – Secure Coding Review
