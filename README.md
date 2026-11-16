# CDBL & QOffice API Testing Platform

This directory contains the standalone mock server, sample payloads, authentic PDF, and documentation for testing the complete Online BO Auto-Pilot and CDBL synchronization pipeline without hitting live CDBL services.

---

## 📌 Direct Access & URL Formats

Access the testing platform directly from your browser, curl, or application:

- **Dashboard (Interactive UI):** `http://localhost/online_bo/data/docs/cdbl/cdbl_api.php`
- **Plain Text API Docs:** `http://localhost/online_bo/data/docs/cdbl/cdbl_api.php?raw=1`
- **Zero Laravel Dependency:** Works directly as a standalone PHP file without touching `www/routes/web.php`.
- **Direct Subpath Routing:** All endpoints support direct REST path append:
  - `http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/{rest_path}`
  - Example: `http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/v1/cdbl/download/pdf/BO_Ack_1604940078052399_M9501.pdf`
  - *(Query parameter format `?endpoint=/...` is also supported as a fallback).*

---

## 🎯 Quick Test Client Codes (Defined at Top of `cdbl_api.php`)

You can change these client codes at **lines 12–15** of [`cdbl_api.php`](file:///var/www/html/online_bo/data/docs/cdbl/cdbl_api.php) to simulate different outcomes across **ALL** APIs without running an upload first:

```php
// data/docs/cdbl/cdbl_api.php (Lines 12-15)
define('TEST_CLIENT_SUCCESS', 'M9501');     // Always returns SUCCESS / COMPLETED across all APIs
define('TEST_CLIENT_FAILED',  'FAIL99');    // Always returns 404 / 400 REJECTED / FAILED across all APIs
define('TEST_CLIENT_PENDING', 'PEND01');    // Always returns 200 QUEUED / PENDING status across all APIs
define('DEFAULT_BOID',        '1604940078052399'); // Simulated 16-digit BOID for success client
```

### Behavior Across Endpoints by Client Code:

| Endpoint | `TEST_CLIENT_SUCCESS` (`M9501`) | `TEST_CLIENT_FAILED` (`FAIL99`) | `TEST_CLIENT_PENDING` (`PEND01`) |
| :--- | :--- | :--- | :--- |
| `GET /bo/ack/{code}` | **HTTP 200** `COMPLETED` + BOID + PDF URL | **HTTP 404** Not Found in CDBL | **HTTP 200** `QUEUED` (boid: null) |
| `POST /upload` (Type 03/05/04) | **HTTP 200** `QUEUED` + Callback job spawned | **HTTP 400** `REJECTED` by clearinghouse | **HTTP 200** `QUEUED` |
| `POST /bo-account/clients` (QOffice) | **HTTP 200** Registered + Ledger Opened | **HTTP 400** `FAILED` to open ledger | **HTTP 200** Registered |
| `GET /download/pdf/{filename}` | **HTTP 200** Valid PDF binary stream | **HTTP 404** PDF Not Found | **HTTP 200** Valid PDF binary |

---

## 📂 Directory Files & Purpose

| File | Purpose |
| :--- | :--- |
| `cdbl_api.php` | Main mock server & real-time testing dashboard. Handles inquiry, uploads, asynchronous webhook dispatch, and QOffice sync. |
| `cdbl_ack_sample.pdf` | Authentic binary CDBL BO Acknowledgement PDF returned on approval. |
| `sample_setup_upload_response.json` | Sample immediate response from `POST /v1/cdbl/upload` (Type 03/05). |
| `sample_ack_webhook_payload.json` | Sample asynchronous webhook callback payload sent to Online BO with approved BOID and Base64 PDF. |
| `sample_nominee_webhook_payload.json` | Sample asynchronous webhook callback payload for Type 04 Nominee upload. |
| `sample_qoffice_sync_response.json` | Sample response from QOffice Back-Office ERP client registration. |
| `sample_status_inquiry_queued.json` | Sample response from `GET /v1/cdbl/bo/ack/{code}` when status is `QUEUED`. |
| `sample_status_inquiry_completed.json` | Sample response from `GET /v1/cdbl/bo/ack/{code}` when status is `COMPLETED`. |
| `CDBL Sync API.md` | Original architectural specification of the CDBL synchronization pipeline. |
| `README.md` | This complete reference manual. |

---

## 🔄 Complete Execution Pipeline

```
[ Step 1: Pre-Flight Inquiry ]
  GET http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/v1/cdbl/bo/ack/{clientCode}
  └─ Checks if client code already exists or is queued. Returns 404 if safe to submit.

[ Step 2: Setup VEDAS Upload ]
  POST http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/v1/cdbl/upload
  └─ Uploads Type 03 (New BO) or Type 05 (Link BO) ZIP file. Returns 200 QUEUED immediately.

[ Step 3: Delayed Asynchronous Webhook (BO Opened) ]
  POST {callbackUrl} (e.g., http://localhost:8111/api/cdbl/callback)
  └─ Dispatches BO_SETUP_COMPLETED with newly assigned 16-digit BOID and authentic Base64 PDF.
  └─ Online BO saves PDF, activates account (bo_status = Active), and assigns BOID.

[ Step 4: QOffice ERP Back-Office Sync ]
  POST http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/bo-account/clients
  └─ Online BO provisions client ledger, uploading photos, signatures, and bank details.

[ Step 5: Nominee VEDAS Upload ]
  POST http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/v1/cdbl/upload (uploadType=04)
  └─ Uploads Type 04 Nominee VEDAS archive with newly assigned BOID. Returns 200 QUEUED.

[ Step 6: Delayed Asynchronous Nominee Webhook ]
  POST {callbackUrl} (e.g., http://localhost:8111/api/cdbl/callback)
  └─ Dispatches NOMINEE_COMPLETED callback to finalize nominee status in Online BO.
```

---

## ⚙️ How to Configure Online BO

Update your `.env` in Online BO to route all CDBL and QOffice requests to `cdbl_api.php`:

```ini
# CDBL Gateway Mock Endpoint (Direct subpath)
CDBL_BASE_URL=http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/v1/cdbl

# QOffice ERP Client Sync Endpoint
QOFFICE_CLIENT_URL=http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/bo-account/clients
```

---

## 📡 API Endpoints & Sample Payloads

### 1. CDBL Status Inquiry
- **Endpoint:** `GET http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/v1/cdbl/bo/ack/{clientCode}`
- **Headers:**
  ```http
  Authorization: Bearer <TOKEN>
  Accept: application/json
  ```
- **Sample Request:**
  ```bash
  curl -X GET "http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/v1/cdbl/bo/ack/M9501" \
    -H "Authorization: Bearer test-token" \
    -H "Accept: application/json"
  ```
- **Response when Client NOT uploaded yet (HTTP 404 - Safe to Submit):**
  ```json
  {
    "success": false,
    "error": "Client code M9501 not found in CDBL."
  }
  ```
- **Response when Client is QUEUED (HTTP 200):**
  ```json
  {
    "success": true,
    "data": {
      "status": "QUEUED",
      "dpInternalRefNo": "M9501",
      "boid": null,
      "setupDate": "2026-11-16",
      "pdfDownloadUrl": null
    }
  }
  ```
- **Response when Client is COMPLETED (HTTP 200):**
  ```json
  {
    "success": true,
    "data": {
      "status": "COMPLETED",
      "dpInternalRefNo": "M9501",
      "boid": "1604940078052399",
      "setupDate": "2026-11-16",
      "pdfDownloadUrl": "http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/v1/cdbl/download/pdf/BO_Ack_1604940078052399_M9501.pdf"
    }
  }
  ```

---

### 2. CDBL Setup VEDAS Upload (Type 03 / 05)
- **Endpoint:** `POST http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/v1/cdbl/upload`
- **Headers:**
  ```http
  Authorization: Bearer <TOKEN>
  Content-Type: multipart/form-data
  ```
- **Sample Request:**
  ```bash
  curl -X POST "http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/v1/cdbl/upload" \
    -H "Authorization: Bearer test-token" \
    -F "file=@vedas_M9501_16112026.01.zip" \
    -F "uploadType=03" \
    -F "dpInternalRefNo=M9501" \
    -F "callbackUrl=http://localhost:8111/api/cdbl/callback"
  ```
- **Immediate Response (HTTP 200):**
  ```json
  {
    "success": true,
    "status": "QUEUED",
    "message": "File accepted for processing and queued for CDBL submission.",
    "data": {
      "uploadRecordId": 4821,
      "uploadId": 4821,
      "queuedJobId": 9812,
      "mainFileName": "033110001.16112026.01",
      "fileName": "033110001.16112026.01",
      "dpInternalRefNo": "M9501",
      "callbackUrl": "http://localhost:8111/api/cdbl/callback",
      "simulated_delay": "3s"
    }
  }
  ```

---

### 3. Asynchronous Webhook Callback (BO Opened)
- **Dispatched To:** `{callbackUrl}` (e.g. `http://localhost:8111/api/cdbl/callback`)
- **Headers:**
  ```http
  Content-Type: application/json
  ```
- **Dispatched JSON Payload:**
  ```json
  {
    "event": "BO_SETUP_COMPLETED",
    "success": true,
    "dpInternalRefNo": "M9501",
    "client_code": "M9501",
    "boid": "1604940078052399",
    "applicantName": "CDBL TEST HOLDER",
    "setupDate": "2026-11-16",
    "ackPdfFileName": "BO_Ack_1604940078052399_M9501.pdf",
    "ackPdfBase64": "JVBERi0xLjQKMSAwIG9iago8PC9UeXBlL0ZvbnQvU3VidHlwZS9UeXBlMS9CYXNl..."
  }
  ```
- **Expected Online BO Handler Response (HTTP 200):**
  ```json
  {
    "success": true,
    "message": "CDBL callback processed and BO account activated successfully.",
    "data": {
      "client_code": "M9501",
      "boid": "1604940078052399",
      "setup_date": "2026-11-16",
      "pdf_file": "uploads/bo_cdbl_approval_file/BO_Ack_1604940078052399_M9501.pdf"
    }
  }
  ```

---

### 4. QOffice Back-Office ERP Client Sync
- **Endpoint:** `POST http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/bo-account/clients`
- **Headers:**
  ```http
  Authorization: Bearer <TOKEN>
  Content-Type: multipart/form-data
  ```
- **Form Fields:** `client_code`, `boid`, `bo_type`, `first_name`, `father_name`, `mother_name`, `dob`, `gender`, `nid`, `email`, `mobile`, `bank_name`, `bank_account_no`, `routing_no`, `nominees`, `image_1`, `signature_1`, `nominee_1_image`, `nominee_1_signature`, `signature_card`.
- **Sample Request:**
  ```bash
  curl -X POST "http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/bo-account/clients" \
    -H "Authorization: Bearer test-token" \
    -F "client_code=M9501" \
    -F "boid=1604940078052399" \
    -F "bo_type=New" \
    -F "first_name=Rahim" \
    -F "father_name=Karim" \
    -F "mother_name=Fatima" \
    -F "dob=1990-05-15" \
    -F "gender=Male" \
    -F "nid=19901234567890" \
    -F "email=rahim@example.com" \
    -F "mobile=01700000000" \
    -F "bank_name=Dutch-Bangla Bank Ltd." \
    -F "bank_account_no=1011510001234" \
    -F "routing_no=090261726" \
    -F "image_1=@applicant.jpg" \
    -F "signature_1=@signature.jpg"
  ```
- **Response (HTTP 200):**
  ```json
  {
    "status": true,
    "success": true,
    "message": "Client M9501 registered and ledger opened in QOffice Back-Office ERP successfully.",
    "client_id": 4336,
    "client_code": "M9501",
    "boid": "1604940078052399",
    "files_saved": 2,
    "timestamp": "2026-11-16 14:10:00"
  }
  ```

---

### 5. CDBL Nominee VEDAS Upload (Type 04)
- **Endpoint:** `POST http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/v1/cdbl/upload`
- **Sample Request:**
  ```bash
  curl -X POST "http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/v1/cdbl/upload" \
    -H "Authorization: Bearer test-token" \
    -F "file=@vedas_nominee_M9501_16112026.01.zip" \
    -F "uploadType=04" \
    -F "dpInternalRefNo=M9501" \
    -F "callbackUrl=http://localhost:8111/api/cdbl/callback"
  ```
- **Immediate Response (HTTP 200):**
  ```json
  {
    "success": true,
    "status": "QUEUED",
    "message": "File accepted for processing and queued for CDBL submission.",
    "data": {
      "uploadRecordId": 7666,
      "uploadId": 7666,
      "queuedJobId": 66086,
      "mainFileName": "043110001.16112026.01",
      "fileName": "043110001.16112026.01",
      "dpInternalRefNo": "M9501",
      "callbackUrl": "http://localhost:8111/api/cdbl/callback",
      "simulated_delay": "2s"
    }
  }
  ```

---

### 6. Asynchronous Nominee Webhook Callback
- **Dispatched To:** `{callbackUrl}`
- **Dispatched JSON Payload:**
  ```json
  {
    "uploadCode": "04",
    "event": "NOMINEE_COMPLETED",
    "success": true,
    "dpInternalRefNo": "M9501",
    "client_code": "M9501",
    "uploadId": 7666,
    "fileName": "043110001.16112026.01",
    "rawResponse": {
      "success": true,
      "message": "Nominee processed successfully by CDBL"
    }
  }
  ```

---

### 7. ACK PDF Download & Preview
- **Subpath Download:** `http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/v1/cdbl/download/pdf/BO_Ack_1604940078052399_M9501.pdf`
- **Direct Preview:** `http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/sample-ack-pdf`
- **Physical Sample File:** [`data/docs/cdbl/cdbl_ack_sample.pdf`](file:///var/www/html/online_bo/data/docs/cdbl/cdbl_ack_sample.pdf)
- **Sample Request:**
  ```bash
  curl -X GET "http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/v1/cdbl/download/pdf/BO_Ack_1604940078052399_M9501.pdf" \
    --output "BO_Ack_1604940078052399_M9501.pdf"
  ```
