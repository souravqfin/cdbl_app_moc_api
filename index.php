<?php
/**
 * CDBL & QOffice API Testing Platform
 * Direct Route: http://localhost/online_bo/data/docs/cdbl/cdbl_api.php/{rest_path}
 * Full documentation & samples: README.md
 */

// ==============================================================================
// 1. TEST CLIENT CODES CONFIGURATION (MODIFY HERE TO CHANGE API BEHAVIOR)
// Change these client codes to easily test SUCCESS, FAILED, or PENDING across ALL APIs
// ==============================================================================
define('TEST_CLIENT_SUCCESS', 'S1001');     // Client code that returns SUCCESS across all APIs
define('TEST_CLIENT_FAILED',  'F1001');    // Client code that returns FAILED / REJECTED / 404
define('TEST_CLIENT_PENDING', 'P1001');    // Client code that returns QUEUED / PENDING status
define('DEFAULT_BOID',        '1600000000000001'); // Simulated 16-digit BOID for success client

// ------------------------------------------------------------------------------
// Embedded Authentic CDBL Acknowledgement PDF (Base64)
// This eliminates any requirement for a separate external .pdf file on disk.
// ------------------------------------------------------------------------------
define('AUTHENTIC_CDBL_ACK_PDF_BASE64', 'JVBERi0xLjQKJeLjz9MKMSAwIG9iago8PC9UeXBlL0ZvbnQvU3VidHlwZS9UeXBlMS9CYXNlRm9udC9IZWx2ZXRpY2EtQm9sZC9FbmNvZGluZy9XaW5BbnNpRW5jb2Rpbmc+PgplbmRvYmoKMiAwIG9iago8PC9Qcm9kdWNlcihpVGV4dFNoYXJwkiA1LjUuMTIgqTIwMDAtMjAxNyBpVGV4dCBHcm91cCBOViBcKEFHUEwtdmVyc2lvblwpOyBtb2RpZmllZCB1c2luZyBpVGV4dFNoYXJwkiA1LjUuMTIgqTIwMDAtMjAxNyBpVGV4dCBHcm91cCBOViBcKEFHUEwtdmVyc2lvblwpKS9DcmVhdGlvbkRhdGUoRDoyMDI2MDEyMTE3MDA0OCswNicwMCcpL01vZERhdGUoRDoyMDI2MDEyMTE3MDA0OCswNicwMCcpPj4KZW5kb2JqCjMgMCBvYmoKPDwvTGVuZ3RoIDEwL0ZpbHRlci9GbGF0ZURlY29kZT4+c3RyZWFtCnicK+QCAADuAHwKZW5kc3RyZWFtCmVuZG9iago0IDAgb2JqCjw8L0xlbmd0aCA2My9GaWx0ZXIvRmxhdGVEZWNvZGU+PnN0cmVhbQp4nFMI5CoEQqcQLkMFAyA0VDA1NVUwMlEIyeXSj8g0ULBUCEnjMgDLFaVzaRhphmQBuelcriFcgUAIAKLmDPkKZW5kc3RyZWFtCmVuZG9iago1IDAgb2JqCjw8L0xlbmd0aCAxMC9GaWx0ZXIvRmxhdGVEZWNvZGU+PnN0cmVhbQp4nCvkAgAA7gB8CmVuZHN0cmVhbQplbmRvYmoKNiAwIG9iago8PC9MZW5ndGggNjMvRmlsdGVyL0ZsYXRlRGVjb2RlPj5zdHJlYW0KeJxTCOQqBEKnEC5DBQMgNFQwNTVVMDJRCMnl0o/INFSwVAhJ4zIAyxWlc2kYaYZkAbnpXK4hXIFACACjBwz6CmVuZHN0cmVhbQplbmRvYmoKNyAwIG9iago8PC9UeXBlL0ZvbnQvU3VidHlwZS9UeXBlMS9CYXNlRm9udC9IZWx2ZXRpY2EtQm9sZC9FbmNvZGluZy9XaW5BbnNpRW5jb2Rpbmc+PgplbmRvYmoKOCAwIG9iago8PC9UeXBlL0ZvbnQvU3VidHlwZS9UeXBlMS9CYXNlRm9udC9IZWx2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NTk1IDg0Ml0vUmVzb3VyY2VzPDwvRm9udDw8L0YxIDcgMCBSL0YyIDggMCBSL1hpMSAxIDAgUj4+Pj4vQ29udGVudHNbNSAwIFIgMTMgMCBSIDYgMCBSXS9QYXJlbnQgMTAgMCBSPj4KZW5kb2JqCjE0IDAgb2JqCjw8L1R5cGUvQ2F0YWxvZy9QYWdlcyAxMCAwIFI+PgplbmRvYmoKeHJlZgowIDE1CjAwMDAwMDAwMDAgNjU1MzUgZiAKMDAwMDAwMDAxNSAwMDAwMCBuIAowMDAwMDAwMTA4IDAwMDAwIG4gCjAwMDAwMDAzNDkgMDAwMDAgbiAKMDAwMDAwMDQyNSAwMDAwMCBuIAowMDAwMDAwNTU0IDAwMDAwIG4gCjAwMDAwMDA2MzAgMDAwMDAgbiAKMDAwMDAwMDc1OSAwMDAwMCBuIAowMDAwMDAwODUyIDAwMDAwIG4gCjAwMDAwMDA5NDAgMDAwMDAgbiAKMDAwMDAwMTg3NSAwMDAwMCBuIAowMDAwMDAxOTM1IDAwMDAwIG4gCjAwMDAwMDM1NjUgMDAwMDAgbiAKMDAwMDAwMjA4MSAwMDAwMCBuIAowMDAwMDAzNzEyIDAwMDAwIG4gCnRyYWlsZXIKPDwvU2l6ZSAxNS9Sb290IDE0IDAgUi9JbmZvIDIgMCBSL0lEIFs8MWI4MDdmZmJjMTliY2I3NGMyMzk4YWViOGE0NzQxYmI+PDk5YWUwNjQ2MjFkNWU2YzY1MmMyYjZjMGM4Mjk0NGI0Pl0+PgolaVRleHQtNS41LjEyCnN0YXJ0eHJlZgozNzU5CiUlRU9GCg==');

// ------------------------------------------------------------------------------
// Embedded Sample Response & Payload Data Templates
// ------------------------------------------------------------------------------
class CdblMockTemplates {
    public static function getSetupUploadResponse(string $clientCode = TEST_CLIENT_SUCCESS): array {
        return [
            'success' => true,
            'status'  => 'QUEUED',
            'message' => 'File accepted for processing and queued for CDBL submission.',
            'data'    => [
                'uploadRecordId'  => rand(4000, 9999),
                'uploadId'        => rand(4000, 9999),
                'queuedJobId'     => rand(10000, 99999),
                'mainFileName'    => '033110001.' . date('dmY') . '.01',
                'fileName'        => '033110001.' . date('dmY') . '.01',
                'dpInternalRefNo' => $clientCode,
                'callbackUrl'     => 'http://localhost:8111/api/cdbl/callback',
                'simulated_delay' => '3s',
            ],
        ];
    }

    public static function getAckWebhookPayload(string $clientCode = TEST_CLIENT_SUCCESS, ?string $boid = null): array {
        $boid = $boid ?: DEFAULT_BOID;
        return [
            'event'          => 'BO_SETUP_COMPLETED',
            'success'        => true,
            'dpInternalRefNo'=> $clientCode,
            'client_code'    => $clientCode,
            'boid'           => $boid,
            'applicantName'  => 'CDBL TEST HOLDER',
            'setupDate'      => date('Y-m-d'),
            'ackPdfFileName' => "BO_Ack_{$boid}_{$clientCode}.pdf",
            'ackPdfBase64'   => AUTHENTIC_CDBL_ACK_PDF_BASE64,
        ];
    }

    public static function getNomineeWebhookPayload(string $clientCode = TEST_CLIENT_SUCCESS): array {
        return [
            'uploadCode'      => '04',
            'event'           => 'NOMINEE_COMPLETED',
            'success'         => true,
            'dpInternalRefNo' => $clientCode,
            'client_code'     => $clientCode,
            'uploadId'        => rand(4000, 9999),
            'fileName'        => '043110001.' . date('dmY') . '.01',
            'rawResponse'     => [
                'success' => true,
                'message' => 'Nominee processed successfully by CDBL',
            ],
        ];
    }

    public static function getQofficeSyncResponse(string $clientCode = TEST_CLIENT_SUCCESS, ?string $boid = null): array {
        return [
            'status'      => true,
            'success'     => true,
            'message'     => "Client {$clientCode} registered and ledger opened in QOffice Back-Office ERP successfully.",
            'client_id'   => rand(1000, 9999),
            'client_code' => $clientCode,
            'boid'        => $boid ?: DEFAULT_BOID,
            'files_saved' => 2,
            'timestamp'   => date('Y-m-d H:i:s'),
        ];
    }
}

// ------------------------------------------------------------------------------
// Plain Text API Documentation & Sample Call Generator
// ------------------------------------------------------------------------------
function getApiDocumentationText(string $baseUrl = 'http://localhost/online_bo/data/docs/cdbl/cdbl_api.php'): string {
    $successCode = TEST_CLIENT_SUCCESS;
    $failedCode  = TEST_CLIENT_FAILED;
    $pendingCode = TEST_CLIENT_PENDING;
    $boid        = DEFAULT_BOID;

    return <<<TEXT
==============================================================================
CDBL & QOFFICE MOCK API TESTING PLATFORM - COMPLETE API REFERENCE & SAMPLES
==============================================================================
Direct Route Base URL: {$baseUrl}
Direct Subpath Format: {$baseUrl}/{rest_path}

CONFIGURED TEST CLIENT CODES (Configured at top of cdbl_api.php):
  - SUCCESS Client: {$successCode}  -> Always returns SUCCESS / COMPLETED across all APIs
  - FAILED Client:  {$failedCode} -> Always returns FAILED / REJECTED / 404 across all APIs
  - PENDING Client: {$pendingCode} -> Always returns QUEUED / IN-PROGRESS across all APIs
  - Simulated BOID: {$boid}

WORKFLOW PIPELINE:
  1. Status Inquiry -> 2. Setup VEDAS Upload -> 3. Setup Webhook Callback ->
  4. QOffice ERP Client Sync -> 5. Nominee VEDAS Upload -> 6. Nominee Webhook Callback

------------------------------------------------------------------------------
1. CDBL STATUS INQUIRY
------------------------------------------------------------------------------
Endpoint: GET {$baseUrl}/v1/cdbl/bo/ack/{clientCode}
          (or GET {$baseUrl}/bo/ack/{clientCode} or ?endpoint=/bo/ack/{clientCode})
Headers:  Authorization: Bearer <TOKEN>
          Accept: application/json

A) SUCCESS Client Sample (HTTP 200 COMPLETED):
  curl -X GET "{$baseUrl}/v1/cdbl/bo/ack/{$successCode}" -H "Authorization: Bearer test-token"
Response:
{
  "success": true,
  "data": {
    "status": "COMPLETED",
    "dpInternalRefNo": "{$successCode}",
    "boid": "{$boid}",
    "setupDate": "2026-11-16",
    "pdfDownloadUrl": "{$baseUrl}/v1/cdbl/download/pdf/BO_Ack_{$boid}_{$successCode}.pdf"
  }
}

B) FAILED Client Sample (HTTP 404 - Not Found / Rejected):
  curl -X GET "{$baseUrl}/v1/cdbl/bo/ack/{$failedCode}" -H "Authorization: Bearer test-token"
Response:
{
  "success": false,
  "error": "Client code {$failedCode} not found in CDBL."
}

C) PENDING Client Sample (HTTP 200 QUEUED):
  curl -X GET "{$baseUrl}/v1/cdbl/bo/ack/{$pendingCode}" -H "Authorization: Bearer test-token"
Response:
{
  "success": true,
  "data": {
    "status": "QUEUED",
    "dpInternalRefNo": "{$pendingCode}",
    "boid": null,
    "setupDate": "2026-11-16",
    "pdfDownloadUrl": null
  }
}

------------------------------------------------------------------------------
2. CDBL SETUP VEDAS UPLOAD (Type 03 New BO / Type 05 Link BO)
------------------------------------------------------------------------------
Endpoint: POST {$baseUrl}/v1/cdbl/upload
          (or POST {$baseUrl}/upload or ?endpoint=/upload)
Headers:  Authorization: Bearer <TOKEN>
          Content-Type: multipart/form-data

Sample Request:
  curl -X POST "{$baseUrl}/v1/cdbl/upload" \\
    -H "Authorization: Bearer test-token" \\
    -F "file=@vedas_M9501_16112026.01.zip" \\
    -F "uploadType=03" \\
    -F "dpInternalRefNo=M9501" \\
    -F "callbackUrl=http://localhost:8111/api/cdbl/callback"

Form-Data Fields:
  - file:            (binary) The packaged VEDAS ZIP archive
  - uploadType:      "03" (New BO) or "05" (Link BO)
  - dpInternalRefNo: "M9501" (Client Code)
  - callbackUrl:     "http://localhost:8111/api/cdbl/callback" (Webhook endpoint)

Immediate Response returned to Online BO (HTTP 200):
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

------------------------------------------------------------------------------
3. CDBL WEBHOOK CALLBACK: SETUP ACK (BO Opened - Dispatched after delay)
------------------------------------------------------------------------------
Endpoint: POST {callbackUrl} (e.g., http://localhost:8111/api/cdbl/callback)
Headers:  Content-Type: application/json

Sample Payload dispatched by Mock Server to Online BO:
{
  "event": "BO_SETUP_COMPLETED",
  "success": true,
  "dpInternalRefNo": "M9501",
  "client_code": "M9501",
  "boid": "1604940078052399",
  "applicantName": "CDBL TEST HOLDER",
  "setupDate": "2026-11-16",
  "ackPdfFileName": "BO_Ack_1604940078052399_M9501.pdf",
  "ackPdfBase64": "JVBERi0xLjQKMSAwIG9iago8PC9UeXBlL0ZvbnQvU3VidHlwZS9UeXBlMS9CYXNlRm9udC9IZWx2..."
}

Expected Response from Online BO (HTTP 200):
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

------------------------------------------------------------------------------
4. QOFFICE BACK-OFFICE ERP CLIENT SYNC
------------------------------------------------------------------------------
Endpoint: POST {$baseUrl}/api/v1/system/bo-account/clients
          (or POST {$baseUrl})
Headers:  Authorization: Bearer <TOKEN>
          Content-Type: multipart/form-data

Sample Payload fields sent by Online BO to QOffice:
  - client_code:         "M9501"
  - boid:                "1604940078052399"
  - bo_type:             "New"
  - first_name:          "Rahim"
  - father_name:         "Karim"
  - mother_name:         "Fatima"
  - dob:                 "1990-05-15"
  - gender:              "Male"
  - nid:                 "19901234567890"
  - email:               "rahim@example.com"
  - mobile:              "01700000000"
  - account_type:        "Individual"
  - residential_status:  "Resident"
  - bank_name:           "Dutch-Bangla Bank Ltd."
  - bank_branch:         "Motijheel"
  - bank_account_no:     "1011510001234"
  - routing_no:          "090261726"
  - nominees:            [{"name":"Sakib","relation":"Brother","percentage":100}]
  - image_1:             (multipart file: Applicant Photo)
  - signature_1:         (multipart file: Applicant Signature)
  - nominee_1_image:     (multipart file: Nominee Photo)
  - nominee_1_signature: (multipart file: Nominee Signature)
  - signature_card:      (multipart file: Combined Signature Card)

Response returned by Mock Server to Online BO (HTTP 200):
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

------------------------------------------------------------------------------
5. CDBL NOMINEE VEDAS UPLOAD (Type 04)
------------------------------------------------------------------------------
Endpoint: POST {$baseUrl}/v1/cdbl/upload
          (or POST {$baseUrl}/upload or ?endpoint=/upload)
Headers:  Authorization: Bearer <TOKEN>
          Content-Type: multipart/form-data

Sample Request:
  curl -X POST "{$baseUrl}/v1/cdbl/upload" \\
    -H "Authorization: Bearer test-token" \\
    -F "file=@vedas_nominee_M9501_16112026.01.zip" \\
    -F "uploadType=04" \\
    -F "dpInternalRefNo=M9501" \\
    -F "callbackUrl=http://localhost:8111/api/cdbl/callback"

Immediate Response returned to Online BO (HTTP 200):
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

------------------------------------------------------------------------------
6. CDBL WEBHOOK CALLBACK: NOMINEE ACK (Dispatched after delay)
------------------------------------------------------------------------------
Endpoint: POST {callbackUrl} (e.g., http://localhost:8111/api/cdbl/callback)
Headers:  Content-Type: application/json

Sample Payload dispatched by Mock Server to Online BO:
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

Expected Response from Online BO (HTTP 200):
{
  "success": true,
  "message": "Nominee callback processed successfully for client M9501.",
  "data": {
    "client_code": "M9501",
    "status": "COMPLETED"
  }
}

------------------------------------------------------------------------------
7. ACK PDF FILE DOWNLOAD & PREVIEW
------------------------------------------------------------------------------
Endpoint: GET {$baseUrl}/v1/cdbl/download/pdf/{filename}
          (or GET {$baseUrl}/download/pdf/{filename} or ?endpoint=/download/pdf/{filename})
Direct Preview: GET {$baseUrl}/sample-ack-pdf
Sample Request:
  curl -X GET "{$baseUrl}/v1/cdbl/download/pdf/BO_Ack_1604940078052399_M9501.pdf" \\
    --output "BO_Ack_1604940078052399_M9501.pdf"
Response: Content-Type: application/pdf (Valid %PDF-1.4 binary stream)
==============================================================================
TEXT;
}

// ------------------------------------------------------------------------------
// CLI Worker Mode: Asynchronous Background Callback Dispatcher
// ------------------------------------------------------------------------------
if (php_sapi_name() === 'cli' && isset($argv[1])) {
    foreach ($argv as $arg) {
        if (str_starts_with($arg, '--run-callback=')) {
            $jobFile = substr($arg, strlen('--run-callback='));
            runBackgroundCallbackJob($jobFile);
            exit(0);
        }
    }
}

// ------------------------------------------------------------------------------
// State & Storage Configuration (Stored in system temp directory)
// ------------------------------------------------------------------------------
define('STATE_FILE', sys_get_temp_dir() . '/cdbl_mock_state.json');
define('PDF_STORAGE_DIR', sys_get_temp_dir() . '/cdbl_mock_pdfs');

if (!is_dir(PDF_STORAGE_DIR)) {
    @mkdir(PDF_STORAGE_DIR, 0777, true);
}

// ------------------------------------------------------------------------------
// State Helpers
// ------------------------------------------------------------------------------
function loadState(): array {
    if (!file_exists(STATE_FILE)) {
        $default = [
            'config' => [
                'setup_delay_sec'   => 3,
                'nominee_delay_sec' => 2,
                'qoffice_delay_ms'  => 500,
                'simulation_mode'   => 'AUTO_APPROVE',
                'default_callback'  => 'http://localhost:8111/api/cdbl/callback',
            ],
            'clients' => [],
            'logs'    => [],
        ];
        saveState($default);
        return $default;
    }
    $content = @file_get_contents(STATE_FILE);
    $data = json_decode($content, true);
    return is_array($data) ? $data : ['config' => [], 'clients' => [], 'logs' => []];
}

function saveState(array $state): void {
    @file_put_contents(STATE_FILE, json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
}

function recordTransaction(string $service, string $endpoint, string $method, ?string $clientCode, string $status, array $summary, ?string $rawPayload = null): void {
    $state = loadState();
    $entry = [
        'id'          => uniqid('tx_'),
        'timestamp'   => date('Y-m-d H:i:s'),
        'service'     => $service,
        'endpoint'    => $endpoint,
        'method'      => $method,
        'client_code' => $clientCode ?: 'N/A',
        'status'      => $status,
        'summary'     => $summary,
        'raw_payload' => $rawPayload ? substr($rawPayload, 0, 1000) : null,
    ];
    array_unshift($state['logs'], $entry);
    if (count($state['logs']) > 100) {
        $state['logs'] = array_slice($state['logs'], 0, 100);
    }
    saveState($state);
}

function getAckPdfContent(): string {
    return base64_decode(AUTHENTIC_CDBL_ACK_PDF_BASE64);
}

// ------------------------------------------------------------------------------
// Asynchronous Callback Dispatcher via Spawned Background Process
// ------------------------------------------------------------------------------
function spawnBackgroundCallback(array $job): void {
    $jobId = uniqid('cdbl_job_', true);
    $jobFile = sys_get_temp_dir() . '/' . $jobId . '.json';
    file_put_contents($jobFile, json_encode($job, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    $phpBinary = PHP_BINARY;
    if (empty($phpBinary) || !file_exists($phpBinary)) {
        $phpBinary = 'php';
    }

    $cmd = sprintf(
        '%s %s --run-callback=%s > /dev/null 2>&1 &',
        escapeshellcmd($phpBinary),
        escapeshellarg(__FILE__),
        escapeshellarg($jobFile)
    );

    if (stripos(PHP_OS, 'WIN') === 0) {
        pclose(popen("start /B " . $cmd, "r"));
    } else {
        exec($cmd);
    }
}

function runBackgroundCallbackJob(string $jobFile): void {
    if (!file_exists($jobFile)) return;
    $job = json_decode(file_get_contents($jobFile), true);
    if (!$job) return;

    $delay = (int) ($job['delay'] ?? 3);
    if ($delay > 0) {
        sleep($delay);
    }

    $callbackUrl = $job['callbackUrl'] ?? '';
    $payload     = $job['payload'] ?? [];
    $clientCode  = $job['clientCode'] ?? '';
    $type        = $job['type'] ?? 'setup';

    $jsonPayload = json_encode($payload);
    $opts = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/json\r\n" .
                         "Accept: application/json\r\n" .
                         "Content-Length: " . strlen($jsonPayload) . "\r\n",
            'content' => $jsonPayload,
            'timeout' => 30,
            'ignore_errors' => true,
        ]
    ];
    $context = stream_context_create($opts);
    $res = @file_get_contents($callbackUrl, false, $context);

    $httpCode = 0;
    if (isset($http_response_header)) {
        foreach ($http_response_header as $hdr) {
            if (preg_match('#HTTP/[0-9\.]+\s+([0-9]+)#i', $hdr, $m)) {
                $httpCode = (int) $m[1];
            }
        }
    }

    // Update state
    $state = loadState();
    if ($clientCode && isset($state['clients'][$clientCode])) {
        if ($type === 'setup') {
            $state['clients'][$clientCode]['status'] = 'COMPLETED';
            $state['clients'][$clientCode]['boid'] = $payload['boid'] ?? $state['clients'][$clientCode]['boid'];
            $state['clients'][$clientCode]['callback_dispatched'] = true;
            $state['clients'][$clientCode]['callback_http_status'] = $httpCode;
            $state['clients'][$clientCode]['callback_response'] = substr((string) $res, 0, 500);
        } elseif ($type === 'nominee') {
            $state['clients'][$clientCode]['nominee_status'] = 'COMPLETED';
            $state['clients'][$clientCode]['nominee_callback_http_status'] = $httpCode;
        }
        saveState($state);
    }

    recordTransaction(
        'CDBL_WEBHOOK',
        $callbackUrl,
        'POST',
        $clientCode,
        ($httpCode >= 200 && $httpCode < 300) ? 'DELIVERED' : 'FAILED',
        [
            'type'        => $type,
            'http_code'   => $httpCode,
            'response'    => substr((string) $res, 0, 200),
            'boid'        => $payload['boid'] ?? null,
            'event'       => $payload['event'] ?? null,
        ]
    );

    @unlink($jobFile);
}

// ------------------------------------------------------------------------------
// HTTP Router & Request Processor
// Supports direct route (http://localhost/online_bo/data/docs/cdbl/cdbl_api.php),
// webapp route (/cdbl-api, /cdbl-mock), and standalone CLI server (port 8118)
// ------------------------------------------------------------------------------
$isDirectPhp = str_contains($_SERVER['SCRIPT_NAME'] ?? '', 'cdbl_api.php') || str_contains($_SERVER['REQUEST_URI'] ?? '', 'cdbl_api.php');
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/online_bo/data/docs/cdbl/cdbl_api.php';
if (str_contains($scriptName, 'cdbl_api.php')) {
    $cleanScript = substr($scriptName, 0, strpos($scriptName, 'cdbl_api.php') + strlen('cdbl_api.php'));
    $baseUrl = "{$scheme}://{$host}" . $cleanScript;
} else {
    $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
    if (str_contains($uriPath, 'cdbl_api.php')) {
        $cleanUri = substr($uriPath, 0, strpos($uriPath, 'cdbl_api.php') + strlen('cdbl_api.php'));
        $baseUrl = "{$scheme}://{$host}" . $cleanUri;
    } else {
        $baseUrl = "{$scheme}://{$host}" . rtrim($uriPath, '/');
        if ($baseUrl === "{$scheme}://{$host}") {
            $baseUrl = "{$scheme}://{$host}";
        }
    }
}

// Check if raw text documentation is requested
if (isset($_GET['raw']) || isset($_GET['text']) || (isset($_GET['format']) && $_GET['format'] === 'text')) {
    header('Content-Type: text/plain; charset=utf-8');
    echo getApiDocumentationText($baseUrl);
    exit;
}

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$rawPath = parse_url($requestUri, PHP_URL_PATH) ?? '/';

// 1. Check if Apache passed PATH_INFO
if (!empty($_SERVER['PATH_INFO'])) {
    $path = $_SERVER['PATH_INFO'];
} else {
    // Strip preceding directory structure leading to cdbl_api.php (e.g. /online_bo/data/docs/cdbl/cdbl_api.php)
    $path = preg_replace('#^.*?cdbl_api\.php#', '', $rawPath);
    // Also strip webapp routes like /cdbl-api or /cdbl-mock
    $path = preg_replace('#^/(?:cdbl-api|cdbl-mock)#', '', $path);
}

// 2. Allow query parameter override (e.g. ?endpoint=/upload or ?path=/bo/ack/M9501)
if (!empty($_GET['endpoint'])) {
    $path = '/' . ltrim($_GET['endpoint'], '/');
} elseif (!empty($_GET['path'])) {
    $path = '/' . ltrim($_GET['path'], '/');
}

if ($path === '' || $path === false) {
    $path = '/';
}

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$state = loadState();

// 1. Dashboard View (GET /)
if ($method === 'GET' && ($path === '/' || $path === '/dashboard')) {
    serveDashboardHtml($state, $baseUrl);
    exit;
}

// 2. Mock Server Settings API (POST /api/mock/settings)
if ($method === 'POST' && (preg_match('#(?:^|/)mock/settings$#i', $path) || $path === '/api/mock/settings')) {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    if (isset($input['setup_delay_sec'])) {
        $state['config']['setup_delay_sec'] = max(0, (int) $input['setup_delay_sec']);
    }
    if (isset($input['nominee_delay_sec'])) {
        $state['config']['nominee_delay_sec'] = max(0, (int) $input['nominee_delay_sec']);
    }
    if (isset($input['qoffice_delay_ms'])) {
        $state['config']['qoffice_delay_ms'] = max(0, (int) $input['qoffice_delay_ms']);
    }
    if (!empty($input['simulation_mode'])) {
        $state['config']['simulation_mode'] = trim($input['simulation_mode']);
    }
    if (!empty($input['default_callback'])) {
        $state['config']['default_callback'] = trim($input['default_callback']);
    }
    saveState($state);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Settings updated successfully.', 'config' => $state['config']]);
    exit;
}

// 3. Mock Server Clear Activity / State (POST /api/mock/clear)
if ($method === 'POST' && (preg_match('#(?:^|/)mock/clear$#i', $path) || $path === '/api/mock/clear')) {
    $state['logs'] = [];
    $state['clients'] = [];
    saveState($state);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'State and logs cleared.']);
    exit;
}

// 4. Manual Webhook Trigger API (POST /api/mock/trigger-callback)
if ($method === 'POST' && (preg_match('#(?:^|/)mock/trigger-callback$#i', $path) || $path === '/api/mock/trigger-callback')) {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $clientCode = trim($input['client_code'] ?? 'M9501');
    $boid = trim($input['boid'] ?? ('16049400' . rand(10000000, 99999999)));
    $callbackUrl = trim($input['callback_url'] ?? ($state['config']['default_callback'] ?: 'http://localhost:8111/api/cdbl/callback'));
    $type = $input['type'] ?? 'setup';

    if ($type === 'setup') {
        $ackPdfFileName = "BO_Ack_{$boid}_{$clientCode}.pdf";
        $job = [
            'type'        => 'setup',
            'delay'       => 0,
            'clientCode'  => $clientCode,
            'callbackUrl' => $callbackUrl,
            'payload'     => [
                'event'          => 'BO_SETUP_COMPLETED',
                'success'        => true,
                'dpInternalRefNo'=> $clientCode,
                'client_code'    => $clientCode,
                'boid'           => $boid,
                'applicantName'  => 'MANUAL SIMULATED USER',
                'setupDate'      => date('Y-m-d'),
                'ackPdfFileName' => $ackPdfFileName,
                'ackPdfBase64'   => AUTHENTIC_CDBL_ACK_PDF_BASE64,
            ],
        ];
    } else {
        $job = [
            'type'        => 'nominee',
            'delay'       => 0,
            'clientCode'  => $clientCode,
            'callbackUrl' => $callbackUrl,
            'payload'     => [
                'uploadCode'      => '04',
                'event'           => 'NOMINEE_COMPLETED',
                'success'         => true,
                'dpInternalRefNo' => $clientCode,
                'client_code'     => $clientCode,
                'uploadId'        => rand(1000, 9999),
                'fileName'        => "043110001." . date('dmY') . ".01",
                'rawResponse'     => [
                    'success' => true,
                    'message' => 'Nominee processed successfully by CDBL',
                ],
            ],
        ];
    }

    spawnBackgroundCallback($job);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => "Callback dispatched to {$callbackUrl}", 'boid' => $boid]);
    exit;
}

// 5. View Embedded Sample ACK PDF Directly in Browser (GET /sample-ack-pdf)
if ($method === 'GET' && preg_match('#(?:sample-ack-pdf|cdbl_ack_sample\.pdf|view/ack-pdf)#i', $path)) {
    $content = getAckPdfContent();
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="cdbl_ack_sample.pdf"');
    header('Content-Length: ' . strlen($content));
    echo $content;
    exit;
}

// 6. View Embedded Sample JSON Templates (GET /sample-json/{type})
if ($method === 'GET' && preg_match('#(?:^|/)sample-json/([a-zA-Z0-9_\-\.]+)$#i', $path, $matches)) {
    $fileKey = strtolower(basename($matches[1], '.json'));
    header('Content-Type: application/json');

    switch ($fileKey) {
        case 'sample_setup_upload_response':
        case 'setup_upload':
            echo json_encode(CdblMockTemplates::getSetupUploadResponse(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        case 'sample_ack_webhook_payload':
        case 'ack_webhook':
            echo json_encode(CdblMockTemplates::getAckWebhookPayload(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        case 'sample_nominee_webhook_payload':
        case 'nominee_webhook':
            echo json_encode(CdblMockTemplates::getNomineeWebhookPayload(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        case 'sample_qoffice_sync_response':
        case 'qoffice_sync':
            echo json_encode(CdblMockTemplates::getQofficeSyncResponse(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        case 'cdbl_mock_state':
        case 'state':
            echo json_encode(loadState(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Sample template not found: ' . $fileKey]);
            exit;
    }
}

// 7. PDF File Download Endpoint (GET /download/pdf/{filename} or /v1/cdbl/download/pdf/{filename})
if ($method === 'GET' && preg_match('#(?:^|/)download/pdf/(.+)$#i', $path, $matches)) {
    $filename = basename($matches[1]);

    // If filename references the designated FAILED client, return 404
    if (stripos($filename, TEST_CLIENT_FAILED) !== false) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => "PDF for client " . TEST_CLIENT_FAILED . " not found."]);
        exit;
    }

    $filePath = PDF_STORAGE_DIR . '/' . $filename;
    if (!file_exists($filePath)) {
        file_put_contents($filePath, getAckPdfContent());
    }
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
    exit;
}

// ------------------------------------------------------------------------------
// ROUTE: CDBL Status Inquiry (GET /bo/ack/{code} or /v1/cdbl/bo/ack/{code})
// ------------------------------------------------------------------------------
if ($method === 'GET' && preg_match('#(?:^|/)bo/ack/([^/]+)$#i', $path, $matches)) {
    $clientCode = trim($matches[1]);
    $mode = $state['config']['simulation_mode'] ?? 'AUTO_APPROVE';

    // 1. Designated FAILED client code
    if (strcasecmp($clientCode, TEST_CLIENT_FAILED) === 0) {
        http_response_code(404);
        header('Content-Type: application/json');
        $resp = [
            'success' => false,
            'error'   => "Client code {$clientCode} not found in CDBL.",
        ];
        echo json_encode($resp);
        recordTransaction('CDBL_INQUIRY', $path, 'GET', $clientCode, '404 NOT_FOUND', $resp);
        exit;
    }

    // 2. Designated PENDING client code
    if (strcasecmp($clientCode, TEST_CLIENT_PENDING) === 0) {
        http_response_code(200);
        header('Content-Type: application/json');
        $resp = [
            'success' => true,
            'data'    => [
                'status'          => 'QUEUED',
                'dpInternalRefNo' => $clientCode,
                'boid'            => null,
                'setupDate'       => date('Y-m-d'),
                'pdfDownloadUrl'  => null,
            ],
        ];
        echo json_encode($resp);
        recordTransaction('CDBL_INQUIRY', $path, 'GET', $clientCode, '200 QUEUED', $resp);
        exit;
    }

    // 3. Designated SUCCESS client code or state client
    $isSuccessCode = (strcasecmp($clientCode, TEST_CLIENT_SUCCESS) === 0);
    $client = $state['clients'][$clientCode] ?? null;

    if (!$client && !$isSuccessCode) {
        http_response_code(404);
        header('Content-Type: application/json');
        $resp = [
            'success' => false,
            'error'   => "Client code {$clientCode} not found in CDBL.",
        ];
        echo json_encode($resp);
        recordTransaction('CDBL_INQUIRY', $path, 'GET', $clientCode, '404 NOT_FOUND', $resp);
        exit;
    }

    http_response_code(200);
    header('Content-Type: application/json');
    $statusStr = $client['status'] ?? ($isSuccessCode ? 'COMPLETED' : 'QUEUED');
    $boid = $client['boid'] ?? ($isSuccessCode ? DEFAULT_BOID : ('16049400' . rand(10000000, 99999999)));
    $setupDate = $client['setupDate'] ?? date('Y-m-d');
    $pdfUrl = rtrim($baseUrl, '/') . "/v1/cdbl/download/pdf/BO_Ack_{$boid}_{$clientCode}.pdf";

    $resp = [
        'success' => true,
        'data'    => [
            'status'          => $statusStr,
            'dpInternalRefNo' => $clientCode,
            'boid'            => $boid,
            'setupDate'       => $setupDate,
            'pdfDownloadUrl'  => ($statusStr === 'COMPLETED' ? $pdfUrl : null),
        ],
    ];
    echo json_encode($resp);
    recordTransaction('CDBL_INQUIRY', $path, 'GET', $clientCode, '200 ' . $statusStr, $resp);
    exit;
}

// ------------------------------------------------------------------------------
// ROUTE: CDBL VEDAS Upload (POST /upload or /v1/cdbl/upload)
// ------------------------------------------------------------------------------
if ($method === 'POST' && preg_match('#(?:^|/)upload$#i', $path)) {
    $uploadType = $_POST['uploadType'] ?? '03';
    $clientCode = trim($_POST['dpInternalRefNo'] ?? ($_POST['client_code'] ?? ''));
    $callbackUrl = trim($_POST['callbackUrl'] ?? ($state['config']['default_callback'] ?: 'http://localhost:8111/api/cdbl/callback'));
    $mode = $state['config']['simulation_mode'] ?? 'AUTO_APPROVE';

    // Check if this client code is designated as FAILED
    if (strcasecmp($clientCode, TEST_CLIENT_FAILED) === 0 || $mode === 'REJECT_SETUP') {
        http_response_code(400);
        header('Content-Type: application/json');
        $rejectResp = [
            'success' => false,
            'status'  => 'REJECTED',
            'error'   => "CDBL validation error: Client code {$clientCode} rejected by CDBL clearinghouse.",
            'message' => 'Rejected by CDBL clearinghouse.',
        ];
        echo json_encode($rejectResp);
        recordTransaction('CDBL_UPLOAD', $path, 'POST', $clientCode, '400 REJECTED', $rejectResp);
        exit;
    }

    if ($mode === 'GATEWAY_BUSY') {
        http_response_code(503);
        header('Content-Type: application/json');
        $busyResp = [
            'success' => false,
            'status'  => 'BUSY',
            'message' => 'CDBL upload gateway is currently busy. Parallel transmission rejected.',
        ];
        echo json_encode($busyResp);
        recordTransaction('CDBL_UPLOAD', $path, 'POST', $clientCode, '503 BUSY', $busyResp);
        exit;
    }

    $uploadRecordId = rand(4000, 9999);
    $queuedJobId    = rand(10000, 99999);
    $prefix         = ($uploadType === '04' ? '04' : '03');
    $mainFileName   = "{$prefix}3110001." . date('dmY') . "." . str_pad((string) rand(1, 99), 2, '0', STR_PAD_LEFT);
    $boid           = (strcasecmp($clientCode, TEST_CLIENT_SUCCESS) === 0) 
        ? DEFAULT_BOID 
        : ($state['clients'][$clientCode]['boid'] ?? ('16049400' . rand(10000000, 99999999)));

    if ($clientCode) {
        if (!isset($state['clients'][$clientCode])) {
            $state['clients'][$clientCode] = [
                'client_code'         => $clientCode,
                'boid'                => $boid,
                'setupDate'           => date('Y-m-d'),
                'created_at'          => date('Y-m-d H:i:s'),
                'status'              => 'QUEUED',
                'uploadRecordId'      => $uploadRecordId,
                'queuedJobId'         => $queuedJobId,
                'callbackUrl'         => $callbackUrl,
                'callback_dispatched' => false,
            ];
        }
        $state['clients'][$clientCode]['last_upload_type'] = $uploadType;
        $state['clients'][$clientCode]['last_upload_time'] = date('Y-m-d H:i:s');
        saveState($state);
    }

    $setupDelay = (int) ($state['config']['setup_delay_sec'] ?? 3);
    $nomineeDelay = (int) ($state['config']['nominee_delay_sec'] ?? 2);

    if ($mode !== 'NEVER_CALLBACK') {
        if ($uploadType === '04') {
            $job = [
                'type'        => 'nominee',
                'delay'       => $nomineeDelay,
                'clientCode'  => $clientCode,
                'callbackUrl' => $callbackUrl,
                'payload'     => [
                    'uploadCode'      => '04',
                    'event'           => 'NOMINEE_COMPLETED',
                    'success'         => true,
                    'dpInternalRefNo' => $clientCode,
                    'client_code'     => $clientCode,
                    'uploadId'        => $uploadRecordId,
                    'fileName'        => $mainFileName,
                    'rawResponse'     => [
                        'success' => true,
                        'message' => 'Nominee processed successfully by CDBL',
                    ],
                ],
            ];
            spawnBackgroundCallback($job);
        } else {
            $ackPdfFileName = "BO_Ack_{$boid}_{$clientCode}.pdf";
            file_put_contents(PDF_STORAGE_DIR . '/' . $ackPdfFileName, getAckPdfContent());

            $job = [
                'type'        => 'setup',
                'delay'       => $setupDelay,
                'clientCode'  => $clientCode,
                'callbackUrl' => $callbackUrl,
                'payload'     => [
                    'event'          => 'BO_SETUP_COMPLETED',
                    'success'        => true,
                    'dpInternalRefNo'=> $clientCode,
                    'client_code'    => $clientCode,
                    'boid'           => $boid,
                    'applicantName'  => 'CDBL TEST HOLDER',
                    'setupDate'      => date('Y-m-d'),
                    'ackPdfFileName' => $ackPdfFileName,
                    'ackPdfBase64'   => AUTHENTIC_CDBL_ACK_PDF_BASE64,
                ],
            ];
            spawnBackgroundCallback($job);
        }
    }

    http_response_code(200);
    header('Content-Type: application/json');
    $queuedResp = [
        'success' => true,
        'status'  => 'QUEUED',
        'message' => 'File accepted for processing and queued for CDBL submission.',
        'data'    => [
            'uploadRecordId'  => $uploadRecordId,
            'uploadId'        => $uploadRecordId,
            'queuedJobId'     => $queuedJobId,
            'mainFileName'    => $mainFileName,
            'fileName'        => $mainFileName,
            'dpInternalRefNo' => $clientCode,
            'callbackUrl'     => $callbackUrl,
            'simulated_delay' => ($uploadType === '04' ? $nomineeDelay : $setupDelay) . 's',
        ],
    ];
    echo json_encode($queuedResp);

    recordTransaction(
        'CDBL_UPLOAD',
        $path,
        'POST',
        $clientCode,
        "200 QUEUED (Type {$uploadType})",
        $queuedResp,
        json_encode($_POST)
    );
    exit;
}

// ------------------------------------------------------------------------------
// ROUTE: QOffice Back-Office ERP Client Sync Endpoint
// ------------------------------------------------------------------------------
if ($method === 'POST' && ($path === '/' || str_contains($path, 'bo-account/clients') || str_contains($path, 'qoffice') || str_contains($path, 'client'))) {
    $clientCode = $_POST['client_code'] ?? ($_POST['dpInternalRefNo'] ?? 'UNKNOWN');
    $boid = $_POST['boid'] ?? 'N/A';
    $name = $_POST['first_name'] ?? ($_POST['name'] ?? 'Applicant');
    $filesCount = count($_FILES ?? []);

    // Check if this is the designated FAILED client
    if (strcasecmp($clientCode, TEST_CLIENT_FAILED) === 0) {
        http_response_code(400);
        header('Content-Type: application/json');
        $failResp = [
            'status'      => false,
            'success'     => false,
            'error'       => "QOffice ERP Error: Failed to open ledger for client {$clientCode}.",
            'message'     => "Client {$clientCode} rejected by QOffice ERP.",
            'client_code' => $clientCode,
        ];
        echo json_encode($failResp);
        recordTransaction('QOFFICE_ERP', $path, 'POST', $clientCode, '400 FAILED', $failResp);
        exit;
    }

    $qofficeDelayMs = (int) ($state['config']['qoffice_delay_ms'] ?? 500);
    if ($qofficeDelayMs > 0) {
        usleep($qofficeDelayMs * 1000);
    }

    http_response_code(200);
    header('Content-Type: application/json');
    $effectiveBoid = (strcasecmp($clientCode, TEST_CLIENT_SUCCESS) === 0 && ($boid === 'N/A' || empty($boid))) ? DEFAULT_BOID : $boid;
    $qofficeResp = [
        'status'     => true,
        'success'    => true,
        'message'    => "Client {$clientCode} registered and ledger opened in QOffice Back-Office ERP successfully.",
        'client_id'  => rand(1000, 9999),
        'client_code'=> $clientCode,
        'boid'       => $effectiveBoid,
        'files_saved'=> $filesCount,
        'timestamp'  => date('Y-m-d H:i:s'),
    ];
    echo json_encode($qofficeResp);

    recordTransaction(
        'QOFFICE_ERP',
        $path,
        'POST',
        $clientCode,
        '200 SUCCESS',
        $qofficeResp,
        json_encode([
            'client_code' => $clientCode,
            'boid'        => $effectiveBoid,
            'name'        => $name,
            'files_count' => $filesCount,
            'post_fields' => array_keys($_POST),
        ])
    );
    exit;
}

// ------------------------------------------------------------------------------
// Fallback 404 for Unrecognized Routes
// ------------------------------------------------------------------------------
http_response_code(404);
header('Content-Type: application/json');
echo json_encode([
    'success' => false,
    'error'   => 'Not Found',
    'message' => "Unrecognized endpoint or path: {$path}",
    'method'  => $method,
    'path'    => $path,
    'available_endpoints' => [
        'GET  /' => 'Interactive Dashboard & API Documentation',
        'GET  /?raw=1' => 'Raw Plaintext API Documentation',
        'POST /v1/cdbl/upload' => 'CDBL VEDAS Upload (Type 03/04/05)',
        'GET  /v1/cdbl/bo/ack/{clientCode}' => 'CDBL Status Inquiry',
        'GET  /v1/cdbl/download/pdf/{filename}' => 'CDBL ACK PDF Download',
        'POST /bo-account/clients' => 'QOffice Back-Office ERP Client Sync',
        'GET  /sample-ack-pdf' => 'Direct Sample ACK PDF View',
        'GET  /sample-json/{type}' => 'Direct Sample JSON View',
    ]
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
exit;

// ------------------------------------------------------------------------------
// Helper: Serve HTML Dashboard
// ------------------------------------------------------------------------------
function serveDashboardHtml(array $state, string $baseUrl = ''): void {
    $config = $state['config'] ?? [];
    $logs = $state['logs'] ?? [];
    $clients = $state['clients'] ?? [];

    if (empty($baseUrl)) {
        $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $baseUrl = "{$scheme}://{$host}" . ($_SERVER['SCRIPT_NAME'] ?? '/online_bo/data/docs/cdbl/cdbl_api.php');
    }
    $rawDocs = getApiDocumentationText($baseUrl);
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CDBL & QOffice API Testing Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 14px; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 8px; }
        .text-muted { color: #94a3b8 !important; }
        .nav-pills .nav-link { color: #94a3b8; font-size: 13px; font-weight: 500; border-radius: 6px; padding: 6px 12px; margin-right: 4px; margin-bottom: 4px; }
        .nav-pills .nav-link:hover { color: #fff; background: #334155; }
        .nav-pills .nav-link.active { background: #2563eb; color: #fff; }
        .code-box { position: relative; background: #020617; border: 1px solid #334155; border-radius: 6px; padding: 10px; font-family: SFMono-Regular, Consolas, monospace; font-size: 12px; color: #38bdf8; }
        .code-box pre { margin: 0; max-height: 260px; overflow-y: auto; color: inherit; font-size: inherit; }
        .btn-copy { position: absolute; top: 6px; right: 6px; font-size: 11px; padding: 2px 8px; }
        .file-chip { display: inline-block; background: #1e293b; border: 1px solid #334155; border-radius: 4px; padding: 4px 8px; color: #38bdf8; text-decoration: none; font-size: 12px; margin: 2px; }
        .file-chip:hover { background: #334155; color: #fff; }
        .table { color: #e2e8f0; font-size: 13px; }
        .table-dark { --bs-table-bg: #1e293b; }
    </style>
</head>
<body class="p-3">
<div class="container-fluid" style="max-width: 1200px;">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary">
        <div>
            <h4 class="fw-bold mb-0">CDBL & QOffice API Testing Platform</h4>
            <span class="small text-muted">Direct Base URL: <code class="text-warning"><?= htmlspecialchars($baseUrl) ?></code></span>
        </div>
        <div class="d-flex gap-2 align-items-center mt-2 mt-md-0">
            <a href="?raw=1" target="_blank" class="btn btn-sm btn-outline-info">Plain Text Docs (?raw=1)</a>
            <a href="cdbl_ack_sample.pdf" target="_blank" class="btn btn-sm btn-outline-success">Sample PDF</a>
            <a href="README.md" target="_blank" class="btn btn-sm btn-outline-light">README</a>
            <button class="btn btn-sm btn-outline-danger" onclick="clearState()">Clear History</button>
            <button class="btn btn-sm btn-primary" onclick="location.reload()">Refresh</button>
        </div>
    </div>

    <!-- Test Client Codes Banner -->
    <div class="card p-2 px-3 mb-3 border-info">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 small">
            <div>
                <span class="text-muted fw-bold me-2">Test Client Codes (Defined at top of cdbl_api.php):</span>
                <span class="badge bg-success me-1">Success: <?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?></span>
                <span class="badge bg-danger me-1">Failed: <?= htmlspecialchars(TEST_CLIENT_FAILED) ?></span>
                <span class="badge bg-warning text-dark me-1">Queued: <?= htmlspecialchars(TEST_CLIENT_PENDING) ?></span>
            </div>
            <div>
                <span class="text-muted">Simulated BOID:</span> <code class="text-warning"><?= htmlspecialchars(DEFAULT_BOID) ?></code>
            </div>
        </div>
    </div>

    <!-- Quick File Links Row -->
    <div class="mb-3">
        <span class="small text-muted me-2">Sample Files on Disk:</span>
        <a class="file-chip" href="sample_setup_upload_response.json" target="_blank">📄 setup_upload_response.json</a>
        <a class="file-chip" href="sample_ack_webhook_payload.json" target="_blank">📄 ack_webhook_payload.json</a>
        <a class="file-chip" href="sample_nominee_webhook_payload.json" target="_blank">📄 nominee_webhook_payload.json</a>
        <a class="file-chip" href="sample_qoffice_sync_response.json" target="_blank">📄 qoffice_sync_response.json</a>
        <a class="file-chip" href="sample_status_inquiry_queued.json" target="_blank">📄 inquiry_queued.json</a>
        <a class="file-chip" href="sample_status_inquiry_completed.json" target="_blank">📄 inquiry_completed.json</a>
        <a class="file-chip text-success" href="cdbl_ack_sample.pdf" target="_blank">📑 cdbl_ack_sample.pdf</a>
    </div>

    <!-- API Samples Panel with Simple Tabs -->
    <div class="card p-3 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold text-primary mb-0">API Endpoints & Sample Calls</h6>
            <span class="small text-muted">Direct subpath: <code>cdbl_api.php/{endpoint}</code></span>
        </div>

        <ul class="nav nav-pills mb-3" id="apiTabs" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab1">1. Status Inquiry (GET)</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab2">2. Setup Upload (POST)</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab3">3. Setup Webhook (ACK)</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab4">4. QOffice Sync (POST)</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab5">5. Nominee Upload (POST)</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab6">6. Nominee Webhook (ACK)</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab7">7. PDF Download (GET)</button></li>
        </ul>

        <div class="tab-content" id="apiTabsContent">
            <!-- Tab 1: Status Inquiry -->
            <div class="tab-pane fade show active" id="tab1">
                <div class="mb-2">
                    <span class="badge bg-info text-dark">GET</span> <code><?= htmlspecialchars($baseUrl) ?>/v1/cdbl/bo/ack/{clientCode}</code>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <small class="text-success fw-bold d-block mb-1">A) SUCCESS Client (<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>):</small>
                        <div class="code-box">
                            <button class="btn btn-sm btn-outline-light btn-copy" onclick="copyText('curl-inquiry-success', this)">Copy</button>
                            <pre id="curl-inquiry-success">curl -X GET "<?= htmlspecialchars($baseUrl) ?>/v1/cdbl/bo/ack/<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>" \
  -H "Authorization: Bearer test-token" \
  -H "Accept: application/json"</pre>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-danger fw-bold d-block mb-1">B) FAILED Client (<?= htmlspecialchars(TEST_CLIENT_FAILED) ?>):</small>
                        <div class="code-box">
                            <button class="btn btn-sm btn-outline-light btn-copy" onclick="copyText('curl-inquiry-failed', this)">Copy</button>
                            <pre id="curl-inquiry-failed">curl -X GET "<?= htmlspecialchars($baseUrl) ?>/v1/cdbl/bo/ack/<?= htmlspecialchars(TEST_CLIENT_FAILED) ?>" \
  -H "Authorization: Bearer test-token" \
  -H "Accept: application/json"</pre>
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Response for <?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?> (200 COMPLETED):</small>
                        <div class="code-box"><pre>{
  "success": true,
  "data": {
    "status": "COMPLETED",
    "dpInternalRefNo": "<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>",
    "boid": "<?= htmlspecialchars(DEFAULT_BOID) ?>",
    "setupDate": "<?= date('Y-m-d') ?>",
    "pdfDownloadUrl": "<?= htmlspecialchars($baseUrl) ?>/v1/cdbl/download/pdf/BO_Ack_<?= htmlspecialchars(DEFAULT_BOID) ?>_<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>.pdf"
  }
}</pre></div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Response for <?= htmlspecialchars(TEST_CLIENT_FAILED) ?> (404 NOT FOUND):</small>
                        <div class="code-box"><pre>{
  "success": false,
  "error": "Client code <?= htmlspecialchars(TEST_CLIENT_FAILED) ?> not found in CDBL."
}</pre></div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Setup Upload -->
            <div class="tab-pane fade" id="tab2">
                <div class="mb-2">
                    <span class="badge bg-warning text-dark">POST</span> <code><?= htmlspecialchars($baseUrl) ?>/v1/cdbl/upload</code>
                </div>
                <div class="code-box mb-2">
                    <button class="btn btn-sm btn-outline-light btn-copy" onclick="copyText('curl-upload', this)">Copy curl</button>
                    <pre id="curl-upload">curl -X POST "<?= htmlspecialchars($baseUrl) ?>/v1/cdbl/upload" \
  -H "Authorization: Bearer test-token" \
  -F "file=@vedas_<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>_16112026.01.zip" \
  -F "uploadType=03" \
  -F "dpInternalRefNo=<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>" \
  -F "callbackUrl=http://localhost:8111/api/cdbl/callback"</pre>
                </div>
                <small class="text-muted d-block mb-1">Immediate Queued Response (200 OK):</small>
                <div class="code-box"><pre>{
  "success": true,
  "status": "QUEUED",
  "message": "File accepted for processing and queued for CDBL submission.",
  "data": {
    "uploadRecordId": 4821,
    "mainFileName": "033110001.<?= date('dmY') ?>.01",
    "dpInternalRefNo": "<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>",
    "callbackUrl": "http://localhost:8111/api/cdbl/callback",
    "simulated_delay": "3s"
  }
}</pre></div>
            </div>

            <!-- Tab 3: Setup Webhook ACK -->
            <div class="tab-pane fade" id="tab3">
                <div class="mb-2">
                    <span class="badge bg-success">POST</span> <code>{callbackUrl} (e.g. http://localhost:8111/api/cdbl/callback)</code>
                </div>
                <small class="text-muted d-block mb-1">Payload dispatched asynchronously by mock server after delay:</small>
                <div class="code-box mb-2">
                    <button class="btn btn-sm btn-outline-light btn-copy" onclick="copyText('payload-ack', this)">Copy</button>
                    <pre id="payload-ack">{
  "event": "BO_SETUP_COMPLETED",
  "success": true,
  "dpInternalRefNo": "<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>",
  "client_code": "<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>",
  "boid": "<?= htmlspecialchars(DEFAULT_BOID) ?>",
  "applicantName": "CDBL TEST HOLDER",
  "setupDate": "<?= date('Y-m-d') ?>",
  "ackPdfFileName": "BO_Ack_<?= htmlspecialchars(DEFAULT_BOID) ?>_<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>.pdf",
  "ackPdfBase64": "JVBERi0xLjQKMSAwIG9iago8PC9UeXBlL0ZvbnQvU3VidHlwZS9UeXBlMS..."
}</pre>
                </div>
            </div>

            <!-- Tab 4: QOffice Sync -->
            <div class="tab-pane fade" id="tab4">
                <div class="mb-2">
                    <span class="badge bg-primary">POST</span> <code><?= htmlspecialchars($baseUrl) ?>/bo-account/clients</code>
                </div>
                <div class="code-box mb-2">
                    <button class="btn btn-sm btn-outline-light btn-copy" onclick="copyText('curl-qoffice', this)">Copy curl</button>
                    <pre id="curl-qoffice">curl -X POST "<?= htmlspecialchars($baseUrl) ?>/bo-account/clients" \
  -H "Authorization: Bearer test-token" \
  -F "client_code=<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>" \
  -F "boid=<?= htmlspecialchars(DEFAULT_BOID) ?>" \
  -F "bo_type=New" \
  -F "first_name=Rahim" \
  -F "bank_name=Dutch-Bangla Bank Ltd." \
  -F "bank_account_no=1011510001234" \
  -F "routing_no=090261726"</pre>
                </div>
                <small class="text-muted d-block mb-1">Response from QOffice Mock (200 OK):</small>
                <div class="code-box"><pre>{
  "status": true,
  "success": true,
  "message": "Client <?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?> registered and ledger opened in QOffice Back-Office ERP successfully.",
  "client_id": 4336,
  "client_code": "<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>",
  "boid": "<?= htmlspecialchars(DEFAULT_BOID) ?>",
  "timestamp": "<?= date('Y-m-d H:i:s') ?>"
}</pre></div>
            </div>

            <!-- Tab 5: Nominee Upload -->
            <div class="tab-pane fade" id="tab5">
                <div class="mb-2">
                    <span class="badge bg-warning text-dark">POST</span> <code><?= htmlspecialchars($baseUrl) ?>/v1/cdbl/upload (uploadType=04)</code>
                </div>
                <div class="code-box mb-2">
                    <button class="btn btn-sm btn-outline-light btn-copy" onclick="copyText('curl-nominee', this)">Copy curl</button>
                    <pre id="curl-nominee">curl -X POST "<?= htmlspecialchars($baseUrl) ?>/v1/cdbl/upload" \
  -H "Authorization: Bearer test-token" \
  -F "file=@vedas_nominee_<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>_16112026.01.zip" \
  -F "uploadType=04" \
  -F "dpInternalRefNo=<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>" \
  -F "callbackUrl=http://localhost:8111/api/cdbl/callback"</pre>
                </div>
            </div>

            <!-- Tab 6: Nominee Webhook ACK -->
            <div class="tab-pane fade" id="tab6">
                <div class="mb-2">
                    <span class="badge bg-success">POST</span> <code>{callbackUrl}</code>
                </div>
                <small class="text-muted d-block mb-1">Payload dispatched asynchronously by mock server after nominee delay:</small>
                <div class="code-box"><pre>{
  "uploadCode": "04",
  "event": "NOMINEE_COMPLETED",
  "success": true,
  "dpInternalRefNo": "<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>",
  "client_code": "<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>",
  "uploadId": 7666,
  "fileName": "043110001.<?= date('dmY') ?>.01",
  "rawResponse": {
    "success": true,
    "message": "Nominee processed successfully by CDBL"
  }
}</pre></div>
            </div>

            <!-- Tab 7: PDF Download -->
            <div class="tab-pane fade" id="tab7">
                <div class="mb-2">
                    <span class="badge bg-info text-dark">GET</span> <code><?= htmlspecialchars($baseUrl) ?>/v1/cdbl/download/pdf/{filename}</code>
                </div>
                <div class="code-box mb-2">
                    <button class="btn btn-sm btn-outline-light btn-copy" onclick="copyText('curl-pdf', this)">Copy curl</button>
                    <pre id="curl-pdf">curl -X GET "<?= htmlspecialchars($baseUrl) ?>/v1/cdbl/download/pdf/BO_Ack_<?= htmlspecialchars(DEFAULT_BOID) ?>_<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>.pdf" \
  --output "BO_Ack_<?= htmlspecialchars(DEFAULT_BOID) ?>_<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>.pdf"</pre>
                </div>
                <a href="cdbl_ack_sample.pdf" target="_blank" class="btn btn-sm btn-success me-2">Download Sample PDF</a>
                <a href="<?= htmlspecialchars($baseUrl) ?>/sample-ack-pdf" target="_blank" class="btn btn-sm btn-outline-info">Stream /sample-ack-pdf</a>
            </div>
        </div>
    </div>

    <!-- Simple Testing Controls (One Row) -->
    <div class="card p-3 mb-3">
        <h6 class="fw-bold mb-2">Manual Callback Test</h6>
        <div class="row g-2 align-items-center">
            <div class="col-md-2">
                <input type="text" id="manualClient" class="form-control form-control-sm bg-dark text-light border-secondary" placeholder="Client Code" value="<?= htmlspecialchars(TEST_CLIENT_SUCCESS) ?>">
            </div>
            <div class="col-md-3">
                <input type="text" id="manualBoid" class="form-control form-control-sm bg-dark text-light border-secondary" placeholder="16-Digit BOID" value="<?= htmlspecialchars(DEFAULT_BOID) ?>">
            </div>
            <div class="col-md-4">
                <input type="text" id="txtCallbackUrl" class="form-control form-control-sm bg-dark text-light border-secondary" placeholder="Webhook URL" value="<?= htmlspecialchars((string)($config['default_callback'] ?? 'http://localhost:8111/api/cdbl/callback')) ?>">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-sm btn-warning flex-fill" onclick="triggerManual('setup')">Send Setup Callback</button>
                <button class="btn btn-sm btn-info flex-fill" onclick="triggerManual('nominee')">Send Nominee Callback</button>
            </div>
        </div>
    </div>

    <!-- Recent Activity Log (Simple Table) -->
    <div class="card p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0">Recent Requests & Activity Log</h6>
            <div>
                <span class="badge bg-secondary"><?= count($logs) ?> logs</span>
                <button class="btn btn-sm btn-outline-secondary ms-2" onclick="location.reload()">Refresh Log</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-dark table-sm table-hover mb-0">
                <thead>
                    <tr class="text-secondary">
                        <th style="width: 150px;">Time</th>
                        <th style="width: 120px;">Service</th>
                        <th>Endpoint</th>
                        <th style="width: 100px;">Client</th>
                        <th style="width: 120px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-3">No transactions recorded yet.</td></tr>
                    <?php else: ?>
                        <?php foreach (array_slice(array_reverse($logs), 0, 20) as $log): ?>
                            <tr>
                                <td class="small text-muted"><?= htmlspecialchars((string)($log['timestamp'] ?? '')) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars((string)($log['service'] ?? 'API')) ?></span></td>
                                <td><code><?= htmlspecialchars((string)($log['method'] ?? 'GET')) ?> <?= htmlspecialchars((string)($log['endpoint'] ?? '')) ?></code></td>
                                <td><strong class="text-info"><?= htmlspecialchars((string)($log['client_code'] ?? 'N/A')) ?></strong></td>
                                <td>
                                    <?php 
                                    $st = (string)($log['status'] ?? '');
                                    $cls = 'badge bg-secondary';
                                    if (str_contains($st, '200') || str_contains($st, 'DELIVERED') || str_contains($st, 'SUCCESS')) $cls = 'badge bg-success';
                                    elseif (str_contains($st, 'QUEUED')) $cls = 'badge bg-warning text-dark';
                                    elseif (str_contains($st, '404') || str_contains($st, 'FAILED') || str_contains($st, '503')) $cls = 'badge bg-danger';
                                    ?>
                                    <span class="<?= $cls ?>"><?= htmlspecialchars($st) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const currentScriptPath = window.location.pathname;

function getApiUrl(endpoint) {
    let base = currentScriptPath;
    const phpIdx = base.indexOf('cdbl_api.php');
    if (phpIdx !== -1) {
        base = base.substring(0, phpIdx + 'cdbl_api.php'.length);
    } else {
        base = base.replace(/\/+$/, '');
    }
    return base + '/' + endpoint.replace(/^\/+/, '');
}

function copyText(id, btn) {
    const el = document.getElementById(id);
    if (!el) return;
    navigator.clipboard.writeText(el.innerText || el.textContent).then(() => {
        const orig = btn.innerText;
        btn.innerText = 'Copied!';
        btn.classList.add('btn-success');
        setTimeout(() => { btn.innerText = orig; btn.classList.remove('btn-success'); }, 1500);
    });
}

function clearState() {
    if (!confirm('Clear all logs and state?')) return;
    fetch(getApiUrl('/api/mock/clear'), {method: 'POST'}).then(() => location.reload());
}

function triggerManual(type) {
    const client = document.getElementById('manualClient').value;
    const boid = document.getElementById('manualBoid').value;
    const callbackUrl = document.getElementById('txtCallbackUrl').value;
    fetch(getApiUrl('/api/mock/trigger-callback'), {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({client_code: client, boid: boid, callback_url: callbackUrl, type: type})
    }).then(r => r.json()).then(res => {
        alert(res.message);
        location.reload();
    });
}
</script>
</body>
</html>
<?php
}
