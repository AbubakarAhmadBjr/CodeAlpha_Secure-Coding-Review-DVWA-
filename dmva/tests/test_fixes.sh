#!/bin/bash
echo "=== Testing DVWA Security Fixes ==="

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m'

# Function to test a URL
test_url() {
    url=$1
    expected_text=$2
    response=$(curl -s -L "$url")
    if echo "$response" | grep -q "$expected_text"; then
        echo -e "${GREEN}✓ PASS${NC}: $url"
    else
        echo -e "${RED}✗ FAIL${NC}: $url (expected '$expected_text')"
    fi
}

echo "1. SQL Injection (Low) - should reject payload"
test_url "http://localhost/dmva/vulnerabilities/sqli/?id=1%27%20OR%20%271%27=%271&Submit=Submit" "Invalid ID"

echo "2. Command Injection (Low) - should reject"
test_url "http://localhost/dmva/vulnerabilities/exec/?ip=127.0.0.1%3B%20dir&Submit=Submit" "Invalid IP"

echo "3. XSS (Low) - should encode script"
test_url "http://localhost/dmva/vulnerabilities/xss_r/?name=<script>alert(1)</script>" "&lt;script&gt;"

echo "4. CSRF attempt - should reject (requires POST, not tested via curl easily)"

echo "5. LFI attempt - should reject"
test_url "http://localhost/dmva/vulnerabilities/fi/?page=../../../../etc/passwd" "Invalid page"

echo "6. Session cookie flags (check manually)"